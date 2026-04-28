<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected string $guard_name = 'web';

    public function getDefaultGuardName(): string
    {
        return $this->guard_name;
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'google_id',
        'telegram_id',
        'telegram_username',
        'telegram_photo_url',
        'auth_provider',
        'usertype',
        'status',
        'last_login_at',
        'blood_type',
        'id_number',
        'kyc_status',
        'kyc_rejected_reason',
        'kyc_verified_at',
        'kyc_verified_by_admin_id',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'kyc_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'requester_id');
    }

    public function donationInvoices(): HasMany
    {
        return $this->hasMany(DonationInvoice::class);
    }

    public function requestResponses(): HasMany
    {
        return $this->hasMany(RequestResponse::class, 'responder_id');
    }

    public function idPhoto(): MorphOne
    {
        return $this->morphOne(ProofFile::class, 'owner')
            ->where('file_type', 'id_photo');
    }

    public function proofFiles(): MorphMany
    {
        return $this->morphMany(ProofFile::class, 'fileable');
    }
}