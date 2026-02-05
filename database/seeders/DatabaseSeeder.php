<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BloodRequest;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create YOUR Test User (Login: 0123456789 / password123)
        $user = User::create([
            'name' => 'Sokha Developer',
            'phone' => '0123456789',
            'password' => Hash::make('password123'),
            'id_number' => 'KH-123456789',
            'kyc_status' => 'verified',
            'blood_type' => 'O+',
        ]);

        // 2. Create 10 Fake Blood Requests
        $bloodTypes = ['A+', 'B+', 'O+', 'AB+'];
        $hospitals = ['Calmette', 'Khmer-Soviet', 'Royal Phnom Penh', 'Hebron'];

        for ($i = 0; $i < 10; $i++) {
            BloodRequest::create([
                'requester_id' => $user->id,
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'quantity' => '1 Bag',
                'hospital_name' => $hospitals[array_rand($hospitals)],
                'needed_date' => now()->addDays(rand(1, 5)), // Needed in 1-5 days
                'status' => 'open',
            ]);
        }
    }
}