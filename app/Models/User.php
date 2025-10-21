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
     * Get cart items for this user
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get cart items collection for this user
     */
    public function getCartItems()
    {
        return $this->cartItems()->get();
    }

    /**
     * Add item to cart
     */
    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->status !== 'available') {
            return false;
        }

        // Check if item already exists in cart
        $existingItem = $this->cartItems()
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->quantity += $quantity;
            $existingItem->total_price = $existingItem->quantity * $existingItem->price;
            $existingItem->save();
        } else {
            // Create new item
            $this->cartItems()->create([
                'session_id' => session()->getId(),
                'user_id' => $this->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price ?? 0,
                'total_price' => ($product->price ?? 0) * $quantity,
            ]);
        }

        return true;
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem($productId, $quantity)
    {
        $cartItem = $this->cartItems()
            ->where('product_id', $productId)
            ->first();
        
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
     * Remove item from cart
     */
    public function removeFromCart($productId)
    {
        $cartItem = $this->cartItems()
            ->where('product_id', $productId)
            ->first();
        
        if (!$cartItem) {
            return false;
        }

        return $cartItem->delete();
    }

    /**
     * Clear user's cart
     */
    public function clearCart()
    {
        return $this->cartItems()->delete() > 0;
    }

    /**
     * Get cart total
     */
    public function getCartTotalAttribute()
    {
        return $this->cartItems()->with('product')->get()->sum('subtotal');
    }

    /**
     * Get cart count
     */
    public function getCartCountAttribute()
    {
        return $this->cartItems()->sum('quantity');
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
}
