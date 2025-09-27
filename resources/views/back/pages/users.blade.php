@extends('layouts.back')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
@if(session('success'))
  <div class="alert custom-alert alert-success alert-dismissible fade show auto-dismiss-alert" role="alert">
    <div class="alert-content">
      <i class="material-symbols-rounded alert-icon">check_circle</i>
      <div class="alert-text">
        <strong>Success!</strong>
        <span>{{ session('success') }}</span>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('delete_success'))
  <div class="alert custom-alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert">
    <div class="alert-content">
      <i class="material-symbols-rounded alert-icon">delete</i>
      <div class="alert-text">
        <strong>Deleted!</strong>
        <span>{{ session('delete_success') }}</span>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert custom-alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert">
    <div class="alert-content">
      <i class="material-symbols-rounded alert-icon">error</i>
      <div class="alert-text">
        <strong>Error!</strong>
        <span>{{ session('error') }}</span>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0">Users Management</h6>
            <div class="text-white">
              <small>Total: {{ $users->total() }} users</small>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Search and Filter Section -->
      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Filter Users</h6>
          <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="material-symbols-rounded me-1">person_add</i>Add New User
          </button>
        </div>
        
        <form method="GET" action="{{ route('admin.users') }}" class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="input-group input-group-outline">
              <label class="form-label">Search users...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="role">
                <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="status">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn bg-gradient-dark mb-0 w-100">
              <i class="material-symbols-rounded me-1">search</i>Search
            </button>
          </div>
          <div class="col-md-2">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary mb-0 w-100">
              <i class="material-symbols-rounded me-1">refresh</i>Reset
            </a>
          </div>
        </form>
        
        @if(request()->hasAny(['search', 'role', 'status']))
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <small class="text-muted">
                Showing {{ $users->count() }} of {{ $users->total() }} results
                @if(request('search'))
                  for "<strong>{{ request('search') }}</strong>"
                @endif
              </small>
            </div>
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm">
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
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Joined</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $index => $user)
                @php
                  // Cycle through available team images for profile pictures
                  $avatarImages = ['team-1.jpg', 'team-2.jpg', 'team-3.jpg', 'team-4.jpg', 'team-5.jpg', 'team-6.jpg'];
                  $avatarImage = $avatarImages[$index % count($avatarImages)];
                @endphp
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ asset('assets/back/img/' . $avatarImage) }}" class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $user->name }}">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                    <p class="text-xs text-secondary mb-0">
                      @php
                        $isVerified = !is_null($user->email_verified_at) || ($user->is_active ?? false);
                      @endphp
                      @if($isVerified)
                        <span class="text-success">Verified</span>
                        @if(!is_null($user->email_verified_at))
                          <span class="text-muted"> ({{ $user->email_verified_at->format('d/m/y') }})</span>
                        @endif
                      @else
                        <span class="text-warning">Unverified</span>
                      @endif
                    </p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    @if($user->role === 'admin')
                      <span class="badge badge-sm bg-gradient-success">Admin</span>
                    @elseif($user->role === 'moderator')
                      <span class="badge badge-sm bg-gradient-info">Moderator</span>
                    @else
                      <span class="badge badge-sm bg-gradient-secondary">User</span>
                    @endif
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">
                      {{ $user->created_at ? $user->created_at->format('d/m/y') : 'N/A' }}
                    </span>
                  </td>
                  <td class="align-middle text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2 edit-btn" 
                       onclick="editUser({{ $user->id }})" 
                       data-toggle="tooltip" data-original-title="Edit user">
                      Edit
                    </a>
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs delete-btn" 
                       onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                       data-toggle="tooltip" data-original-title="Delete user">
                      Delete
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4">
                    <p class="text-secondary mb-0">No users found</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination Section -->
        @if($users->hasPages())
          <div class="card-footer px-3 py-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted">
                  Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                </small>
              </div>
              <div>
                {{ $users->links('back.partials.pagination') }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white" id="editUserModalLabel">
          <i class="material-symbols-rounded me-2">person_edit</i>Edit User
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editUserForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <!-- Personal Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Personal Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Full Name *</label>
                <input type="text" class="form-control" id="editName" name="name" required placeholder="Enter full name">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Username</label>
                <input type="text" class="form-control" id="editUsername" name="username" placeholder="Enter username">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">First Name</label>
                <input type="text" class="form-control" id="editFirstName" name="first_name" placeholder="Enter first name">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Last Name</label>
                <input type="text" class="form-control" id="editLastName" name="last_name" placeholder="Enter last name">
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Contact & Account -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Contact & Account</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Email Address *</label>
                <input type="email" class="form-control" id="editEmail" name="email" required placeholder="Enter email address">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Phone Number</label>
                <input type="tel" class="form-control" id="editPhone" name="phone" placeholder="Enter phone number">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Address</label>
                <textarea class="form-control" id="editAddress" name="address" rows="3" placeholder="Enter address"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Role *</label>
                <select class="form-control" id="editRole" name="role" required>
                  <option value="">Select Role</option>
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                  <option value="moderator">Moderator</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
            </div>
          </div>
          
          <!-- Account Settings -->
          <div class="row mt-3">
            <div class="col-12">
              <h6 class="text-dark font-weight-bold mb-3">Account Settings</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active" value="1">
                    <label class="form-check-label" for="editIsActive">
                      <strong>Active Account</strong>
                      <small class="d-block text-muted">User can login and access the system</small>
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="editFaceidEnabled" name="faceid_enabled" value="1">
                    <label class="form-check-label" for="editFaceidEnabled">
                      <strong>Face ID Enabled</strong>
                      <small class="d-block text-muted">Allow Face ID authentication</small>
                    </label>
                  </div>
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

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</p>
        <p class="text-danger">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteUserForm" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete User</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white" id="addUserModalLabel">
          <i class="material-symbols-rounded me-2">person_add</i>Add New User
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Personal Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Personal Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Full Name *</label>
                <input type="text" class="form-control" name="name" required autocomplete="name" placeholder="Enter full name">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Username</label>
                <input type="text" class="form-control" name="username" autocomplete="username" placeholder="Enter username">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">First Name</label>
                <input type="text" class="form-control" name="first_name" autocomplete="given-name" placeholder="Enter first name">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Last Name</label>
                <input type="text" class="form-control" name="last_name" autocomplete="family-name" placeholder="Enter last name">
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Contact & Account -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Contact & Account</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Email Address *</label>
                <input type="email" class="form-control" name="email" required autocomplete="email" placeholder="Enter email address">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Password *</label>
                <input type="password" class="form-control" name="password" required minlength="8" autocomplete="new-password" placeholder="Enter password (min 8 characters)">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Confirm Password *</label>
                <input type="password" class="form-control" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Confirm password">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Phone Number</label>
                <input type="tel" class="form-control" name="phone" autocomplete="tel" placeholder="Enter phone number">
                <div class="invalid-feedback"></div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label text-dark">Address</label>
                <textarea class="form-control" name="address" rows="3" autocomplete="street-address" placeholder="Enter address"></textarea>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label text-dark">Role *</label>
                <select class="form-control" name="role" required>
                  <option value="">Select Role</option>
                  <option value="user" selected>User</option>
                  <option value="admin">Admin</option>
                  <option value="moderator">Moderator</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
            </div>
          </div>
          
          <!-- Account Settings -->
          <div class="row mt-3">
            <div class="col-12">
              <h6 class="text-dark font-weight-bold mb-3">Account Settings</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                    <label class="form-check-label">
                      <strong>Active Account</strong>
                      <small class="d-block text-muted">User can login and access the system</small>
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="faceid_enabled" value="1">
                    <label class="form-check-label">
                      <strong>Face ID Enabled</strong>
                      <small class="d-block text-muted">Allow Face ID authentication</small>
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="send_welcome_email" value="1" checked>
                <label class="form-check-label">
                  <strong>Send Welcome Email</strong>
                  <small class="d-block text-muted">Send account details and welcome message to user</small>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="material-symbols-rounded me-1">close</i>Cancel
          </button>
          <button type="submit" class="btn bg-gradient-success">
            <i class="material-symbols-rounded me-1">person_add</i>Create User
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
async function editUser(id) {
    try {
        // Fetch user data
        const response = await fetch(`/admin/users/${id}/data`);
        const user = await response.json();
        
        // Set form action
        document.getElementById('editUserForm').action = `/admin/users/${id}`;
        
        // Fill all form fields
        document.getElementById('editName').value = user.name || '';
        document.getElementById('editUsername').value = user.username || '';
        document.getElementById('editFirstName').value = user.first_name || '';
        document.getElementById('editLastName').value = user.last_name || '';
        document.getElementById('editEmail').value = user.email || '';
        document.getElementById('editPhone').value = user.phone || '';
        document.getElementById('editAddress').value = user.address || '';
        document.getElementById('editRole').value = user.role || '';
        document.getElementById('editIsActive').checked = user.is_active || false;
        document.getElementById('editFaceidEnabled').checked = user.faceid_enabled || false;
        
        // Handle floating labels for filled fields
        const inputs = document.querySelectorAll('#editUserModal .input-group-outline input, #editUserModal .input-group-outline textarea, #editUserModal .input-group-outline select');
        inputs.forEach(input => {
            const inputGroup = input.closest('.input-group-outline');
            if (input.value.trim() !== '') {
                inputGroup.classList.add('is-filled');
            } else {
                inputGroup.classList.remove('is-filled');
            }
        });
        
        // Show modal
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    } catch (error) {
        console.error('Error fetching user data:', error);
        alert('Error loading user data. Please try again.');
    }
}

function deleteUser(id, name) {
    // Set form action
    document.getElementById('deleteUserForm').action = `/admin/users/${id}`;
    
    // Set user name in confirmation
    document.getElementById('deleteUserName').textContent = name;
    
    // Show modal
    new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
}

// Handle floating labels and search functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 3 seconds
    const autoDismissAlerts = document.querySelectorAll('.auto-dismiss-alert');
    autoDismissAlerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.add('fade-out');
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 300); // Wait for fade-out animation to complete
        }, 3000); // 3 seconds
    });

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
    
    // Enhanced search functionality
    const searchForm = document.querySelector('form[action*="users"]');
    const searchInput = document.querySelector('input[name="search"]');
    const roleSelect = document.querySelector('select[name="role"]');
    const statusSelect = document.querySelector('select[name="status"]');
    
    // Auto-submit form when filters change
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            searchForm.submit();
        });
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            searchForm.submit();
        });
    }
    
    // Add search on Enter key
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    }
    
    // Clear search functionality
    const clearBtn = document.querySelector('a[href*="users"]:not([href*="?"])');
    if (clearBtn && (searchInput?.value || roleSelect?.value !== 'all' || statusSelect?.value)) {
        clearBtn.style.display = 'inline-block';
    }
    
    // Add User Modal with Form Validation
    const addUserModal = document.getElementById('addUserModal');
    const addUserForm = document.getElementById('addUserForm');
    
    if (addUserModal && addUserForm) {
        // Validation functions
        function validateField(field, value) {
            const fieldName = field.name;
            let isValid = true;
            let errorMessage = '';
            
            switch(fieldName) {
                case 'name':
                    if (!value || value.trim().length < 2) {
                        isValid = false;
                        errorMessage = 'Full name must be at least 2 characters long';
                    }
                    break;
                    
                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!value || !emailRegex.test(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid email address';
                    }
                    break;
                    
                case 'password':
                    if (!value || value.length < 8) {
                        isValid = false;
                        errorMessage = 'Password must be at least 8 characters long';
                    }
                    break;
                    
                case 'password_confirmation':
                    const password = addUserForm.querySelector('input[name="password"]').value;
                    if (!value || value !== password) {
                        isValid = false;
                        errorMessage = 'Passwords do not match';
                    }
                    break;
                    
                case 'role':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Please select a role';
                    }
                    break;
                    
                case 'username':
                    if (value && value.length < 3) {
                        isValid = false;
                        errorMessage = 'Username must be at least 3 characters long';
                    }
                    break;
                    
                case 'phone':
                    if (value && !/^[\+]?[0-9\s\-\(\)]{10,}$/.test(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid phone number';
                    }
                    break;
            }
            
            return { isValid, errorMessage };
        }
        
        function showFieldError(field, message) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = message;
                feedback.style.display = 'block';
            }
            
            // Reset submit button if there's an error
            resetSubmitButton();
        }
        
        function resetSubmitButton() {
            const submitBtn = addUserForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="material-symbols-rounded me-1">person_add</i>Create User';
            }
        }
        
        function showFieldSuccess(field) {
            field.classList.add('is-valid');
            field.classList.remove('is-invalid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        }
        
        function clearFieldValidation(field) {
            field.classList.remove('is-valid', 'is-invalid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        }
        
        // Real-time validation on input - only show errors when user makes mistakes
        const formFields = addUserForm.querySelectorAll('input, select, textarea');
        formFields.forEach(field => {
            let hasBeenTouched = false;
            
            field.addEventListener('focus', function() {
                hasBeenTouched = true;
            });
            
            field.addEventListener('input', function() {
                // Reset button if user starts typing in a field that had an error
                if (field.classList.contains('is-invalid')) {
                    resetSubmitButton();
                }
                
                // Only validate if field has been touched and has content, or if it was previously invalid
                if (hasBeenTouched && (field.value.trim() !== '' || field.classList.contains('is-invalid'))) {
                    const validation = validateField(field, field.value);
                    if (field.value.trim() === '' && !field.required) {
                        clearFieldValidation(field);
                    } else if (validation.isValid) {
                        showFieldSuccess(field);
                    } else {
                        showFieldError(field, validation.errorMessage);
                    }
                }
            });
            
            field.addEventListener('blur', function() {
                // Only validate on blur if field has content or is required
                if (hasBeenTouched && (field.value.trim() !== '' || field.required)) {
                    const validation = validateField(field, field.value);
                    if (field.value.trim() === '' && !field.required) {
                        clearFieldValidation(field);
                    } else if (validation.isValid) {
                        showFieldSuccess(field);
                    } else {
                        showFieldError(field, validation.errorMessage);
                    }
                }
            });
        });
        
        // Form submission validation
        addUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isFormValid = true;
            const requiredFields = addUserForm.querySelectorAll('input[required], select[required]');
            
            // Validate all required fields
            requiredFields.forEach(field => {
                const validation = validateField(field, field.value);
                if (!validation.isValid) {
                    showFieldError(field, validation.errorMessage);
                    isFormValid = false;
                } else {
                    showFieldSuccess(field);
                }
            });
            
            // Validate optional fields that have values
            const optionalFields = addUserForm.querySelectorAll('input:not([required]), select:not([required]), textarea:not([required])');
            optionalFields.forEach(field => {
                if (field.value.trim() !== '') {
                    const validation = validateField(field, field.value);
                    if (!validation.isValid) {
                        showFieldError(field, validation.errorMessage);
                        isFormValid = false;
                    } else {
                        showFieldSuccess(field);
                    }
                }
            });
            
            // If basic validation failed, don't proceed
            if (!isFormValid) {
                // Scroll to first error
                const firstError = addUserForm.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return;
            }
            
            // Check for duplicate email with AJAX
            const emailField = addUserForm.querySelector('input[name="email"]');
            if (emailField.value.trim() !== '') {
                // Show loading state for email check
                const submitBtn = addUserForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="material-symbols-rounded me-1">hourglass_empty</i>Checking...';
                
                // Check email uniqueness via AJAX
                fetch('/admin/users/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                       document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ email: emailField.value })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.available) {
                        showFieldError(emailField, 'This email address is already taken');
                        // Button will be reset by showFieldError function
                        // Scroll to email field
                        emailField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        emailField.focus();
                    } else {
                        // Email is available, proceed with submission
                        submitForm();
                    }
                })
                .catch(error => {
                    console.log('Email check failed, proceeding with submission');
                    submitForm();
                });
            } else {
                // No email to check, submit directly
                submitForm();
            }
            
            function submitForm() {
                // Show final loading state
                const submitBtn = addUserForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="material-symbols-rounded me-1">hourglass_empty</i>Creating...';
                
                // Submit the form
                addUserForm.submit();
            }
        });
        
        addUserModal.addEventListener('shown.bs.modal', function () {
            // Focus on first input when modal opens
            const firstInput = addUserModal.querySelector('input[name="name"]');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        });
        
        addUserModal.addEventListener('hidden.bs.modal', function () {
            // Reset form when modal closes
            addUserForm.reset();
            
            // Clear all validation states
            formFields.forEach(field => {
                clearFieldValidation(field);
            });
            
            // Reset submit button
            const submitBtn = addUserForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="material-symbols-rounded me-1">person_add</i>Create User';
            }
        });
    }
    
    // Edit User Modal with Form Validation
    const editUserModal = document.getElementById('editUserModal');
    const editUserForm = document.getElementById('editUserForm');
    
    if (editUserModal && editUserForm) {
        // Validation functions for edit form (similar to add form but without password)
        function validateEditField(field, value, originalEmail = '') {
            const fieldName = field.name;
            let isValid = true;
            let errorMessage = '';
            
            switch(fieldName) {
                case 'name':
                    if (!value || value.trim().length < 2) {
                        isValid = false;
                        errorMessage = 'Full name must be at least 2 characters long';
                    }
                    break;
                    
                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!value || !emailRegex.test(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid email address';
                    }
                    break;
                    
                case 'role':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Please select a role';
                    }
                    break;
                    
                case 'username':
                    if (value && value.length < 3) {
                        isValid = false;
                        errorMessage = 'Username must be at least 3 characters long';
                    }
                    break;
                    
                case 'phone':
                    if (value && !/^[\+]?[0-9\s\-\(\)]{10,}$/.test(value)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid phone number';
                    }
                    break;
            }
            
            return { isValid, errorMessage };
        }
        
        function showEditFieldError(field, message) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = message;
                feedback.style.display = 'block';
            }
            resetEditSubmitButton();
        }
        
        function showEditFieldSuccess(field) {
            field.classList.add('is-valid');
            field.classList.remove('is-invalid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        }
        
        function clearEditFieldValidation(field) {
            field.classList.remove('is-valid', 'is-invalid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        }
        
        function resetEditSubmitButton() {
            const submitBtn = editUserForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="material-symbols-rounded me-1">save</i>Save Changes';
            }
        }
        
        // Real-time validation for edit form
        const editFormFields = editUserForm.querySelectorAll('input, select, textarea');
        let originalEmail = '';
        
        editFormFields.forEach(field => {
            let hasBeenTouched = false;
            
            field.addEventListener('focus', function() {
                hasBeenTouched = true;
            });
            
            field.addEventListener('input', function() {
                if (field.classList.contains('is-invalid')) {
                    resetEditSubmitButton();
                }
                
                if (hasBeenTouched && (field.value.trim() !== '' || field.classList.contains('is-invalid'))) {
                    const validation = validateEditField(field, field.value, originalEmail);
                    if (field.value.trim() === '' && !field.required) {
                        clearEditFieldValidation(field);
                    } else if (validation.isValid) {
                        showEditFieldSuccess(field);
                    } else {
                        showEditFieldError(field, validation.errorMessage);
                    }
                }
            });
            
            field.addEventListener('blur', function() {
                if (hasBeenTouched && (field.value.trim() !== '' || field.required)) {
                    const validation = validateEditField(field, field.value, originalEmail);
                    if (field.value.trim() === '' && !field.required) {
                        clearEditFieldValidation(field);
                    } else if (validation.isValid) {
                        showEditFieldSuccess(field);
                    } else {
                        showEditFieldError(field, validation.errorMessage);
                    }
                }
            });
        });
        
        // Form submission validation for edit
        editUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isFormValid = true;
            const requiredFields = editUserForm.querySelectorAll('input[required], select[required]');
            
            // Validate all required fields
            requiredFields.forEach(field => {
                const validation = validateEditField(field, field.value, originalEmail);
                if (!validation.isValid) {
                    showEditFieldError(field, validation.errorMessage);
                    isFormValid = false;
                } else {
                    showEditFieldSuccess(field);
                }
            });
            
            // Validate optional fields that have values
            const optionalFields = editUserForm.querySelectorAll('input:not([required]), select:not([required]), textarea:not([required])');
            optionalFields.forEach(field => {
                if (field.value.trim() !== '') {
                    const validation = validateEditField(field, field.value, originalEmail);
                    if (!validation.isValid) {
                        showEditFieldError(field, validation.errorMessage);
                        isFormValid = false;
                    } else {
                        showEditFieldSuccess(field);
                    }
                }
            });
            
            if (!isFormValid) {
                const firstError = editUserForm.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return;
            }
            
            // Check for duplicate email if email was changed
            const emailField = editUserForm.querySelector('input[name="email"]');
            if (emailField.value.trim() !== '' && emailField.value !== originalEmail) {
                const submitBtn = editUserForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="material-symbols-rounded me-1">hourglass_empty</i>Checking...';
                
                fetch('/admin/users/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ email: emailField.value })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.available) {
                        showEditFieldError(emailField, 'This email address is already taken');
                        emailField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        emailField.focus();
                    } else {
                        submitEditForm();
                    }
                })
                .catch(error => {
                    console.log('Email check failed, proceeding with submission');
                    submitEditForm();
                });
            } else {
                submitEditForm();
            }
            
            function submitEditForm() {
                const submitBtn = editUserForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="material-symbols-rounded me-1">hourglass_empty</i>Saving...';
                editUserForm.submit();
            }
        });
        
        // Store original email when modal opens
        editUserModal.addEventListener('shown.bs.modal', function() {
            const emailField = editUserForm.querySelector('input[name="email"]');
            originalEmail = emailField.value;
        });
        
        // Reset validation when modal closes
        editUserModal.addEventListener('hidden.bs.modal', function() {
            editFormFields.forEach(field => {
                clearEditFieldValidation(field);
            });
            resetEditSubmitButton();
        });
    }
    
    // Password confirmation validation
    const passwordInput = document.querySelector('#addUserModal input[name="password"]');
    const confirmPasswordInput = document.querySelector('#addUserModal input[name="password_confirmation"]');
    
    if (passwordInput && confirmPasswordInput) {
        function validatePasswordMatch() {
            if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        }
        
        passwordInput.addEventListener('input', validatePasswordMatch);
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    }
    
    // Form submission handling
    if (addUserForm) {
        addUserForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="material-symbols-rounded me-1">hourglass_empty</i>Creating...';
        });
    }
});
</script>

