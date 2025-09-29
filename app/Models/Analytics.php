<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'waste_data',
        'product_data',
        'order_data',
        'total_waste_quantity',
        'total_income',
        'total_products',
        'total_orders',
    ];

    protected $casts = [
        'date' => 'date',
        'waste_data' => 'array',
        'product_data' => 'array',
        'order_data' => 'array',
        'total_waste_quantity' => 'decimal:2',
        'total_income' => 'decimal:2',
    ];

    /**
     * Scope for today's analytics
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    /**
     * Scope for current month analytics
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
    }

    /**
     * Scope for specific month analytics
     */
    public function scopeForMonth($query, $month, $year = null)
    {
        $year = $year ?: Carbon::now()->year;
        return $query->whereMonth('date', $month)
                    ->whereYear('date', $year);
    }

    /**
     * Get waste types distribution for charts
     */
    public function getWasteTypesDistribution()
    {
        if (!$this->waste_data || !isset($this->waste_data['waste_types'])) {
            return [];
        }
        
        return $this->waste_data['waste_types'];
    }

    /**
     * Get daily waste quantity for the date
     */
    public function getDailyWasteQuantity()
    {
        return $this->waste_data['daily_quantity'] ?? 0;
    }

    /**
     * Get product categories distribution for charts
     */
    public function getProductCategoriesDistribution()
    {
        if (!$this->product_data || !isset($this->product_data['categories'])) {
            return [];
        }
        
        return $this->product_data['categories'];
    }

    /**
     * Get product status distribution for charts
     */
    public function getProductStatusDistribution()
    {
        if (!$this->product_data || !isset($this->product_data['status'])) {
            return [];
        }
        
        return $this->product_data['status'];
    }

    /**
     * Get product condition distribution for charts
     */
    public function getProductConditionDistribution()
    {
        if (!$this->product_data || !isset($this->product_data['conditions'])) {
            return [];
        }
        
        return $this->product_data['conditions'];
    }

    /**
     * Get order status distribution for charts
     */
    public function getOrderStatusDistribution()
    {
        if (!$this->order_data || !isset($this->order_data['status'])) {
            return [];
        }
        
        return $this->order_data['status'];
    }

    /**
     * Get daily income for the date
     */
    public function getDailyIncome()
    {
        return $this->order_data['daily_income'] ?? 0;
    }

    /**
     * Generate summary statistics for dashboard
     */
    public function getSummaryStats()
    {
        return [
            'date' => $this->date->format('Y-m-d'),
            'total_waste_quantity' => $this->total_waste_quantity,
            'total_income' => $this->total_income,
            'total_products' => $this->total_products,
            'total_orders' => $this->total_orders,
            'waste_types_count' => count($this->getWasteTypesDistribution()),
            'product_categories_count' => count($this->getProductCategoriesDistribution()),
            'order_statuses_count' => count($this->getOrderStatusDistribution()),
        ];
    }

    /**
     * Check if analytics exist for a specific date
     */
    public static function existsForDate($date)
    {
        return static::where('date', $date)->exists();
    }

    /**
     * Get or create analytics for a specific date
     */
    public static function getOrCreateForDate($date)
    {
        return static::firstOrCreate(
            ['date' => $date],
            [
                'waste_data' => [],
                'product_data' => [],
                'order_data' => [],
                'total_waste_quantity' => 0,
                'total_income' => 0,
                'total_products' => 0,
                'total_orders' => 0,
            ]
        );
    }
}