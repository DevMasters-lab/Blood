<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\DonationInvoice;
use App\Models\BloodRequest;
use Carbon\Carbon;

// 1. Daily Job: Expire Old Invoices
Schedule::call(function () {
    $today = Carbon::today();
    
    // Find active invoices where expiry_date is in the past
    $count = DonationInvoice::where('status', 'active')
        ->where('expiry_date', '<', $today)
        ->update(['status' => 'expired']);

    if ($count > 0) {
        // In a real app, you would send Push Notifications here
        // Notification::send($users, new InvoiceExpiredNotification());
        echo "Expired $count invoices.\n";
    }
})->daily();

// 2. Daily Job: Expire Blood Requests (Past Needed Date)
Schedule::call(function () {
    $today = Carbon::today();

    BloodRequest::where('status', 'open')
        ->where('needed_date', '<', $today)
        ->update(['status' => 'expired']);

})->daily();