@extends('layouts.back')

@section('title', 'Gestion des Produits')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-white text-capitalize mb-0">Products Management</h6>
                            <div class="text-white">
                                <small>Total: {{ $products->total() }} products</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <!-- Search and Filter Section -->
                    <div class="card-body px-3 pt-3 pb-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-dark">Search & Filter Products</h6>
                            <a href="{{ route('admin.products.create') }}" class="btn bg-gradient-success">
                                <i class="material-symbols-rounded me-1">add</i>Add New Product
                            </a>
                        </div>
                    </div>
                    <div class="row px-3 mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('admin.products.index') }}">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Search products..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form method="GET" action="{{ route('admin.products.index') }}">
                                <select name="category" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    <option value="furniture" {{ request('category') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                                    <option value="plastic" {{ request('category') == 'plastic' ? 'selected' : '' }}>Recycled Plastic</option>
                                    <option value="textile" {{ request('category') == 'textile' ? 'selected' : '' }}>Textile</option>
                                    <option value="metal" {{ request('category') == 'metal' ? 'selected' : '' }}>Metal</option>
                                </select>
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form method="GET" action="{{ route('admin.products.index') }}">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                    <option value="donated" {{ request('status') == 'donated' ? 'selected' : '' }}>Donated</option>
                                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                </select>
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form method="GET" action="{{ route('admin.products.index') }}">
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price Low-High</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price High-Low</option>
                                </select>
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            </form>
                        </div>
                    </div>

                    @if($products->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Owner</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                @if($product->image_path)
                                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="avatar avatar-sm me-3 border-radius-lg" alt="product">
                                                @else
                                                    <img src="{{ asset('assets/back/img/team-2.jpg') }}" class="avatar avatar-sm me-3 border-radius-lg" alt="product">
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ Str::limit($product->description, 50) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $product->category }}</p>
                                        <span class="badge badge-sm bg-gradient-{{ $product->condition_badge_class }}">{{ ucfirst($product->condition) }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $product->formatted_price }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-{{ $product->status_badge_class }}">{{ ucfirst($product->status) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $product->user->name }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $product->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal{{ $product->id }}" title="Change Status">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Status Change Modal -->
                                <div class="modal fade" id="statusModal{{ $product->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Change Product Status</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.products.changeStatus', $product) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="status">New Status</label>
                                                        <select class="form-control" id="status" name="status" required>
                                                            <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>Available</option>
                                                            <option value="sold" {{ $product->status == 'sold' ? 'selected' : '' }}>Sold</option>
                                                            <option value="donated" {{ $product->status == 'donated' ? 'selected' : '' }}>Donated</option>
                                                            <option value="reserved" {{ $product->status == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                    @else
                    <!-- No Products Available -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-box-open fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted">No Products Available</h4>
                        <p class="text-muted">There are currently no products in the system.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn bg-gradient-dark">
                            <i class="fas fa-plus me-2"></i>
                            Create First Product
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
