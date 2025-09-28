@extends('layouts.back')

@section('title', 'Posts')
@section('page-title', 'Posts')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0">Posts Management</h6>
            <div class="text-white">
              <small>Total: {{ $posts->total() }} posts</small>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Search and Filter Section -->
      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Filter Posts</h6>
          <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addPostModal">
            <i class="material-symbols-rounded me-1">add</i>Add New Post
          </button>
        </div>
        
        <form method="GET" action="{{ route('admin.posts') }}" class="row g-3 mb-3">
          <div class="col-md-6">
            <div class="input-group input-group-outline">
              <label class="form-label">Search posts...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn bg-gradient-dark mb-0 w-100">
              <i class="material-symbols-rounded me-1">search</i>Search
            </button>
          </div>
          <div class="col-md-2">
            <a href="{{ route('admin.posts') }}" class="btn btn-outline-secondary mb-0 w-100">
              <i class="material-symbols-rounded me-1">refresh</i>Reset
            </a>
          </div>
        </form>
        
        @if(request()->has('search'))
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <small class="text-muted">
                Showing {{ $posts->count() }} of {{ $posts->total() }} results
                @if(request('search'))
                  for "<strong>{{ request('search') }}</strong>"
                @endif
              </small>
            </div>
            <a href="{{ route('admin.posts') }}" class="btn btn-outline-secondary btn-sm">
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
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Author</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Post Details</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Engagement</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($posts as $index => $post)
                @php
                  // Cycle through available team images for profile pictures
                  $avatarImages = ['team-1.jpg', 'team-2.jpg', 'team-3.jpg', 'team-4.jpg', 'team-5.jpg', 'team-6.jpg'];
                  $avatarImage = $avatarImages[$index % count($avatarImages)];
                @endphp
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ asset('assets/back/img/' . $avatarImage) }}" class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $post->user->name }}">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $post->user->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $post->user->email }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ Str::limit($post->title, 40) }}</p>
                    <p class="text-xs text-secondary mb-0">
                      {{ Str::limit($post->description, 60) }}
                    </p>
                  </td>
                  <td class="align-middle text-center">
                    @if($post->image)
                      <img src="{{ Storage::url($post->image) }}" 
                           class="avatar avatar-lg border-radius-lg" 
                           alt="Post image"
                           onerror="this.src='{{ asset('assets/front/img/demo/home-1.jpg') }}'; this.onerror=null;">
                    @else
                      <img src="{{ asset('assets/front/img/demo/home-1.jpg') }}" 
                           class="avatar avatar-lg border-radius-lg opacity-6" 
                           alt="Default image">
                    @endif
                  </td>
                  <td class="align-middle text-center text-sm">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                      <span class="text-xs font-weight-bold">{{ $post->likes }} Likes</span>
                      <span class="text-xs text-secondary">{{ $post->comments->count() }} Comments</span>
                    </div>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">
                      {{ $post->created_at ? $post->created_at->format('d/m/y') : 'N/A' }}
                    </span>
                  </td>
                  <td class="align-middle text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2 edit-btn" 
                       onclick="editPost({{ $post->id }})" 
                       data-toggle="tooltip" data-original-title="Edit post">
                      Edit
                    </a>
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs delete-btn" 
                       onclick="deletePost({{ $post->id }}, '{{ $post->title }}')" 
                       data-toggle="tooltip" data-original-title="Delete post">
                      Delete
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-4">
                    <p class="text-secondary mb-0">No posts found</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination Section -->
        @if($posts->hasPages())
          <div class="card-footer px-3 py-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted">
                  Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }} of {{ $posts->total() }} results
                </small>
              </div>
              <div>
                {{ $posts->appends(request()->query())->links('back.partials.pagination') }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Add New Post Modal -->
<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white" id="addPostModalLabel">
          <i class="material-symbols-rounded me-2">add</i>Add New Post
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addPostForm" method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Post Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Post Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Author *</label>
                <select class="form-control" name="user_id" id="author_id" required>
                  <option value="">Select Author</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Title *</label>
                <input type="text" class="form-control" name="title" required placeholder="Enter post title">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Image</label>
                <input type="file" class="form-control" name="image" accept="image/*">
                <small class="text-muted">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Post Content -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Post Content</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control" name="description" rows="8" required placeholder="Enter post description"></textarea>
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
            <i class="material-symbols-rounded me-1">add</i>Create Post
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white" id="editPostModalLabel">
          <i class="material-symbols-rounded me-2">edit</i>Edit Post
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editPostForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <!-- Post Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Post Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Author *</label>
                <select class="form-control" name="user_id" id="edit_author_id" required>
                  <option value="">Select Author</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Title *</label>
                <input type="text" class="form-control" name="title" id="edit_title" required placeholder="Enter post title">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Current Image</label>
                <div id="current_image_preview" class="mb-2"></div>
                <input type="file" class="form-control" name="image" accept="image/*">
                <small class="text-muted">Leave empty to keep current image. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Post Content & Stats -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Post Content & Stats</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control" name="description" id="edit_description" rows="6" required placeholder="Enter post description"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="row">
                <div class="col-6">
                  <div class="mb-3">
                    <label class="form-label text-dark">Likes</label>
                    <input type="number" class="form-control" name="likes" id="edit_likes" min="0" placeholder="0">
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label class="form-label text-dark">Comments</label>
                    <input type="text" class="form-control" id="edit_comments_count" readonly placeholder="0">
                    <small class="text-muted">Read only</small>
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

<!-- Delete Post Modal -->
<div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePostModalLabel">Delete Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the post "<strong id="deletePostTitle"></strong>"?</p>
        <p class="text-danger">This action cannot be undone and will also delete all associated comments.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deletePostForm" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete Post</button>
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

// Load users for dropdowns
function loadUsers() {
    fetch('{{ route("admin.posts.users") }}')
        .then(response => response.json())
        .then(users => {
            const userSelects = ['author_id', 'edit_author_id'];
            userSelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Select Author</option>';
                    users.forEach(user => {
                        select.innerHTML += `<option value="${user.id}">${user.name} (${user.email})</option>`;
                    });
                }
            });
        })
        .catch(error => console.error('Error loading users:', error));
}

