<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\WasteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['user', 'wasteRequest']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(15)->withQueryString();
        
        return view('back.pages.products', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $users = User::all();
        $wasteRequests = WasteRequest::where('status', 'completed')->get();
        
        // For AJAX requests, return JSON
        if ($request->expectsJson()) {
            return response()->json($users);
        }
        
        return view('back.pages.products', compact('users', 'wasteRequests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|in:furniture,electronics,plastic,textile,metal',
                'condition' => 'nullable|in:excellent,good,fair,poor',
                'price' => 'nullable|numeric|min:0',
                'status' => 'required|in:available,sold,reserved',
                'waste_request_id' => 'nullable|exists:waste_requests,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'name.required' => 'Le nom du produit est obligatoire.',
                'name.string' => 'Le nom du produit doit être une chaîne de caractères.',
                'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
                'description.string' => 'La description doit être une chaîne de caractères.',
                'category.required' => 'La catégorie est obligatoire.',
                'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
                'condition.in' => 'L\'état sélectionné n\'est pas valide.',
                'price.numeric' => 'Le prix doit être un nombre.',
                'price.min' => 'Le prix ne peut pas être négatif.',
                'status.required' => 'Le statut est obligatoire.',
                'status.in' => 'Le statut sélectionné n\'est pas valide.',
                'waste_request_id.exists' => 'La demande de déchets sélectionnée n\'existe pas.',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF.',
                'image.max' => 'L\'image ne peut pas dépasser 2MB.',
            ]);

        $data = $request->only([
            'user_id', 'name', 'description', 'category', 
            'condition', 'price', 'status', 'waste_request_id'
        ]);
        
        // Assign the authenticated user as the product owner
        $data['user_id'] = auth()->id();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = $imagePath;
            \Log::info('Product image stored at: ' . $imagePath);
        }

        $product = Product::create($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'data' => $product->load(['user'])
            ], 201);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la création du produit'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création du produit');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product)
    {
        $product->load(['user', 'wasteRequest', 'orders']);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        }
        
        return view('back.pages.products', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Product $product)
    {
        $users = User::all();
        $wasteRequests = WasteRequest::where('status', 'completed')->get();
        
        // For AJAX requests, return JSON
        if ($request->expectsJson()) {
            return response()->json($product);
        }
        
        return view('back.pages.products', compact('product', 'users', 'wasteRequests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|in:furniture,electronics,plastic,textile,metal',
                'condition' => 'nullable|in:excellent,good,fair,poor',
                'price' => 'nullable|numeric|min:0',
                'status' => 'required|in:available,sold,reserved',
                'waste_request_id' => 'nullable|exists:waste_requests,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'name.required' => 'Le nom du produit est obligatoire.',
                'name.string' => 'Le nom du produit doit être une chaîne de caractères.',
                'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
                'description.string' => 'La description doit être une chaîne de caractères.',
                'category.required' => 'La catégorie est obligatoire.',
                'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
                'condition.in' => 'L\'état sélectionné n\'est pas valide.',
                'price.numeric' => 'Le prix doit être un nombre.',
                'price.min' => 'Le prix ne peut pas être négatif.',
                'status.required' => 'Le statut est obligatoire.',
                'status.in' => 'Le statut sélectionné n\'est pas valide.',
                'waste_request_id.exists' => 'La demande de déchets sélectionnée n\'existe pas.',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF.',
                'image.max' => 'L\'image ne peut pas dépasser 2MB.',
            ]);

        $data = $request->only([
            'user_id', 'name', 'description', 'category', 
            'condition', 'price', 'status', 'waste_request_id'
        ]);
        
        // Assign the authenticated user as the product owner
        $data['user_id'] = auth()->id();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = $imagePath;
        }

        $product->update($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'data' => $product->load(['user'])
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la mise à jour du produit'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du produit');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Change product status.
     */
    public function changeStatus(Request $request, Product $product)
    {
        $request->validate([
            'status' => 'required|in:available,sold,reserved'
        ]);

        // Use special method for making available
        if ($request->status === 'available') {
            $product->makeAvailable();
        } else {
            $product->update(['status' => $request->status]);
        }

        return redirect()->back()
            ->with('success', 'Product status updated successfully.');
    }

    /**
     * Get users for admin dropdown (Admin only)
     */
    public function getUsers()
    {
        try {
            $users = User::select('id', 'name', 'email')
                        ->orderBy('name')
                        ->get();

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load users',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product data for editing (Admin only)
     */
    public function getData(Product $product)
    {
        $product->load(['user']);

        return response()->json([
            'id' => $product->id,
            'user_id' => $product->user_id,
            'name' => $product->name,
            'description' => $product->description,
            'category' => $product->category,
            'condition' => $product->condition,
            'price' => $product->price,
            'status' => $product->status,
            'image_path' => $product->image_path,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]);
    }
}
