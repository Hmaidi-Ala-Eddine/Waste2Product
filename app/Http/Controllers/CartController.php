<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index(): View
    {
        $userId = Auth::id();
        $cartItems = Cart::getCartItems($userId);
        $cartTotal = Cart::getCartTotal($userId);
        $cartItemsCount = Cart::getCartItemsCount($userId);

        return view('front.pages.cart', compact('cartItems', 'cartTotal', 'cartItemsCount'));
    }

    /**
     * Add item to cart (AJAX).
     */
    public function addItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;
        $userId = Auth::id();

        // Check if product is available
        $product = Product::find($productId);
        if (!$product || $product->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ], 400);
        }

        // Add to cart
        $success = Cart::addItem($productId, $quantity, $userId);

        if ($success) {
            $cartItemsCount = Cart::getCartItemsCount($userId);
            $cartTotal = Cart::getCartTotal($userId);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_items_count' => $cartItemsCount,
                'cart_total' => number_format($cartTotal, 2)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to add product to cart'
        ], 500);
    }

    /**
     * Update item quantity in cart (AJAX).
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $request->validate([
            'cart_item_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:0|max:10'
        ]);

        $cartItemId = $request->cart_item_id;
        $quantity = $request->quantity;
        $userId = Auth::id();

        // Verify cart item belongs to current user/session
        $cartItem = Cart::find($cartItemId);
        if (!$cartItem || ($userId && $cartItem->user_id !== $userId)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        $success = Cart::updateQuantity($cartItemId, $quantity);

        if ($success) {
            $cartItemsCount = Cart::getCartItemsCount($userId);
            $cartTotal = Cart::getCartTotal($userId);
            
            // Get updated cart item with new total price
            $updatedCartItem = Cart::withProduct()->find($cartItemId);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cart_items_count' => $cartItemsCount,
                'cart_total' => number_format($cartTotal, 2),
                'item_total_price' => number_format($updatedCartItem->total_price, 2)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to update cart'
        ], 500);
    }

    /**
     * Remove item from cart (AJAX).
     */
    public function removeItem(Request $request): JsonResponse
    {
        $request->validate([
            'cart_item_id' => 'required|exists:carts,id'
        ]);

        $cartItemId = $request->cart_item_id;
        $userId = Auth::id();

        // Verify cart item belongs to current user/session
        $cartItem = Cart::find($cartItemId);
        if (!$cartItem || ($userId && $cartItem->user_id !== $userId)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        $success = Cart::removeItem($cartItemId);

        if ($success) {
            $cartItemsCount = Cart::getCartItemsCount($userId);
            $cartTotal = Cart::getCartTotal($userId);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully!',
                'cart_items_count' => $cartItemsCount,
                'cart_total' => number_format($cartTotal, 2)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to remove item from cart'
        ], 500);
    }

    /**
     * Clear entire cart (AJAX).
     */
    public function clearCart(): JsonResponse
    {
        $userId = Auth::id();
        $success = Cart::clearCart($userId);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!',
                'cart_items_count' => 0,
                'cart_total' => '0.00'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to clear cart'
        ], 500);
    }

    /**
     * Get cart summary (AJAX).
     */
    public function getCartSummary(): JsonResponse
    {
        $userId = Auth::id();
        $cartItemsCount = Cart::getCartItemsCount($userId);
        $cartTotal = Cart::getCartTotal($userId);

        return response()->json([
            'success' => true,
            'cart_items_count' => $cartItemsCount,
            'cart_total' => number_format($cartTotal, 2)
        ]);
    }

    /**
     * Proceed to checkout.
     */
    public function checkout(): View
    {
        $userId = Auth::id();
        $cartItems = Cart::getCartItems($userId);
        $cartTotal = Cart::getCartTotal($userId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('front.cart')->with('error', 'Your cart is empty!');
        }

        return view('front.pages.checkout', compact('cartItems', 'cartTotal'));
    }

    /**
     * Process checkout and create orders.
     */
    public function processCheckout(Request $request): JsonResponse
    {
        $request->validate([
            'payment_method' => 'required|string|in:credit_card,paypal,bank_transfer,cash',
            'shipping_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ]);

        $userId = Auth::id();
        $cartItems = Cart::getCartItems($userId);

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty!'
            ], 400);
        }

        try {
            $orders = [];
            $totalAmount = 0;

            // Create orders for each cart item
            foreach ($cartItems as $cartItem) {
                $order = Order::create([
                    'user_id' => $userId,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'shipping_address' => $request->shipping_address,
                    'order_notes' => $request->notes,
                    'ordered_at' => now(),
                ]);

                $orders[] = $order;
                $totalAmount += $cartItem->total_price;

                // Update product status to reserved (will be sold after payment)
                if ($cartItem->product->status === 'available') {
                    $cartItem->product->status = 'reserved';
                    $cartItem->product->save();
                    \Log::info("Product #{$cartItem->product->id} ({$cartItem->product->name}) marked as RESERVED for Order #{$order->id}");
                }
            }

            // Clear cart after successful checkout
            Cart::clearCart($userId);

            // Redirect to payment page for the first order
            if (!empty($orders)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Checkout completed successfully! Redirecting to payment...',
                    'redirect_url' => route('front.payment.show', $orders[0]->id),
                    'orders' => $orders,
                    'total_amount' => number_format($totalAmount, 2)
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Transfer cart from session to user (when user logs in).
     */
    public function transferToUser(): JsonResponse
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $success = Cart::transferToUser($userId);

        if ($success) {
            $cartItemsCount = Cart::getCartItemsCount($userId);
            $cartTotal = Cart::getCartTotal($userId);

            return response()->json([
                'success' => true,
                'message' => 'Cart transferred successfully!',
                'cart_items_count' => $cartItemsCount,
                'cart_total' => number_format($cartTotal, 2)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No cart items to transfer'
        ]);
    }
}
