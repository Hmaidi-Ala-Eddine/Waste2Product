<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'role',
        'profile_photo_path',
        'profile_picture',
        'password',
        'is_active',
        'faceid_enabled',
        'forgot_password_token',
        'jwt_token',
        'jwt_expires_at',
        'cart_json',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'forgot_password_token',
        'jwt_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'jwt_expires_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'faceid_enabled' => 'boolean',
            'password' => 'hashed',
            'cart_json' => 'array',
        ];
    }

    /**
     * Get the waste requests made by this user (as customer)
     */
    public function wasteRequests()
    {
        return $this->hasMany(WasteRequest::class, 'user_id');
    }

    /**
     * Get the waste requests assigned to this user (as collector)
     */
    public function assignedWasteRequests()
    {
        return $this->hasMany(WasteRequest::class, 'collector_id');
    }

    /**
     * Get the posts for this user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comments for this user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the collector profile for this user (if they are a collector)
     */
    public function collector()
    {
        return $this->hasOne(Collector::class);
    }

    /**
     * Check if user is a verified collector
     */
    public function isVerifiedCollector()
    {
        return $this->collector && $this->collector->verification_status === 'verified';
    }

    /**
     * Check if user has a collector profile (any status)
     */
    public function hasCollectorProfile()
    {
        return $this->collector !== null;
    }

    /**
     * Get collector status badge class for UI
     */
    public function getCollectorBadgeAttribute()
    {
        if (!$this->collector) {
            return null;
        }

        return match($this->collector->verification_status) {
            'pending' => 'bg-warning',
            'verified' => 'bg-gradient-success',
            'suspended' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the profile picture URL with fallback to UI Avatars
     */
    public function getProfilePictureUrlAttribute()
    {
        // If user has uploaded profile picture and file exists
        if ($this->profile_picture && file_exists(public_path($this->profile_picture))) {
            return asset($this->profile_picture);
        }
        
        // Fallback to UI Avatars with user's name
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=fff&background=667eea&size=200&font-size=0.5&bold=true';
    }

    /**
     * Get user initials for avatar
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get cart items from JSON
     */
    public function getCartItems()
    {
        if (!$this->cart_json || !isset($this->cart_json['items'])) {
            return collect();
        }

        return collect($this->cart_json['items'])->map(function ($item, $index) {
            $product = Product::find($item['product_id']);
            if ($product) {
                return (object) [
                    'id' => $index, // Use index as ID for frontend compatibility
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'product' => $product,
                    'subtotal' => $product->price * $item['quantity'],
                    'added_at' => $item['added_at'] ?? null
                ];
            }
            return null;
        })->filter();
    }

    /**
     * Add product to cart
     */
    public function addToCart($productId, $quantity = 1)
    {
        $cart = $this->cart_json ?? ['items' => []];
        
        // Check if product already exists in cart
        $existingIndex = null;
        foreach ($cart['items'] as $index => $item) {
            if ($item['product_id'] == $productId) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            // Update existing item
            $cart['items'][$existingIndex]['quantity'] += $quantity;
        } else {
            // Add new item
            $cart['items'][] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'added_at' => now()->toISOString()
            ];
        }

        $this->cart_json = $cart;
        $this->save();
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem($productId, $quantity)
    {
        $cart = $this->cart_json ?? ['items' => []];
        
        foreach ($cart['items'] as $index => $item) {
            if ($item['product_id'] == $productId) {
                if ($quantity <= 0) {
                    unset($cart['items'][$index]);
                } else {
                    $cart['items'][$index]['quantity'] = $quantity;
                }
                break;
            }
        }

        $cart['items'] = array_values($cart['items']); // Re-index array
        $this->cart_json = $cart;
        $this->save();
    }

    /**
     * Remove product from cart
     */
    public function removeFromCart($productId)
    {
        $cart = $this->cart_json ?? ['items' => []];
        
        $cart['items'] = array_filter($cart['items'], function ($item) use ($productId) {
            return $item['product_id'] != $productId;
        });

        $cart['items'] = array_values($cart['items']); // Re-index array
        $this->cart_json = $cart;
        $this->save();
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        $this->cart_json = ['items' => []];
        $this->save();
    }

    /**
     * Get cart count
     */
    public function getCartCountAttribute()
    {
        if (!$this->cart_json || !isset($this->cart_json['items'])) {
            return 0;
        }

        return array_sum(array_column($this->cart_json['items'], 'quantity'));
    }

    /**
     * Get cart total
     */
    public function getCartTotalAttribute()
    {
        $items = $this->getCartItems();
        return $items->sum('subtotal');
    }
}
