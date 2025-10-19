<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReservation;
use App\Mail\ProductReservationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ProductReservationController extends Controller
{
    /**
     * Store a new product reservation
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.',
        ]);

        // Create reservation
        $reservation = ProductReservation::create([
            'product_id' => $product->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'message' => $request->message,
            'status' => 'active', // Use existing enum value
        ]);

        // Change product status to reserved
        $product->update(['status' => 'reserved']);

        // Send email to product owner
        try {
            Mail::to($product->user->email)->send(new ProductReservationMail($product, [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'message' => $request->message,
            ]));
        } catch (\Exception $e) {
            \Log::error('Failed to send reservation email: ' . $e->getMessage());
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre demande de réservation a été envoyée avec succès !'
            ]);
        }

        return redirect()->back()->with('success', 'Votre demande de réservation a été envoyée avec succès !');
    }

    /**
     * Update product status to available (for product owners)
     */
    public function makeAvailable(Product $product)
    {
        // Check if user is the product owner
        if (!Auth::check() || Auth::id() !== $product->user_id) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à effectuer cette action.'
                ], 403);
            }
            abort(403, 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        $product->update(['status' => 'available']);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Le produit a été remis en disponible !'
            ]);
        }

        return redirect()->back()->with('success', 'Le produit a été remis en disponible !');
    }
}