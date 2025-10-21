@extends('layouts.back')

@section('title', 'Waste Requests')
@section('page-title', 'Waste Requests')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0">Waste Requests Management</h6>
            <div class="text-white">
              <small>Total: {{ $wasteRequests->total() }} requests</small>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Search and Filter Section -->
      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Filter Requests</h6>
          <div class="d-flex gap-2">
            <button type="button" class="btn bg-gradient-info" id="generateAIReportBtn">
              <i class="material-symbols-rounded me-1">auto_awesome</i>Generate AI Report
            </button>
            <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addRequestModal">
              <i class="material-symbols-rounded me-1">person_add</i>Add New Request
            </button>
          </div>
        </div>
        
        <form method="GET" action="{{ route('admin.waste-requests') }}" class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="input-group input-group-outline">
              <label class="form-label">Search requests...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                @foreach(\App\Models\WasteRequest::getStatuses() as $key => $value)
                  <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="waste_type">
                <option value="all" {{ request('waste_type') == 'all' ? 'selected' : '' }}>All Types</option>
                @foreach(\App\Models\WasteRequest::getWasteTypes() as $key => $value)
                  <option value="{{ $key }}" {{ request('waste_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn bg-gradient-dark mb-0 w-100">
              <i class="material-symbols-rounded me-1">search</i>Search
            </button>
          </div>
          <div class="col-md-2">
            <a href="{{ route('admin.waste-requests') }}" class="btn btn-outline-secondary mb-0 w-100">
              <i class="material-symbols-rounded me-1">refresh</i>Reset
            </a>
          </div>
        </form>
        
        @if(request()->hasAny(['search', 'status', 'waste_type']))
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <small class="text-muted">
                Showing {{ $wasteRequests->count() }} of {{ $wasteRequests->total() }} results
                @if(request('search'))
                  for "<strong>{{ request('search') }}</strong>"
                @endif
              </small>
            </div>
            <a href="{{ route('admin.waste-requests') }}" class="btn btn-outline-secondary btn-sm">
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
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Waste Details</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($wasteRequests as $index => $request)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ $request->customer->profile_picture_url }}" class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $request->customer->name }}" style="object-fit: cover;">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $request->customer->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $request->customer->email }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $request->waste_type_formatted }} - {{ $request->quantity }} kg</p>
                    <p class="text-xs text-secondary mb-0">
                      <i class="material-symbols-rounded text-xs">location_on</i> {{ $request->state ?? 'N/A' }}
                      <br>{{ Str::limit($request->address, 35) }}
                      @if($request->collector)
                        <br><span class="text-info">Collector: {{ $request->collector->name }}</span>
                      @else
                        <br><span class="text-warning">Unassigned</span>
                      @endif
                    </p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    @if($request->status === 'pending')
                      <span class="badge badge-sm bg-gradient-warning">Pending</span>
                    @elseif($request->status === 'accepted')
                      <span class="badge badge-sm bg-gradient-info">Accepted</span>
                    @elseif($request->status === 'collected')
                      <span class="badge badge-sm bg-gradient-success">Collected</span>
                    @else
                      <span class="badge badge-sm bg-gradient-danger">Cancelled</span>
                    @endif
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">
                      {{ $request->created_at ? $request->created_at->format('d/m/y') : 'N/A' }}
                    </span>
                  </td>
                  <td class="align-middle text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2 edit-btn" 
                       onclick="editRequest({{ $request->id }})" 
                       data-toggle="tooltip" data-original-title="Edit request">
                      Edit
                    </a>
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs delete-btn" 
                       onclick="deleteRequest({{ $request->id }}, '{{ $request->customer->name }}')" 
                       data-toggle="tooltip" data-original-title="Delete request">
                      Delete
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4">
                    <p class="text-secondary mb-0">No waste requests found</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination Section -->
        @if($wasteRequests->hasPages())
          <div class="card-footer px-3 py-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted">
                  Showing {{ $wasteRequests->firstItem() }} to {{ $wasteRequests->lastItem() }} of {{ $wasteRequests->total() }} results
                </small>
              </div>
              <div>
                {{ $wasteRequests->links('back.partials.pagination') }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Add New Request Modal -->
<div class="modal fade" id="addRequestModal" tabindex="-1" aria-labelledby="addRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white" id="addRequestModalLabel">
          <i class="material-symbols-rounded me-2">add</i>Add New Waste Request
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addRequestForm" method="POST" action="{{ route('admin.waste-requests.store') }}">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Request Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Request Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Requested By</label>
                <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }} ({{ auth()->user()->email }})" readonly disabled>
                <small class="text-muted">Requests you create will belong to your account</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Waste Type *</label>
                <select class="form-control" name="waste_type" required>
                  <option value="">Select Waste Type</option>
                  @foreach(\App\Models\WasteRequest::getWasteTypes() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Quantity (kg) *</label>
                <input type="number" class="form-control" name="quantity" step="0.01" min="0.1" required placeholder="Enter quantity in kg">
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Collection Details -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Collection Details</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Collector (Optional)</label>
                <select class="form-control" name="collector_id" id="collector_id">
                  <option value="">Select governorate first</option>
                </select>
                <small class="text-muted">Choose governorate to see available collectors</small>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Governorate *</label>
                <select class="form-control" name="state" id="state" required>
                  <option value="">Select Governorate</option>
                  @foreach(\App\Helpers\TunisiaStates::getStates() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Specific Address *</label>
                <textarea class="form-control" name="address" rows="3" required placeholder="Street, building, floor, etc."></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Description</label>
                <textarea class="form-control" name="description" rows="3" placeholder="Additional details (optional)"></textarea>
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
            <i class="material-symbols-rounded me-1">add</i>Create Request
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Request Modal -->
<div class="modal fade" id="editRequestModal" tabindex="-1" aria-labelledby="editRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white" id="editRequestModalLabel">
          <i class="material-symbols-rounded me-2">edit</i>Edit Waste Request
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editRequestForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <!-- Request Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Request Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Requested By</label>
                <input type="text" class="form-control bg-light" id="edit_customer_name" readonly disabled>
                <small class="text-muted">Owner cannot be changed - requests belong to their creator</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Waste Type *</label>
                <select class="form-control" name="waste_type" id="edit_waste_type" required>
                  <option value="">Select Waste Type</option>
                  @foreach(\App\Models\WasteRequest::getWasteTypes() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Quantity (kg) *</label>
                <input type="number" class="form-control" name="quantity" id="edit_quantity" step="0.01" min="0.1" required placeholder="Enter quantity in kg">
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Collection & Status -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Collection & Status</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Collector</label>
                <select class="form-control" name="collector_id" id="edit_collector_id">
                  <option value="">Unassigned</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status *</label>
                <select class="form-control" name="status" id="edit_status" required>
                  @foreach(\App\Models\WasteRequest::getStatuses() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Governorate *</label>
                <select class="form-control" name="state" id="edit_state" required>
                  <option value="">Select Governorate</option>
                  @foreach(\App\Helpers\TunisiaStates::getStates() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Specific Address *</label>
                <textarea class="form-control" name="address" id="edit_address" rows="3" required placeholder="Street, building, floor, etc."></textarea>
                <div class="invalid-feedback"></div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description</label>
                <textarea class="form-control" name="description" id="edit_description" rows="3" placeholder="Additional details (optional)"></textarea>
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

<!-- Delete Request Modal -->
<div class="modal fade" id="deleteRequestModal" tabindex="-1" aria-labelledby="deleteRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteRequestModalLabel">Delete Waste Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the waste request from <strong id="deleteRequestCustomer"></strong>?</p>
        <p class="text-danger">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteRequestForm" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete Request</button>
        </form>
      </div>
    </div>
  </div>
</div>



@push('scripts')
<script src="{{ asset('assets/js/waste-request-validator.js') }}"></script>
<script>
// Load customers and collectors when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadCustomers();
    loadCollectors();
    if (window.WasteRequestValidation && typeof window.WasteRequestValidation.init === 'function') {
        window.WasteRequestValidation.init();
    }
});

// Load customers for dropdowns
function loadCustomers() {
    fetch('{{ route("admin.waste-requests.customers") }}')
        .then(response => response.json())
        .then(customers => {
            const customerSelects = ['customer_id', 'edit_customer_id'];
            customerSelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Select Customer</option>';
                    customers.forEach(customer => {
                        select.innerHTML += `<option value="${customer.id}">${customer.name} (${customer.email})</option>`;
                    });
                }
            });
        })
        .catch(error => console.error('Error loading customers:', error));
}

