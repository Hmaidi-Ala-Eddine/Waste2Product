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
        'stock',
        'status',
        'image_path',
        'reserved_by',
        'reserved_at',
        'reserved_message',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'reserved_at' => 'datetime',
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
            'excellent' => 'success',
            'good' => 'info',
            'fair' => 'warning',
            'poor' => 'danger',
            default => 'secondary'
        };
    }

    /**
<<<<<<< HEAD
     * Scope for products with orders.
     */
    public function scopeWithOrders($query)
    {
        return $query->with(['orders' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);
    }

    /**
     * Scope for products with order statistics.
     */
    public function scopeWithOrderStats($query)
    {
        return $query->withCount([
            'orders as total_orders',
            'orders as pending_orders' => function($q) {
                $q->where('status', 'pending');
            },
            'orders as confirmed_orders' => function($q) {
                $q->where('status', 'confirmed');
            },
            'orders as completed_orders' => function($q) {
                $q->where('status', 'delivered');
            }
        ])->withSum('orders', 'total_price');
    }

    /**
     * Scope for products with recent orders.
     */
    public function scopeWithRecentOrders($query, $days = 30)
    {
        return $query->with(['orders' => function($q) use ($days) {
            $q->where('created_at', '>=', now()->subDays($days))
              ->orderBy('created_at', 'desc');
        }]);
    }

    /**
     * Scope for products with buyer information.
     */
    public function scopeWithBuyers($query)
    {
        return $query->with(['orders.buyer' => function($q) {
            $q->select('id', 'name', 'email');
        }]);
    }

    /**
     * Scope for products with full order details.
     */
    public function scopeWithFullOrderDetails($query)
    {
        return $query->with([
            'orders' => function($q) {
                $q->with(['buyer:id,name,email'])
                  ->orderBy('created_at', 'desc');
            },
            'user:id,name,email'
        ]);
    }

    /**
     * Scope for products with highest revenue.
     */
    public function scopeHighestRevenue($query, $limit = 10)
    {
        return $query->withSum('orders', 'total_price as total_revenue')
                    ->orderBy('total_revenue', 'desc')
                    ->limit($limit);
    }

    /**
     * Scope for products with no orders.
     */
    public function scopeWithoutOrders($query)
    {
        return $query->doesntHave('orders');
    }

    /**
     * Scope for products with pending orders.
     */
    public function scopeWithPendingOrders($query)
    {
        return $query->whereHas('orders', function($q) {
            $q->where('status', 'pending');
        });
    }

    /**
     * Get total revenue for this product.
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->orders()->sum('total_price') ?? 0;
    }

    /**
     * Get total sales count for this product.
     */
    public function getTotalSalesAttribute(): int
    {
        return $this->orders()->count();
    }

    /**
     * Get average order value for this product.
     */
    public function getAverageOrderValueAttribute(): float
    {
        $totalOrders = $this->orders()->count();
        if ($totalOrders === 0) return 0;
        
        return $this->total_revenue / $totalOrders;
    }

    /**
     * Get last order for this product.
     */
    public function getLastOrderAttribute()
    {
        return $this->orders()->latest()->first();
    }

    /**
     * Check if product has any orders.
     */
    public function hasOrders(): bool
    {
        return $this->orders()->exists();
    }

    /**
     * Check if product has pending orders.
     */
    public function hasPendingOrders(): bool
    {
        return $this->orders()->where('status', 'pending')->exists();
    }

    /**
     * Check if product is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Check if product is available for purchase.
     */
    public function isAvailableForPurchase(): bool
    {
        return $this->status === 'available' && $this->isInStock();
    }

    /**
     * Decrease stock and update status if out of stock.
     */
    public function decreaseStock(int $quantity = 1): void
    {
        $this->decrement('stock', $quantity);
        
        if ($this->stock <= 0) {
            $this->update(['status' => 'sold', 'stock' => 0]);
        }
    }

    /**
     * Increase stock.
     */
    public function increaseStock(int $quantity = 1): void
    {
        $this->increment('stock', $quantity);
        
        // If was sold and stock is added, make available again
        if ($this->status === 'sold' && $this->stock > 0) {
            $this->update(['status' => 'available']);
        }
    }

    /**
     * Make product available and ensure it has stock
     */
    public function makeAvailable(int $stock = 1): void
    {
        $this->update([
            'status' => 'available',
            'stock' => max($stock, 1), // Ensure at least 1 in stock
            'reserved_by' => null,
            'reserved_at' => null,
            'reserved_message' => null,
        ]);
    }

    /**
     * Boot method to handle automatic stock management
     */
    protected static function boot()
    {
        parent::boot();

        // When status is updated to 'available', ensure stock > 0
        static::updating(function ($product) {
            if ($product->isDirty('status') && $product->status === 'available') {
                // If stock is 0 or null, set it to 1
                if ($product->stock <= 0) {
                    $product->stock = 1;
                }
                // Clear any reservation data
                $product->reserved_by = null;
                $product->reserved_at = null;
                $product->reserved_message = null;
            }
        });
    }
}
