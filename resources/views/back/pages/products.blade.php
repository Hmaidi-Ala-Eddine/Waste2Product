@extends('layouts.back')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')

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
      
      <!-- Search and Filter Section -->
      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Filter Products</h6>
          <div class="d-flex gap-2">
            <div class="btn-group me-2" role="group" aria-label="View toggle">
              <button type="button" class="btn btn-outline-dark btn-sm" id="productsListBtn" title="List view">
                <i class="material-symbols-rounded align-middle">view_list</i>
              </button>
              <button type="button" class="btn btn-outline-dark btn-sm" id="productsGridBtn" title="Grid view">
                <i class="material-symbols-rounded align-middle">grid_view</i>
              </button>
            </div>
            <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
              <i class="material-symbols-rounded me-1">add</i>Add New Product
            </button>
          </div>
        </div>
        
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="input-group input-group-outline">
              <label class="form-label">Search products...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="category">
                <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                <option value="furniture" {{ request('category') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="plastic" {{ request('category') == 'plastic' ? 'selected' : '' }}>Recycled Plastic</option>
                <option value="textile" {{ request('category') == 'textile' ? 'selected' : '' }}>Textile</option>
                <option value="metal" {{ request('category') == 'metal' ? 'selected' : '' }}>Metal</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                <option value="donated" {{ request('status') == 'donated' ? 'selected' : '' }}>Donated</option>
                <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn bg-gradient-dark mb-0 w-100">
              <i class="material-symbols-rounded me-1">search</i>Search
            </button>
          </div>
          <div class="col-md-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary mb-0 w-100">
              <i class="material-symbols-rounded me-1">refresh</i>Reset
            </a>
          </div>
        </form>
        
        @if(request()->hasAny(['search', 'category', 'status']))
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <small class="text-muted">
                Showing {{ $products->count() }} of {{ $products->total() }} results
                @if(request('search'))
                  for "<strong>{{ request('search') }}</strong>"
                @endif
              </small>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
              <i class="material-symbols-rounded me-1">clear</i>Clear Filters
            </a>
          </div>
        @endif
      </div>
      <div class="card-body px-0 pb-2">
        <div id="productsListView" class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Owner</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($products as $index => $product)
                @php
                  // Cycle through available team images for profile pictures
                  $avatarImages = ['team-1.jpg', 'team-2.jpg', 'team-3.jpg', 'team-4.jpg', 'team-5.jpg'];
                  $avatarImage = $avatarImages[$index % count($avatarImages)];
                @endphp
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        @if($product->image_path && trim($product->image_path) !== '')
                          <img src="{{ asset('storage/' . $product->image_path) }}" 
                               class="avatar avatar-sm me-3 border-radius-lg" 
                               alt="Product image"
                               loading="lazy"
                               onerror="this.src='{{ asset('assets/back/img/' . $avatarImage) }}'; this.onerror=null; this.classList.add('opacity-6');">
                        @else
                          <img src="{{ asset('assets/back/img/' . $avatarImage) }}" class="avatar avatar-sm me-3 border-radius-lg opacity-6" alt="No image uploaded">
                        @endif
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ Str::limit($product->description, 50) }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ ucfirst($product->category) }}</p>
                    <p class="text-xs text-secondary mb-0">{{ ucfirst($product->condition ?? 'Good') }}</p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <span class="text-xs font-weight-bold">${{ number_format($product->price, 2) }}</span>
                  </td>
                  <td class="align-middle text-center text-sm">
                    @if($product->status === 'available')
                      <span class="badge badge-sm bg-gradient-success">Available</span>
                    @elseif($product->status === 'sold')
                      <span class="badge badge-sm bg-gradient-info">Sold</span>
                    @elseif($product->status === 'donated')
                      <span class="badge badge-sm bg-gradient-warning">Donated</span>
                    @else
                      <span class="badge badge-sm bg-gradient-secondary">Reserved</span>
                    @endif
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $product->user->name ?? 'Unknown' }}</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">
                      {{ $product->created_at ? $product->created_at->format('d/m/y') : 'N/A' }}
                    </span>
                  </td>
                  <td class="align-middle text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2 edit-btn" 
                       onclick="editProduct({{ $product->id }})" 
                       data-toggle="tooltip" data-original-title="Edit product">
                      Edit
                    </a>
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs delete-btn" 
                       onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')" 
                       data-toggle="tooltip" data-original-title="Delete product">
                      Delete
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4">
                    <p class="text-secondary mb-0">No products found</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Grid View -->
        <div id="productsGridView" class="d-none px-3 pb-3">
          <div class="row g-3">
            @foreach($products as $index => $product)
              @php
                $avatarImages = ['team-1.jpg', 'team-2.jpg', 'team-3.jpg', 'team-4.jpg', 'team-5.jpg'];
                $avatarImage = $avatarImages[$index % count($avatarImages)];
              @endphp
              <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-sm border-0 h-100 product-card">
                  <div class="ratio ratio-16x9 card-img-top bg-light overflow-hidden">
                    @if($product->image_path && trim($product->image_path) !== '')
                      <img src="{{ asset('storage/' . $product->image_path) }}" class="object-fit-cover w-100 h-100" alt="product" loading="lazy" onerror="this.src='{{ asset('assets/back/img/' . $avatarImage) }}'; this.onerror=null; this.classList.add('opacity-6');">
                    @else
                      <img src="{{ asset('assets/back/img/' . $avatarImage) }}" class="object-fit-cover w-100 h-100 opacity-6" alt="No image uploaded">
                    @endif
                  </div>
                  <div class="card-body">
                    <small class="text-muted d-block mb-1">{{ ucfirst($product->category) }} • {{ ucfirst($product->condition ?? 'Good') }}</small>
                    <h6 class="card-title mb-2">{{ Str::limit($product->name, 50) }}</h6>
                    <p class="card-text text-secondary mb-3">{{ Str::limit($product->description, 90) }}</p>
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark">{{ $product->formatted_price ?? ('$' . number_format($product->price,2)) }}</span>
                        @php
                          $map = ['available'=>'success','sold'=>'info','donated'=>'warning','reserved'=>'secondary'];
                          $cls = $map[$product->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-gradient-{{ $cls }}">{{ strtoupper($product->status) }}</span>
                      </div>
                      <div class="btn-group">
                        <a href="javascript:;" class="btn btn-sm btn-outline-dark" onclick="editProduct({{ $product->id }})">Edit</a>
                        <a href="javascript:;" class="btn btn-sm btn-outline-danger" onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')">Delete</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        
        <!-- Pagination Section -->
        @if($products->hasPages())
          <div class="card-footer px-3 py-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted">
                  Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                </small>
              </div>
              <div>
                {{ $products->links('back.partials.pagination') }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Add New Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white" id="addProductModalLabel">
          <i class="material-symbols-rounded me-2">add</i>Add New Product
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addProductForm" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        <!-- Hidden field for user_id - automatically filled -->
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        <div class="modal-body p-4">
          <div class="row">
            <!-- Product Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Product Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Product Name *</label>
                <input type="text" class="form-control" name="name" id="product_name" required placeholder="Enter product name" minlength="3" maxlength="100">
                <div class="invalid-feedback" id="product_name_error"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Category *</label>
                <select class="form-control" name="category" id="product_category" required>
                  <option value="">Select Category</option>
                  <option value="furniture">Furniture</option>
                  <option value="electronics">Electronics</option>
                  <option value="plastic">Recycled Plastic</option>
                  <option value="textile">Textile</option>
                  <option value="metal">Metal</option>
                </select>
                <div class="invalid-feedback" id="product_category_error"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Price *</label>
                <input type="number" class="form-control" name="price" id="product_price" step="0.01" min="0" max="999999.99" required placeholder="0.00">
                <div class="invalid-feedback" id="product_price_error"></div>
              </div>
            </div>
            
            <!-- Product Details -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Product Details</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status *</label>
                <select class="form-control" name="status" id="product_status" required>
                  <option value="available" selected>Available</option>
                  <option value="sold">Sold</option>
                  <option value="donated">Donated</option>
                  <option value="reserved">Reserved</option>
                </select>
                <div class="invalid-feedback" id="product_status_error"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Condition</label>
                <select class="form-control" name="condition" id="product_condition">
                  <option value="excellent">Excellent</option>
                  <option value="good" selected>Good</option>
                  <option value="fair">Fair</option>
                  <option value="poor">Poor</option>
                </select>
                <div class="invalid-feedback" id="product_condition_error"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Product Image</label>
                <div class="input-group input-group-outline">
                  <input type="file" class="form-control" name="image" accept="image/*" id="productImageUpload">
                </div>
                <div class="invalid-feedback" id="productImageUpload_error"></div>
                <div id="image_preview" class="mt-2"></div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description</label>
                <textarea class="form-control" name="description" id="product_description" rows="4" placeholder="Enter product description" maxlength="1000"></textarea>
                <div class="invalid-feedback" id="product_description_error"></div>
                <div class="text-end">
                  <small class="text-muted" id="description_counter">0/1000</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="material-symbols-rounded me-1">close</i>Cancel
          </button>
          <button type="submit" class="btn bg-gradient-success">
            <i class="material-symbols-rounded me-1">add</i>Create Product
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white" id="editProductModalLabel">
          <i class="material-symbols-rounded me-2">edit</i>Edit Product
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editProductForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Hidden field for user_id - automatically filled -->
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        <div class="modal-body p-4">
          <div class="row">
            <!-- Product Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Product Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Product Name *</label>
                <input type="text" class="form-control" name="name" id="edit_name" required placeholder="Enter product name" minlength="3" maxlength="100">
                <div class="invalid-feedback" id="edit_name_error"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Category *</label>
                <select class="form-control" name="category" id="edit_category" required>
                  <option value="">Select Category</option>
                  <option value="furniture">Furniture</option>
                  <option value="electronics">Electronics</option>
                  <option value="plastic">Recycled Plastic</option>
                  <option value="textile">Textile</option>
                  <option value="metal">Metal</option>
                </select>
                <div class="invalid-feedback" id="edit_category_error"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Price *</label>
                <input type="number" class="form-control" name="price" id="edit_price" step="0.01" min="0" max="999999.99" required placeholder="0.00">
                <div class="invalid-feedback" id="edit_price_error"></div>
              </div>
            </div>
            
            <!-- Product Details -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Product Details</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status *</label>
                <select class="form-control" name="status" id="edit_status" required>
                  <option value="available">Available</option>
                  <option value="sold">Sold</option>
                  <option value="donated">Donated</option>
                  <option value="reserved">Reserved</option>
                </select>
                <div class="invalid-feedback" id="edit_status_error"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Condition</label>
                <select class="form-control" name="condition" id="edit_condition">
                  <option value="excellent">Excellent</option>
                  <option value="good">Good</option>
                  <option value="fair">Fair</option>
                  <option value="poor">Poor</option>
                </select>
                <div class="invalid-feedback" id="edit_condition_error"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Current Image</label>
                <div id="current_product_image_preview" class="mb-2"></div>
                <div class="input-group input-group-outline">
                  <input type="file" class="form-control" name="image" accept="image/*" id="editProductImageUpload">
                </div>
                <div class="invalid-feedback" id="edit_image_error"></div>
                <div id="edit_image_preview" class="mt-2"></div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description</label>
                <textarea class="form-control" name="description" id="edit_description" rows="4" placeholder="Enter product description" maxlength="1000"></textarea>
                <div class="invalid-feedback" id="edit_description_error"></div>
                <div class="text-end">
                  <small class="text-muted" id="edit_description_counter">0/1000</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="material-symbols-rounded me-1">close</i>Cancel
          </button>
          <button type="submit" class="btn bg-gradient-dark">
            <i class="material-symbols-rounded me-1">save</i>Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteProductModalLabel">Delete Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the product "<strong id="deleteProductName"></strong>"?</p>
        <p class="text-danger">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteProductForm" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Pass Laravel validation errors to JavaScript
window.laravelErrors = @json($errors->all());
window.laravelErrorFields = @json($errors->getMessages());
// Load users when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeFloatingLabels();
    initProductsViewToggle();
    initializeFormValidation();
});