// Store collectors globally for filtering
let allCollectors = [];

// Load collectors for dropdowns
function loadCollectors() {
    fetch('{{ route("admin.waste-requests.collectors") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(collectors => {
            allCollectors = collectors; // Store for later filtering
            console.log('✅ Loaded collectors:', collectors); // Debug
            console.log('Total collectors:', collectors.length);
            
            if (collectors.length === 0) {
                console.warn('⚠️ No verified collectors found. Make sure:');
                console.warn('1. Collectors exist in database');
                console.warn('2. Collectors have verification_status = "verified"');
                console.warn('3. Collectors have active users');
            }
        })
        .catch(error => {
            console.error('❌ Error loading collectors:', error);
            alert('Failed to load collectors. Please refresh the page.');
        });
}

// Filter collectors for ADD form when governorate is selected
document.addEventListener('DOMContentLoaded', function() {
    const stateSelect = document.getElementById('state');
    const collectorSelect = document.getElementById('collector_id');
    
    if (stateSelect && collectorSelect) {
        stateSelect.addEventListener('change', function() {
            const selectedState = this.value;
            
            if (!selectedState) {
                // No state selected, show placeholder
                collectorSelect.innerHTML = '<option value="">Select governorate first</option>';
                return;
            }
            
            // Filter collectors by selected governorate
            collectorSelect.innerHTML = '<option value="">Unassigned</option>';
            
            const matchingCollectors = allCollectors.filter(collector => {
                return collector.service_areas && 
                       Array.isArray(collector.service_areas) && 
                       collector.service_areas.includes(selectedState);
            });
            
            if (matchingCollectors.length > 0) {
                // Show only matching collectors
                matchingCollectors.forEach(collector => {
                    collectorSelect.innerHTML += `<option value="${collector.id}">${collector.name} (${collector.email})</option>`;
                });
            } else {
                // No collectors serve this area, show all with note
                collectorSelect.innerHTML = '<option value="">No collectors serve this area</option>';
                allCollectors.forEach(collector => {
                    collectorSelect.innerHTML += `<option value="${collector.id}">${collector.name} (${collector.email}) - Outside area</option>`;
                });
            }
        });
    }
});