// Edit post function
function editPost(id) {
    fetch(`{{ url('admin/posts') }}/${id}/data`)
        .then(response => response.json())
        .then(post => {
            document.getElementById('edit_author_id').value = post.user_id;
            document.getElementById('edit_title').value = post.title;
            document.getElementById('edit_description').value = post.description;
            document.getElementById('edit_likes').value = post.likes;
            document.getElementById('edit_comments_count').value = post.comments_count || 0;
            
            // Show current image preview
            const imagePreview = document.getElementById('current_image_preview');
            if (post.image) {
                imagePreview.innerHTML = `<img src="/storage/${post.image}" class="img-thumbnail" style="max-height: 100px;" alt="Current image">`;
            } else {
                imagePreview.innerHTML = '<p class="text-muted">No image uploaded</p>';
            }
            
            document.getElementById('editPostForm').action = `{{ url('admin/posts') }}/${id}`;
            new bootstrap.Modal(document.getElementById('editPostModal')).show();
        })
        .catch(error => {
            console.error('Error loading post:', error);
            alert('Error loading post details');
        });
}

// Delete post function
function deletePost(id, postTitle) {
    document.getElementById('deletePostTitle').textContent = postTitle;
    document.getElementById('deletePostForm').action = `{{ url('admin/posts') }}/${id}`;
    new bootstrap.Modal(document.getElementById('deletePostModal')).show();
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

/* Add Post Button Styling */
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

/* Add Post Modal Styling */
.modal-header.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-bottom: none;
}

/* Simple and Clean Modal Form Styling for Add and Edit */
#addPostModal .form-label,
#editPostModal .form-label {
    font-weight: 500;
    color: #344767;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

#addPostModal .form-control,
#editPostModal .form-control {
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
    background-color: #fff;
}

#addPostModal .form-control:focus,
#editPostModal .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
}

#addPostModal .form-control::placeholder,
#editPostModal .form-control::placeholder {
    color: #6c757d;
    opacity: 0.7;
}

#addPostModal select.form-control,
#editPostModal select.form-control {
    cursor: pointer;
}

#addPostModal textarea.form-control,
#editPostModal textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* Form validation styling for both modals */
#addPostModal .form-control.is-invalid,
#editPostModal .form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
}

#addPostModal .form-control.is-valid,
#editPostModal .form-control.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
}

#addPostModal .invalid-feedback,
#editPostModal .invalid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #dc3545;
    font-weight: 500;
}

#addPostModal .valid-feedback,
#editPostModal .valid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #28a745;
    font-weight: 500;
}

/* Form section headers */
#addPostModal h6,
#editPostModal h6 {
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
#addPostModal .modal-body,
#editPostModal .modal-body {
    padding: 2rem;
}

#addPostModal .row + .row,
#editPostModal .row + .row {
    margin-top: 1.5rem;
}

/* Button improvements */
#addPostModal .modal-footer,
#editPostModal .modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e9ecef;
}

#addPostModal .btn,
#editPostModal .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
}

/* Image preview styling */
.img-thumbnail {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 0.25rem;
}

/* Avatar styling for post images */
.avatar-lg {
    width: 48px;
    height: 48px;
    object-fit: cover;
}
</style>
@endpush

@endsection