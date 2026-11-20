<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('customer_email')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('original_amount', 10, 2)->nullable();
            $table->decimal('fees', 10, 2)->nullable();
            $table->string('currency', 10)->default('EUR');
            $table->string('description')->nullable();
            $table->string('status')->default('pending');
            $table->json('billing_details')->nullable();
            $table->string('receipt_url')->nullable();
            $table->string('pdf_receipt_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