// Initialize floating labels
function initializeFloatingLabels() {
    const inputs = document.querySelectorAll('.input-group-outline .form-control');
    
    inputs.forEach(function(input) {
        const inputGroup = input.closest('.input-group-outline');
        
        function checkInput() {
            if (input.value.trim() !== '') {
                inputGroup.classList.add('is-filled');
            } else {
                inputGroup.classList.remove('is-filled');
            }
        }
        
        // Initial check
        checkInput();
        
        // Focus event
        input.addEventListener('focus', function() {
            inputGroup.classList.add('is-focused');
        });
        
        // Blur event
        input.addEventListener('blur', function() {
            inputGroup.classList.remove('is-focused');
            checkInput();
        });
        
        // Input event
        input.addEventListener('input', function() {
            checkInput();
        });
    });
}

// Handle server validation errors
function handleServerErrors(formType = 'add') {
    // Hide global error messages
    hideGlobalErrors();
    
    // Check if there are server validation errors
    const hasErrors = window.laravelErrorFields && Object.keys(window.laravelErrorFields).length > 0;
    
    if (hasErrors) {
        console.log('Server errors found:', window.laravelErrorFields);
        
        // Get errors from Laravel session
        const errors = getServerErrors();
        
        // Display errors under each field
        Object.keys(errors).forEach(fieldName => {
            const fieldId = getFieldId(fieldName, formType);
            const errorMessage = errors[fieldName][0]; // Get first error message
            
            console.log(`Field: ${fieldName}, FieldId: ${fieldId}, Message: ${errorMessage}`);
            
            if (fieldId && errorMessage) {
                showFieldError(fieldId, errorMessage);
            }
        });
    } else {
        console.log('No server errors found');
    }
}

