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
        'total_price' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    /**
     * Get the buyer (user) for this order.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the product for this order.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for orders by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for confirmed orders.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for delivered orders.
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope for cancelled orders.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for completed orders.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for orders with product details.
     */
    public function scopeWithProduct($query)
    {
        return $query->with(['product:id,name,price,category,status']);
    }

    /**
     * Scope for orders with buyer details.
     */
    public function scopeWithBuyer($query)
    {
        return $query->with(['buyer:id,name,email']);
    }

    /**
     * Scope for orders with full details.
     */
    public function scopeWithFullDetails($query)
    {
        return $query->with([
            'product:id,name,price,category,status,user_id',
            'product.user:id,name,email',
            'buyer:id,name,email'
        ]);
    }

    /**
     * Scope for recent orders.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for orders by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for high value orders.
     */
    public function scopeHighValue($query, $minAmount = 100)
    {
        return $query->where('total_price', '>=', $minAmount);
    }

    /**
     * Get formatted total price.
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Check if order is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if order is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if order is delivered.
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if order is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if order is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Scope for orders by payment status.
     */
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope for orders by payment method.
     */
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope for paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'completed');
    }

    /**
     * Scope for unpaid orders.
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', ['pending', 'failed']);
    }

    /**
     * Get payment status badge class.
     */
    public function getPaymentStatusBadgeClassAttribute(): string
    {
        return match($this->payment_status) {
            'completed' => 'success',
            'pending' => 'warning',
            'processing' => 'info',
            'failed' => 'danger',
            'refunded' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Check if payment is completed.
     */
    public function isPaymentCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if payment is pending.
     */
    public function isPaymentPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment is failed.
     */
    public function isPaymentFailed(): bool
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Mark payment as completed.
     */
    public function markPaymentAsCompleted(string $transactionId = null, array $gatewayResponse = null): bool
    {
        $this->payment_status = 'completed';
        $this->payment_processed_at = now();
        
        if ($transactionId) {
            $this->transaction_id = $transactionId;
        }
        
        if ($gatewayResponse) {
            $this->gateway_response = $gatewayResponse;
        }
        
        return $this->save();
    }

    /**
     * Mark payment as failed.
     */
    public function markPaymentAsFailed(string $reason = null, array $gatewayResponse = null): bool
    {
        $this->payment_status = 'failed';
        
        if ($reason) {
            $this->payment_notes = $reason;
        }
        
        if ($gatewayResponse) {
            $this->gateway_response = $gatewayResponse;
        }
        
        return $this->save();
    }

    /**
     * Mark payment as processing.
     */
    public function markPaymentAsProcessing(): bool
    {
        $this->payment_status = 'processing';
        return $this->save();
    }
}
