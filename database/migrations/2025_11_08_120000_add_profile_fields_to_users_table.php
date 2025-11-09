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
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->string('gender', 32)->nullable()->after('date_of_birth');
            $table->string('nationality', 120)->nullable()->after('gender');
            $table->string('address_line1')->nullable()->after('nationality');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('city', 120)->nullable()->after('address_line2');
            $table->string('state', 120)->nullable()->after('city');
            $table->string('postal_code', 20)->nullable()->after('state');
            $table->string('country', 120)->nullable()->after('postal_code');
            $table->string('passport_number', 64)->nullable()->after('country');
            $table->string('passport_country', 120)->nullable()->after('passport_number');
            $table->date('passport_expiry')->nullable()->after('passport_country');
            $table->string('tax_identification_number', 120)->nullable()->after('passport_expiry');
            $table->string('occupation', 120)->nullable()->after('tax_identification_number');
            $table->string('employment_status', 120)->nullable()->after('occupation');
            $table->string('source_of_funds', 160)->nullable()->after('employment_status');
            $table->string('branch_code', 64)->nullable()->after('source_of_funds');
            $table->string('preferred_currency', 3)->default('USD')->after('branch_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'nationality',
                'address_line1',
                'address_line2',
                'city',
                'state',
                'postal_code',
                'country',
                'passport_number',
                'passport_country',
                'passport_expiry',
                'tax_identification_number',
                'occupation',
                'employment_status',
                'source_of_funds',
                'branch_code',
                'preferred_currency',
            ]);
        });
    }
};

