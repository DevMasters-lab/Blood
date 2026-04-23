<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles and permissions first
        $this->call(PermissionSeeder::class);

        // 1. Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@bloodshare.kh'],
            [
                'name' => 'Super Admin',
                'phone' => '015916217',
                'password' => Hash::make('123456'),
                'usertype' => 'admin',
                'status' => 'active',
                'kyc_status' => 'verified',
            ]
        );

        $superAdmin->syncRoles(['Super Admin']);

        // 2. Test normal user
        User::updateOrCreate(
            ['email' => 'sokha@bloodshare.kh'],
            [
                'name' => 'Sokha Developer',
                'phone' => '0123456789',
                'password' => Hash::make('123456'),
                'usertype' => 'user',
                'status' => 'active',
                'id_number' => 'KH-123456789',
                'kyc_status' => 'verified',
                'blood_type' => 'O+',
            ]
        );
    }
}