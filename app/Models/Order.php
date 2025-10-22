<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'transaction_id',
        'gateway',
        'gateway_response',
        'payment_notes',
        'payment_processed_at',
        'shipping_address',
        'order_notes',
        'ordered_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'payment_processed_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