<style>
/* Hover effects for Edit and Delete buttons */
.edit-btn {
    transition: all 0.3s ease;
    text-decoration: none;
}

.edit-btn:hover {
    color: #007bff !important;
    text-decoration: none;
    transform: translateY(-1px);
}

.delete-btn {
    transition: all 0.3s ease;
    text-decoration: none;
}

.delete-btn:hover {
    color: #dc3545 !important;
    text-decoration: none;
    transform: translateY(-1px);
}

/* Search and Filter Styling */
.input-group-outline.is-filled .form-label,
.input-group-outline.is-focused .form-label {
    transform: translateY(-0.85rem) scale(0.75);
    transform-origin: 0 0;
    color: #344767;
}

/* Simple and Clean Modal Form Styling for Add and Edit */
#addUserModal .form-label,
#editUserModal .form-label {
    font-weight: 500;
    color: #344767;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

#addUserModal .form-control,
#editUserModal .form-control {
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
    background-color: #fff;
}

#addUserModal .form-control:focus,
#editUserModal .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
}

#addUserModal .form-control::placeholder,
#editUserModal .form-control::placeholder {
    color: #6c757d;
    opacity: 0.7;
}

#addUserModal select.form-control,
#editUserModal select.form-control {
    cursor: pointer;
}

#addUserModal textarea.form-control,
#editUserModal textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

