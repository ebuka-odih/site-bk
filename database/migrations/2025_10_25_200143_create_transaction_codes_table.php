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
        Schema::create('transaction_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->enum('type', ['deposit', 'withdrawal', 'transfer']);
            $table->decimal('amount', 20, 2)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('used_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_codes');
    }
};
