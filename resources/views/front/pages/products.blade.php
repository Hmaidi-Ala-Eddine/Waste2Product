@extends('layouts.front')

@section('title', 'Products')

@section('content')
    <div class="team-style-two-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Our Products</h4>
                        <h2 class="title split-text">Discover amazing recycled products from our community</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <!-- Search and Filter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="search-filter-section">
                        <form method="GET" action="{{ route('front.products') }}" class="row g-3">
                            <!-- Search Input -->
                            <div class="col-md-4">
                                <div class="search-box">
                                    <input type="text" class="form-control" name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Search products...">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="col-md-2">
                                <select class="form-control" name="category">
                                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                                    <option value="furniture" {{ request('category') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                                    <option value="plastic" {{ request('category') == 'plastic' ? 'selected' : '' }}>Recycled Plastic</option>
                                    <option value="textile" {{ request('category') == 'textile' ? 'selected' : '' }}>Textile</option>
                                    <option value="metal" {{ request('category') == 'metal' ? 'selected' : '' }}>Metal</option>
                                </select>
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="col-md-2">
                                <select class="form-control" name="status">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                </select>
                            </div>
                            
                            <!-- Price Range -->
                            <div class="col-md-2">
                                <input type="number" class="form-control" name="min_price" 
                                       value="{{ request('min_price') }}" 
                                       placeholder="Min Price" step="0.01" min="0">
                            </div>
                            
                            <div class="col-md-2">
                                <input type="number" class="form-control" name="max_price" 
                                       value="{{ request('max_price') }}" 
                                       placeholder="Max Price" step="0.01" min="0">
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="col-md-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Search
                                    </button>
                                    <a href="{{ route('front.products') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Active Filters Display -->
                        @if(request()->hasAny(['search', 'category', 'status', 'min_price', 'max_price']))
                            <div class="active-filters mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Active filters:</small>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAllFilters()">
                                        <i class="fas fa-times me-1"></i>Clear All
                                    </button>
                                </div>
                                <div class="filter-tags">
                                    @if(request('search'))
                                        <span class="badge bg-primary me-1 mb-1">
                                            Search: "{{ request('search') }}"
                                            <button type="button" class="btn-close btn-close-white ms-1" onclick="clearFilter('search')" style="font-size: 0.7em;"></button>
                                        </span>
                                    @endif
                                    @if(request('category') && request('category') !== 'all')
                                        <span class="badge bg-info me-1 mb-1">
                                            Category: {{ ucfirst(request('category')) }}
                                            <button type="button" class="btn-close btn-close-white ms-1" onclick="clearFilter('category')" style="font-size: 0.7em;"></button>
                                        </span>
                                    @endif
                                    @if(request('status') && request('status') !== 'all')
                                        <span class="badge bg-warning me-1 mb-1">
                                            Status: {{ ucfirst(request('status')) }}
                                            <button type="button" class="btn-close btn-close-white ms-1" onclick="clearFilter('status')" style="font-size: 0.7em;"></button>
                                        </span>
                                    @endif
                                    @if(request('min_price'))
                                        <span class="badge bg-success me-1 mb-1">
                                            Min: ${{ request('min_price') }}
                                            <button type="button" class="btn-close btn-close-white ms-1" onclick="clearFilter('min_price')" style="font-size: 0.7em;"></button>
                                        </span>
                                    @endif
                                    @if(request('max_price'))
                                        <span class="badge bg-success me-1 mb-1">
                                            Max: ${{ request('max_price') }}
                                            <button type="button" class="btn-close btn-close-white ms-1" onclick="clearFilter('max_price')" style="font-size: 0.7em;"></button>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Results Count -->
                        <div class="results-info mt-2">
                            <small class="text-muted">
                                Showing {{ $products->count() }} of {{ $products->total() }} products
                                @if(request('search'))
                                    for "<strong>{{ request('search') }}</strong>"
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 team-style-two wow fadeInUp" data-wow-delay="{{ ($loop->index * 0.1) }}s">
                        <div class="team-style-two-item product-card" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                            <div class="thumb">
                                @if($product->image_path && file_exists(storage_path('app/public/' . $product->image_path)))
                                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                                         alt="{{ $product->name }}"
                                         onerror="this.src='{{ asset('assets/front/img/demo/home-1.jpg') }}'; this.onerror=null;">
                                @else
                                    <img src="{{ asset('assets/front/img/demo/home-1.jpg') }}" alt="Default Product Image">
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="status-badge status-{{ $product->status }}">
                                    {{ ucfirst($product->status) }}
                                </div>
                                
                                <!-- Price Tag -->
                                <div class="price-tag">
                                    ${{ number_format($product->price, 2) }}
                                </div>
                            </div>
                            <div class="info">
                                <h4><a href="#">{{ Str::limit($product->name, 30) }}</a></h4>
                                <p class="description">{{ Str::limit($product->description, 80) }}</p>
                                <div class="product-meta">
                                    <small class="text-muted">Category: {{ ucfirst($product->category) }}</small>
                                    <span class="condition">Condition: {{ ucfirst($product->condition ?? 'Good') }}</span>
                                </div>
                                <div class="product-actions-wrapper">
                                    <div class="product-actions mt-3">
                                        @if($product->status === 'available')
                                            <button class="btn btn-cart" onclick="addToCart({{ $product->id }})">
                                                <i class="fas fa-shopping-cart"></i> Add to Cart
                                            </button>
                                            <button class="btn btn-contact" onclick="openContactModal({{ $product->id }})">
                                                <i class="fas fa-envelope"></i> Contact Seller
                                            </button>
                                            <button class="btn btn-reserve" onclick="reserveProduct({{ $product->id }})">
                                                <i class="fas fa-bookmark"></i> Reserve
                                            </button>
                                        @elseif($product->status === 'reserved')
                                            <button class="btn btn-reserved" disabled>
                                                <i class="fas fa-clock"></i> Reserved
                                            </button>
                                            @auth
                                                @if($product->user_id === auth()->id())
                                                    <button class="btn btn-sold" onclick="markAsSold({{ $product->id }})">
                                                        <i class="fas fa-check"></i> Mark as Sold
                                                    </button>
                                                    <button class="btn btn-available" onclick="markAsAvailable({{ $product->id }})">
                                                        <i class="fas fa-undo"></i> Make Available
                                                    </button>
                                                @endif
                                            @endauth
                                        @elseif($product->status === 'sold')
                                            <button class="btn btn-sold" disabled>
                                                <i class="fas fa-check"></i> Sold
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <div class="no-products">
                            <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
                            <h4>No products available</h4>
                            <p class="text-muted">Check back later for new recycled products!</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
                <div class="row">
                    <div class="col-12 text-center mt-4">
                        <div class="pagination-wrapper">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">
                        <i class="fas fa-envelope me-2"></i>Contact Seller
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Product Info -->
                    <div class="product-info mb-4">
                        <h6 id="modal-product-name" class="fw-bold"></h6>
                        <p id="modal-product-price" class="text-primary fw-bold"></p>
                        <p id="modal-product-description" class="text-muted"></p>
                    </div>
                    
                    <!-- Contact Form -->
                    <div class="contact-form-section">
                        <h6>Send Message to Seller</h6>
                        <form id="contactForm" onsubmit="submitContact(event)">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contactName" class="form-label">Your Name *</label>
                                        <input type="text" class="form-control" id="contactName" placeholder="Enter your name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contactEmail" class="form-label">Your Email *</label>
                                        <input type="email" class="form-control" id="contactEmail" placeholder="Enter your email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="contactPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="contactPhone" placeholder="Enter your phone number">
                            </div>
                            <div class="mb-3">
                                <label for="contactMessage" class="form-label">Message *</label>
                                <textarea class="form-control" id="contactMessage" rows="4" placeholder="Tell the seller about your interest..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reserve Modal -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reserveModalLabel">
                        <i class="fas fa-bookmark me-2"></i>Reserve Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="product-info mb-4">
                        <h6 id="reserve-product-name" class="fw-bold"></h6>
                        <p id="reserve-product-price" class="text-primary fw-bold"></p>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Reservation Policy:</strong> This will reserve the product for you for 24 hours. You'll need to contact the seller to complete the purchase.
                    </div>
                    
                    <form id="reserveForm" onsubmit="submitReservation(event)">
                        <div class="mb-3">
                            <label for="reserveName" class="form-label">Your Name *</label>
                            <input type="text" class="form-control" id="reserveName" placeholder="Enter your name" required>
                        </div>
                        <div class="mb-3">
                            <label for="reserveEmail" class="form-label">Your Email *</label>
                            <input type="email" class="form-control" id="reserveEmail" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="reservePhone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="reservePhone" placeholder="Enter your phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="reserveMessage" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="reserveMessage" rows="3" placeholder="Any special requests or questions..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-bookmark me-2"></i>Reserve Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>
/* Search and Filter Section */
.search-filter-section {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.search-box {
    position: relative;
}

.search-box .search-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    pointer-events: none;
}

.search-box .form-control {
    padding-right: 45px;
    border-radius: 25px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.search-box .form-control:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
}

.search-filter-section .form-control {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.search-filter-section .form-control:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
}

.search-filter-section .btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.search-filter-section .btn-primary {
    background: linear-gradient(135deg, #4a90e2, #5aa3f0);
    border: none;
}

.search-filter-section .btn-primary:hover {
    background: linear-gradient(135deg, #357abd, #4a90e2);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
}

.search-filter-section .btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
}

.search-filter-section .btn-outline-secondary:hover {
    background: #6c757d;
    border-color: #6c757d;
    transform: translateY(-1px);
}

.active-filters {
    padding: 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.active-filters .badge {
    font-size: 0.8rem;
    padding: 5px 10px;
    border-radius: 15px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.active-filters .btn-close {
    background: none;
    border: none;
    opacity: 0.7;
    font-size: 0.7em;
    padding: 0;
    margin-left: 5px;
}

.active-filters .btn-close:hover {
    opacity: 1;
}

.results-info {
    padding: 10px 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .search-filter-section {
        padding: 20px 15px;
    }
    
    .search-filter-section .row .col-md-2,
    .search-filter-section .row .col-md-4 {
        margin-bottom: 15px;
    }
    
    .search-filter-section .d-flex {
        flex-direction: column;
    }
    
    .search-filter-section .d-flex .btn {
        margin-bottom: 10px;
    }
}

/* Product Card Styling */
.product-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: visible;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.product-card .card-body {
    overflow: visible;
}

.product-actions-wrapper {
    overflow: visible !important;
    position: relative;
    z-index: 20;
    padding: 20px 0;
    margin: 0 -10px;
    background: transparent;
}

.product-actions {
    overflow: visible !important;
    position: relative;
    z-index: 10;
    padding: 0;
    margin: 0;
}

/* Cart Button Styling */
.btn-cart {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
    padding: 6px 12px;
    border-radius: 18px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-right: 4px;
    margin-bottom: 6px;
    min-width: 100px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.15);
    position: relative;
    z-index: 5;
    filter: drop-shadow(0 2px 6px rgba(40, 167, 69, 0.15));
}

.btn-cart:hover {
    background: linear-gradient(135deg, #218838, #1ea085);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
    z-index: 15;
    filter: drop-shadow(0 8px 20px rgba(40, 167, 69, 0.4));
}

.btn-cart:active {
    transform: translateY(0);
    filter: drop-shadow(0 2px 6px rgba(40, 167, 69, 0.2));
}

.btn-cart:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    filter: none;
}

/* Status Management Buttons - Owner Actions */
.btn-sold {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-right: 6px;
    margin-bottom: 8px;
    min-width: 140px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
}

.btn-sold:hover {
    background: linear-gradient(135deg, #218838, #1ea085);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
    color: white;
    text-decoration: none;
}

.btn-sold:active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}


.btn-available {
    background: linear-gradient(135deg, #17a2b8, #138496);
    border: none;
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-right: 6px;
    margin-bottom: 8px;
    min-width: 140px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(23, 162, 184, 0.2);
}

.btn-available:hover {
    background: linear-gradient(135deg, #138496, #117a8b);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(23, 162, 184, 0.4);
    color: white;
    text-decoration: none;
}

.btn-available:active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}

/* Other Action Buttons */
.btn-contact {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    color: white;
    padding: 6px 12px;
    border-radius: 18px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-right: 4px;
    margin-bottom: 6px;
    min-width: 100px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    box-shadow: 0 2px 6px rgba(0, 123, 255, 0.15);
    position: relative;
    z-index: 5;
    filter: drop-shadow(0 2px 6px rgba(0, 123, 255, 0.15));
}

.btn-contact:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
    z-index: 15;
    filter: drop-shadow(0 8px 20px rgba(0, 123, 255, 0.4));
}

.btn-contact:active {
    transform: translateY(0);
    filter: drop-shadow(0 2px 6px rgba(0, 123, 255, 0.2));
}

.btn-reserve {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    border: none;
    color: #212529;
    padding: 6px 12px;
    border-radius: 18px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-right: 4px;
    margin-bottom: 6px;
    min-width: 100px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    box-shadow: 0 2px 6px rgba(255, 193, 7, 0.15);
    position: relative;
    z-index: 5;
    filter: drop-shadow(0 2px 6px rgba(255, 193, 7, 0.15));
}

.btn-reserve:hover {
    background: linear-gradient(135deg, #e0a800, #d39e00);
    transform: translateY(-2px);
    color: #212529;
    text-decoration: none;
    z-index: 15;
    filter: drop-shadow(0 8px 20px rgba(255, 193, 7, 0.4));
}

.btn-reserve:active {
    transform: translateY(0);
    filter: drop-shadow(0 2px 6px rgba(255, 193, 7, 0.2));
}

/* Disabled State Buttons */
.btn-reserved,
.btn-sold:disabled {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    border: none;
    color: white;
    padding: 10px 18px;
    border-radius: 22px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-right: 6px;
    margin-bottom: 8px;
    min-width: 120px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    opacity: 0.7;
    cursor: not-allowed;
}

/* Responsive Design for Buttons */
@media (max-width: 768px) {
    .btn-cart,
    .btn-contact,
    .btn-reserve,
    .btn-sold,
    .btn-available,
    .btn-reserved {
        min-width: 90px;
        padding: 5px 10px;
        font-size: 0.75rem;
        margin-right: 3px;
        margin-bottom: 5px;
    }
}

@media (max-width: 576px) {
    .product-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .btn-cart,
    .btn-contact,
    .btn-reserve,
    .btn-sold,
    .btn-available,
    .btn-reserved {
        min-width: 80px;
        padding: 4px 8px;
        font-size: 0.7rem;
        margin: 2px;
    }
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.product-card .thumb {
    position: relative;
    overflow: hidden;
}

.product-card .thumb img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .thumb img {
    transform: scale(1.05);
}

/* Status Badge */
.status-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    color: white;
    backdrop-filter: blur(10px);
}

.status-available {
    background: rgba(40, 167, 69, 0.9);
}

.status-reserved {
    background: rgba(255, 193, 7, 0.9);
}

.status-sold {
    background: rgba(0, 123, 255, 0.9);
}

.status-donated {
    background: rgba(255, 87, 34, 0.9);
}

/* Price Tag */
.price-tag {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 16px;
    backdrop-filter: blur(10px);
}

/* Info Section */
.product-card .info {
    padding: 20px;
}

.product-card .info h4 {
    margin-bottom: 10px;
    font-size: 18px;
    line-height: 1.4;
}

.product-card .info h4 a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-card .info h4 a:hover {
    color: #4a90e2;
}

.description {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 12px;
}

.product-meta .condition {
    color: #999;
}

.product-actions {
    display: flex;
    gap: 10px;
}

.product-actions .btn {
    flex: 1;
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.btn-contact {
    background: #4a90e2;
    color: white;
}

.btn-contact:hover {
    background: #357abd;
    transform: translateY(-1px);
}

.btn-reserve {
    background: #ffc107;
    color: #212529;
}

.btn-reserve:hover {
    background: #e0a800;
    transform: translateY(-1px);
}

.btn-reserved {
    background: #ffc107;
    color: #212529;
    opacity: 0.7;
}

.btn-sold {
    background: #007bff;
    color: white;
    opacity: 0.7;
}

.btn-donated {
    background: #ff5722;
    color: white;
    opacity: 0.7;
}

/* No Products Styling */
.no-products {
    padding: 60px 20px;
}

/* Modal Styling */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.modal-header {
    background: linear-gradient(135deg, #4a90e2, #5aa3f0);
    color: white;
    border-bottom: none;
    border-radius: 15px 15px 0 0;
}

.modal-header .btn-close {
    filter: invert(1);
}

.product-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #4a90e2;
}

.contact-form-section {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
    .product-actions {
        flex-direction: column;
    }
    
    .product-actions .btn {
        margin-bottom: 5px;
    }
    
    .product-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentProductId = null;

// Initialize search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeSearchAndFilters();
});

// Initialize search and filter functionality
function initializeSearchAndFilters() {
    const searchForm = document.querySelector('.search-filter-section form');
    const minPriceInput = document.querySelector('input[name="min_price"]');
    const maxPriceInput = document.querySelector('input[name="max_price"]');
    const searchInput = document.querySelector('input[name="search"]');
    
    // Price validation
    if (minPriceInput && maxPriceInput) {
        minPriceInput.addEventListener('input', validatePriceRange);
        maxPriceInput.addEventListener('input', validatePriceRange);
    }
    
    // Auto-submit on Enter key in search
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    }
    
    // Add loading state to form submission
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Searching...';
                submitBtn.disabled = true;
            }
        });
    }
}

