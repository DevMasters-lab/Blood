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
        Schema::create('proof_files', function (Blueprint $table) {
            $table->id();
            $table->morphs('fileable'); 
            $table->enum('file_type', ['id_photo', 'invoice_proof', 'request_document', 'donation_proof']);
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->enum('status', ['active', 'removed'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proof_files');
    }
};