// Hide global error messages
function hideGlobalErrors() {
    // Hide Laravel's default error messages
    const globalErrors = document.querySelectorAll('.alert-danger, .error-message, .alert');
    globalErrors.forEach(error => {
        error.style.display = 'none';
    });
}

// Get server errors (this would need to be implemented based on your Laravel setup)
function getServerErrors() {
    // Use Laravel errors passed to JavaScript
    if (window.laravelErrorFields && Object.keys(window.laravelErrorFields).length > 0) {
        return window.laravelErrorFields;
    }
    
    // Fallback: Check for common Laravel validation error patterns
    const errors = {};
    
    // Check for common Laravel validation error patterns
    const errorElements = document.querySelectorAll('.alert-danger li, .error-message');
    errorElements.forEach(element => {
        const text = element.textContent.trim();
        
        // Map error messages to field names
        if (text.includes('description') && text.includes('characters')) {
            errors['description'] = [text];
        } else if (text.includes('user id') && text.includes('required')) {
            errors['user_id'] = [text];
        } else if (text.includes('name') && text.includes('required')) {
            errors['name'] = [text];
        } else if (text.includes('category') && text.includes('required')) {
            errors['category'] = [text];
        } else if (text.includes('price') && text.includes('required')) {
            errors['price'] = [text];
        }
    });
    
    return errors;
}

