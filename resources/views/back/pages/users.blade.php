@extends('layouts.back')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show auto-dismiss-alert" role="alert" style="background-color: #4CAF50; border-color: #4CAF50;">
    <span class="text-white"><strong>Success!</strong> {{ session('success') }}</span>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('delete_success'))
  <div class="alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert" style="background-color: #dc3545; border-color: #dc3545;">
    <span class="text-white"><strong>Deleted!</strong> {{ session('delete_success') }}</span>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert" style="background-color: #f44336; border-color: #f44336;">
    <span class="text-white"><strong>Error!</strong> {{ session('error') }}</span>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <h6 class="text-white text-capitalize ps-3">Users Management</h6>
        </div>
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
              
              <div class="input-group input-group-outline mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" id="editName" name="name" required>
              </div>
              
              <div class="input-group input-group-outline mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="editUsername" name="username">
              </div>
              
              <div class="input-group input-group-outline mb-3">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" id="editFirstName" name="first_name">
              </div>
              
              <div class="input-group input-group-outline mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" id="editLastName" name="last_name">
              </div>
            </div>
            
            <!-- Contact & Account -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Contact & Account</h6>
              
              <div class="input-group input-group-outline mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" id="editEmail" name="email" required>
              </div>
              
              <div class="input-group input-group-outline mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="editPhone" name="phone">
              </div>
              
              <div class="input-group input-group-outline mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" id="editAddress" name="address" rows="3"></textarea>
              </div>
              
              <div class="input-group input-group-outline mb-3">
                <label class="form-label">Role</label>
                <select class="form-control" id="editRole" name="role" required>
                  <option value="">Select Role</option>
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                  <option value="moderator">Moderator</option>
                </select>
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

// Handle floating labels
document.addEventListener('DOMContentLoaded', function() {
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
</style>

@endsection
