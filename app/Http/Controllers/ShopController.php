<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReservation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display products shop page
     */
    public function index(Request $request)
    {
        $query = Product::with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category') && $request->get('category') !== 'all') {
            $query->where('category', $request->get('category'));
        }

        // Condition filter
        if ($request->filled('condition') && $request->get('condition') !== 'all') {
            $query->where('condition', $request->get('condition'));
        }

        // Status filter
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('status', $request->get('status'));
        }

        // Price filter
        if ($request->filled('price_type')) {
            if ($request->get('price_type') === 'free') {
                $query->whereNull('price');
            } else if ($request->get('price_type') === 'paid') {
                $query->whereNotNull('price');
            }
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(price, 0) ASC');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        
        // Get categories and conditions for filters
        $categories = ['furniture', 'electronics', 'plastic', 'textile', 'metal'];
        $conditions = ['excellent', 'good', 'fair', 'poor'];

        // Get cart product IDs for logged-in user
        $cartProductIds = [];
        if (auth()->check()) {
            $cartProductIds = auth()->user()->cartItems()->pluck('product_id')->toArray();
        }

        return view('front.pages.shop', compact('products', 'categories', 'conditions', 'cartProductIds'));
    }

    /**
     * Show single product details
     */
    public function show($id)
    {
        $product = Product::with(['user', 'wasteRequest'])->findOrFail($id);
        
        // Get related products (same category, excluding current product)
        $relatedProducts = Product::where('category', $product->category)
            ->where('status', 'available')
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('front.pages.product-detail', compact('product', 'relatedProducts'));
    }

    /**
     * Reserve a product with contact form
     */
    public function reserve(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        $product = Product::findOrFail($id);

        // Check if product is available
        if (!$product->isAvailableForPurchase()) {
            return response()->json([
                'success' => false,
                'message' => 'This product is not available for reservation.'
            ], 400);
        }

        $reservationData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'notes' => $request->notes,
        ];

        try {
            // Send email to the product owner
            \Mail::to($product->user->email)->send(new \App\Mail\ProductReservationMail($product, $reservationData));

            // Create reservation record
            \App\Models\ProductReservation::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'status' => 'active',
                'reserved_at' => now(),
                'reservation_data' => json_encode($reservationData) // Store the form data
            ]);

            // Update product status to reserved
            $product->update(['status' => 'reserved']);

            return response()->json([
                'success' => true,
                'message' => 'Reservation request sent successfully! The seller has been notified and will contact you soon.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send reservation email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reservation request. Please try again.'
            ], 500);
        }
    }

    /**
     * Make product available again (for product owners)
     */
    public function makeAvailable($id)
    {
        $product = Product::findOrFail($id);

        // Check if user is the owner of the product
        if ($product->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action.'
            ], 403);
        }

        // Update product status to available and ensure stock > 0
        $product->update([
            'status' => 'available',
            'stock' => max($product->stock, 1) // Ensure stock is at least 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product is now available for purchase!'
        ]);
    }

    /**
     * Cancel a reservation
     */
    public function cancelReservation($id)
    {
        $reservation = \App\Models\ProductReservation::findOrFail($id);

        // Check if user owns this reservation
        if ($reservation->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action.'
            ], 403);
        }

        // Update reservation status to cancelled
        $reservation->update(['status' => 'cancelled']);

        // Update product status back to available
        $reservation->product->update(['status' => 'available']);

        return response()->json([
            'success' => true,
            'message' => 'Reservation cancelled successfully!'
        ]);
    }

    /**
     * Show user's orders and reservations
     */
    public function myOrders()
    {
        $orders = Order::with(['product.user'])
            ->where('user_id', Auth::id())
            ->orderBy('ordered_at', 'desc')
            ->get();

        $reservations = ProductReservation::with(['product.user'])
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->orderBy('reserved_at', 'desc')
            ->get();

        return view('front.pages.my-orders', compact('orders', 'reservations'));
    }
}
