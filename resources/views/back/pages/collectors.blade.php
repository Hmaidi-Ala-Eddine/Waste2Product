@extends('layouts.back')

@section('title', 'Collectors')
@section('page-title', 'Collectors')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0">Collectors Management</h6>
            <div class="text-white">
              <small>Total: {{ $collectors->total() }} collectors</small>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Search and Filter Section -->
      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Filter Collectors</h6>
          <div class="d-flex gap-2">
            <button type="button" class="btn bg-gradient-info" id="generateCollectorAIReportBtn">
              <i class="material-symbols-rounded me-1">auto_awesome</i>Generate AI Report
            </button>
            <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addCollectorModal">
              <i class="material-symbols-rounded me-1">person_add</i>Add New Collector
            </button>
          </div>
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
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ $collector->user->profile_picture_url }}" class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $collector->user->name }}" style="object-fit: cover;">
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
      <div class="modal-header">
        <h5 class="modal-title" id="addCollectorModalLabel">
          <i class="material-symbols-rounded me-2">add</i>Add New Collector
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addCollectorForm" method="POST" action="{{ route('admin.collectors.store') }}">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Basic Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">User</label>
                <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }} ({{ auth()->user()->email }})" readonly disabled>
                <small class="text-muted">Collector profiles you create will belong to your account</small>
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
                <label class="form-label text-dark">Service Areas (Governorates) *</label>
                <div class="row g-2">
                  @foreach(\App\Helpers\TunisiaStates::getStates() as $state)
                    <div class="col-md-4">
                      <div class="form-check">
                        <input class="form-check-input service-area-checkbox" type="checkbox" name="service_areas[]" value="{{ $state }}" id="area_{{ str_replace(' ', '_', $state) }}">
                        <label class="form-check-label" for="area_{{ str_replace(' ', '_', $state) }}">
                          {{ $state }}
                        </label>
                      </div>
                    </div>
                  @endforeach
                </div>
                <small class="text-muted">Select at least one governorate (min: 1, max: 24)</small>
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
      <div class="modal-header">
        <h5 class="modal-title" id="editCollectorModalLabel">
          <i class="material-symbols-rounded me-2">edit</i>Edit Collector
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <label class="form-label text-dark">Service Areas (Governorates) *</label>
                <div class="row g-2" id="edit_service_areas_container">
                  @foreach(\App\Helpers\TunisiaStates::getStates() as $state)
                    <div class="col-md-4">
                      <div class="form-check">
                        <input class="form-check-input edit-service-area-checkbox" type="checkbox" name="service_areas[]" value="{{ $state }}" id="edit_area_{{ str_replace(' ', '_', $state) }}">
                        <label class="form-check-label" for="edit_area_{{ str_replace(' ', '_', $state) }}">
                          {{ $state }}
                        </label>
                      </div>
                    </div>
                  @endforeach
                </div>
                <small class="text-muted">Select at least one governorate (min: 1, max: 24)</small>
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
// Setup validation when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupValidation();
});

