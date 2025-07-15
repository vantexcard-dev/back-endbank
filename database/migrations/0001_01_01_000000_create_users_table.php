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
      Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Infos basiques
            $table->string('name');
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('avatar')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('profession')->nullable();

            // Infos entreprise
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_siret')->nullable();
            $table->string('company_vat')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_registration_number')->nullable();
            $table->date('company_registration_date')->nullable();
            $table->string('company_registration_city')->nullable();
            $table->string('company_registration_country')->nullable();
            $table->string('company_registration_office')->nullable();
            $table->string('company_registration_office_address')->nullable();
            $table->string('company_registration_office_phone')->nullable();
            $table->string('company_registration_office_email')->nullable();

            // Documents
            $table->string('selfie')->nullable();
            $table->string('passport_photo')->nullable();
            $table->string('prof_address')->nullable();

            // Sécurité et authentification
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // 2FA (Two Factor Authentication) si utilisé
            $table->text('two_factor_recovery_codes')->nullable();
            $table->text('two_factor_secret')->nullable();

            // Timestamps Laravel classiques
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