// Validate price range
function validatePriceRange() {
    const minPrice = parseFloat(document.querySelector('input[name="min_price"]').value) || 0;
    const maxPrice = parseFloat(document.querySelector('input[name="max_price"]').value) || 0;
    
    if (minPrice > 0 && maxPrice > 0 && minPrice > maxPrice) {
        // Swap values if min is greater than max
        document.querySelector('input[name="min_price"]').value = maxPrice;
        document.querySelector('input[name="max_price"]').value = minPrice;
        
        // Show a subtle notification
        showNotification('Price range adjusted automatically', 'info');
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Clear individual filter
function clearFilter(filterType) {
    const url = new URL(window.location);
    url.searchParams.delete(filterType);
    window.location.href = url.toString();
}

// Clear all filters
function clearAllFilters() {
    window.location.href = '{{ route("front.products") }}';
}

// Add to Cart Function
function addToCart(productId) {
    const button = event.target.closest('.btn-cart');
    const originalText = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success state
            button.innerHTML = '<i class="fas fa-check"></i> Added!';
            button.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            
            // Update cart counter if exists
            updateCartCounter(data.cart_items_count);
            
            // Show notification
            showNotification(data.message, 'success');
            
            // Reset button after 2 seconds
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = originalText;
                button.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            }, 2000);
        } else {
            // Show error state
            button.innerHTML = '<i class="fas fa-times"></i> Error';
            button.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
            
            showNotification(data.message, 'error');
            
            // Reset button after 2 seconds
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = originalText;
                button.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = originalText;
        showNotification('An error occurred while adding to cart', 'error');
    });
}

