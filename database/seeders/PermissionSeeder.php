<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    private const GUARD = 'web';

    public function run()
    {
        // 1. Clear the cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Your exact custom permissions
        $permissions = [
            'view_requests', 'accept_requests', 'reject_requests',
            'view_history',
            'view_invoices', 'accept_invoices', 'reject_invoices',
            'view_users', 'reset_users', 'block_users', 'delete_users',
            'view_kyc', 'accept_kyc', 'reject_kyc',
            'view_account', 'update_account',
            'view_settings', 'update_settings',
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles'
        ];

        $userPermissions = [
            'view_account',
            'update_account',
        ];

        // 3. Save permissions safely
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => self::GUARD,
            ]);
        }

        // ==========================================
        // DYNAMIC SETUP: ONLY SUPER ADMIN
        // ==========================================

        // 4. Create ONLY the Super Admin role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => self::GUARD,
        ]);

        // 5. Give the Super Admin ALL permissions automatically
        $superAdminRole->syncPermissions(
            Permission::query()->where('guard_name', self::GUARD)->get()
        );

        // 5.1 Keep default user role limited to user-safe actions only
        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => self::GUARD,
        ]);

        $userRole->syncPermissions(
            Permission::query()
                ->where('guard_name', self::GUARD)
                ->whereIn('name', $userPermissions)
                ->get()
        );

        // 6. Assign Super Admin only to the first admin-type account
        $adminUser = User::query()
            ->where('usertype', 'admin')
            ->orderBy('id')
            ->first();

        if ($adminUser) {
            $adminUser->assignRole('Super Admin');
        }

        $this->command->info('Dynamic setup complete! Super Admin role synced for admin guard scope.');
    }
}