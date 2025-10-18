<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Show payment page for an order.
     */
    public function show(Order $order): View
    {
        // Verify order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Check if order is already paid
        if ($order->isPaymentCompleted()) {
            return redirect()->route('front.orders.show', $order)
                ->with('info', 'This order has already been paid.');
        }

        return view('front.pages.payment', compact('order'));
    }

    /**
     * Process payment for an order.
     */
    public function process(Request $request, Order $order): JsonResponse
    {
        // Verify order belongs to current user
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order'
            ], 403);
        }

        // Check if order is already paid
        if ($order->isPaymentCompleted()) {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been paid.'
            ], 400);
        }

        $request->validate([
            'payment_method' => 'required|string|in:credit_card,paypal,bank_transfer,cash',
            'shipping_address' => 'required|string|max:500',
            'order_notes' => 'nullable|string|max:1000'
        ]);

        try {
            // Mark payment as processing
            $order->markPaymentAsProcessing();
            
            // Update order with additional information
            $order->update([
                'shipping_address' => $request->shipping_address,
                'order_notes' => $request->order_notes,
            ]);

            // Simulate payment processing based on method
            $paymentResult = $this->processPaymentByMethod($order, $request->payment_method);

            if ($paymentResult['success']) {
                // Mark payment as completed
                $order->markPaymentAsCompleted(
                    $paymentResult['transaction_id'] ?? null,
                    $paymentResult['gateway_response'] ?? null
                );

                // Update order status
                $order->update(['status' => 'confirmed']);

                // Update product status to SOLD
                $product = $order->product;
                if ($product) {
                    $product->update(['status' => 'sold']);
                    Log::info("Product #{$product->id} ({$product->name}) marked as SOLD after successful payment for Order #{$order->id}");
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Payment completed successfully!',
                    'order_id' => $order->id,
                    'transaction_id' => $paymentResult['transaction_id'] ?? null
                ]);
            } else {
                // Mark payment as failed
                $order->markPaymentAsFailed(
                    $paymentResult['error'] ?? 'Payment processing failed',
                    $paymentResult['gateway_response'] ?? null
                );

                return response()->json([
                    'success' => false,
                    'message' => $paymentResult['error'] ?? 'Payment failed. Please try again.'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            
            // Mark payment as failed
            $order->markPaymentAsFailed('System error during payment processing');

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during payment processing. Please try again.'
            ], 500);
        }
    }

    /**
     * Process payment based on method.
     */
    private function processPaymentByMethod(Order $order, string $method): array
    {
        switch ($method) {
            case 'credit_card':
                return $this->processCreditCardPayment($order);
            
            case 'paypal':
                return $this->processPayPalPayment($order);
            
            case 'bank_transfer':
                return $this->processBankTransferPayment($order);
            
            case 'cash':
                return $this->processCashPayment($order);
            
            default:
                return [
                    'success' => false,
                    'error' => 'Invalid payment method'
                ];
        }
    }

    /**
     * Simulate credit card payment processing.
     */
    private function processCreditCardPayment(Order $order): array
    {
        // Simulate API call to payment gateway
        sleep(2); // Simulate processing time
        
        // Simulate 90% success rate
        if (rand(1, 10) <= 9) {
            return [
                'success' => true,
                'transaction_id' => 'CC_' . time() . '_' . rand(1000, 9999),
                'gateway_response' => [
                    'gateway' => 'stripe',
                    'status' => 'succeeded',
                    'amount' => $order->total_price,
                    'currency' => 'USD'
                ]
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Credit card payment declined. Please check your card details.',
                'gateway_response' => [
                    'gateway' => 'stripe',
                    'status' => 'declined',
                    'reason' => 'insufficient_funds'
                ]
            ];
        }
    }

    /**
     * Simulate PayPal payment processing.
     */
    private function processPayPalPayment(Order $order): array
    {
        // Simulate API call to PayPal
        sleep(3); // Simulate processing time
        
        // Simulate 95% success rate
        if (rand(1, 20) <= 19) {
            return [
                'success' => true,
                'transaction_id' => 'PP_' . time() . '_' . rand(1000, 9999),
                'gateway_response' => [
                    'gateway' => 'paypal',
                    'status' => 'completed',
                    'amount' => $order->total_price,
                    'currency' => 'USD'
                ]
            ];
        } else {
            return [
                'success' => false,
                'error' => 'PayPal payment failed. Please try again.',
                'gateway_response' => [
                    'gateway' => 'paypal',
                    'status' => 'failed',
                    'reason' => 'network_error'
                ]
            ];
        }
    }

    /**
     * Simulate bank transfer payment processing.
     */
    private function processBankTransferPayment(Order $order): array
    {
        // Bank transfers are always successful (manual verification)
        return [
            'success' => true,
            'transaction_id' => 'BT_' . time() . '_' . rand(1000, 9999),
            'gateway_response' => [
                'gateway' => 'bank_transfer',
                'status' => 'pending_verification',
                'amount' => $order->total_price,
                'currency' => 'USD',
                'note' => 'Payment will be verified within 1-2 business days'
            ]
        ];
    }

    /**
     * Simulate cash payment processing.
     */
    private function processCashPayment(Order $order): array
    {
        // Cash payments are always successful
        return [
            'success' => true,
            'transaction_id' => 'CASH_' . time() . '_' . rand(1000, 9999),
            'gateway_response' => [
                'gateway' => 'cash',
                'status' => 'completed',
                'amount' => $order->total_price,
                'currency' => 'USD',
                'note' => 'Cash payment on delivery'
            ]
        ];
    }

    /**
     * Show payment success page.
     */
    public function success(Order $order): View
    {
        // Verify order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        return view('front.pages.payment-success', compact('order'));
    }

    /**
     * Show payment failure page.
     */
    public function failure(Order $order): View
    {
        // Verify order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        return view('front.pages.payment-failure', compact('order'));
    }
}