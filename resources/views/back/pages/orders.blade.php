@extends('layouts.back')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0">Orders Management</h6>
            <div class="text-white">
              @isset($orders)
                <small>Total: {{ $orders->total() }} orders</small>
              @else
                <small id="ordersTotalText">&nbsp;</small>
              @endisset
            </div>
          </div>
        </div>
      </div>

      <!-- Search and Filter Section -->
      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Filter Orders</h6>
          <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addOrderModal">
            <i class="material-symbols-rounded me-1">add_shopping_cart</i>Add New Order
          </button>
        </div>
        
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 mb-3 orders-filter">
          <div class="col-md-4">
            <div class="input-group input-group-outline">
              <label class="form-label">Search orders...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <label class="visually-hidden" for="orders_date_from">From date</label>
            <input id="orders_date_from" type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" placeholder="From date" aria-label="From date">
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn bg-gradient-dark mb-0 w-100">
              <i class="material-symbols-rounded me-1">search</i>Search
            </button>
          </div>
          <div class="col-md-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mb-0 w-100">
              <i class="material-symbols-rounded me-1">refresh</i>Reset
            </a>
          </div>
        </form>
        
        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <small class="text-muted">
                @isset($orders)
                  Showing {{ $orders->count() }} of {{ $orders->total() }} results
                @endisset
                @if(request('search'))
                  for "<strong>{{ request('search') }}</strong>"
                @endif
              </small>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
              <i class="material-symbols-rounded me-1">clear</i>Clear Filters
            </a>
          </div>
        @endif
      </div>

      <div class="card-body px-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Buyer</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Product</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Quantity</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ordered At</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody id="ordersTbody">
              @isset($orders)
                @forelse($orders as $order)
                  <tr>
                    <td><p class="text-xs font-weight-bold mb-0">{{ $order->id }}</p></td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ $order->buyer?->name ?? 'User #'.$order->user_id }}</p>
                      <p class="text-xs text-secondary mb-0">{{ $order->buyer?->email }}</p>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ $order->product?->name ?? 'Product #'.$order->product_id }}</p>
                      <p class="text-xs text-secondary mb-0">@if(!is_null($order->product?->price)) {{ number_format($order->product->price, 2) }} € @endif</p>
                    </td>
                    <td><p class="text-xs font-weight-bold mb-0">{{ $order->quantity }}</p></td>
                    <td><p class="text-xs font-weight-bold mb-0">{{ number_format($order->total_price, 2) }} €</p></td>
                    <td class="align-middle text-center">
                      @php
                        $map = ['pending'=>'bg-gradient-warning','confirmed'=>'bg-gradient-info','shipped'=>'bg-gradient-primary','delivered'=>'bg-gradient-success','cancelled'=>'bg-gradient-danger'];
                        $cls = $map[$order->status] ?? 'bg-gradient-secondary';
                      @endphp
                      <span class="badge badge-sm {{ $cls }}">{{ $order->status }}</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">{{ optional($order->ordered_at)->format('Y-m-d H:i') }}</span>
                    </td>
                    <td class="align-middle text-center">
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2 edit-btn" 
                         onclick='openEdit(@json($order))' 
                         data-toggle="tooltip" data-original-title="Edit order">
                        Edit
                      </a>
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs delete-btn" 
                         onclick='openDelete({{ $order->id }})' 
                         data-toggle="tooltip" data-original-title="Delete order">
                        Delete
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center py-4 text-secondary">No orders found</td>
                  </tr>
                @endforelse
              @else
                <tr>
                  <td colspan="8" class="text-center py-4 text-secondary">Loading...</td>
                </tr>
              @endisset
            </tbody>
          </table>
        </div>
        @isset($orders)
          @if($orders->hasPages())
            <div class="card-footer px-3 py-3">
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results</small>
                <div>{{ $orders->withQueryString()->links('back.partials.pagination') }}</div>
              </div>
            </div>
          @endif
        @else
          <div class="card-footer px-3 py-3" id="ordersPagination" style="display:none;"></div>
        @endisset
      </div>
    </div>
  </div>
</div>