// Map Laravel field names to form field IDs
function getFieldId(fieldName, formType = 'add') {
    const fieldMap = {
        'name': formType === 'add' ? 'product_name' : 'edit_name',
        'category': formType === 'add' ? 'product_category' : 'edit_category',
        'price': formType === 'add' ? 'product_price' : 'edit_price',
        'status': formType === 'add' ? 'product_status' : 'edit_status',
        'condition': formType === 'add' ? 'product_condition' : 'edit_condition',
        'description': formType === 'add' ? 'product_description' : 'edit_description',
        'image': formType === 'add' ? 'productImageUpload' : 'editProductImageUpload',
        'user_id': null // Hidden field, no need to show error
    };
    
    return fieldMap[fieldName] || null;
}

// Initialize form validation
function initializeFormValidation() {
    // Add validation for add product form
    const addForm = document.getElementById('addProductForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            // Validate all fields and prevent submission if validation fails
            if (!validateAddForm()) {
                e.preventDefault();
                return false;
            }
            // Let the form submit normally if validation passes
        });
        
        // Handle server validation errors when modal opens
        const addModal = document.getElementById('addProductModal');
        if (addModal) {
            addModal.addEventListener('shown.bs.modal', function() {
                handleServerErrors('add');
            });
        }
        
        // Add real-time validation
        addRealTimeValidation('product_name', validateName);
        addRealTimeValidation('product_category', validateCategory);
        addRealTimeValidation('product_price', validatePrice);
        addRealTimeValidation('product_status', validateStatus);
        addRealTimeValidation('product_condition', validateCondition);
        addRealTimeValidation('product_description', validateDescription);
        
        // Image validation
        const imageInput = document.getElementById('productImageUpload');
        if (imageInput) {
            imageInput.addEventListener('change', validateImage);
        }
        
        // Description counter
        const descriptionTextarea = document.getElementById('product_description');
        const descriptionCounter = document.getElementById('description_counter');
        if (descriptionTextarea && descriptionCounter) {
            descriptionTextarea.addEventListener('input', function() {
                const length = this.value.length;
                descriptionCounter.textContent = `${length}/1000`;
                if (length > 1000) {
                    descriptionCounter.classList.add('text-danger');
                } else {
                    descriptionCounter.classList.remove('text-danger');
                }
            });
        }
    }
    
    // Add validation for edit product form
    const editForm = document.getElementById('editProductForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            // Validate all fields and prevent submission if validation fails
            if (!validateEditForm()) {
                e.preventDefault();
                return false;
            }
            // Let the form submit normally if validation passes
        });
        
        // Handle server validation errors when edit modal opens
        const editModal = document.getElementById('editProductModal');
        if (editModal) {
            editModal.addEventListener('shown.bs.modal', function() {
                handleServerErrors('edit');
            });
        }
        
        // Add real-time validation for edit form
        addRealTimeValidation('edit_name', validateName);
        addRealTimeValidation('edit_category', validateCategory);
        addRealTimeValidation('edit_price', validatePrice);
        addRealTimeValidation('edit_status', validateStatus);
        addRealTimeValidation('edit_condition', validateCondition);
        addRealTimeValidation('edit_description', validateDescription);
        
        // Image validation for edit form
        const editImageInput = document.getElementById('editProductImageUpload');
        if (editImageInput) {
            editImageInput.addEventListener('change', validateImage);
        }
        
        // Description counter for edit form
        const editDescriptionTextarea = document.getElementById('edit_description');
        const editDescriptionCounter = document.getElementById('edit_description_counter');
        if (editDescriptionTextarea && editDescriptionCounter) {
            editDescriptionTextarea.addEventListener('input', function() {
                const length = this.value.length;
                editDescriptionCounter.textContent = `${length}/1000`;
                if (length > 1000) {
                    editDescriptionCounter.classList.add('text-danger');
                } else {
                    editDescriptionCounter.classList.remove('text-danger');
                            }
                        });
                    }
    }
}