// Filter collectors by governorate for edit form
function filterCollectorsByGovernorate(governorate, currentCollectorId = null) {
    const editSelect = document.getElementById('edit_collector_id');
    if (!editSelect) return;
    
    editSelect.innerHTML = '<option value="">Unassigned</option>';
    
    // Filter collectors who serve this governorate
    const matchingCollectors = allCollectors.filter(collector => {
        return collector.service_areas && 
               Array.isArray(collector.service_areas) && 
               collector.service_areas.includes(governorate);
    });
    
    // If no collectors match, show all collectors
    const collectorsToShow = matchingCollectors.length > 0 ? matchingCollectors : allCollectors;
    
    collectorsToShow.forEach(collector => {
        const label = matchingCollectors.length > 0 
            ? `${collector.name} (${collector.email})` 
            : `${collector.name} (${collector.email}) - Outside area`;
        editSelect.innerHTML += `<option value="${collector.id}">${label}</option>`;
    });
    
    // Set current collector if provided
    if (currentCollectorId) {
        editSelect.value = currentCollectorId;
    }
}

// Edit request function
function editRequest(id) {
    fetch(`{{ url('admin/waste-requests') }}/${id}/data`)
        .then(response => response.json())
        .then(request => {
            document.getElementById('edit_customer_name').value = request.customer ? 
                `${request.customer.name} (${request.customer.email})` : 'Unknown';
            document.getElementById('edit_waste_type').value = request.waste_type;
            document.getElementById('edit_quantity').value = request.quantity;
            document.getElementById('edit_status').value = request.status;
            document.getElementById('edit_state').value = request.state || '';
            document.getElementById('edit_address').value = request.address;
            document.getElementById('edit_description').value = request.description || '';
            
            // Filter collectors by the request's governorate and set current collector
            if (request.state) {
                filterCollectorsByGovernorate(request.state, request.collector_id);
            } else {
                // If no state, show all collectors
                const editSelect = document.getElementById('edit_collector_id');
                editSelect.innerHTML = '<option value="">Unassigned</option>';
                allCollectors.forEach(collector => {
                    editSelect.innerHTML += `<option value="${collector.id}">${collector.name} (${collector.email})</option>`;
                });
                if (request.collector_id) {
                    editSelect.value = request.collector_id;
                }
            }
            
            document.getElementById('editRequestForm').action = `{{ url('admin/waste-requests') }}/${id}`;
            new bootstrap.Modal(document.getElementById('editRequestModal')).show();
        })
        .catch(error => {
            console.error('Error loading request:', error);
            alert('Error loading request details');
        });
}

