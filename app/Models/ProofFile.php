<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProofFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    // CHANGE THIS FROM 'owner' TO 'fileable'
    public function fileable()
    {
        return $this->morphTo();
    }
    
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}