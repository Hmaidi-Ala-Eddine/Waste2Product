<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * List orders with optional filters and pagination.
     */
    public function index(Request $request)
    {
        $query = Order::query()->with([
            'buyer:id,name,email',
            'product:id,name,price,status'
        ]);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }
        if ($request->filled('order_id')) {
            $query->where('id', (int) $request->input('order_id'));
        }
        if ($request->filled('buyer')) {
            $buyer = $request->input('buyer');
            $query->whereHas('buyer', function ($q) use ($buyer) {
                $q->where('name', 'like', "%{$buyer}%")
                  ->orWhere('email', 'like', "%{$buyer}%");
            });
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->input('user_id'));
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', (int) $request->input('product_id'));
        }
        if ($request->filled('from')) {
            $query->whereDate('ordered_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('ordered_at', '<=', $request->input('to'));
        }

        // Paginate and preserve query string
        $orders = $query->orderByDesc('ordered_at')->paginate(10);
        $orders->appends($request->query());

        // Fetch users and available products for modal selects
        $usersList = User::select('id', 'name')->orderBy('name')->get();
        $productsList = Product::select('id', 'name', 'price', 'status')
            ->where('status', 'available')->orderBy('name')->get();

        // Render Blade page similar to users/products pages
        return view('back.pages.orders', compact('orders', 'usersList', 'productsList'));
    }

    /**
     * Create a new order.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'ordered_at' => ['required', 'date'],
        ]);

        Order::create($data);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order created successfully.');
    }

    /**
     * Show a single order.
     */
    public function show($id)
    {
        $order = Order::with(['buyer:id,name,email', 'product:id,name,price,status'])->findOrFail($id);
        return view('back.pages.orders-show', compact('order')); // optional view if needed later
    }

    /**
     * Update an order.
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'user_id' => ['sometimes', 'required', 'exists:users,id'],
            'product_id' => ['sometimes', 'required', 'exists:products,id'],
            'quantity' => ['sometimes', 'required', 'integer', 'min:1'],
            'total_price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', Rule::in(['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])],
            'payment_method' => ['sometimes', 'nullable', 'string', 'max:255'],
            'ordered_at' => ['sometimes', 'required', 'date'],
        ]);

        $order->update($data);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Delete an order.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')
            ->with('delete_success', 'Order deleted successfully.');
    }
}
