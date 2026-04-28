<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'google_id')) {
            $table->string('google_id')->nullable()->unique()->after('email');
        }

        if (!Schema::hasColumn('users', 'telegram_id')) {
            $table->string('telegram_id')->nullable()->unique()->after('google_id');
        }

        if (!Schema::hasColumn('users', 'telegram_username')) {
            $table->string('telegram_username')->nullable()->after('telegram_id');
        }

        if (!Schema::hasColumn('users', 'auth_provider')) {
            $table->string('auth_provider')->nullable()->after('telegram_username');
        }
    });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'auth_provider']);
        });
    }
};