// Add real-time validation to form fields
function addRealTimeValidation(fieldId, validationFunction) {
    const field = document.getElementById(fieldId);
    if (field) {
        // Validate on blur (when user leaves the field)
        field.addEventListener('blur', function() {
            validationFunction(fieldId);
        });
        
        // Clear error when user starts typing
        field.addEventListener('input', function() {
            clearFieldError(fieldId);
        });
        
        // Also validate on change for select fields
        field.addEventListener('change', function() {
            validationFunction(fieldId);
        });
    }
}

// Validation functions
function validateName(fieldId) {
    const field = document.getElementById(fieldId);
    const value = field.value.trim();
    const errorElement = document.getElementById(fieldId + '_error');
    
    if (!value) {
        showFieldError(fieldId, 'Product name is required');
        return false;
    }
    
    if (value.length < 2) {
        showFieldError(fieldId, 'Product name must be at least 2 characters long');
        return false;
    }
    
    if (value.length > 100) {
        showFieldError(fieldId, 'Product name must not exceed 100 characters');
        return false;
    }
    
    clearFieldError(fieldId);
    return true;
}

function validateCategory(fieldId) {
    const field = document.getElementById(fieldId);
    const value = field.value;
    const errorElement = document.getElementById(fieldId + '_error');
    
    if (!value) {
        showFieldError(fieldId, 'Please select a category');
        return false;
    }
    
    const validCategories = ['furniture', 'electronics', 'plastic', 'textile', 'metal'];
    if (!validCategories.includes(value)) {
        showFieldError(fieldId, 'Please select a valid category');
        return false;
    }
    
    clearFieldError(fieldId);
    return true;
}

