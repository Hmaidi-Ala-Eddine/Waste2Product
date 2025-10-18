<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductContactMail;
use App\Mail\ProductReservationMail;
use Illuminate\Support\Facades\Log;

class ProductStatusController extends Controller
{
    /**
     * Update product status to sold
     */
    public function markAsSold(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'sale_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Update product status
            $product->update([
                'status' => 'sold',
                'price' => $request->sale_price ?? $product->price, // Update price if different
            ]);

            // Log the sale
            Log::info('Product Marked as Sold', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'seller_id' => $product->user_id,
                'buyer_name' => $request->buyer_name,
                'buyer_email' => $request->buyer_email,
                'sale_price' => $request->sale_price ?? $product->price,
                'notes' => $request->notes,
                'sold_at' => now(),
            ]);

            // Send confirmation email to buyer
            try {
                Mail::to($request->buyer_email)->send(new ProductSoldConfirmationMail($product, $request->all()));
            } catch (\Exception $e) {
                Log::warning('Failed to send sold confirmation email', [
                    'product_id' => $product->id,
                    'buyer_email' => $request->buyer_email,
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product marked as sold successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to mark product as sold', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark product as sold. Please try again.'
            ], 500);
        }
    }

    /**
     * Revert product status back to available
     */
    public function markAsAvailable(Request $request, Product $product): JsonResponse
    {
        try {
            // Only allow reverting from reserved status
            if ($product->status !== 'reserved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only reserved products can be made available again.'
                ], 400);
            }

            // Update product status
            $product->update(['status' => 'available']);

            // Log the status change
            Log::info('Product Status Reverted to Available', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'seller_id' => $product->user_id,
                'reverted_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product is now available again!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to revert product status', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to revert product status. Please try again.'
            ], 500);
        }
    }

    /**
     * Get product status history
     */
    public function getStatusHistory(Product $product): JsonResponse
    {
        // This would require a status_history table to track changes
        // For now, we'll return basic info
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'current_status' => $product->status,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ]
        ]);
    }
}
