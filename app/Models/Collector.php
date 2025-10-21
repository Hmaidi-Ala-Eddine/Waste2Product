<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collector extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_name',
        'vehicle_type',
        'service_areas',
        'capacity_kg',
        'availability_schedule',
        'verification_status',
        'rating',
        'total_collections',
        'bio',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'service_areas' => 'array',
            'availability_schedule' => 'array',
            'capacity_kg' => 'decimal:2',
            'rating' => 'decimal:2',
            'total_collections' => 'integer',
        ];
    }

    /**
     * Available vehicle types
     */
    public static function getVehicleTypes(): array
    {
        return [
            'truck' => 'Truck',
            'van' => 'Van',
            'motorcycle' => 'Motorcycle',
            'bicycle' => 'Bicycle',
            'cart' => 'Cart',
            'other' => 'Other',
        ];
    }

    /**
     * Available verification statuses
     */
    public static function getVerificationStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'verified' => 'Verified',
            'suspended' => 'Suspended',
        ];
    }

    /**
     * Get the user/owner of this collector profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the waste requests assigned to this collector
     */
    public function wasteRequests()
    {
        return $this->hasMany(WasteRequest::class, 'collector_id', 'user_id');
    }

    /**
     * Scope to filter by verification status
     */
    public function scopeVerificationStatus($query, $status)
    {
        return $query->where('verification_status', $status);
    }

    /**
     * Scope to filter verified collectors only
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope to filter by vehicle type
     */
    public function scopeVehicleType($query, $vehicleType)
    {
        return $query->where('vehicle_type', $vehicleType);
    }

    /**
     * Get the formatted vehicle type
     */
    public function getVehicleTypeFormattedAttribute()
    {
        return self::getVehicleTypes()[$this->vehicle_type] ?? $this->vehicle_type;
    }

    /**
     * Get the formatted verification status
     */
    public function getVerificationStatusFormattedAttribute()
    {
        return self::getVerificationStatuses()[$this->verification_status] ?? $this->verification_status;
    }

    /**
     * Get the status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->verification_status) {
            'pending' => 'bg-warning',
            'verified' => 'bg-success',
            'suspended' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Check if collector is available (verified and active user)
     */
    public function getIsAvailableAttribute()
    {
        return $this->verification_status === 'verified' 
            && $this->user 
            && $this->user->is_active;
    }

    /**
     * Get service areas as comma-separated string
     */
    public function getServiceAreasStringAttribute()
    {
        if (!$this->service_areas || !is_array($this->service_areas)) {
            return 'No areas specified';
        }
        return implode(', ', $this->service_areas);
    }
}