function validatePrice(fieldId) {
    const field = document.getElementById(fieldId);
    const value = field.value.trim();
    const errorElement = document.getElementById(fieldId + '_error');
    
    if (!value || value === '') {
        showFieldError(fieldId, 'Please enter a price');
        return false;
    }
    
    const numValue = parseFloat(value);
    
    if (isNaN(numValue)) {
        showFieldError(fieldId, 'Please enter a valid number');
        return false;
    }
    
    if (numValue < 0) {
        showFieldError(fieldId, 'Price cannot be negative');
        return false;
    }
    
    if (numValue > 999999.99) {
        showFieldError(fieldId, 'Price cannot exceed $999,999.99');
        return false;
    }
    
    clearFieldError(fieldId);
    return true;
}

function validateStatus(fieldId) {
    const field = document.getElementById(fieldId);
    const value = field.value;
    const errorElement = document.getElementById(fieldId + '_error');
    
    if (!value) {
        showFieldError(fieldId, 'Please select a status');
        return false;
    }
    
    const validStatuses = ['available', 'sold', 'donated', 'reserved'];
    if (!validStatuses.includes(value)) {
        showFieldError(fieldId, 'Please select a valid status');
        return false;
    }
    
    clearFieldError(fieldId);
    return true;
}

function validateCondition(fieldId) {
    const field = document.getElementById(fieldId);
    const value = field.value;
    const errorElement = document.getElementById(fieldId + '_error');
    
    if (!value) {
        showFieldError(fieldId, 'Please select a condition');
        return false;
    }
    
    const validConditions = ['excellent', 'good', 'fair', 'poor'];
    if (!validConditions.includes(value)) {
        showFieldError(fieldId, 'Please select a valid condition');
        return false;
    }
    
    clearFieldError(fieldId);
    return true;
}

function validateDescription(fieldId) {
    const field = document.getElementById(fieldId);
    const value = field.value.trim();
    const errorElement = document.getElementById(fieldId + '_error');
    
    if (value.length > 0 && value.length < 10) {
        showFieldError(fieldId, 'Description must be at least 10 characters');
        return false;
    }
    
    if (value.length > 1000) {
        showFieldError(fieldId, 'Description cannot exceed 1000 characters');
        return false;
    }
    
    clearFieldError(fieldId);
    return true;
}

function validateImage() {
    const field = event.target;
    const file = field.files[0];
    const errorElement = document.getElementById('image_error');
    const editErrorElement = document.getElementById('edit_image_error');
    const targetErrorElement = field.id === 'productImageUpload' ? errorElement : editErrorElement;
    
    if (!file) {
        clearFieldError(field.id === 'productImageUpload' ? 'productImageUpload' : 'editProductImageUpload');
        return true;
    }
    
    // Check file size (2MB = 2 * 1024 * 1024 bytes)
    if (file.size > 2 * 1024 * 1024) {
        showFieldError(field.id === 'productImageUpload' ? 'productImageUpload' : 'editProductImageUpload', 'File size must not exceed 2MB');
        return false;
    }
    
    // Check file type
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        showFieldError(field.id === 'productImageUpload' ? 'productImageUpload' : 'editProductImageUpload', 'Only JPEG, PNG, JPG, and GIF files are allowed');
        return false;
    }
    
    // Show image preview
    const previewId = field.id === 'productImageUpload' ? 'image_preview' : 'edit_image_preview';
    const previewElement = document.getElementById(previewId);
    if (previewElement) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewElement.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 100px;" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
    
    clearFieldError(field.id === 'productImageUpload' ? 'productImageUpload' : 'editProductImageUpload');
    return true;
}

// Show field error
function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(fieldId + '_error');
    
    if (field) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
    }
    
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add('show');
        errorElement.style.display = 'block';
        errorElement.style.color = '#dc3545';
        errorElement.style.fontWeight = 'bold';
    }
}