// Update cart counter in header
function updateCartCounter(count) {
    const cartCounter = document.querySelector('.cart-counter');
    if (cartCounter) {
        cartCounter.textContent = count;
        cartCounter.style.display = count > 0 ? 'inline' : 'none';
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Mark as Sold
function markAsSold(productId) {
    const buyerName = prompt('Nom de l\'acheteur:');
    if (!buyerName) return;
    
    const buyerEmail = prompt('Email de l\'acheteur:');
    if (!buyerEmail) return;
    
    const salePrice = prompt('Prix de vente (optionnel):');
    
    const notes = prompt('Notes (optionnel):');
    
    fetch(`/products/${productId}/mark-sold`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            buyer_name: buyerName,
            buyer_email: buyerEmail,
            sale_price: salePrice || null,
            notes: notes || null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 2000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

// Mark as Available
function markAsAvailable(productId) {
    if (confirm('Êtes-vous sûr de vouloir remettre ce produit en vente ?')) {
        fetch(`/products/${productId}/mark-available`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }
}

// Open Contact Modal
function openContactModal(productId) {
    currentProductId = productId;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('contactModal'));
    modal.show();
    
    // Load product data
    loadProductData(productId);
}

// Open Reserve Modal
function openReserveModal(productId) {
    currentProductId = productId;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('reserveModal'));
    modal.show();
    
    // Load product data
    loadReserveProductData(productId);
}

// Load Product Data for Contact
function loadProductData(productId) {
    fetch(`/products/${productId}/data`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modal-product-name').textContent = data.product.name;
            document.getElementById('modal-product-price').textContent = `$${parseFloat(data.product.price).toFixed(2)}`;
            document.getElementById('modal-product-description').textContent = data.product.description || 'No description available';
        }
    })
    .catch(error => {
        console.error('Error loading product:', error);
    });
}

// Load Product Data for Reserve
function loadReserveProductData(productId) {
    fetch(`/products/${productId}/data`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('reserve-product-name').textContent = data.product.name;
            document.getElementById('reserve-product-price').textContent = `$${parseFloat(data.product.price).toFixed(2)}`;
        }
    })
    .catch(error => {
        console.error('Error loading product:', error);
    });
}