<!-- Add Order Modal -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white" id="addOrderModalLabel">
          <i class="material-symbols-rounded me-2">add_shopping_cart</i>New Order
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addOrderForm" method="POST" action="{{ route('admin.orders.store') }}">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label">Buyer *</label>
            <select class="form-control" name="user_id" required>
              @foreach(($usersList ?? collect()) as $u)
                <option value="{{ $u->id }}" @if($loop->first) selected @endif>{{ $u->name }} (ID {{ $u->id }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Product *</label>
            <select class="form-control" name="product_id" required>
              @foreach(($productsList ?? collect()) as $p)
                <option value="{{ $p->id }}" @if($loop->first) selected @endif>{{ $p->name }} (ID {{ $p->id }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity *</label>
            <input type="number" class="form-control" name="quantity" required min="1" value="1">
          </div>
          <div class="mb-3">
            <label class="form-label">Total Price *</label>
            <input type="number" step="0.01" class="form-control" name="total_price" required min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Status *</label>
            <select class="form-control" name="status" required>
              <option value="pending" selected>Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="shipped">Shipped</option>
              <option value="delivered">Delivered</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <input type="text" class="form-control" name="payment_method" placeholder="e.g. card">
          </div>
          <div class="mb-3">
            <label class="form-label">Ordered At *</label>
            <input type="datetime-local" class="form-control" name="ordered_at" required>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="material-symbols-rounded me-1">close</i>Cancel
          </button>
          <button type="submit" class="btn bg-gradient-success">
            <i class="material-symbols-rounded me-1">save</i>Create Order
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Order Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white" id="editOrderModalLabel">
          <i class="material-symbols-rounded me-2">edit</i>Edit Order
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editOrderForm" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label">Buyer *</label>
            <select class="form-control" name="user_id" required>
              @foreach(($usersList ?? collect()) as $u)
                <option value="{{ $u->id }}">{{ $u->name }} (ID {{ $u->id }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Product *</label>
            <select class="form-control" name="product_id" required>
              @foreach(($productsList ?? collect()) as $p)
                <option value="{{ $p->id }}">{{ $p->name }} (ID {{ $p->id }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity *</label>
            <input type="number" class="form-control" name="quantity" required min="1">
          </div>
          <div class="mb-3">
            <label class="form-label">Total Price *</label>
            <input type="number" step="0.01" class="form-control" name="total_price" required min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Status *</label>
            <select class="form-control" name="status" required>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="shipped">Shipped</option>
              <option value="delivered">Delivered</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <input type="text" class="form-control" name="payment_method">
          </div>
          <div class="mb-3">
            <label class="form-label">Ordered At *</label>
            <input type="datetime-local" class="form-control" name="ordered_at" required>
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

<!-- Delete Order Modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteOrderModalLabel">Delete Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete order <strong id="deleteOrderId"></strong>?</p>
        <p class="text-danger">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteOrderForm" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete Order</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function q(selector) { return document.querySelector(selector); }

// Initialize floating labels when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeFloatingLabels();
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

function openEdit(order) {
  const form = q('#editOrderForm');
  form.action = `/admin/orders/${order.id}`;
  form.elements.id.value = order.id;
  form.elements.user_id.value = order.user_id;
  form.elements.product_id.value = order.product_id;
  form.elements.quantity.value = order.quantity;
  form.elements.total_price.value = order.total_price;
  form.elements.status.value = order.status;
  form.elements.payment_method.value = order.payment_method ?? '';
  if (order.ordered_at) {
    const dt = new Date(order.ordered_at);
    const pad = (n) => n.toString().padStart(2,'0');
    const val = `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
    form.elements.ordered_at.value = val;
  } else {
    form.elements.ordered_at.value = '';
  }
  new bootstrap.Modal(q('#editOrderModal')).show();
}

function openDelete(id) {
  q('#deleteOrderId').innerText = `#${id}`;
  const form = q('#deleteOrderForm');
  form.action = `/admin/orders/${id}`;
  new bootstrap.Modal(q('#deleteOrderModal')).show();
}
</script>

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

/* Add Order Button Styling */
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
#addOrderModal .form-label,
#editOrderModal .form-label {
    font-weight: 500;
    color: #344767;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

#addOrderModal .form-control,
#editOrderModal .form-control {
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
    background-color: #fff;
}

#addOrderModal .form-control:focus,
#editOrderModal .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
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

/* Button improvements */
#addOrderModal .modal-footer,
#editOrderModal .modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e9ecef;
}

#addOrderModal .btn,
#editOrderModal .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
}

/* Neat date/datetime inputs (makes mm/dd/yyyy look subtle and aligned) */
input[type="date"],
input[type="datetime-local"] {
    height: 43px;
    line-height: 43px;
    color: #344767;
}

/* Text inside the control */
input[type="date"]::-webkit-datetime-edit,
input[type="datetime-local"]::-webkit-datetime-edit {
    padding: 0.25rem 0.5rem;
    color: #6c757d; /* main text color */
}

/* Separator slashes and dashes lighter */
input[type="date"]::-webkit-datetime-edit-text,
input[type="datetime-local"]::-webkit-datetime-edit-text {
    color: #adb5bd;
}

/* Individual fields slightly darker than separators */
input[type="date"]::-webkit-datetime-edit-month-field,
input[type="date"]::-webkit-datetime-edit-day-field,
input[type="date"]::-webkit-datetime-edit-year-field,
input[type="datetime-local"]::-webkit-datetime-edit-month-field,
input[type="datetime-local"]::-webkit-datetime-edit-day-field,
input[type="datetime-local"]::-webkit-datetime-edit-year-field,
input[type="datetime-local"]::-webkit-datetime-edit-hour-field,
input[type="datetime-local"]::-webkit-datetime-edit-minute-field {
    color: #6c757d;
}

/* Calendar icon subtle */
input[type="date"]::-webkit-calendar-picker-indicator,
input[type="datetime-local"]::-webkit-calendar-picker-indicator {
    opacity: .65;
    filter: grayscale(100%);
}

/* Keep floating label always shrunk for date in filter row */
/* Scoped tweak only for the standalone date input */
#orders_date_from {
    padding-top: .6rem;
    padding-bottom: .6rem;
}
</style>
@endpush

@endsection
