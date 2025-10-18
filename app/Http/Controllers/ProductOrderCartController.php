<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductOrderCartController extends Controller
{
    /**
     * Get products with their orders and cart items
     */
    public function productsWithOrdersAndCart(): JsonResponse
    {
        $products = Product::withOrdersAndCart()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products that are in carts but not yet ordered
     */
    public function productsInCartNotOrdered(): JsonResponse
    {
        $products = Product::whereHas('cartItems')
            ->whereDoesntHave('orders')
            ->withCartItems()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products that have orders but are still in carts
     */
    public function productsOrderedButInCart(): JsonResponse
    {
        $products = Product::whereHas('orders')
            ->whereHas('cartItems')
            ->withOrdersAndCart()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get cart items that became orders
     */
    public function cartItemsToOrders(): JsonResponse
    {
        // This is complex because cart items don't have direct relationship to orders
        // We need to match by user_id, product_id, quantity, and total_price
        
        $cartItems = Cart::with(['product', 'user'])
            ->whereHas('product.orders', function($q) {
                $q->where('user_id', Cart::raw('carts.user_id'))
                  ->where('product_id', Cart::raw('carts.product_id'));
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cartItems
        ]);
    }

    /**
     * Get analytics for products, orders, and cart
     */
    public function analytics(): JsonResponse
    {
        $analytics = [
            'products' => [
                'total' => Product::count(),
                'available' => Product::where('status', 'available')->count(),
                'reserved' => Product::where('status', 'reserved')->count(),
                'sold' => Product::where('status', 'sold')->count(),
                'donated' => Product::where('status', 'donated')->count(),
            ],
            'orders' => [
                'total' => Order::count(),
                'pending' => Order::where('status', 'pending')->count(),
                'confirmed' => Order::where('status', 'confirmed')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ],
            'cart' => [
                'total_items' => Cart::sum('quantity'),
                'unique_products' => Cart::distinct('product_id')->count(),
                'total_value' => Cart::sum('total_price'),
            ],
            'conversion' => [
                'cart_to_order_rate' => $this->calculateCartToOrderRate(),
                'products_with_orders' => Product::whereHas('orders')->count(),
                'products_in_cart' => Product::whereHas('cartItems')->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Calculate cart to order conversion rate
     */
    private function calculateCartToOrderRate(): float
    {
        $totalCartItems = Cart::count();
        $totalOrders = Order::count();
        
        if ($totalCartItems === 0) {
            return 0;
        }
        
        return round(($totalOrders / $totalCartItems) * 100, 2);
    }

    /**
     * Get products with pending cart items and orders
     */
    public function productsWithPendingActions(): JsonResponse
    {
        $products = Product::where(function($q) {
            $q->whereHas('cartItems')
              ->orWhereHas('orders', function($orderQuery) {
                  $orderQuery->where('status', 'pending');
              });
        })
        ->withOrdersAndCart()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get user's cart items and their corresponding orders
     */
    public function userCartAndOrders(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        
        $cartItems = Cart::where('user_id', $userId)
            ->with(['product'])
            ->get();
            
        $orders = Order::where('user_id', $userId)
            ->with(['product'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'cart_items' => $cartItems,
                'orders' => $orders,
                'cart_total' => $cartItems->sum('total_price'),
                'orders_total' => $orders->sum('total_price'),
            ]
        ]);
    }
}
