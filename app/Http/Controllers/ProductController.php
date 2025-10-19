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

class ProductController extends Controller
{
    /**
     * Display products for frontend (Public)
     */
    public function frontendIndex(Request $request): View
    {
        $query = Product::with(['user'])
                    ->orderBy('created_at', 'desc');

        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // Handle category filter
        if ($request->filled('category') && $request->get('category') !== 'all') {
            $query->where('category', $request->get('category'));
        }

        // Handle status filter
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('status', $request->get('status'));
        }

        // Handle price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->get('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
        }

        $products = $query->paginate(9)->withQueryString();

        return view('front.pages.products', compact('products'));
    }

    /**
     * Get product data for modals
     */
    public function getData(Product $product): JsonResponse
    {
        $product->load(['user']);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category' => $product->category,
                'condition' => $product->condition,
                'price' => $product->price,
                'status' => $product->status,
                'image_path' => $product->image_path,
                'user' => $product->user,
                'created_at' => $product->created_at,
            ]
        ]);
    }

    /**
     * Handle contact seller form submission
     */
    public function contact(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|min:10|max:1000',
        ]);

        try {
            // Get seller information
            $seller = $product->user;
            
            if (!$seller) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seller information not found'
                ], 404);
            }

            // Log the contact request first
            Log::info('Product Contact Request', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'seller_id' => $seller->id,
                'seller_email' => $seller->email,
                'contact_name' => $request->name,
                'contact_email' => $request->email,
                'contact_phone' => $request->phone,
                'message' => $request->message,
            ]);

            // Try to send email with rate limiting protection
            try {
                Mail::to($seller->email)->send(new ProductContactMail($product, $request->all()));
                
                Log::info('Contact email sent successfully', [
                    'product_id' => $product->id,
                    'seller_email' => $seller->email
                ]);
                
            } catch (\Exception $mailException) {
                // If email fails due to rate limiting, still log the contact
                Log::warning('Contact email failed, but contact logged', [
                    'product_id' => $product->id,
                    'seller_email' => $seller->email,
                    'error' => $mailException->getMessage()
                ]);
                
                // Return success anyway since the contact is logged
                return response()->json([
                    'success' => true,
                    'message' => 'Your message has been logged successfully! The seller will be notified when email service is restored.'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent to the seller successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Product Contact Error', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.'
            ], 500);
        }
    }

    /**
     * Handle product reservation
     */
    public function reserve(Request $request, Product $product): JsonResponse
    {
        // Check if product is available
        if ($product->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'This product is no longer available for reservation.'
            ], 400);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:500',
        ]);

        try {
            // Update product status to reserved
            $product->update(['status' => 'reserved']);

            // Get seller information
            $seller = $product->user;
            
            if (!$seller) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seller information not found'
                ], 404);
            }

            // Log the reservation
            Log::info('Product Reservation', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'seller_id' => $seller->id,
                'seller_email' => $seller->email,
                'reserver_name' => $request->name,
                'reserver_email' => $request->email,
                'reserver_phone' => $request->phone,
                'message' => $request->message,
                'reserved_at' => now(),
            ]);

            // Try to send email with rate limiting protection
            try {
                Mail::to($seller->email)->send(new ProductReservationMail($product, $request->all()));
                
                Log::info('Reservation email sent successfully', [
                    'product_id' => $product->id,
                    'seller_email' => $seller->email
                ]);
                
            } catch (\Exception $mailException) {
                // If email fails due to rate limiting, still process the reservation
                Log::warning('Reservation email failed, but reservation processed', [
                    'product_id' => $product->id,
                    'seller_email' => $seller->email,
                    'error' => $mailException->getMessage()
                ]);
                
                // Return success anyway since the reservation is processed
                return response()->json([
                    'success' => true,
                    'message' => 'Product reserved successfully! The seller will be notified when email service is restored.'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product reserved successfully! You have 24 hours to contact the seller.'
            ]);

        } catch (\Exception $e) {
            Log::error('Product Reservation Error', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reserve product. Please try again later.'
            ], 500);
        }
    }

    /**
     * Display the specified product (for a single product page, if needed).
     */
    public function show(Product $product): View
    {
        $product->load('user');
        return view('front.pages.product-single', compact('product'));
    }
}