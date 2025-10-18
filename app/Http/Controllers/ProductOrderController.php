<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProductOrderController extends Controller
{
    /**
     * Get products with their order statistics
     */
    public function productsWithOrderStats(Request $request): JsonResponse
    {
        $products = Product::withOrderStats()
            ->when($request->filled('category'), function($q) use ($request) {
                $q->where('category', $request->category);
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderBy('total_orders', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get best selling products
     */
    public function bestSellingProducts(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $days = $request->get('days', 30);

        $products = Product::bestSelling($limit)
            ->withRecentOrders($days)
            ->with(['user:id,name'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products with highest revenue
     */
    public function highestRevenueProducts(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);

        $products = Product::highestRevenue($limit)
            ->with(['user:id,name'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products with pending orders
     */
    public function productsWithPendingOrders(): JsonResponse
    {
        $products = Product::withPendingOrders()
            ->withFullOrderDetails()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products without orders
     */
    public function productsWithoutOrders(): JsonResponse
    {
        $products = Product::withoutOrders()
            ->with(['user:id,name'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get detailed product with all orders
     */
    public function productWithOrders(Product $product): JsonResponse
    {
        $product->load([
            'orders' => function($q) {
                $q->with(['buyer:id,name,email'])
                  ->orderBy('created_at', 'desc');
            },
            'user:id,name,email'
        ]);

        $stats = $product->getOrderStats();

        return response()->json([
            'success' => true,
            'product' => $product,
            'stats' => $stats
        ]);
    }

    /**
     * Get sales analytics for products
     */
    public function salesAnalytics(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $startDate = now()->subDays($days);
        $endDate = now();

        // Products with sales in the period
        $productsWithSales = Product::whereHas('orders', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->withCount([
            'orders as period_orders' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
        ])
        ->withSum([
            'orders as period_revenue' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
        ], 'total_price')
        ->orderBy('period_revenue', 'desc')
        ->get();

        // Overall statistics
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return response()->json([
            'success' => true,
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'days' => $days
            ],
            'statistics' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'average_order_value' => round($averageOrderValue, 2),
                'products_with_sales' => $productsWithSales->count()
            ],
            'products' => $productsWithSales
        ]);
    }

    /**
     * Get complex join query example
     */
    public function complexJoinExample(): JsonResponse
    {
        // Example of complex join with multiple conditions
        $results = DB::table('products')
            ->join('orders', 'products.id', '=', 'orders.product_id')
            ->join('users as buyers', 'orders.user_id', '=', 'buyers.id')
            ->join('users as sellers', 'products.user_id', '=', 'sellers.id')
            ->select([
                'products.id as product_id',
                'products.name as product_name',
                'products.price as product_price',
                'products.category',
                'orders.id as order_id',
                'orders.total_price',
                'orders.status as order_status',
                'orders.created_at as order_date',
                'buyers.name as buyer_name',
                'buyers.email as buyer_email',
                'sellers.name as seller_name',
                'sellers.email as seller_email'
            ])
            ->where('orders.status', '!=', 'cancelled')
            ->orderBy('orders.created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Get products with order history for admin dashboard
     */
    public function adminDashboardData(): JsonResponse
    {
        // Recent orders with product and buyer info
        $recentOrders = Order::withFullDetails()
            ->recent(7)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top selling products this month
        $topSellingProducts = Product::bestSelling(5)
            ->with(['user:id,name'])
            ->get();

        // Products with pending orders
        $productsWithPendingOrders = Product::withPendingOrders()
            ->withCount('orders as pending_count')
            ->get();

        // Revenue statistics
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        $weeklyRevenue = Order::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('total_price');

        return response()->json([
            'success' => true,
            'data' => [
                'recent_orders' => $recentOrders,
                'top_selling_products' => $topSellingProducts,
                'products_with_pending_orders' => $productsWithPendingOrders,
                'revenue_stats' => [
                    'monthly' => $monthlyRevenue,
                    'weekly' => $weeklyRevenue
                ]
            ]
        ]);
    }
}
