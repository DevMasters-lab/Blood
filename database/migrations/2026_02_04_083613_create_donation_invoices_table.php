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
        Schema::create('donation_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('blood_bank_name'); // <--- ADD THIS LINE (This is what was missing)
            $table->string('invoice_code')->nullable()->unique(); 
            $table->date('donation_date');
            $table->date('expiry_date'); 
            $table->string('blood_type')->nullable();
            $table->enum('status', ['pending', 'active', 'expired', 'rejected', 'used'])->default('pending');
            $table->text('review_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_invoices');
    }
};
