<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Keep Spatie roles/permissions on the web guard for this shared users table.
     */
    protected string $guard_name = 'web';

    public function getDefaultGuardName(): string
    {
        return $this->guard_name;
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'avatar',
        'password',
        'usertype',
        'blood_type',
        'id_number',
        'kyc_status',
        'kyc_rejected_reason',
        'kyc_verified_at',
        'kyc_verified_by_admin_id',
        'status',
        'last_login_at',
        'google_id',
        'telegram_id',
        'telegram_username',
        'auth_provider'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'kyc_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // --- Relationships ---

    // A user can make many blood requests
    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class, 'requester_id');
    }

    // A user can submit many donation invoices
    public function donationInvoices()
    {
        return $this->hasMany(DonationInvoice::class);
    }

    // A user can respond to many requests (donate to specific people)
    public function requestResponses()
    {
        return $this->hasMany(RequestResponse::class, 'responder_id');
    }

    // Polymorphic: The user's ID Card photo
    public function idPhoto()
    {
        return $this->morphOne(ProofFile::class, 'owner')->where('file_type', 'id_photo');
    }

    public function proofFiles()
    {
        return $this->morphMany(ProofFile::class, 'fileable');
    }
}