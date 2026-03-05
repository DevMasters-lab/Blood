<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProofFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    // This connects the file to the user or invoice
    public function fileable()
    {
        return $this->morphTo();
    }
    
    // Helper to get the full image URL
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}