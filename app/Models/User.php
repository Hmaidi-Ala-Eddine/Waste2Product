<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'role',
        'profile_photo_path',
        'password',
        'is_active',
        'faceid_enabled',
        'forgot_password_token',
        'jwt_token',
        'jwt_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'forgot_password_token',
        'jwt_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'jwt_expires_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'faceid_enabled' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the waste requests made by this user (as customer)
     */
    public function wasteRequests()
    {
        return $this->hasMany(WasteRequest::class, 'user_id');
    }

    /**
     * Get the waste requests assigned to this user (as collector)
     */
    public function assignedWasteRequests()
    {
        return $this->hasMany(WasteRequest::class, 'collector_id');
    }

    /**
     * Get the posts for this user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comments for this user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the collector profile for this user (if they are a collector)
     */
    public function collector()
    {
        return $this->hasOne(Collector::class);
    }

    /**
     * Check if user is a verified collector
     */
    public function isVerifiedCollector()
    {
        return $this->collector && $this->collector->verification_status === 'verified';
    }

    /**
     * Check if user has a collector profile (any status)
     */
    public function hasCollectorProfile()
    {
        return $this->collector !== null;
    }

    /**
     * Get collector status badge class for UI
     */
    public function getCollectorBadgeAttribute()
    {
        if (!$this->collector) {
            return null;
        }

        return match($this->collector->verification_status) {
            'pending' => 'bg-warning',
            'verified' => 'bg-gradient-success',
            'suspended' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
