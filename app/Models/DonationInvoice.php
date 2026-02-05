<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'donation_date' => 'date',
        'expiry_date' => 'date',
    ];

    // The user who owns this invoice
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // The Blood Bank (optional)
    public function bloodBank()
    {
        return $this->belongsTo(BloodBank::class);
    }

    // The donation slip photo
    public function proofFile()
    {
        return $this->morphOne(ProofFile::class, 'fileable');
    }
}