.pagination .page-link {
    border: 1px solid #dee2e6;
    color: #344767;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
    margin: 0 2px;
    border-radius: 0.375rem;
    transition: all 0.15s ease-in;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #344767;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 7px -1px rgba(0, 0, 0, 0.11), 0 2px 4px -1px rgba(0, 0, 0, 0.07);
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Search Results Styling */
.search-results-info {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid #667eea;
}

/* Loading state for search */
.search-loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Enhanced table styling */
.table tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
    transition: background-color 0.15s ease-in;
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

/* Fade out animation */
.custom-alert.fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

/* Filter badges */
.filter-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.375rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    margin-right: 0.25rem;
}

/* Add User Button Styling */
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

/* Add User Modal Styling */
.modal-header.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-bottom: none;
}

/* Form validation styling for both modals */
#addUserModal .form-control.is-invalid,
#editUserModal .form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
}

#addUserModal .form-control.is-valid,
#editUserModal .form-control.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
}

#addUserModal .invalid-feedback,
#editUserModal .invalid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #dc3545;
    font-weight: 500;
}

#addUserModal .valid-feedback,
#editUserModal .valid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #28a745;
    font-weight: 500;
}

/* Password strength indicator */
.password-strength {
    height: 4px;
    border-radius: 2px;
    margin-top: 5px;
    transition: all 0.3s ease;
}

