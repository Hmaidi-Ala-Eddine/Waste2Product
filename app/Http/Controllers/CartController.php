<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display shopping cart
     */
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product.user')->get();
        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.19; // 19% TVA
        $total = $subtotal + $tax;

        return view('front.pages.cart', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // Check if product is available
        if ($product->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'This product is no longer available.'
            ], 400);
        }

        // Check if user already has this product in cart
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->increment('quantity', $request->get('quantity', 1));
            $cartItem->update(['total_price' => $cartItem->product->price * $cartItem->quantity]);
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $request->get('quantity', 1),
                'price' => $product->price,
                'total_price' => $product->price * $request->get('quantity', 1),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => Auth::user()->cart_count
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cartItem->update([
            'quantity' => $request->quantity,
            'total_price' => $cartItem->product->price * $request->quantity
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'subtotal' => $cartItem->subtotal,
            'cart_total' => Auth::user()->cart_total
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cartItem = Cart::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart!',
            'cart_count' => Auth::user()->cart_count
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        Auth::user()->cartItems()->delete();

        return redirect()->route('front.shop')->with('success', 'Cart cleared successfully!');
    }

    /**
     * Show checkout page
     */
    public function checkout()
    {
        $cartItems = Auth::user()->cartItems()->with('product.user')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('front.cart')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.19; // 19% TVA
        $total = $subtotal + $tax;

        return view('front.pages.checkout', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:card,paypal,cash',
            'card_number' => 'required_if:payment_method,card',
            'card_expiry' => 'required_if:payment_method,card',
            'card_cvv' => 'required_if:payment_method,card',
            'card_name' => 'required_if:payment_method,card',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_postal_code' => 'required|string',
        ]);

        $cartItems = Auth::user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('front.cart')->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            // Create orders for each product
            foreach ($cartItems as $item) {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'total_price' => $item->subtotal,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'ordered_at' => now(),
                ]);

                // If payment method is card, simulate successful payment
                if ($request->payment_method === 'card') {
                    $order->update([
                        'status' => 'completed',
                        'payment_status' => 'completed',
                        'transaction_id' => 'TXN-' . time() . '-' . $order->id,
                        'gateway' => 'simulated_card',
                        'payment_processed_at' => now()
                    ]);
                }

                // Decrease stock - will auto-update status to 'sold' if stock reaches 0
                $item->product->decreaseStock($item->quantity);
            }

            // Clear cart
            Auth::user()->cartItems()->delete();

            DB::commit();

            // If payment method is card, redirect to payment success page
            if ($request->payment_method === 'card') {
                return redirect()->route('payment.success')->with('success', 'Payment successful! Your order has been processed.');
            }

            return redirect()->route('front.order.success')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to process order. Please try again.');
        }
    }

    /**
     * Show order success page
     */
    public function orderSuccess()
    {
        return view('front.pages.order-success');
    }

    /**
     * Show payment success page
     */
    public function paymentSuccess()
    {
        return view('front.pages.payment-success');
    }
}
