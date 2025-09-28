<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'waste_request_id',
        'user_id',
        'name',
        'description',
        'category',
        'condition',
        'price',
        'status',
        'image_path',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the user who owns the product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the waste request that this product was created from.
     */
    public function wasteRequest(): BelongsTo
    {
        return $this->belongsTo(WasteRequest::class);
    }

    /**
     * Get the orders for this product.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope for available products.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope for products by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for free products.
     */
    public function scopeFree($query)
    {
        return $query->whereNull('price');
    }

    /**
     * Scope for paid products.
     */
    public function scopePaid($query)
    {
        return $query->whereNotNull('price');
    }

    /**
     * Check if product is free.
     */
    public function isFree(): bool
    {
        return is_null($this->price);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->isFree() ? 'Gratuit' : number_format($this->price, 2) . ' €';
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'available' => 'success',
            'sold' => 'danger',
            'donated' => 'info',
            'reserved' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Get condition badge class.
     */
    public function getConditionBadgeClassAttribute(): string
    {
        return match($this->condition) {
            'new' => 'success',
            'refurbished' => 'warning',
            'used' => 'secondary',
            default => 'secondary'
        };
    }
}