// Submit Contact Form
function submitContact(event) {
    event.preventDefault();
    
    const contactName = document.getElementById('contactName').value;
    const contactEmail = document.getElementById('contactEmail').value;
    const contactPhone = document.getElementById('contactPhone').value;
    const contactMessage = document.getElementById('contactMessage').value;
    const submitBtn = event.target.querySelector('button[type="submit"]');
    
    if (!contactName.trim() || !contactEmail.trim() || !contactMessage.trim()) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Add loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    
    fetch(`/products/${currentProductId}/contact`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: contactName,
            email: contactEmail,
            phone: contactPhone,
            message: contactMessage
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear form
            document.getElementById('contactForm').reset();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('contactModal'));
            modal.hide();
            
            // Show success message
            alert('Message sent successfully! The seller will contact you soon.');
        } else {
            alert('Error: ' + (data.message || 'Unable to send message'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending message');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Message';
    });
}

// Reserve Product Function
function reserveProduct(productId) {
    openReserveModal(productId);
}

// Submit Reservation
function submitReservation(event) {
    event.preventDefault();
    
    const reserveName = document.getElementById('reserveName').value;
    const reserveEmail = document.getElementById('reserveEmail').value;
    const reservePhone = document.getElementById('reservePhone').value;
    const reserveMessage = document.getElementById('reserveMessage').value;
    const submitBtn = event.target.querySelector('button[type="submit"]');
    
    if (!reserveName.trim() || !reserveEmail.trim() || !reservePhone.trim()) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Add loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Reserving...';
    
    fetch(`/products/${currentProductId}/reserve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: reserveName,
            email: reserveEmail,
            phone: reservePhone,
            message: reserveMessage
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear form
            document.getElementById('reserveForm').reset();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('reserveModal'));
            modal.hide();
            
            // Show success message
            alert('Product reserved successfully! You have 24 hours to contact the seller.');
            
            // Reload page to update status
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unable to reserve product'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error reserving product');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-bookmark me-2"></i>Reserve Product';
    });
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token to meta tag if not present
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }
});
</script>
@endpush

@endsection
