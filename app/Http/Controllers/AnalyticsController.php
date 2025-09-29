<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use App\Models\WasteRequest;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Get current month analytics records (for the table display)
        $monthlyAnalytics = Analytics::forMonth($currentMonth, $currentYear)
            ->orderBy('date')
            ->get();
            
        // Get today's analytics if exists
        $todayAnalytics = Analytics::today()->first();
        
        // Prepare chart data (now gets data directly from database for entire month)
        $chartData = $this->prepareChartData($monthlyAnalytics);
        
        return view('back.pages.analytics', compact('monthlyAnalytics', 'todayAnalytics', 'chartData'));
    }

    /**
     * Generate today's statistics
     */
    public function generateTodayStats()
    {
        $today = Carbon::today();
        
        try {
            // Get or create today's analytics record
            $analytics = Analytics::getOrCreateForDate($today);
            
            // Collect waste request data
            $wasteData = $this->collectWasteData($today);
            
            // Collect product data
            $productData = $this->collectProductData($today);
            
            // Collect order data
            $orderData = $this->collectOrderData($today);
            
            // Update analytics record
            $analytics->update([
                'waste_data' => $wasteData['data'],
                'product_data' => $productData['data'],
                'order_data' => $orderData['data'],
                'total_waste_quantity' => $wasteData['total_quantity'],
                'total_income' => $orderData['total_income'],
                'total_products' => $productData['total_count'],
                'total_orders' => $orderData['total_count'],
            ]);
            
            $successMessage = "Today's statistics generated successfully! ";
            $successMessage .= "Found: {$productData['total_count']} products, ";
            $successMessage .= "{$orderData['total_count']} orders, ";
            $successMessage .= "waste total: {$wasteData['total_quantity']} kg, ";
            $successMessage .= "income: $" . number_format($orderData['total_income'], 2);
            
            return redirect()->route('admin.analytics.index')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.analytics.index')
                ->with('error', 'Failed to generate statistics: ' . $e->getMessage());
        }
    }

    /**
     * Check for missing analytics and update today's record
     */
    public function checkMissingAnalytics()
    {
        $today = Carbon::today();
        
        try {
            // Check if today's analytics exist
            $analytics = Analytics::where('date', $today)->first();
            
            if (!$analytics) {
                return redirect()->route('admin.analytics.index')
                    ->with('info', 'No analytics record found for today. Please generate today\'s statistics first.');
            }
            
            // Store old values for comparison
            $oldWasteQuantity = $analytics->total_waste_quantity;
            $oldIncome = $analytics->total_income;
            $oldProductCount = $analytics->total_products;
            $oldOrderCount = $analytics->total_orders;
            
            // Get current data from database
            $currentWasteData = $this->collectWasteData($today);
            $currentProductData = $this->collectProductData($today);
            $currentOrderData = $this->collectOrderData($today);
            
            // Always update the analytics record with fresh data
            $analytics->update([
                'waste_data' => $currentWasteData['data'],
                'product_data' => $currentProductData['data'],
                'order_data' => $currentOrderData['data'],
                'total_waste_quantity' => $currentWasteData['total_quantity'],
                'total_income' => $currentOrderData['total_income'],
                'total_products' => $currentProductData['total_count'],
                'total_orders' => $currentOrderData['total_count'],
            ]);
            
            // Check if any values changed (using string comparison to catch decimal differences)
            $changes = [];
            if ((string)$oldWasteQuantity !== (string)$currentWasteData['total_quantity']) {
                $changes[] = "Waste: {$oldWasteQuantity} → {$currentWasteData['total_quantity']} kg";
            }
            if ((string)$oldIncome !== (string)$currentOrderData['total_income']) {
                $changes[] = "Income: $" . number_format((float)$oldIncome, 2) . " → $" . number_format((float)$currentOrderData['total_income'], 2);
            }
            if ($oldProductCount !== $currentProductData['total_count']) {
                $changes[] = "Products: {$oldProductCount} → {$currentProductData['total_count']}";
            }
            if ($oldOrderCount !== $currentOrderData['total_count']) {
                $changes[] = "Orders: {$oldOrderCount} → {$currentOrderData['total_count']}";
            }
            
            // Also check if the detailed data structures changed
            $oldWasteDataJson = json_encode($analytics->waste_data);
            $newWasteDataJson = json_encode($currentWasteData['data']);
            $oldProductDataJson = json_encode($analytics->product_data);
            $newProductDataJson = json_encode($currentProductData['data']);
            $oldOrderDataJson = json_encode($analytics->order_data);
            $newOrderDataJson = json_encode($currentOrderData['data']);
            
            if ($oldWasteDataJson !== $newWasteDataJson) {
                if (!in_array('Waste data structure changed', array_column($changes, null))) {
                    $changes[] = "Waste data updated";
                }
            }
            if ($oldProductDataJson !== $newProductDataJson) {
                if (!in_array('Product data structure changed', array_column($changes, null))) {
                    $changes[] = "Product data updated";
                }
            }
            if ($oldOrderDataJson !== $newOrderDataJson) {
                if (!in_array('Order data structure changed', array_column($changes, null))) {
                    $changes[] = "Order data updated";
                }
            }
            
            if (!empty($changes)) {
                $message = 'Analytics updated! Changes: ' . implode(', ', $changes);
                return redirect()->route('admin.analytics.index')
                    ->with('success', $message);
            } else {
                return redirect()->route('admin.analytics.index')
                    ->with('info', 'Analytics refreshed. No changes detected from last update.');
            }
                
        } catch (\Exception $e) {
            return redirect()->route('admin.analytics.index')
                ->with('error', 'Failed to check analytics: ' . $e->getMessage());
        }
    }

    /**
     * Collect waste request data for a specific date
     */
    private function collectWasteData($date)
    {
        // Get ALL waste requests created on this date using date range
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();
        
        $wasteRequests = WasteRequest::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->select('waste_type', 'quantity', 'created_at')
            ->get();
        
        // Debug: Log the found records
        Log::info("Waste Requests for {$date->format('Y-m-d')}: Found {$wasteRequests->count()} records", [
            'date_range' => [$startOfDay->format('Y-m-d H:i:s'), $endOfDay->format('Y-m-d H:i:s')],
            'records' => $wasteRequests->map(function($wr) {
                return [
                    'waste_type' => $wr->waste_type,
                    'quantity' => $wr->quantity,
                    'created_at' => $wr->created_at->format('Y-m-d H:i:s')
                ];
            })->toArray()
        ]);
            
        // Group by waste type and sum quantities
        $wasteTypes = $wasteRequests->groupBy('waste_type')->map(function ($requests) {
            return $requests->sum('quantity');
        })->toArray();
        
        // Sum total quantity from all waste requests created today
        $totalQuantity = $wasteRequests->sum('quantity');
        
        Log::info("Waste total calculated: {$totalQuantity}");
        
        return [
            'data' => [
                'waste_types' => $wasteTypes,
                'daily_quantity' => $totalQuantity,
                'count' => $wasteRequests->count(),
            ],
            'total_quantity' => $totalQuantity,
        ];
    }

    /**
     * Collect product data for a specific date
     */
    private function collectProductData($date)
    {
        // Get ALL products created on this date using date range
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();
        
        $products = Product::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->select('category', 'status', 'condition', 'created_at')
            ->get();
        
        Log::info("Products for {$date->format('Y-m-d')}: Found {$products->count()} records");
            
        // Group by category and count
        $categories = $products->groupBy('category')->map->count()->toArray();
        
        // Group by status and count
        $statuses = $products->groupBy('status')->map->count()->toArray();
        
        // Group by condition and count (filter out nulls)
        $conditions = $products->filter(function($product) {
            return !is_null($product->condition);
        })->groupBy('condition')->map->count()->toArray();
        
        // Total count of all products created today
        $totalCount = $products->count();
        
        return [
            'data' => [
                'categories' => $categories,
                'status' => $statuses,
                'conditions' => $conditions,
                'count' => $totalCount,
            ],
            'total_count' => $totalCount,
        ];
    }

    /**
     * Collect order data for a specific date
     */
    private function collectOrderData($date)
    {
        // Get ALL orders created on this date using date range
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();
        
        $orders = Order::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->select('total_price', 'status', 'created_at')
            ->get();
        
        Log::info("Orders for {$date->format('Y-m-d')}: Found {$orders->count()} records");
            
        // Group by status and count
        $statuses = $orders->groupBy('status')->map->count()->toArray();
        
        // Sum total price from all orders created today
        $totalIncome = $orders->sum('total_price');
        
        // Total count of all orders created today
        $totalCount = $orders->count();
        
        return [
            'data' => [
                'status' => $statuses,
                'daily_income' => $totalIncome,
                'count' => $totalCount,
            ],
            'total_income' => $totalIncome,
            'total_count' => $totalCount,
        ];
    }

    /**
     * Prepare chart data for frontend - gets data directly from database for entire month
     */
    private function prepareChartData($monthlyAnalytics)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Get start and end of current month
        $startOfMonth = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
        $endOfMonth = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth()->endOfDay();
        
        // Get actual data from database for the entire month
        $monthlyWasteRequests = WasteRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
        $monthlyProducts = Product::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
        $monthlyOrders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
        
        // Prepare waste data
        $wasteTypesPie = $monthlyWasteRequests->groupBy('waste_type')->map(function ($requests) {
            return $requests->sum('quantity');
        })->toArray();
        
        // Daily waste quantity (each day in month)
        $wasteQuantityBar = [];
        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $dayDate = Carbon::create($currentYear, $currentMonth, $day);
            $dayWaste = $monthlyWasteRequests->filter(function($wr) use ($dayDate) {
                return $wr->created_at->format('Y-m-d') === $dayDate->format('Y-m-d');
            })->sum('quantity');
            if ($dayWaste > 0) {
                $wasteQuantityBar[$dayDate->format('M d')] = $dayWaste;
            }
        }
        
        // Prepare product data
        $productCategoriesPie = $monthlyProducts->groupBy('category')->map->count()->toArray();
        $productStatusPie = $monthlyProducts->groupBy('status')->map->count()->toArray();
        $productConditionsBar = $monthlyProducts->filter(function($product) {
            return !is_null($product->condition);
        })->groupBy('condition')->map->count()->toArray();
        
        // Prepare order data
        $orderStatusPie = $monthlyOrders->groupBy('status')->map->count()->toArray();
        
        // Daily income (each day in month)
        $incomeLineChart = [];
        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $dayDate = Carbon::create($currentYear, $currentMonth, $day);
            $dayIncome = $monthlyOrders->filter(function($order) use ($dayDate) {
                return $order->created_at->format('Y-m-d') === $dayDate->format('Y-m-d');
            })->sum('total_price');
            if ($dayIncome > 0) {
                $incomeLineChart[$dayDate->format('M d')] = $dayIncome;
            }
        }
        
        return [
            'waste_types_pie' => $wasteTypesPie,
            'waste_quantity_bar' => $wasteQuantityBar,
            'product_categories_pie' => $productCategoriesPie,
            'product_status_pie' => $productStatusPie,
            'product_conditions_bar' => $productConditionsBar,
            'order_status_pie' => $orderStatusPie,
            'income_line_chart' => $incomeLineChart,
        ];
    }

    /**
     * Get analytics data for AJAX requests
     */
    public function getData(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $analytics = Analytics::forMonth($month, $year)
            ->orderBy('date')
            ->get();
            
        $chartData = $this->prepareChartData($analytics);
        
        return response()->json([
            'success' => true,
            'data' => $chartData,
            'analytics' => $analytics->map->getSummaryStats(),
        ]);
    }

    /**
     * Delete analytics record
     */
    public function destroy(Analytics $analytics)
    {
        $analytics->delete();
        
        return redirect()->route('admin.analytics.index')
            ->with('success', 'Analytics record deleted successfully!');
    }
}