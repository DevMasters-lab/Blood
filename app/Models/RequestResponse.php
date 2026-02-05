<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestResponse extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // The request being responded to
    public function request()
    {
        return $this->belongsTo(BloodRequest::class, 'request_id');
    }

    // The user responding (The Donor)
    public function responder()
    {
        return $this->belongsTo(User::class, 'responder_id');
    }

    // Proof of donation for this specific response
    public function proofFile()
    {
        return $this->morphOne(ProofFile::class, 'owner');
    }
}