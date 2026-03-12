<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonationInvoice;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =================================================================
        // 1. CREATE SUPER ADMIN (Login: 099999999 / password)
        // =================================================================
        User::create([
            'name' => 'Super Admin',
            'phone' => '099999999', 
            'email' => 'admin@bloodshare.kh',
            'password' => Hash::make('password'),
            'usertype' => 'admin',
            'status' => 'active',
            'kyc_status' => 'verified',
        ]);

        // =================================================================
        // 2. CREATE TEST USER (Login: 0123456789 / password123)
        // =================================================================
        $user = User::create([
            'name' => 'Sokha Developer',
            'phone' => '0123456789',
            'email' => 'sokha@bloodshare.kh',
            'password' => Hash::make('password123'),
            'usertype' => 'user', // <--- Normal User Role
            'status' => 'active',
            'id_number' => 'KH-123456789',
            'kyc_status' => 'verified',
            'blood_type' => 'O+',
        ]);

        // =================================================================
        // 3. CREATE 10 FAKE BLOOD REQUESTS
        // =================================================================
    //     $bloodTypes = ['A+', 'B+', 'O+', 'AB+', 'O-', 'A-'];
    //     $hospitals = ['Calmette', 'Khmer-Soviet', 'Royal Phnom Penh', 'Hebron', 'Sunrise Japan'];

    //     for ($i = 0; $i < 10; $i++) {
    //         BloodRequest::create([
    //             'requester_id' => $user->id,
    //             'blood_type' => $bloodTypes[array_rand($bloodTypes)],
    //             'quantity' => rand(1, 3) . ' Bags',
    //             'hospital_name' => $hospitals[array_rand($hospitals)],
    //             'needed_date' => Carbon::today()->addDays(rand(1, 7)), 
    //             'status' => 'open',
    //             'patient_name' => 'Anonymous Patient ' . ($i + 1),
    //         ]);
    //     }

    //     // =================================================================
    //     // 4. CREATE 3 FAKE DONATION RECORDS (For "My History")
    //     // =================================================================
    //     // Approved Donation
    //     DonationInvoice::create([
    //         'user_id' => $user->id,
    //         'blood_bank_name' => 'National Blood Transfusion Center',
    //         'donation_date' => Carbon::today()->subMonths(2),
    //         'expiry_date' => Carbon::today()->subMonths(1),
    //         'blood_type' => 'O+',
    //         'status' => 'active',
    //         'invoice_code' => 'INV-' . rand(1000, 9999),
    //     ]);

    //     // Pending Donation
    //     DonationInvoice::create([
    //         'user_id' => $user->id,
    //         'blood_bank_name' => 'Calmette Blood Bank',
    //         'donation_date' => Carbon::today()->subDays(2),
    //         'expiry_date' => Carbon::today()->addDays(28),
    //         'blood_type' => 'O+',
    //         'status' => 'pending',
    //     ]);
        
    //     // Rejected Donation
    //     DonationInvoice::create([
    //         'user_id' => $user->id,
    //         'blood_bank_name' => 'Unknown Center',
    //         'donation_date' => Carbon::today()->subDays(10),
    //         'expiry_date' => Carbon::today()->addDays(20),
    //         'blood_type' => 'O+',
    //         'status' => 'rejected',
    //         'review_note' => 'Image was blurry, please resubmit.',
    //     ]);
    // }
    }
}