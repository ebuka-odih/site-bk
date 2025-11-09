<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table to modify enum values
        // Store current data
        $users = DB::table('users')->get();
        
        // Drop the status column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        // Add it back with the new enum values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'suspended', 'locked'])->default('active')->after('phone');
        });
        
        // Restore the status values
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['status' => $user->status ?? 'active']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Store current data
        $users = DB::table('users')->get();
        
        // Drop the status column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        // Add it back with the old enum values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('phone');
        });
        
        // Restore the status values (convert 'locked' to 'suspended')
        foreach ($users as $user) {
            $status = $user->status === 'locked' ? 'suspended' : $user->status;
            DB::table('users')
                ->where('id', $user->id)
                ->update(['status' => $status ?? 'active']);
        }
    }
};
