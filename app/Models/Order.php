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
        'ordered_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
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
