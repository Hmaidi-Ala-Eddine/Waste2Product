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
        <div class="modal-body p-4">
          <div class="row">
            <!-- Product Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Product Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Owner *</label>
                <select class="form-control" name="user_id" id="owner_id" required>
                  <option value="">Select Owner</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Product Name *</label>
                <input type="text" class="form-control" name="name" required placeholder="Enter product name">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Category *</label>
                <select class="form-control" name="category" required>
                  <option value="">Select Category</option>
                  <option value="furniture">Furniture</option>
                  <option value="electronics">Electronics</option>
                  <option value="plastic">Recycled Plastic</option>
                  <option value="textile">Textile</option>
                  <option value="metal">Metal</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Price *</label>
                <input type="number" class="form-control" name="price" step="0.01" min="0" required placeholder="0.00">
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Product Details -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Product Details</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status *</label>
                <select class="form-control" name="status" required>
                  <option value="available" selected>Available</option>
                  <option value="sold">Sold</option>
                  <option value="donated">Donated</option>
                  <option value="reserved">Reserved</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Condition</label>
                <select class="form-control" name="condition">
                  <option value="excellent">Excellent</option>
                  <option value="good" selected>Good</option>
                  <option value="fair">Fair</option>
                  <option value="poor">Poor</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Product Image</label>
                <div class="input-group input-group-outline">
                  <input type="file" class="form-control" name="image" accept="image/*" id="productImageUpload">
                </div>
                <small class="text-muted">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                <div class="invalid-feedback"></div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description</label>
                <textarea class="form-control" name="description" rows="4" placeholder="Enter product description"></textarea>
                <div class="invalid-feedback"></div>
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
        <div class="modal-body p-4">
          <div class="row">
            <!-- Product Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Product Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Owner *</label>
                <select class="form-control" name="user_id" id="edit_owner_id" required>
                  <option value="">Select Owner</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Product Name *</label>
                <input type="text" class="form-control" name="name" id="edit_name" required placeholder="Enter product name">
                <div class="invalid-feedback"></div>
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
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Price *</label>
                <input type="number" class="form-control" name="price" id="edit_price" step="0.01" min="0" required placeholder="0.00">
                <div class="invalid-feedback"></div>
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
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Condition</label>
                <select class="form-control" name="condition" id="edit_condition">
                  <option value="excellent">Excellent</option>
                  <option value="good">Good</option>
                  <option value="fair">Fair</option>
                  <option value="poor">Poor</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Current Image</label>
                <div id="current_product_image_preview" class="mb-2"></div>
                <div class="input-group input-group-outline">
                  <input type="file" class="form-control" name="image" accept="image/*" id="editProductImageUpload">
                </div>
                <small class="text-muted">Leave empty to keep current image. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                <div class="invalid-feedback"></div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description</label>
                <textarea class="form-control" name="description" id="edit_description" rows="4" placeholder="Enter product description"></textarea>
                <div class="invalid-feedback"></div>
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
// Load users when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    initializeFloatingLabels();
    initProductsViewToggle();
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

// Load users for dropdowns
function loadUsers() {
    fetch('/admin/products/users')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(users => {
            console.log('Users loaded:', users); // Debug log
            const userSelects = ['owner_id', 'edit_owner_id'];
            userSelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Select Owner</option>';
                    users.forEach(user => {
                        select.innerHTML += `<option value="${user.id}">${user.name} (${user.email})</option>`;
                    });
                }
            });
        })
        .catch(error => {
            console.error('Error loading users:', error);
            // Fallback: try to load from a different endpoint
            console.log('Trying fallback endpoint...');
            fetch('/admin/users')
                .then(response => response.json())
                .then(data => {
                    // Handle different response formats
                    const users = data.users || data.data || data;
                    if (Array.isArray(users)) {
                        const userSelects = ['owner_id', 'edit_owner_id'];
                        userSelects.forEach(selectId => {
                            const select = document.getElementById(selectId);
                            if (select) {
                                select.innerHTML = '<option value="">Select Owner</option>';
                                users.forEach(user => {
                                    select.innerHTML += `<option value="${user.id}">${user.name} (${user.email || user.username || 'No email'})</option>`;
                                });
                            }
                        });
                    }
                })
                .catch(fallbackError => console.error('Fallback also failed:', fallbackError));
        });
}

// Edit product function
function editProduct(id) {
    fetch(`{{ url('admin/products') }}/${id}/data`)
        .then(response => response.json())
        .then(product => {
            document.getElementById('edit_owner_id').value = product.user_id;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_category').value = product.category;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_status').value = product.status;
            document.getElementById('edit_condition').value = product.condition || 'good';
            document.getElementById('edit_description').value = product.description || '';
            
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
</style>
@endpush

@endsection
