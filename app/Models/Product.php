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
     * Get the cart items for this product.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
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
     * Scope for products with cart items.
     */
    public function scopeWithCartItems($query)
    {
        return $query->with(['cartItems' => function($q) {
            $q->with(['user:id,name,email'])
              ->orderBy('created_at', 'desc');
        }]);
    }

    /**
     * Scope for products with both orders and cart items.
     */
    public function scopeWithOrdersAndCart($query)
    {
        return $query->with([
            'orders' => function($q) {
                $q->with(['buyer:id,name,email'])
                  ->orderBy('created_at', 'desc');
            },
            'cartItems' => function($q) {
                $q->with(['user:id,name,email'])
                  ->orderBy('created_at', 'desc');
            },
            'user:id,name,email'
        ]);
    }

    /**
     * Scope for best selling products.
     */
    public function scopeBestSelling($query, $limit = 10)
    {
        return $query->withCount('orders as sales_count')
                    ->orderBy('sales_count', 'desc')
                    ->limit($limit);
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
        
        // If was sold/donated and stock is added, make available again
        if (in_array($this->status, ['sold', 'donated']) && $this->stock > 0) {
            $this->update(['status' => 'available']);
        }
    }
}
