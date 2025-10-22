<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        $user = Auth::user();
        $cartItems = $user->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $subtotal = $cartItems->sum('total_price');
        $tax = $subtotal * 0.19; // 19% tax
        $total = $subtotal + $tax;

        return view('front.pages.checkout', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Process the checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:cash,card,paypal',
            'shipping_address' => 'required|string|max:500',
            'order_notes' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $cartItems = $user->getCartItems();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        DB::beginTransaction();

        try {
            foreach ($cartItems as $cartItem) {
                if (!$cartItem->product->isAvailableForPurchase()) {
                    throw new \Exception('Le produit "' . $cartItem->product->name . '" n\'est plus disponible.');
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'shipping_address' => $request->shipping_address,
                    'order_notes' => $request->order_notes,
                    'ordered_at' => now()
                ]);

                // Si paiement par carte, simuler un paiement réussi
                if ($request->payment_method === 'card') {
                    $order->update([
                        'status' => 'completed',
                        'payment_status' => 'completed',
                        'transaction_id' => 'TXN-' . time() . '-' . $order->id,
                        'gateway' => 'simulated_card',
                        'payment_processed_at' => now()
                    ]);
                }

                $cartItem->product->decreaseStock($cartItem->quantity, true);
            }

            $user->clearCart();

            DB::commit();

            // Si paiement par carte, rediriger vers page de succès
            if ($request->payment_method === 'card') {
                return redirect()->route('payment.success')->with('success', 'Paiement réussi ! Votre commande a été traitée.');
            }

            return redirect()->route('orders.index')->with('success', 'Commande passée avec succès !');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display payment success page
     */
    public function paymentSuccess()
    {
        return view('front.pages.payment-success');
    }
}