<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    protected $table = 'cart';
    
    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'quantity',
        'price',
        'total_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the product for this cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user for this cart item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for cart items by session.
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope for cart items by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for cart items with product details.
     */
    public function scopeWithProduct($query)
    {
        return $query->with(['product:id,name,price,category,status,image_path']);
    }

    /**
     * Get current session ID.
     */
    public static function getSessionId(): string
    {
        return Session::getId();
    }

    /**
     * Add item to cart.
     */
    public static function addItem($productId, $quantity = 1, $userId = null): bool
    {
        $product = Product::find($productId);
        
        if (!$product || $product->status !== 'available') {
            return false;
        }

        $sessionId = self::getSessionId();
        
        // Check if item already exists in cart
        $existingItem = self::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->when($userId, function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->quantity += $quantity;
            $existingItem->total_price = $existingItem->quantity * $existingItem->price;
            $existingItem->save();
        } else {
            // Create new item
            self::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price ?? 0,
                'total_price' => ($product->price ?? 0) * $quantity,
            ]);
        }

        return true;
    }

    /**
     * Update item quantity in cart.
     */
    public static function updateQuantity($cartItemId, $quantity): bool
    {
        $cartItem = self::find($cartItemId);
        
        if (!$cartItem) {
            return false;
        }

        if ($quantity <= 0) {
            return $cartItem->delete();
        }

        $cartItem->quantity = $quantity;
        $cartItem->total_price = $cartItem->quantity * $cartItem->price;
        $cartItem->save();

        return true;
    }

    /**
     * Remove item from cart.
     */
    public static function removeItem($cartItemId): bool
    {
        $cartItem = self::find($cartItemId);
        
        if (!$cartItem) {
            return false;
        }

        return $cartItem->delete();
    }

    /**
     * Get cart items for current session.
     */
    public static function getCartItems($userId = null)
    {
        $sessionId = self::getSessionId();
        
        return self::withProduct()
            ->where('session_id', $sessionId)
            ->when($userId, function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get();
    }

    /**
     * Get cart total.
     */
    public static function getCartTotal($userId = null): float
    {
        $sessionId = self::getSessionId();
        
        return self::where('session_id', $sessionId)
            ->when($userId, function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->sum('total_price');
    }

    /**
     * Get cart items count.
     */
    public static function getCartItemsCount($userId = null): int
    {
        $sessionId = self::getSessionId();
        
        return self::where('session_id', $sessionId)
            ->when($userId, function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->sum('quantity');
    }

    /**
     * Clear cart for current session.
     */
    public static function clearCart($userId = null): bool
    {
        $sessionId = self::getSessionId();
        
        return self::where('session_id', $sessionId)
            ->when($userId, function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->delete() > 0;
    }

    /**
     * Transfer cart from session to user.
     */
    public static function transferToUser($userId): bool
    {
        $sessionId = self::getSessionId();
        
        // Update existing cart items to belong to user
        return self::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->update(['user_id' => $userId]) > 0;
    }

    /**
     * Get formatted total price.
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get subtotal (alias for total_price for compatibility).
     */
    public function getSubtotalAttribute(): float
    {
        return $this->total_price;
    }
}