// Clear field error
function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(fieldId + '_error');
    
    if (field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    }
    
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.classList.remove('show');
        errorElement.style.display = 'none';
    }
}

// Validate add form
function validateAddForm() {
    const fields = ['product_name', 'product_category', 'product_price', 'product_status', 'product_condition', 'product_description'];
    let isValid = true;
    
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            let fieldValid = true;
            switch(fieldId) {
                case 'product_name':
                    fieldValid = validateName(fieldId);
                    break;
                case 'product_category':
                    fieldValid = validateCategory(fieldId);
                    break;
                case 'product_price':
                    fieldValid = validatePrice(fieldId);
                    break;
                case 'product_status':
                    fieldValid = validateStatus(fieldId);
                    break;
                case 'product_condition':
                    fieldValid = validateCondition(fieldId);
                    break;
                case 'product_description':
                    fieldValid = validateDescription(fieldId);
                    break;
            }
            if (!fieldValid) isValid = false;
        }
    });
    
    // Validate image if provided
    const imageInput = document.getElementById('productImageUpload');
    if (imageInput && imageInput.files.length > 0) {
        if (!validateImage()) {
            isValid = false;
        }
    }
    
    return isValid;
}

// Validate edit form
function validateEditForm() {
    const fields = ['edit_name', 'edit_category', 'edit_price', 'edit_status', 'edit_condition', 'edit_description'];
    let isValid = true;
    
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            let fieldValid = true;
            switch(fieldId) {
                case 'edit_name':
                    fieldValid = validateName(fieldId);
                    break;
                case 'edit_category':
                    fieldValid = validateCategory(fieldId);
                    break;
                case 'edit_price':
                    fieldValid = validatePrice(fieldId);
                    break;
                case 'edit_status':
                    fieldValid = validateStatus(fieldId);
                    break;
                case 'edit_condition':
                    fieldValid = validateCondition(fieldId);
                    break;
                case 'edit_description':
                    fieldValid = validateDescription(fieldId);
                    break;
            }
            if (!fieldValid) isValid = false;
        }
    });
    
    // Validate image if provided
    const imageInput = document.getElementById('editProductImageUpload');
    if (imageInput && imageInput.files.length > 0) {
        if (!validateImage()) {
            isValid = false;
        }
    }
    
    return isValid;
}

// Edit product function
function editProduct(id) {
    fetch(`{{ url('admin/products') }}/${id}/data`)
        .then(response => response.json())
        .then(product => {
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_category').value = product.category;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_status').value = product.status;
            document.getElementById('edit_condition').value = product.condition || 'good';
            document.getElementById('edit_description').value = product.description || '';
            
            // Update description counter
            const descriptionCounter = document.getElementById('edit_description_counter');
            if (descriptionCounter) {
                const length = product.description ? product.description.length : 0;
                descriptionCounter.textContent = `${length}/1000`;
            }
            
            // Show current image preview
            const imagePreview = document.getElementById('current_product_image_preview');
            if (product.image_path) {
                imagePreview.innerHTML = `<img src="/storage/${product.image_path}" class="img-thumbnail" style="max-height: 100px;" alt="Current image">`;
            } else {
                imagePreview.innerHTML = '<p class="text-muted">No image uploaded</p>';
            }
            
            document.getElementById('editProductForm').action = `{{ url('admin/products') }}/${id}`;
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        })
        .catch(error => {
            console.error('Error loading product:', error);
            alert('Error loading product details');
        });
}

// Delete product function
function deleteProduct(id, productName) {
    document.getElementById('deleteProductName').textContent = productName;
    document.getElementById('deleteProductForm').action = `{{ url('admin/products') }}/${id}`;
    new bootstrap.Modal(document.getElementById('deleteProductModal')).show();
}


