<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_dashboard',
            'view_reports',
            'view_requests',
            'view_history',
            'view_invoices',
            'view_users',
            'view_kyc',
            'view_account',
            'view_settings',
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'manage_permissions',
            'manage_admins',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdminRole = Role::findOrCreate('Super Admin', 'web');
        $superAdminRole->syncPermissions($permissions);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}