// Delete request function
function deleteRequest(id, customerName) {
    document.getElementById('deleteRequestCustomer').textContent = customerName;
    document.getElementById('deleteRequestForm').action = `{{ url('admin/waste-requests') }}/${id}`;
    new bootstrap.Modal(document.getElementById('deleteRequestModal')).show();
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

/* Beautiful Custom Alerts */
.custom-alert {
    border: none;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    animation: slideInDown 0.3s ease-out;
}

.custom-alert.alert-success {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.95) 0%, rgba(32, 201, 151, 0.95) 100%);
    color: white;
}

.custom-alert.alert-danger {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.95) 0%, rgba(244, 67, 54, 0.95) 100%);
    color: white;
}

.custom-alert.alert-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.95) 0%, rgba(255, 152, 0, 0.95) 100%);
    color: white;
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-icon {
    font-size: 1.5rem;
    opacity: 0.9;
}

.alert-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.alert-text strong {
    font-weight: 600;
    font-size: 0.95rem;
}

.alert-text span {
    font-size: 0.875rem;
    opacity: 0.95;
}

.custom-alert .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.custom-alert .btn-close:hover {
    opacity: 1;
}

@keyframes slideInDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Add Request Button Styling */
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

/* Add Request Modal Styling */
.modal-header.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-bottom: none;
}

/* Simple and Clean Modal Form Styling for Add and Edit */
#addRequestModal .form-label,
#editRequestModal .form-label {
    font-weight: 500;
    color: #344767;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

#addRequestModal .form-control,
#editRequestModal .form-control {
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
    background-color: #fff;
}

#addRequestModal .form-control:focus,
#editRequestModal .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
}

#addRequestModal .form-control::placeholder,
#editRequestModal .form-control::placeholder {
    color: #6c757d;
    opacity: 0.7;
}

#addRequestModal select.form-control,
#editRequestModal select.form-control {
    cursor: pointer;
}

#addRequestModal textarea.form-control,
#editRequestModal textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Form validation styling for both modals */
#addRequestModal .form-control.is-invalid,
#editRequestModal .form-control.is-invalid,
#addRequestModal select.form-control.is-invalid,
#editRequestModal select.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
    background-image: none !important;
    background-color: #fff !important;
}

#addRequestModal .form-control.is-valid,
#editRequestModal .form-control.is-valid,
#addRequestModal select.form-control.is-valid,
#editRequestModal select.form-control.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    background-image: none !important;
    background-color: #fff !important;
}

#addRequestModal .invalid-feedback,
#editRequestModal .invalid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #dc3545;
    font-weight: 500;
}

#addRequestModal .valid-feedback,
#editRequestModal .valid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #28a745;
    font-weight: 500;
}

/* Form section headers */
#addRequestModal h6,
#editRequestModal h6 {
    color: #344767;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
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

/* Improved spacing */
#addRequestModal .modal-body,
#editRequestModal .modal-body {
    padding: 2rem;
}

#addRequestModal .row + .row,
#editRequestModal .row + .row {
    margin-top: 1.5rem;
}

