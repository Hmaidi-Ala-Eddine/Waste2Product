@extends('layouts.back')

@section('title', 'Collectors')
@section('page-title', 'Collectors')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-success shadow-success border-radius-lg pt-4 pb-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0 d-flex align-items-center gap-2">
              <i class="material-symbols-rounded">local_shipping</i>
              <span style="font-weight: 700; font-size: 1.1rem; letter-spacing: 0.5px;">Collectors Management</span>
            </h6>
            <div class="text-white">
              <small style="font-weight: 600;">Total: {{ $collectors->total() }} collectors</small>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Search and Filter Section -->
      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Filter Collectors</h6>
          <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addCollectorModal">
            <i class="material-symbols-rounded me-1">person_add</i>Add New Collector
          </button>
        </div>
        
        <form method="GET" action="{{ route('admin.collectors') }}" class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="input-group input-group-outline">
              <label class="form-label">Search collectors...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="verification_status">
                <option value="all" {{ request('verification_status') == 'all' ? 'selected' : '' }}>All Status</option>
                @foreach(\App\Models\Collector::getVerificationStatuses() as $key => $value)
                  <option value="{{ $key }}" {{ request('verification_status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="vehicle_type">
                <option value="all" {{ request('vehicle_type') == 'all' ? 'selected' : '' }}>All Vehicles</option>
                @foreach(\App\Models\Collector::getVehicleTypes() as $key => $value)
                  <option value="{{ $key }}" {{ request('vehicle_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
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
            <a href="{{ route('admin.collectors') }}" class="btn btn-outline-secondary mb-0 w-100">
              <i class="material-symbols-rounded me-1">refresh</i>Reset
            </a>
          </div>
        </form>
        
        @if(request()->hasAny(['search', 'verification_status', 'vehicle_type']))
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <small class="text-muted">
                Showing {{ $collectors->count() }} of {{ $collectors->total() }} results
                @if(request('search'))
                  for "<strong>{{ request('search') }}</strong>"
                @endif
              </small>
            </div>
            <a href="{{ route('admin.collectors') }}" class="btn btn-outline-secondary btn-sm">
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
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Collector</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Details</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($collectors as $index => $collector)
                @php
                  $avatarImages = ['team-1.jpg', 'team-2.jpg', 'team-3.jpg', 'team-4.jpg', 'team-5.jpg'];
                  $avatarImage = $avatarImages[$index % count($avatarImages)];
                @endphp
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ asset('assets/back/img/' . $avatarImage) }}" class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $collector->user->name }}">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $collector->user->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $collector->user->email }}</p>
                        @if($collector->company_name)
                          <p class="text-xs text-info mb-0">{{ $collector->company_name }}</p>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">Capacity: {{ $collector->capacity_kg }} kg</p>
                    <p class="text-xs text-secondary mb-0">
                      Collections: {{ $collector->total_collections }}
                      @if($collector->rating > 0)
                        | Rating: {{ number_format($collector->rating, 1) }}/5
                      @endif
                    </p>
                    <p class="text-xs text-secondary mb-0">
                      Areas: {{ Str::limit($collector->service_areas_string, 30) }}
                    </p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <span class="badge badge-sm bg-gradient-info">{{ $collector->vehicle_type_formatted }}</span>
                  </td>
                  <td class="align-middle text-center text-sm">
                    @if($collector->verification_status === 'pending')
                      <span class="badge badge-sm bg-gradient-warning">Pending</span>
                    @elseif($collector->verification_status === 'verified')
                      <span class="badge badge-sm bg-gradient-success">Verified</span>
                    @else
                      <span class="badge badge-sm bg-gradient-danger">Suspended</span>
                    @endif
                  </td>
                  <td class="align-middle text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2 edit-btn" 
                       onclick="editCollector({{ $collector->id }})" 
                       data-toggle="tooltip" data-original-title="Edit collector">
                      Edit
                    </a>
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs delete-btn" 
                       onclick="deleteCollector({{ $collector->id }}, '{{ $collector->user->name }}')" 
                       data-toggle="tooltip" data-original-title="Delete collector">
                      Delete
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4">
                    <p class="text-secondary mb-0">No collectors found</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination Section -->
        @if($collectors->hasPages())
          <div class="card-footer px-3 py-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted">
                  Showing {{ $collectors->firstItem() }} to {{ $collectors->lastItem() }} of {{ $collectors->total() }} results
                </small>
              </div>
              <div>
                {{ $collectors->links('back.partials.pagination') }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Add New Collector Modal -->
<div class="modal fade" id="addCollectorModal" tabindex="-1" aria-labelledby="addCollectorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white" id="addCollectorModalLabel">
          <i class="material-symbols-rounded me-2">add</i>Add New Collector
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addCollectorForm" method="POST" action="{{ route('admin.collectors.store') }}">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Basic Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">User *</label>
                <select class="form-control" name="user_id" id="user_id" required>
                  <option value="">Select User</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Company Name</label>
                <input type="text" class="form-control" name="company_name" placeholder="Optional company/organization name">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Vehicle Type *</label>
                <select class="form-control" name="vehicle_type" required>
                  <option value="">Select Vehicle Type</option>
                  @foreach(\App\Models\Collector::getVehicleTypes() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Capacity (kg) *</label>
                <input type="number" class="form-control" name="capacity_kg" step="0.01" min="1" required placeholder="Maximum collection capacity">
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Service Details -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Service Details</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Service Areas</label>
                <input type="text" class="form-control" name="service_areas" placeholder="Enter areas separated by commas" id="service_areas_input">
                <small class="text-muted">Enter service areas separated by commas</small>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Bio</label>
                <textarea class="form-control" name="bio" rows="4" placeholder="Brief description about the collector"></textarea>
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
            <i class="material-symbols-rounded me-1">add</i>Create Collector
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Collector Modal -->
<div class="modal fade" id="editCollectorModal" tabindex="-1" aria-labelledby="editCollectorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white" id="editCollectorModalLabel">
          <i class="material-symbols-rounded me-2">edit</i>Edit Collector
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editCollectorForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Basic Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">User *</label>
                <input type="text" class="form-control" id="edit_user_name" readonly disabled>
                <input type="hidden" name="user_id" id="edit_user_id">
                <small class="text-muted">User cannot be changed after creation</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Company Name</label>
                <input type="text" class="form-control" name="company_name" id="edit_company_name" placeholder="Optional company/organization name">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Vehicle Type *</label>
                <select class="form-control" name="vehicle_type" id="edit_vehicle_type" required>
                  <option value="">Select Vehicle Type</option>
                  @foreach(\App\Models\Collector::getVehicleTypes() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Capacity (kg) *</label>
                <input type="number" class="form-control" name="capacity_kg" id="edit_capacity_kg" step="0.01" min="1" required placeholder="Maximum collection capacity">
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Service & Status -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Service & Status</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Verification Status *</label>
                <select class="form-control" name="verification_status" id="edit_verification_status" required>
                  @foreach(\App\Models\Collector::getVerificationStatuses() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Service Areas</label>
                <input type="text" class="form-control" name="service_areas" id="edit_service_areas" placeholder="Enter areas separated by commas">
                <small class="text-muted">Enter service areas separated by commas</small>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Bio</label>
                <textarea class="form-control" name="bio" id="edit_bio" rows="4" placeholder="Brief description about the collector"></textarea>
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

<!-- Delete Collector Modal -->
<div class="modal fade" id="deleteCollectorModal" tabindex="-1" aria-labelledby="deleteCollectorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteCollectorModalLabel">Delete Collector</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the collector profile for <strong id="deleteCollectorName"></strong>?</p>
        <p class="text-danger">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteCollectorForm" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete Collector</button>
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
});

// Load users for dropdown (only users without collector profile)
function loadUsers() {
    fetch('{{ route("admin.collectors.users") }}')
        .then(response => response.json())
        .then(users => {
            const select = document.getElementById('user_id');
            if (select) {
                select.innerHTML = '<option value="">Select User</option>';
                users.forEach(user => {
                    select.innerHTML += `<option value="${user.id}">${user.name} (${user.email})</option>`;
                });
            }
        })
        .catch(error => console.error('Error loading users:', error));
}

// Edit collector function
function editCollector(id) {
    fetch(`{{ url('admin/collectors') }}/${id}/data`)
        .then(response => response.json())
        .then(collector => {
            document.getElementById('edit_user_id').value = collector.user_id;
            document.getElementById('edit_user_name').value = collector.user ? `${collector.user.name} (${collector.user.email})` : 'N/A';
            document.getElementById('edit_company_name').value = collector.company_name || '';
            document.getElementById('edit_vehicle_type').value = collector.vehicle_type;
            document.getElementById('edit_capacity_kg').value = collector.capacity_kg;
            document.getElementById('edit_verification_status').value = collector.verification_status;
            document.getElementById('edit_bio').value = collector.bio || '';
            
            // Handle service areas (array to comma-separated string)
            const serviceAreas = collector.service_areas && Array.isArray(collector.service_areas) 
                ? collector.service_areas.join(', ') 
                : '';
            document.getElementById('edit_service_areas').value = serviceAreas;
            
            document.getElementById('editCollectorForm').action = `{{ url('admin/collectors') }}/${id}`;
            new bootstrap.Modal(document.getElementById('editCollectorModal')).show();
        })
        .catch(error => {
            console.error('Error loading collector:', error);
            alert('Error loading collector details');
        });
}

// Delete collector function
function deleteCollector(id, name) {
    document.getElementById('deleteCollectorName').textContent = name;
    document.getElementById('deleteCollectorForm').action = `{{ url('admin/collectors') }}/${id}`;
    new bootstrap.Modal(document.getElementById('deleteCollectorModal')).show();
}

// Handle service areas input (convert comma-separated to array before submit)
document.getElementById('addCollectorForm').addEventListener('submit', function(e) {
    const serviceAreasInput = document.querySelector('#service_areas_input');
    if (serviceAreasInput && serviceAreasInput.value) {
        const areas = serviceAreasInput.value.split(',').map(a => a.trim()).filter(a => a);
        // Create hidden input for array
        serviceAreasInput.name = '';
        areas.forEach((area, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `service_areas[${index}]`;
            input.value = area;
            this.appendChild(input);
        });
    }
});

document.getElementById('editCollectorForm').addEventListener('submit', function(e) {
    const serviceAreasInput = document.getElementById('edit_service_areas');
    if (serviceAreasInput && serviceAreasInput.value) {
        const areas = serviceAreasInput.value.split(',').map(a => a.trim()).filter(a => a);
        serviceAreasInput.name = '';
        areas.forEach((area, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `service_areas[${index}]`;
            input.value = area;
            this.appendChild(input);
        });
    }
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced Card Styling */
.card {
    box-shadow: 0 4px 16px rgba(0,0,0,0.08) !important;
    border: none !important;
}

.card-body {
    background: white;
}

/* Edit and Delete Button Hover Effects */
.edit-btn {
    color: #6b7280;
    transition: all 0.2s ease;
    text-decoration: none;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 6px;
    display: inline-block;
}

.edit-btn:hover {
    background: #dbeafe;
    color: #2563eb !important;
    text-decoration: none;
}

.delete-btn {
    color: #6b7280;
    transition: all 0.2s ease;
    text-decoration: none;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 6px;
    display: inline-block;
}

.delete-btn:hover {
    background: #fee2e2;
    color: #dc2626 !important;
    text-decoration: none;
}

/* Enhanced Badge Styling */
.badge {
    font-weight: 600 !important;
    padding: 6px 12px !important;
    border-radius: 6px !important;
}

.bg-gradient-info {
    background: #3b82f6 !important;
}

.bg-gradient-success {
    background: #10b981 !important;
}

.bg-warning {
    background: #f59e0b !important;
}

.bg-danger {
    background: #ef4444 !important;
}

/* Modal styling - Complete Redesign */
.modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-header {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 16px 16px 0 0;
    padding: 24px 32px;
}

.modal-header h5 {
    font-weight: 700;
    font-size: 1.35rem;
    color: #1f2937;
    margin: 0;
}

.modal-header .btn-close {
    padding: 0;
    margin: 0;
    opacity: 0.5;
}

.modal-header .btn-close:hover {
    opacity: 1;
}

.modal-body {
    padding: 32px;
    background: white;
}

.modal-body h6 {
    font-size: 0.875rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}

.modal-body .form-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.95rem;
    margin-bottom: 8px;
}

.modal-body .form-control,
.modal-body .form-select {
    border: 1px solid #d1d5db;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.modal-body .form-control:focus,
.modal-body .form-select:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    outline: none;
}

.modal-body textarea.form-control {
    min-height: 100px;
}

.modal-body .text-muted {
    font-size: 0.85rem;
    color: #9ca3af;
}

.modal-footer {
    padding: 20px 32px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    border-radius: 0 0 16px 16px;
}

.modal-footer .btn {
    padding: 10px 24px;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.2s ease;
}

.modal-footer .btn-secondary {
    background: white;
    border: 1px solid #d1d5db;
    color: #374151;
}

.modal-footer .btn-secondary:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.modal-footer .btn-primary,
.modal-footer .btn-success {
    background: #10b981;
    border: none;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
}

.modal-footer .btn-primary:hover,
.modal-footer .btn-success:hover {
    background: #059669;
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
}

.modal-footer .btn-danger {
    background: #ef4444;
    border: none;
}

.modal-footer .btn-danger:hover {
    background: #dc2626;
}

/* Two Column Layout */
.row.g-3 > .col-md-6 {
    padding-right: 12px;
    padding-left: 12px;
}

/* Enhanced Search Bar */
.input-group-outline {
    background: white;
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.input-group-outline .form-control {
    border: 1px solid #d1d5db;
    padding: 10px 14px;
    font-weight: 400;
}

.input-group-outline .form-control:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Enhanced Buttons */
.btn {
    font-weight: 600;
    border-radius: 10px;
    padding: 10px 20px;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.bg-gradient-success {
    background: #10b981 !important;
}

.bg-gradient-success:hover {
    background: #059669 !important;
}

/* Table Enhancements */
.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background: rgba(249, 250, 251, 0.8);
}
</style>
@endpush

@endsection
