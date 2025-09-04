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
            // Email Verification
            $table->boolean('email_verified')->default(false)->after('email');
            $table->string('email_verification_token')->nullable()->after('email_verified');
            $table->timestamp('email_verification_sent_at')->nullable()->after('email_verification_token');
            
            // Phone Verification  
            $table->boolean('phone_verified')->default(false)->after('phone_number');
            $table->string('phone_verification_code')->nullable()->after('phone_verified');
            $table->timestamp('phone_verification_sent_at')->nullable()->after('phone_verification_code');
            $table->tinyInteger('phone_verification_attempts')->default(0)->after('phone_verification_sent_at');
            
            // Two-Factor Authentication (TOTP/Authenticator Apps)
            $table->boolean('two_factor_enabled')->default(false)->after('verified_at');
            $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_enabled_at')->nullable()->after('two_factor_recovery_codes');
            
            // Security & Rate Limiting
            $table->tinyInteger('failed_verification_attempts')->default(0)->after('two_factor_enabled_at');
            $table->timestamp('verification_locked_until')->nullable()->after('failed_verification_attempts');
            $table->timestamp('last_security_check')->nullable()->after('verification_locked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_verified',
                'email_verification_token',
                'email_verification_sent_at',
                'phone_verified',
                'phone_verification_code',
                'phone_verification_sent_at',
                'phone_verification_attempts',
                'two_factor_enabled',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_enabled_at',
                'failed_verification_attempts',
                'verification_locked_until',
                'last_security_check'
            ]);
        });
    }
};
