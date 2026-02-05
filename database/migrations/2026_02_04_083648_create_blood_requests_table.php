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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->string('blood_type'); 
            $table->string('quantity'); 
            $table->string('hospital_name');
            $table->date('needed_date');
            $table->string('patient_name')->nullable();
            $table->enum('status', ['open', 'reserved', 'completed', 'cancelled', 'expired'])->default('open');
            $table->integer('resend_count')->default(0);
            $table->timestamp('last_broadcast_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
