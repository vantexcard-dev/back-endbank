<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('receiver_account_id')->constrained('accounts')->onDelete('cascade');
            $table->string('sender_name');
            $table->string('receiver_name');
            $table->string('sender_iban');
            $table->string('receiver_iban');
            $table->string('sender_bic')->nullable();
            $table->string('receiver_bic')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency');
            $table->enum('type', ['transfer', 'deposit', 'withdrawal']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
