<?php

namespace App\Http\Controllers;

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
        $cartItems = Auth::user()->getCartItems();
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

        // Add to cart using new JSON method
        Auth::user()->addToCart($productId, $request->get('quantity', 1));

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => Auth::user()->cart_count
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        Auth::user()->updateCartItem($productId, $request->quantity);

        // Get updated item for response
        $cartItems = Auth::user()->getCartItems();
        $updatedItem = $cartItems->firstWhere('product_id', $productId);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'subtotal' => $updatedItem ? $updatedItem->subtotal : 0,
            'cart_total' => Auth::user()->cart_total
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($productId)
    {
        Auth::user()->removeFromCart($productId);

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
        Auth::user()->clearCart();

        return redirect()->route('front.shop')->with('success', 'Cart cleared successfully!');
    }

    /**
     * Show checkout page
     */
    public function checkout()
    {
        $cartItems = Auth::user()->getCartItems();

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
        // Handle JSON data from AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            $data = $request->json()->all();
            // Merge JSON data into request for validation
            $request->merge($data);
        }

        $request->validate([
            'payment_method' => 'required|in:card,paypal,cash',
            'card_number' => 'required_if:payment_method,card',
            'card_expiry' => 'required_if:payment_method,card',
            'card_cvv' => 'required_if:payment_method,card',
            'card_name' => 'required_if:payment_method,card',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ], [
            'payment_method.required' => 'Le mode de paiement est obligatoire.',
            'payment_method.in' => 'Le mode de paiement sélectionné n\'est pas valide.',
            'card_number.required_if' => 'Le numéro de carte est obligatoire pour les paiements par carte.',
            'card_expiry.required_if' => 'La date d\'expiration est obligatoire pour les paiements par carte.',
            'card_cvv.required_if' => 'Le CVV est obligatoire pour les paiements par carte.',
            'card_name.required_if' => 'Le nom du titulaire est obligatoire pour les paiements par carte.',
            'address.required' => 'L\'adresse est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'postal_code.required' => 'Le code postal est obligatoire.',
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'phone.required' => 'Le téléphone est obligatoire.',
        ]);

        $cartItems = Auth::user()->getCartItems();

        if ($cartItems->isEmpty()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre panier est vide !'
                ], 400);
            }
            return redirect()->route('front.cart')->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            // Generate transaction ID
            $transactionId = 'TXN_' . time() . '_' . Auth::id();
            
            // Create orders for each product
            foreach ($cartItems as $item) {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'total_price' => $item->subtotal,
                    'status' => 'completed', // ✅ Changed to completed for paid orders
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'completed', // ✅ Payment completed
                    'transaction_id' => $transactionId,
                    'gateway' => $this->getGatewayName($request->payment_method),
                    'gateway_response' => $this->generateGatewayResponse($request),
                    'payment_notes' => 'Payment completed successfully via ' . $request->payment_method,
                    'payment_processed_at' => now(),
                    'shipping_address' => $this->formatShippingAddress($request),
                    'order_notes' => 'Order placed via checkout process',
                    'ordered_at' => now(),
                ]);

                // Decrease stock - will auto-update status to 'sold' if stock reaches 0
                $item->product->decreaseStock($item->quantity);
            }

            // Clear cart
            Auth::user()->clearCart();

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Commande passée avec succès !'
                ]);
            }

            return redirect()->route('front.order.success')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Checkout processing error: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors du traitement de la commande. Veuillez réessayer.'
                ], 500);
            }
            
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
     * Get gateway name based on payment method
     */
    private function getGatewayName(string $paymentMethod): string
    {
        return match($paymentMethod) {
            'card' => 'Credit Card Gateway',
            'paypal' => 'PayPal Gateway',
            'cash' => 'Cash on Delivery',
            default => 'Unknown Gateway'
        };
    }

    /**
     * Generate gateway response data
     */
    private function generateGatewayResponse(Request $request): array
    {
        $response = [
            'method' => $request->payment_method,
            'processed_at' => now()->toISOString(),
            'amount' => $request->input('total_amount', 0),
            'currency' => 'TND',
            'status' => 'success'
        ];

        // Add card-specific data if payment method is card
        if ($request->payment_method === 'card') {
            $response['card'] = [
                'last_four' => substr($request->card_number, -4),
                'expiry' => $request->card_expiry,
                'holder_name' => $request->card_name
            ];
        }

        return $response;
    }

    /**
     * Format shipping address from request data
     */
    private function formatShippingAddress(Request $request): string
    {
        $address = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code
        ];

        return json_encode($address, JSON_PRETTY_PRINT);
    }
}