/* Button improvements */
#addRequestModal .modal-footer,
#editRequestModal .modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e9ecef;
}

#addRequestModal .btn,
#editRequestModal .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
}

/* Form validation feedback */
#addRequestModal .form-control.is-invalid,
#editRequestModal .form-control.is-invalid,
#addRequestModal select.form-control.is-invalid,
#editRequestModal select.form-control.is-invalid {
    border-color: #dc3545;
    background-image: none !important;
    background-color: #fff !important;
}

#addRequestModal .form-control.is-valid,
#editRequestModal .form-control.is-valid,
#addRequestModal select.form-control.is-valid,
#editRequestModal select.form-control.is-valid {
    border-color: #28a745;
    background-image: none !important;
    background-color: #fff !important;
}

#addRequestModal .invalid-feedback,
#editRequestModal .invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

#addRequestModal .valid-feedback,
#editRequestModal .valid-feedback {
    display: block;
    color: #28a745;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* AI Report Modal */
#aiReportModal .modal-dialog {
    max-width: 900px;
}

#aiReportModal .report-content {
    background: #ffffff;
    border-radius: 12px;
    padding: 3rem;
    max-height: 650px;
    overflow-y: auto;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    font-size: 1rem;
    line-height: 1.75;
    color: #1a202c;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
}

#aiReportModal .report-content h1,
#aiReportModal .report-content h2,
#aiReportModal .report-content h3 {
    color: #2d3748;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

#aiReportModal .report-content strong {
    color: #667eea;
    font-weight: 700;
}

#aiReportModal .report-content hr {
    border: none;
    height: 2px;
    background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
    margin: 2rem 0;
    opacity: 0.3;
}

#aiReportModal .report-content ul {
    list-style: none;
    padding-left: 0;
}

#aiReportModal .report-content ul li {
    padding-left: 1.5rem;
    margin-bottom: 0.5rem;
    position: relative;
}

#aiReportModal .report-content ul li:before {
    content: "▸";
    position: absolute;
    left: 0;
    color: #667eea;
    font-weight: bold;
}

#aiReportModal .report-content code {
    background: #f7fafc;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.9em;
    color: #667eea;
    border: 1px solid #e2e8f0;
}

#aiReportModal .report-content blockquote {
    border-left: 4px solid #667eea;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    background: #f7fafc;
    padding: 1rem 1rem 1rem 1.5rem;
    border-radius: 0 8px 8px 0;
}

#aiReportModal .report-content::-webkit-scrollbar {
    width: 10px;
}

#aiReportModal .report-content::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

#aiReportModal .report-content::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    border: 2px solid #f1f5f9;
}

#aiReportModal .report-content::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

#aiReportModal .loading-state {
    text-align: center;
    padding: 3rem;
}

#aiReportModal .loading-state .spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
@endpush

@push('scripts')
<script>
// AI Report Generation
document.getElementById('generateAIReportBtn').addEventListener('click', async function() {
    const modal = new bootstrap.Modal(document.getElementById('aiReportModal'));
    modal.show();
    
    const loadingState = document.getElementById('reportLoadingState');
    const reportContent = document.getElementById('reportContent');
    const downloadBtn = document.getElementById('downloadReportBtn');
    
    // Show loading
    loadingState.style.display = 'block';
    reportContent.style.display = 'none';
    downloadBtn.disabled = true;
    
    try {
        const response = await fetch('/admin/waste-requests/ai-report', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Convert markdown-style formatting to HTML
            let htmlReport = convertMarkdownToHtml(data.report);
            reportContent.innerHTML = htmlReport;
            reportContent.style.display = 'block';
            loadingState.style.display = 'none';
            downloadBtn.disabled = false;
            
            // Store report for download
            downloadBtn.dataset.report = htmlReport;
            downloadBtn.dataset.reportTitle = 'Waste Requests Report';
        } else {
            reportContent.textContent = 'Error: ' + (data.message || 'Failed to generate report');
            reportContent.style.display = 'block';
            loadingState.style.display = 'none';
        }
    } catch (error) {
        console.error('Error:', error);
        reportContent.textContent = 'Error: Failed to generate report. Please try again.';
        reportContent.style.display = 'block';
        loadingState.style.display = 'none';
    }
});

