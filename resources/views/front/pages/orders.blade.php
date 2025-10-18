@extends('layouts.front')

@section('title', 'My Orders')

@section('content')
    <div class="default-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4">My Orders</h2>

                    @if($orders->isEmpty())
                        <div class="alert alert-info">You have no orders yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Ordered At</th>
                                        <th style="width:90px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ optional($order->product)->name ?? 'Product #'.$order->product_id }}</td>
                                            <td>{{ $order->quantity }}</td>
                                            <td><span class="badge badge-secondary">{{ ucfirst($order->status) }}</span></td>
                                            <td>${{ number_format((float)$order->total_price, 2) }}</td>
                                            <td>{{ optional($order->ordered_at)->format('Y-m-d H:i') ?? $order->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('front.orders.destroy', $order->id) }}" onsubmit="return confirm('Delete this order?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
