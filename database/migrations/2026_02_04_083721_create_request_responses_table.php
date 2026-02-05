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
        Schema::create('request_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('blood_requests')->onDelete('cascade');
            $table->foreignId('responder_id')->constrained('users')->onDelete('cascade');
            $table->enum('response_status', ['reserve', 'active', 'expired'])->default('reserve');
            $table->text('note')->nullable();
            $table->enum('proof_status', ['none', 'pending', 'verified', 'rejected'])->default('none');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->unique(['request_id', 'responder_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_responses');
    }
};