// Markdown to HTML converter
function convertMarkdownToHtml(text) {
    let html = text;
    
    // Headers
    html = html.replace(/^### (.+)$/gm, '<h3>$1</h3>');
    html = html.replace(/^## (.+)$/gm, '<h2>$1</h2>');
    html = html.replace(/^# (.+)$/gm, '<h1>$1</h1>');
    
    // Bold
    html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/__(.+?)__/g, '<strong>$1</strong>');
    
    // Horizontal rules
    html = html.replace(/^(═{3,}|─{3,}|•{3,})$/gm, '<hr>');
    
    // Line breaks
    html = html.replace(/\n/g, '<br>');
    
    return html;
}

// Download Report as PDF
document.getElementById('downloadReportBtn').addEventListener('click', function() {
    const reportHtml = this.dataset.report;
    const reportTitle = this.dataset.reportTitle || 'Report';
    
    if (!reportHtml) return;
    
    // Create a beautiful PDF template
    const pdfContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                @page { margin: 40px; }
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    font-size: 11pt;
                    line-height: 1.6;
                    color: #2d3748;
                    padding: 30px;
                }
                .header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 30px;
                    border-radius: 12px;
                    margin-bottom: 30px;
                    text-align: center;
                }
                .header h1 {
                    font-size: 28pt;
                    font-weight: 700;
                    margin-bottom: 10px;
                }
                .header .meta {
                    font-size: 10pt;
                    opacity: 0.9;
                }
                h1, h2, h3 { 
                    color: #667eea; 
                    font-weight: 700; 
                    margin-top: 25px; 
                    margin-bottom: 12px;
                    page-break-after: avoid;
                }
                h1 { font-size: 24pt; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
                h2 { font-size: 18pt; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; }
                h3 { font-size: 14pt; }
                strong { 
                    color: #667eea; 
                    font-weight: 700; 
                }
                hr {
                    border: none;
                    height: 2px;
                    background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
                    margin: 20px 0;
                    opacity: 0.3;
                }
                br { display: block; content: ""; margin: 6px 0; }
                .footer {
                    position: fixed;
                    bottom: 20px;
                    left: 40px;
                    right: 40px;
                    text-align: center;
                    font-size: 9pt;
                    color: #718096;
                    border-top: 1px solid #e2e8f0;
                    padding-top: 10px;
                }
                .page-number:before {
                    content: "Page " counter(page);
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>🚀 ${reportTitle}</h1>
                <div class="meta">
                    Generated: ${new Date().toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })} | Waste2Product Tunisia
                </div>
            </div>
            <div class="content">
                ${reportHtml}
            </div>
            <div class="footer">
                <div class="page-number"></div>
                <div>© ${new Date().getFullYear()} Waste2Product Tunisia - AI-Powered Analytics</div>
            </div>
        </body>
        </html>
    `;
    
    // Create temporary window for printing
    const printWindow = window.open('', '_blank');
    printWindow.document.write(pdfContent);
    printWindow.document.close();
    
    // Wait for content to load, then print
    setTimeout(() => {
        printWindow.print();
        setTimeout(() => printWindow.close(), 100);
    }, 500);
});
</script>
@endpush

<!-- AI Report Modal -->
<div class="modal fade" id="aiReportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h5 class="modal-title text-white">
          <i class="material-symbols-rounded me-2">auto_awesome</i>AI-Generated Waste Requests Report
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="reportLoadingState" class="loading-state">
          <div class="spinner-border text-info" role="status">
            <span class="visually-hidden">Generating report...</span>
          </div>
          <p class="mt-3 text-muted">AI is analyzing data and generating your comprehensive report...</p>
        </div>
        <div id="reportContent" class="report-content" style="display: none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn bg-gradient-success" id="downloadReportBtn" disabled>
          <i class="material-symbols-rounded me-1">picture_as_pdf</i>Download PDF
        </button>
      </div>
    </div>
  </div>
</div>

@endsection
