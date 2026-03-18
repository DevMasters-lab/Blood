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
            $table->string('google_id')->nullable()->unique()->after('email');
            $table->string('telegram_id')->nullable()->unique()->after('google_id');
            $table->string('telegram_username')->nullable()->after('telegram_id');
            $table->string('auth_provider')->nullable()->after('telegram_username');
            $table->string('phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'telegram_id', 'telegram_username', 'auth_provider']);
            $table->string('phone')->nullable(false)->change();
        });
    }
};