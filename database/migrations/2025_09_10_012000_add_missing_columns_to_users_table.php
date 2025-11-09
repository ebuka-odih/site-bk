<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_number', 20)->unique()->nullable();
            $table->enum('account_type', ['savings', 'current', 'business'])->default('savings');
            $table->string('phone', 20)->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'locked'])->default('active');
            $table->boolean('is_admin')->default(false);
            $table->bigInteger('balance')->default(0); // in kobo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_number',
                'account_type',
                'phone',
                'status',
                'is_admin',
                'balance'
            ]);
        });
    }
};
