<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectorRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'waste_request_id',
        'collector_id',
        'customer_id',
        'rating',
        'review',
    ];

    /**
     * Get the waste request that was rated
     */
    public function wasteRequest()
    {
        return $this->belongsTo(WasteRequest::class);
    }

    /**
     * Get the collector being rated
     */
    public function collector()
    {
        return $this->belongsTo(Collector::class);
    }

    /**
     * Get the customer who gave the rating
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