.password-strength.weak {
    background: linear-gradient(90deg, #dc3545 0%, #dc3545 33%, #f8f9fa 33%, #f8f9fa 100%);
}

.password-strength.medium {
    background: linear-gradient(90deg, #ffc107 0%, #ffc107 66%, #f8f9fa 66%, #f8f9fa 100%);
}

.password-strength.strong {
    background: linear-gradient(90deg, #28a745 0%, #28a745 100%);
}

/* Modal animation improvements */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: translate(0, -50px);
}

.modal.show .modal-dialog {
    transform: none;
}

/* Form section headers */
#addUserModal h6 {
    color: #344767;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

/* Switch styling improvements */
#addUserModal .form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

#addUserModal .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

/* Required field indicator */
#addUserModal .form-label:after {
    content: "";
}

#addUserModal .input-group-outline .form-label:contains("*"):after {
    content: " *";
    color: #dc3545;
    font-weight: bold;
}

/* Improved spacing */
#addUserModal .modal-body {
    padding: 2rem;
}

#addUserModal .row + .row {
    margin-top: 1.5rem;
}

/* Button improvements */
#addUserModal .modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e9ecef;
}

#addUserModal .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
}

/* Form validation feedback */
#addUserModal .form-control.is-invalid {
    border-color: #dc3545;
}

#addUserModal .form-control.is-valid {
    border-color: #28a745;
}

#addUserModal .invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

#addUserModal .valid-feedback {
    display: block;
    color: #28a745;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}
</style>

@endsection
