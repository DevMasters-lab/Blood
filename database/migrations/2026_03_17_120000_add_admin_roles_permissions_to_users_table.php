<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('admin_role', 50)->default('staff')->after('usertype');
            $table->json('admin_permissions')->nullable()->after('admin_role');
        });

        // Keep existing admin accounts functional by default.
        DB::table('users')
            ->where('usertype', 'admin')
            ->update(['admin_role' => 'super_admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['admin_role', 'admin_permissions']);
        });
    }
};
