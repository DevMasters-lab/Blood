<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'needed_date' => 'date',
        'last_broadcast_at' => 'datetime',
    ];

    // The user who requested the blood
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    // The people who responded to help
    public function responses()
    {
        return $this->hasMany(RequestResponse::class, 'request_id');
    }

    // Documents (Doctor's note, etc.)
    public function proofFiles()
    {
        return $this->morphMany(ProofFile::class, 'owner');
    }
}