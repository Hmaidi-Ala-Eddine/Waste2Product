@extends('layouts.front')

@section('title', $product->name)

@push('styles')
<style>
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .product-detail-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
    }

    .product-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .product-detail-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 40px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        margin-bottom: 40px;
    }

    .product-image-section {
        position: relative;
    }

    .main-product-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .status-available {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
    }

    .status-sold {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
    }

    .status-donated {
        background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
        color: white;
    }

    .status-reserved {
        background: linear-gradient(135deg, #9E9E9E 0%, #757575 100%);
        color: white;
    }

    .product-info-section h1 {
        font-size: 36px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .product-badges {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .info-badge {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-category {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-condition {
        background: #e3f2fd;
        color: #1565c0;
    }

    .product-price {
        font-size: 42px;
        font-weight: 800;
        color: #667eea;
        margin-bottom: 25px;
    }

    .stock-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 20px;
        background: #f5f7fa;
        border-radius: 10px;
        margin-bottom: 25px;
    }

    .stock-info i {
        font-size: 20px;
        color: #667eea;
    }

    .stock-text {
        font-weight: 600;
        color: #2c3e50;
    }

    .stock-count {
        font-weight: 800;
        color: #667eea;
    }

    .product-description {
        margin-bottom: 30px;
    }

    .product-description h3 {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .product-description p {
        color: #7f8c8d;
        line-height: 1.8;
        font-size: 15px;
    }

    .seller-info {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f5f7fa;
        border-radius: 12px;
        margin-bottom: 30px;
    }

    .seller-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .seller-details h4 {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .seller-details p {
        color: #7f8c8d;
        font-size: 14px;
    }

    .product-actions {
        display: flex;
        gap: 15px;
    }

    .btn-action {
        flex: 1;
        padding: 18px 30px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-add-cart:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-reserve {
        background: linear-gradient(135deg, #9E9E9E 0%, #757575 100%);
        color: white;
    }

    .btn-reserve:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(158, 158, 158, 0.4);
    }

    .btn-make-available {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .btn-make-available:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    }

    .btn-back {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
        padding: 12px 24px;
        font-size: 15px;
    }

    .btn-back:hover {
        background: #667eea;
        color: white;
    }

    .btn-disabled {
        background: #e0e0e0;
        color: #999;
        cursor: not-allowed;
    }

    .btn-disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .related-products {
        margin-top: 60px;
    }

    .related-products h2 {
        font-size: 32px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    /* Toast Notifications */
    .toast-notification {
        position: fixed;
        top: 100px;
        right: -400px;
        background: white;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 10000;
        min-width: 320px;
        transition: right 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .toast-notification.show {
        right: 30px;
    }

    .toast-notification.success {
        border-left: 4px solid #4CAF50;
    }

    .toast-notification.error {
        border-left: 4px solid #f44336;
    }

    .toast-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-notification.success .toast-icon {
        background: #e8f5e9;
        color: #4CAF50;
    }

    .toast-notification.error .toast-icon {
        background: #ffebee;
        color: #f44336;
    }

    .toast-content {
        flex: 1;
    }

    .toast-message {
        color: #2c3e50;
        font-size: 15px;
        font-weight: 600;
    }

    .toast-close {
        background: none;
        border: none;
        color: #7f8c8d;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .toast-close:hover {
        background: #f0f0f0;
        color: #2c3e50;
    }

    @media (max-width: 992px) {
        .product-grid {
            grid-template-columns: 1fr;
        }

        .main-product-image {
            height: 400px;
        }

        .toast-notification {
            right: -100%;
            min-width: calc(100% - 40px);
            left: 20px;
        }

        .toast-notification.show {
            right: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="product-detail-wrapper">
    <div class="product-container">
        <!-- Back Button -->
        <a href="{{ route('front.shop') }}" class="btn-action btn-back" style="display: inline-flex; width: auto; margin-bottom: 30px;">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Shop</span>
        </a>

        <!-- Product Detail Card -->
        <div class="product-detail-card">
            <div class="product-grid">
                <!-- Product Image -->
                <div class="product-image-section">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="main-product-image">
                    @else
                        <img src="{{ asset('assets/img/default-product.jpg') }}" alt="{{ $product->name }}" class="main-product-image">
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="status-badge status-{{ $product->status }}">
                        {{ ucfirst($product->status) }}
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info-section">
                    <h1>{{ $product->name }}</h1>

                    <!-- Badges -->
                    <div class="product-badges">
                        <span class="info-badge badge-category">{{ ucfirst($product->category) }}</span>
                        @if($product->condition)
                            <span class="info-badge badge-condition">{{ ucfirst($product->condition) }}</span>
                        @endif
                    </div>

                    <!-- Price -->
                    <div class="product-price">
                        {{ $product->isFree() ? 'FREE' : number_format($product->price, 2) . ' TND' }}
                    </div>

                    <!-- Stock Info -->
                    <div class="stock-info">
                        <i class="fas fa-box"></i>
                        <span class="stock-text">Stock:</span>
                        <span class="stock-count">{{ $product->stock }} available</span>
                    </div>

                    <!-- Description -->
                    <div class="product-description">
                        <h3>Description</h3>
                        <p>{{ $product->description ?? 'No description available for this product.' }}</p>
                    </div>

                    <!-- Seller Info -->
                    <div class="seller-info">
                        <img src="{{ $product->user->profile_picture_url }}" alt="{{ $product->user->name }}" class="seller-avatar">
                        <div class="seller-details">
                            <h4>{{ $product->user->name }}</h4>
                            <p>Product Seller</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    @auth
                        @if($product->isAvailableForPurchase())
                            <div class="product-actions">
                                <button class="btn-action btn-add-cart" onclick="addToCart({{ $product->id }}, this)">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>Add to Cart</span>
                                </button>
                                <button class="btn-action btn-reserve" onclick="openReservationModal({{ $product->id }})">
                                    <i class="fas fa-bookmark"></i>
                                    <span>Reserve</span>
                                </button>
                            </div>
                        @elseif($product->status === 'reserved' && auth()->id() === $product->user_id)
                            <div class="product-actions">
                                <button class="btn-action btn-disabled" disabled>
                                    <i class="fas fa-times-circle"></i>
                                    <span>Reserved</span>
                                </button>
                                <button class="btn-action btn-make-available" onclick="makeProductAvailable({{ $product->id }}, this)">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Make Available</span>
                                </button>
                            </div>
                        @elseif($product->status === 'reserved')
                            <button class="btn-action btn-disabled" disabled>
                                <i class="fas fa-bookmark"></i>
                                <span>Reserved</span>
                            </button>
                        @else
                            <button class="btn-action btn-disabled" disabled>
                                <i class="fas fa-times-circle"></i>
                                <span>Not Available</span>
                            </button>
                        @endif
                    @else
                        <a href="{{ route('front.login') }}" class="btn-action btn-add-cart">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login to Purchase</span>
                        </a>
                    @endauth
        </div>
    </div>
</div>

<!-- Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title" id="reservationModalLabel">
                    <i class="fas fa-bookmark me-2"></i>Réserver ce produit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reservationForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prénom *</label>
                                <input type="text" class="form-control" name="first_name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nom *</label>
                                <input type="text" class="form-control" name="last_name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea class="form-control" name="message" rows="4" placeholder="Expliquez pourquoi vous souhaitez réserver ce produit..." required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                        <i class="fas fa-paper-plane me-1"></i>Envoyer la demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="related-products">
            <h2>Related Products</h2>
            <div class="related-grid">
                @foreach($relatedProducts as $related)
                <a href="{{ route('front.shop.show', $related->id) }}" style="text-decoration: none;">
                    <div class="product-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                        @if($related->image_path)
                            <img src="{{ asset('storage/' . $related->image_path) }}" alt="{{ $related->name }}" style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('assets/img/default-product.jpg') }}" alt="{{ $related->name }}" style="width: 100%; height: 200px; object-fit: cover;">
                        @endif
                        <div style="padding: 20px;">
                            <h4 style="color: #2c3e50; font-weight: 700; margin-bottom: 10px;">{{ $related->name }}</h4>
                            <p style="color: #667eea; font-weight: 800; font-size: 20px;">
                                {{ $related->isFree() ? 'FREE' : number_format($related->price, 2) . ' TND' }}
                            </p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toast Notification System
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Add to Cart
function addToCart(productId, buttonElement) {
    buttonElement.disabled = true;
    const originalHTML = buttonElement.innerHTML;
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Adding...</span>';

    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Product added to cart successfully!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route("front.cart") }}';
            }, 1000);
        } else {
            showToast(data.message, 'error');
            buttonElement.disabled = false;
            buttonElement.innerHTML = originalHTML;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to add product to cart', 'error');
        buttonElement.disabled = false;
        buttonElement.innerHTML = originalHTML;
    });
}

// Reserve Product - Open Modal
function openReservationModal(productId) {
    document.getElementById('reservationForm').action = `/products/${productId}/reservation`;
    const modal = new bootstrap.Modal(document.getElementById('reservationModal'));
    modal.show();
}

// Submit Reservation Form
document.getElementById('reservationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Envoi en cours...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('reservationModal'));
            modal.hide();
            form.reset();
            // Reload page to show updated status
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else if (data.errors) {
            // Handle validation errors
            clearFormErrors(form);
            Object.keys(data.errors).forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.classList.add('is-invalid');
                    const errorElement = field.parentNode.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.textContent = data.errors[fieldName][0];
                        errorElement.style.display = 'block';
                    }
                }
            });
        } else {
            showToast(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Une erreur est survenue lors de l\'envoi', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Make Product Available (for owners)
function makeProductAvailable(productId, buttonElement) {
    if (!confirm('Êtes-vous sûr de vouloir remettre ce produit en disponible ?')) {
        return;
    }
    
    buttonElement.disabled = true;
    const originalHTML = buttonElement.innerHTML;
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing...</span>';
    
    fetch(`/products/${productId}/make-available`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast(data.message, 'error');
            buttonElement.disabled = false;
            buttonElement.innerHTML = originalHTML;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Une erreur est survenue', 'error');
        buttonElement.disabled = false;
        buttonElement.innerHTML = originalHTML;
    });
}

// Clear form errors
function clearFormErrors(form) {
    const fields = form.querySelectorAll('.form-control');
    fields.forEach(field => {
        field.classList.remove('is-invalid');
        const errorElement = field.parentNode.querySelector('.invalid-feedback');
        if (errorElement) {
            errorElement.style.display = 'none';
            errorElement.textContent = '';
        }
    });
}
</script>
@endpush
