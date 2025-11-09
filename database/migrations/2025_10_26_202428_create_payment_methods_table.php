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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // bank, crypto, paypal, wire_transfer, custom
            $table->string('name'); // Display name
            $table->string('key')->unique(); // Unique identifier (e.g., bank_transfer)
            $table->boolean('enabled')->default(true);
            $table->integer('min_amount')->default(1000); // in cents
            $table->integer('max_amount')->nullable(); // in cents
            $table->string('processing_time')->nullable();
            $table->decimal('fee_percentage', 5, 2)->nullable(); // e.g., 2.90 for 2.9%
            $table->integer('fee_fixed')->nullable(); // Fixed fee in cents
            $table->json('configuration')->nullable(); // Store addresses, account details, etc.
            $table->json('instructions')->nullable(); // Payment instructions
            $table->json('notes')->nullable(); // Additional notes
            $table->boolean('requires_reference')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
