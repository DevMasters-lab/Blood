<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Device extends Model
{
    use Notifiable;

    protected $fillable = ['device_uuid', 'fcm_token', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}