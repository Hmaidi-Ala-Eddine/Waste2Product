<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'reserved_at',
        'reservation_data',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
        'reservation_data' => 'array',
    ];

    /**
     * Get the user who reserved the product
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
