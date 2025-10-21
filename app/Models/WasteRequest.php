<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'collector_id',
        'waste_type',
        'quantity',
        'state',
        'address',
        'description',
        'status',
        'collected_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'collected_at' => 'datetime',
            'quantity' => 'decimal:2',
        ];
    }

    /**
     * Available waste types
     */
    public static function getWasteTypes(): array
    {
        return [
            'organic' => 'Organic Waste',
            'plastic' => 'Plastic',
            'metal' => 'Metal',
            'e-waste' => 'Electronic Waste',
            'paper' => 'Paper',
            'glass' => 'Glass',
            'textile' => 'Textile',
            'mixed' => 'Mixed Waste',
        ];
    }

    /**
     * Available statuses
     */
    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'collected' => 'Collected',
            'cancelled' => 'Cancelled',
        ];
    }

    /**
     * Get the customer who made the request
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the collector assigned to this request
     */
    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    /**
     * Get the rating for this waste request
     */
    public function rating()
    {
        return $this->hasOne(CollectorRating::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by waste type
     */
    public function scopeWasteType($query, $wasteType)
    {
        return $query->where('waste_type', $wasteType);
    }

    /**
     * Get the formatted waste type
     */
    public function getWasteTypeFormattedAttribute()
    {
        return self::getWasteTypes()[$this->waste_type] ?? $this->waste_type;
    }

    /**
     * Get the formatted status
     */
    public function getStatusFormattedAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get the status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'accepted' => 'bg-info',
            'collected' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
