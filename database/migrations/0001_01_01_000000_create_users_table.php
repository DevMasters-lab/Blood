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
            $table->string('name');
            $table->string('phone')->unique(); // Primary Identifier
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable(); // Nullable for OTP-only flows
            
            // Medical Info
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'All'])->nullable();

            // KYC / Identity Verification
            $table->string('id_number')->nullable(); // Passport or National ID
            $table->enum('kyc_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('kyc_rejected_reason')->nullable();
            $table->timestamp('kyc_verified_at')->nullable();
            $table->foreignId('kyc_verified_by_admin_id')->nullable(); // Optional: Link to admin who verified

            // Trust & Stats Counters (useful for the Trust Profile)
            $table->integer('request_count')->default(0);
            $table->integer('donation_invoice_count')->default(0);
            $table->integer('verified_donation_count')->default(0);

            // Account Status
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            
            $table->rememberToken();
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