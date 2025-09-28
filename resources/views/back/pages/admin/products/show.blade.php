@extends('layouts.back')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Product Details</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3" style="max-height: 300px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center" style="height: 300px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ $product->description }}</p>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong>Category:</strong>
                                            <span class="badge bg-gradient-info">{{ ucfirst($product->category) }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Condition:</strong>
                                            <span class="badge bg-gradient-{{ $product->condition_badge_class }}">{{ ucfirst($product->condition) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong>Price:</strong>
                                            <span class="text-success font-weight-bold">{{ $product->formatted_price }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Status:</strong>
                                            <span class="badge bg-gradient-{{ $product->status_badge_class }}">{{ ucfirst($product->status) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <strong>Owner:</strong>
                                            <span class="text-dark">{{ $product->user->name }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong>Created:</strong>
                                            <span class="text-muted">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Updated:</strong>
                                            <span class="text-muted">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    
                                    @if($product->wasteRequest)
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <strong>Source Waste Request:</strong>
                                            <span class="text-info">#{{ $product->wasteRequest->id }} - {{ $product->wasteRequest->waste_type }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn bg-gradient-warning">Edit</a>
                                <button type="button" class="btn bg-gradient-info" data-bs-toggle="modal" data-bs-target="#statusModal">
                                    Change Status
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn bg-gradient-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
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
@endsection