// View toggle for products
function initProductsViewToggle(){
  const listBtn = document.getElementById('productsListBtn');
  const gridBtn = document.getElementById('productsGridBtn');
  const listView = document.getElementById('productsListView');
  const gridView = document.getElementById('productsGridView');
  if(!listBtn || !gridBtn || !listView || !gridView) return;

  const pref = localStorage.getItem('productsView') || 'list';
  setProductsView(pref);

  listBtn.addEventListener('click', ()=> setProductsView('list'));
  gridBtn.addEventListener('click', ()=> setProductsView('grid'));

  function setProductsView(mode){
    if(mode === 'grid'){
      listView.classList.add('d-none');
      gridView.classList.remove('d-none');
      gridBtn.classList.add('active');
      listBtn.classList.remove('active');
      localStorage.setItem('productsView','grid');
    } else {
      gridView.classList.add('d-none');
      listView.classList.remove('d-none');
      listBtn.classList.add('active');
      gridBtn.classList.remove('active');
      localStorage.setItem('productsView','list');
    }
  }
}
</script>
@endpush

@push('styles')
<style>
/* Edit and Delete Button Hover Effects */
.edit-btn {
    color: #6c757d;
    transition: all 0.2s ease-in-out;
    text-decoration: none;
}

.edit-btn:hover {
    color: #007bff !important;
    text-decoration: none;
    transform: translateY(-1px);
}

.delete-btn {
    color: #6c757d;
    transition: all 0.2s ease-in-out;
    text-decoration: none;
}

.delete-btn:hover {
    color: #dc3545 !important;
    text-decoration: none;
    transform: translateY(-1px);
}

/* Add Product Button Styling */
.btn.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 7px -1px rgba(40, 167, 69, 0.25);
}

.btn.bg-gradient-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px -1px rgba(40, 167, 69, 0.35);
    color: white;
}

.btn.bg-gradient-success:active {
    transform: translateY(0);
}

/* Modal Styling */
.modal-header.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-bottom: none;
}

/* Form Styling */
#addProductModal .form-label,
#editProductModal .form-label {
    font-weight: 500;
    color: #344767;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

#addProductModal .form-control,
#editProductModal .form-control {
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
    background-color: #fff;
}

#addProductModal .form-control:focus,
#editProductModal .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
}

/* Custom File Upload Styling */
.input-group-outline input[type="file"] {
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
    background-color: #fff;
    cursor: pointer;
}

.input-group-outline input[type="file"]:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
}

.input-group-outline input[type="file"]::-webkit-file-upload-button {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 1rem;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

.input-group-outline input[type="file"]::-webkit-file-upload-button:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    transform: translateY(-1px);
}

.input-group-outline input[type="file"]::file-selector-button {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 1rem;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

.input-group-outline input[type="file"]::file-selector-button:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    transform: translateY(-1px);
}

/* Enhanced table styling */
.table tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
    transition: background-color 0.15s ease-in;
}

/* Modal animation improvements */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: translate(0, -50px);
}

.modal.show .modal-dialog {
    transform: none;
}

/* Image preview styling */
.img-thumbnail {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 0.25rem;
}

/* Grid cards */
.product-card .card-img-top img{ display:block; }
.object-fit-cover{ object-fit: cover; }
.btn-group .btn.active{ background:#344767; color:#fff; }

/* Hide global Laravel error messages */
.alert-danger, .error-message, .alert {
    display: none !important;
}

/* Validation styles */
.invalid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545 !important;
    font-weight: 500;
}

/* Show error messages when they have content */
.invalid-feedback.show {
    display: block !important;
}

.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.is-invalid:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

/* Character counter styling */
.text-danger {
    color: #dc3545 !important;
}

/* Image preview styling */
.img-thumbnail {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 0.25rem;
    max-width: 100px;
    max-height: 100px;
    object-fit: cover;
}

/* Form field focus improvements */
.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
}

/* Success state for valid fields */
.form-control.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    background-color: rgba(40, 167, 69, 0.05) !important;
}

/* Enhanced error message styling */
.invalid-feedback.show {
    animation: slideInError 0.3s ease-out;
}

@keyframes slideInError {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Error icon for better visibility */
.invalid-feedback.show::before {
    content: "⚠ ";
    font-weight: bold;
    margin-right: 0.25rem;
}

/* Enhanced field styling when invalid */
.is-invalid {
    animation: shakeError 0.5s ease-in-out;
}

@keyframes shakeError {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>
@endpush

@endsection
