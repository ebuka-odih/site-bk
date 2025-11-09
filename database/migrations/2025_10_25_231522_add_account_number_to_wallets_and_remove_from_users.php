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
        // Add account_number to wallets table
        Schema::table('wallets', function (Blueprint $table) {
            $table->string('account_number', 10)->unique()->after('user_id');
        });

        // Remove account_number from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['account_number']); // Drop unique constraint first
            $table->dropColumn('account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add account_number back to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_number', 20)->unique()->nullable()->after('email');
        });

        // Remove account_number from wallets table
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropUnique(['account_number']);
            $table->dropColumn('account_number');
        });
    }
};
