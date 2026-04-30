<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'telegram_id')) {
                $table->string('telegram_id')->nullable()->unique()->after('google_id');
            }

            if (! Schema::hasColumn('users', 'telegram_username')) {
                $table->string('telegram_username')->nullable()->after('telegram_id');
            }

            if (! Schema::hasColumn('users', 'auth_provider')) {
                $table->string('auth_provider')->nullable()->after('telegram_username');
            }

            if (Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->change();
            }

            if (Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'auth_provider')) {
                $table->dropColumn('auth_provider');
            }

            if (Schema::hasColumn('users', 'telegram_username')) {
                $table->dropColumn('telegram_username');
            }

            if (Schema::hasColumn('users', 'telegram_id')) {
                $table->dropColumn('telegram_id');
            }
        });
    }
};