// Setup real-time validation
function setupValidation() {
    // Add Collector Form Validation
    const addForm = document.getElementById('addCollectorForm');
    const addCompanyName = addForm.querySelector('[name="company_name"]');
    const addVehicleType = addForm.querySelector('[name="vehicle_type"]');
    const addCapacity = addForm.querySelector('[name="capacity_kg"]');
    const addBio = addForm.querySelector('[name="bio"]');

    // Edit Collector Form Validation
    const editForm = document.getElementById('editCollectorForm');
    const editCompanyName = editForm.querySelector('[name="company_name"]');
    const editVehicleType = editForm.querySelector('[name="vehicle_type"]');
    const editCapacity = editForm.querySelector('[name="capacity_kg"]');
    const editBio = editForm.querySelector('[name="bio"]');
    const editVerificationStatus = editForm.querySelector('[name="verification_status"]');

    // Validation Rules
    function validateUser(input) {
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        if (!input.value) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Please select a user.';
            return false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            feedback.textContent = '';
            return true;
        }
    }

    function validateCompanyName(input) {
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        if (input.value && input.value.length > 255) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Company name cannot exceed 255 characters.';
            return false;
        } else if (input.value && !/^[a-zA-Z0-9\s\.\,\-\&]+$/.test(input.value)) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Company name contains invalid characters.';
            return false;
        } else {
            input.classList.remove('is-invalid');
            if (input.value) input.classList.add('is-valid');
            feedback.textContent = '';
            return true;
        }
    }

    function validateVehicleType(input) {
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        if (!input.value) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Please select a vehicle type.';
            return false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            feedback.textContent = '';
            return true;
        }
    }

    function validateCapacity(input) {
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        const value = parseFloat(input.value);
        
        if (!input.value) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Please enter the collection capacity.';
            return false;
        } else if (isNaN(value)) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Capacity must be a valid number.';
            return false;
        } else if (value < 1) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Capacity must be at least 1 kg.';
            return false;
        } else if (value > 99999.99) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Capacity cannot exceed 99,999.99 kg.';
            return false;
        } else if (!/^\d+(\.\d{1,2})?$/.test(input.value)) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Capacity can have maximum 2 decimal places.';
            return false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            feedback.textContent = '';
            return true;
        }
    }

    function validateBio(input) {
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        if (input.value && input.value.length > 1000) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Bio cannot exceed 1000 characters.';
            return false;
        } else if (input.value && !/^[a-zA-Z0-9\s\.\,\-\!\?\(\)]*$/.test(input.value)) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedback.textContent = 'Bio contains invalid characters.';
            return false;
        } else {
            input.classList.remove('is-invalid');
            if (input.value) input.classList.add('is-valid');
            feedback.textContent = '';
            return true;
        }
    }

    // Add form listeners
    if (addUserId) {
        addUserId.addEventListener('blur', () => validateUser(addUserId));
        addUserId.addEventListener('change', () => validateUser(addUserId));
    }
    if (addCompanyName) {
        addCompanyName.addEventListener('blur', () => validateCompanyName(addCompanyName));
        addCompanyName.addEventListener('input', () => validateCompanyName(addCompanyName));
    }
    if (addVehicleType) {
        addVehicleType.addEventListener('blur', () => validateVehicleType(addVehicleType));
        addVehicleType.addEventListener('change', () => validateVehicleType(addVehicleType));
    }
    if (addCapacity) {
        addCapacity.addEventListener('blur', () => validateCapacity(addCapacity));
        addCapacity.addEventListener('input', () => validateCapacity(addCapacity));
    }
    if (addBio) {
        addBio.addEventListener('blur', () => validateBio(addBio));
        addBio.addEventListener('input', () => validateBio(addBio));
    }

    // Edit form listeners
    if (editCompanyName) {
        editCompanyName.addEventListener('blur', () => validateCompanyName(editCompanyName));
        editCompanyName.addEventListener('input', () => validateCompanyName(editCompanyName));
    }
    if (editVehicleType) {
        editVehicleType.addEventListener('blur', () => validateVehicleType(editVehicleType));
        editVehicleType.addEventListener('change', () => validateVehicleType(editVehicleType));
    }
    if (editCapacity) {
        editCapacity.addEventListener('blur', () => validateCapacity(editCapacity));
        editCapacity.addEventListener('input', () => validateCapacity(editCapacity));
    }
    if (editBio) {
        editBio.addEventListener('blur', () => validateBio(editBio));
        editBio.addEventListener('input', () => validateBio(editBio));
    }

    // Form submission validation
    addForm.addEventListener('submit', function(e) {
        let isValid = true;
        
        if (!validateUser(addUserId)) isValid = false;
        if (!validateVehicleType(addVehicleType)) isValid = false;
        if (!validateCapacity(addCapacity)) isValid = false;
        if (addCompanyName.value && !validateCompanyName(addCompanyName)) isValid = false;
        if (addBio.value && !validateBio(addBio)) isValid = false;
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });

    editForm.addEventListener('submit', function(e) {
        let isValid = true;
        
        if (!validateVehicleType(editVehicleType)) isValid = false;
        if (!validateCapacity(editCapacity)) isValid = false;
        if (editCompanyName.value && !validateCompanyName(editCompanyName)) isValid = false;
        if (editBio.value && !validateBio(editBio)) isValid = false;
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
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
            
            // Handle service areas checkboxes
            document.querySelectorAll('.edit-service-area-checkbox').forEach(checkbox => {
                checkbox.checked = false; // Uncheck all first
            });
            if (collector.service_areas && Array.isArray(collector.service_areas)) {
                collector.service_areas.forEach(area => {
                    const checkbox = document.querySelector(`.edit-service-area-checkbox[value="${area}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
            
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

// Service areas are now checkboxes - they submit as arrays automatically
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

/* Validation Styles */
.form-control.is-invalid,
.form-select.is-invalid,
select.form-control.is-invalid {
    border-color: #dc3545 !important;
    background-image: none !important;
    background-color: #fff !important;
}

.form-control.is-valid,
.form-select.is-valid,
select.form-control.is-valid {
    border-color: #28a745 !important;
    background-image: none !important;
    background-color: #fff !important;
}

.invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.invalid-feedback.d-block {
    display: block !important;
}

.form-control.is-invalid ~ .invalid-feedback,
.form-select.is-invalid ~ .invalid-feedback {
    display: block;
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

/* AI Report Styling */
.report-content {
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

.report-content h1,
.report-content h2,
.report-content h3 {
    color: #2d3748;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.report-content strong {
    color: #667eea;
    font-weight: 700;
}

.report-content hr {
    border: none;
    height: 2px;
    background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
    margin: 2rem 0;
    opacity: 0.3;
}

.report-content ul {
    list-style: none;
    padding-left: 0;
}

.report-content ul li {
    padding-left: 1.5rem;
    margin-bottom: 0.5rem;
    position: relative;
}

.report-content ul li:before {
    content: "▸";
    position: absolute;
    left: 0;
    color: #667eea;
    font-weight: bold;
}

.report-content::-webkit-scrollbar {
    width: 10px;
}

.report-content::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.report-content::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    border: 2px solid #f1f5f9;
}

.report-content::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
</style>
@endpush

@push('scripts')
<script>
// AI Report Generation for Collectors
document.getElementById('generateCollectorAIReportBtn').addEventListener('click', async function() {
    const modal = new bootstrap.Modal(document.getElementById('collectorAIReportModal'));
    modal.show();
    
    const loadingState = document.getElementById('collectorReportLoadingState');
    const reportContent = document.getElementById('collectorReportContent');
    const downloadBtn = document.getElementById('downloadCollectorReportBtn');
    
    // Show loading
    loadingState.style.display = 'block';
    reportContent.style.display = 'none';
    downloadBtn.disabled = true;
    
    try {
        const response = await fetch('/admin/collectors/ai-report', {
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
            downloadBtn.dataset.reportTitle = 'Collectors Report';
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
    html = html.replace(/^### (.+)$/gm, '<h3>$1</h3>');
    html = html.replace(/^## (.+)$/gm, '<h2>$1</h2>');
    html = html.replace(/^# (.+)$/gm, '<h1>$1</h1>');
    html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/__(.+?)__/g, '<strong>$1</strong>');
    html = html.replace(/^(═{3,}|─{3,}|•{3,})$/gm, '<hr>');
    html = html.replace(/\n/g, '<br>');
    return html;
}

// Download Collector Report as PDF
document.getElementById('downloadCollectorReportBtn').addEventListener('click', function() {
    const reportHtml = this.dataset.report;
    const reportTitle = this.dataset.reportTitle || 'Collectors Report';
    if (!reportHtml) return;
    
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
                .header h1 { font-size: 28pt; font-weight: 700; margin-bottom: 10px; }
                .header .meta { font-size: 10pt; opacity: 0.9; }
                h1, h2, h3 { color: #667eea; font-weight: 700; margin-top: 25px; margin-bottom: 12px; page-break-after: avoid; }
                h1 { font-size: 24pt; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
                h2 { font-size: 18pt; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; }
                h3 { font-size: 14pt; }
                strong { color: #667eea; font-weight: 700; }
                hr { border: none; height: 2px; background: linear-gradient(90deg, #667eea, #764ba2, #667eea); margin: 20px 0; opacity: 0.3; }
                br { display: block; content: ""; margin: 6px 0; }
                .footer { position: fixed; bottom: 20px; left: 40px; right: 40px; text-align: center; font-size: 9pt; color: #718096; border-top: 1px solid #e2e8f0; padding-top: 10px; }
                .page-number:before { content: "Page " counter(page); }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>🚀 ${reportTitle}</h1>
                <div class="meta">Generated: ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })} | Waste2Product Tunisia</div>
            </div>
            <div class="content">${reportHtml}</div>
            <div class="footer">
                <div class="page-number"></div>
                <div>© ${new Date().getFullYear()} Waste2Product Tunisia - AI-Powered Analytics</div>
            </div>
        </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(pdfContent);
    printWindow.document.close();
    setTimeout(() => { printWindow.print(); setTimeout(() => printWindow.close(), 100); }, 500);
});
</script>
@endpush

<!-- AI Report Modal for Collectors -->
<div class="modal fade" id="collectorAIReportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h5 class="modal-title text-white">
          <i class="material-symbols-rounded me-2">auto_awesome</i>AI-Generated Collectors Report
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="collectorReportLoadingState" class="text-center py-5">
          <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Generating report...</span>
          </div>
          <p class="mt-3 text-muted">AI is analyzing data and generating your comprehensive report...</p>
        </div>
        <div id="collectorReportContent" class="report-content" style="display: none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn bg-gradient-success" id="downloadCollectorReportBtn" disabled>
          <i class="material-symbols-rounded me-1">picture_as_pdf</i>Download PDF
        </button>
      </div>
    </div>
  </div>
</div>

@endsection
