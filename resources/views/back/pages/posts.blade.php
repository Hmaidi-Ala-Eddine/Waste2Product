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
          <div class="d-flex gap-2">
            <div class="btn-group me-2" role="group" aria-label="View toggle">
              <button type="button" class="btn btn-outline-dark btn-sm" id="postsListBtn" title="List view">
                <i class="material-symbols-rounded align-middle">view_list</i>
              </button>
              <button type="button" class="btn btn-outline-dark btn-sm" id="postsGridBtn" title="Grid view">
                <i class="material-symbols-rounded align-middle">grid_view</i>
              </button>
            </div>
            <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addPostModal">
              <i class="material-symbols-rounded me-1">add</i>Add New Post
            </button>
          </div>
        </div>
        
        <form method="GET" action="{{ route('admin.posts') }}" class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="input-group input-group-outline">
              <label class="form-label">Search posts...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="author">
                <option value="all" {{ request('author') == 'all' ? 'selected' : '' }}>All Authors</option>
                <!-- Authors will be populated via JavaScript -->
              </select>
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
        
        @if(request()->hasAny(['search', 'status', 'author']))
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
        <div id="postsListView" class="table-responsive p-0">
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
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ $post->user->profile_picture_url }}" class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $post->user->name }}" style="object-fit: cover;">
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
                    @if($post->image && trim($post->image) !== '')
                      <img src="{{ asset('storage/' . $post->image) }}" 
                           class="avatar avatar-lg border-radius-lg" 
                           alt="Post image"
                           style="object-fit: cover;"
                           loading="lazy"
                           onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                      <div style="display: none; width: 74px; height: 74px; border-radius: 0.75rem; background: #f8f9fa; align-items: center; justify-content: center; border: 2px dashed #dee2e6;">
                        <i class="material-symbols-rounded text-secondary">image_not_supported</i>
                      </div>
                    @else
                      <div style="display: flex; width: 74px; height: 74px; border-radius: 0.75rem; background: #f8f9fa; align-items: center; justify-content: center; border: 2px dashed #dee2e6; margin: 0 auto;">
                        <i class="material-symbols-rounded text-secondary">add_photo_alternate</i>
                      </div>
                    @endif
                  </td>
                  <td class="align-middle text-center text-sm">
                    <div class="d-flex flex-column justify-content-center align-items-center gap-1">
                      <a href="javascript:;" class="engagement-link-likes text-xs font-weight-bold" 
                         onclick="showLikes({{ $post->id }}, '{{ addslashes($post->title) }}')"
                         data-toggle="tooltip" 
                         data-original-title="View likes"
                         style="cursor: pointer; text-decoration: none; color: #e74c3c;">
                        <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">favorite</i>
                        {{ $post->likes }} Likes
                      </a>
                      <a href="javascript:;" class="engagement-link-comments text-xs text-secondary" 
                         onclick="showComments({{ $post->id }}, '{{ addslashes($post->title) }}')"
                         data-toggle="tooltip" 
                         data-original-title="View comments"
                         style="cursor: pointer; text-decoration: none;">
                        <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">comment</i>
                        {{ $post->comments->count() }} Comments
                      </a>
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

        <!-- Grid View -->
        <div id="postsGridView" class="d-none px-3 pb-3">
          <div class="row g-3">
            @foreach($posts as $index => $post)
              <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-sm border-0 h-100 post-card">
                  <div class="ratio ratio-16x9 card-img-top bg-light overflow-hidden">
                    @if($post->image && trim($post->image) !== '')
                      <img src="{{ asset('storage/' . $post->image) }}" class="object-fit-cover w-100 h-100" alt="post" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                      <div style="display: none; width: 100%; height: 100%; background: #f8f9fa; align-items: center; justify-content: center;">
                        <i class="material-symbols-rounded text-secondary" style="font-size: 3rem;">image_not_supported</i>
                      </div>
                    @else
                      <div style="display: flex; width: 100%; height: 100%; background: #f8f9fa; align-items: center; justify-content: center;">
                        <i class="material-symbols-rounded text-secondary" style="font-size: 3rem;">add_photo_alternate</i>
                      </div>
                    @endif
                  </div>
                  <div class="card-body">
                    <small class="text-muted d-block mb-1">By {{ $post->user->name }}</small>
                    <h6 class="card-title mb-2">{{ Str::limit($post->title, 50) }}</h6>
                    <p class="card-text text-secondary mb-3">{{ Str::limit($post->description, 90) }}</p>
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="d-flex align-items-center gap-2">
                        <a href="javascript:;" class="badge bg-light text-dark engagement-badge" 
                           onclick="showLikes({{ $post->id }}, '{{ addslashes($post->title) }}')"
                           style="cursor: pointer; text-decoration: none;">
                          <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle; color: #e74c3c;">favorite</i>
                          {{ $post->likes }}
                        </a>
                        <a href="javascript:;" class="badge bg-light text-dark engagement-badge" 
                           onclick="showComments({{ $post->id }}, '{{ addslashes($post->title) }}')"
                           style="cursor: pointer; text-decoration: none;">
                          <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle; color: #6c757d;">comment</i>
                          {{ $post->comments->count() }}
                        </a>
                      </div>
                      <div class="btn-group">
                        <a href="javascript:;" class="btn btn-sm btn-outline-dark" onclick="editPost({{ $post->id }})">Edit</a>
                        <a href="javascript:;" class="btn btn-sm btn-outline-danger" onclick="deletePost({{ $post->id }}, '{{ addslashes($post->title) }}')">Delete</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
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
              
              <!-- Author is automatically set to logged-in user -->
              <input type="hidden" name="user_id" value="{{ auth()->id() }}">
              
              <div class="mb-3">
                <label class="form-label text-dark">Author</label>
                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled style="background-color: #e9ecef;">
                <small class="text-muted d-block mt-1">You will be set as the author of this post</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Title *</label>
                <input type="text" class="form-control" name="title" required placeholder="Enter post title">
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Image</label>
                <div class="input-group input-group-outline">
                  <input type="file" class="form-control" name="image" accept="image/*" id="imageUpload">
                </div>
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
                <div class="input-group input-group-outline">
                  <input type="file" class="form-control" name="image" accept="image/*" id="editImageUpload">
                </div>
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

<!-- Likes Modal -->
<div class="modal fade" id="likesModal" tabindex="-1" aria-labelledby="likesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-danger">
        <h5 class="modal-title text-white" id="likesModalLabel">
          <i class="material-symbols-rounded me-2">favorite</i>Post Likes
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
          <h6 class="text-dark font-weight-bold mb-2">Post: <span id="likesPostTitle"></span></h6>
          <p class="text-sm text-secondary mb-0">Total Likes: <span id="likesCount" class="font-weight-bold text-danger">0</span></p>
        </div>
        <hr>
        <div id="likesList" class="mt-3">
          <!-- Loading spinner -->
          <div class="text-center py-4" id="likesLoading">
            <div class="spinner-border text-danger" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2">Loading likes...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="material-symbols-rounded me-1">close</i>Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h5 class="modal-title text-white" id="commentsModalLabel">
          <i class="material-symbols-rounded me-2">comment</i>Post Comments
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
          <h6 class="text-dark font-weight-bold mb-2">Post: <span id="commentsPostTitle"></span></h6>
          <p class="text-sm text-secondary mb-0">Total Comments: <span id="commentsCount" class="font-weight-bold text-info">0</span></p>
        </div>
        <hr>
        <div id="commentsList" class="mt-3">
          <!-- Loading spinner -->
          <div class="text-center py-4" id="commentsLoading">
            <div class="spinner-border text-info" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2">Loading comments...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="material-symbols-rounded me-1">close</i>Close
        </button>
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
    // Restore view preference
    initPostsViewToggle();
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
// View toggle
function initPostsViewToggle(){
  const listBtn = document.getElementById('postsListBtn');
  const gridBtn = document.getElementById('postsGridBtn');
  const listView = document.getElementById('postsListView');
  const gridView = document.getElementById('postsGridView');
  if(!listBtn || !gridBtn || !listView || !gridView) return;

  const pref = localStorage.getItem('postsView') || 'list';
  setPostsView(pref);

  listBtn.addEventListener('click', ()=> setPostsView('list'));
  gridBtn.addEventListener('click', ()=> setPostsView('grid'));

  function setPostsView(mode){
    if(mode === 'grid'){
      listView.classList.add('d-none');
      gridView.classList.remove('d-none');
      gridBtn.classList.add('active');
      listBtn.classList.remove('active');
      localStorage.setItem('postsView','grid');
    } else {
      gridView.classList.add('d-none');
      listView.classList.remove('d-none');
      listBtn.classList.add('active');
      gridBtn.classList.remove('active');
      localStorage.setItem('postsView','list');
    }
  }
}

// Show Likes function
function showLikes(postId, postTitle) {
    document.getElementById('likesPostTitle').textContent = postTitle;
    document.getElementById('likesList').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div><p class="text-muted mt-2">Loading likes...</p></div>';
    
    const modalElement = document.getElementById('likesModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
    
    fetch(`{{ url('admin/posts') }}/${postId}/likes`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to load likes');
            return response.json();
        })
        .then(data => {
            const likes = data.likes || [];
            document.getElementById('likesCount').textContent = likes.length;
            
            const likesList = document.getElementById('likesList');
            if (likes.length === 0) {
                likesList.innerHTML = '<div class="text-center py-5"><i class="material-symbols-rounded text-secondary" style="font-size: 4rem;">heart_broken</i><p class="text-muted mt-3">No likes yet</p></div>';
            } else {
                let html = '<div class="row g-3">';
                likes.forEach(like => {
                    html += `
                        <div class="col-12 col-md-6">
                            <div class="like-card d-flex align-items-center p-3 border rounded-3 shadow-sm">
                                <div class="me-3">
                                    <img src="${like.profile_picture_url || '/default-avatar.png'}" 
                                         class="rounded-circle" 
                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                         alt="${like.name}">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-dark font-weight-bold">${like.name}</h6>
                                    <p class="mb-0 text-sm text-secondary">${like.email}</p>
                                </div>
                                ${like.liked_at ? `<div class="text-end"><small class="text-muted">${like.liked_at}</small></div>` : ''}
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                likesList.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('likesList').innerHTML = '<div class="alert alert-danger text-center"><i class="material-symbols-rounded me-2">error</i>Failed to load likes</div>';
        });
}

// Show Comments function
function showComments(postId, postTitle) {
    document.getElementById('commentsPostTitle').textContent = postTitle;
    document.getElementById('commentsList').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div><p class="text-muted mt-2">Loading comments...</p></div>';
    
    const modalElement = document.getElementById('commentsModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
    
    fetch(`{{ url('admin/posts') }}/${postId}/comments`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to load comments');
            return response.json();
        })
        .then(data => {
            const comments = data.comments || [];
            document.getElementById('commentsCount').textContent = comments.length;
            
            const commentsList = document.getElementById('commentsList');
            if (comments.length === 0) {
                commentsList.innerHTML = '<div class="text-center py-5"><i class="material-symbols-rounded text-secondary" style="font-size: 4rem;">comment_off</i><p class="text-muted mt-3">No comments yet</p></div>';
            } else {
                let html = '<div class="list-group">';
                comments.forEach(comment => {
                    html += `
                        <div class="list-group-item comment-item mb-3 border rounded-3 shadow-sm">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <img src="${comment.user.profile_picture_url || '/default-avatar.png'}" 
                                         class="rounded-circle" 
                                         style="width: 45px; height: 45px; object-fit: cover;" 
                                         alt="${comment.user.name}">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-0 text-dark font-weight-bold">${comment.user.name}</h6>
                                            <small class="text-muted">${comment.created_at}</small>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-dark">${comment.content}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                commentsList.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('commentsList').innerHTML = '<div class="alert alert-danger text-center"><i class="material-symbols-rounded me-2">error</i>Failed to load comments</div>';
        });
}

// Reset modals when closed
const likesModalEl = document.getElementById('likesModal');
const commentsModalEl = document.getElementById('commentsModal');

if (likesModalEl) {
    likesModalEl.addEventListener('hidden.bs.modal', function () {
        document.getElementById('likesPostTitle').textContent = '';
        document.getElementById('likesCount').textContent = '0';
        document.getElementById('likesList').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div><p class="text-muted mt-2">Loading likes...</p></div>';
    });
}

if (commentsModalEl) {
    commentsModalEl.addEventListener('hidden.bs.modal', function () {
        document.getElementById('commentsPostTitle').textContent = '';
        document.getElementById('commentsCount').textContent = '0';
        document.getElementById('commentsList').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div><p class="text-muted mt-2">Loading comments...</p></div>';
    });
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

/* Grid cards */
.post-card .card-img-top img{ display:block; }
.object-fit-cover{ object-fit: cover; }
.btn-group .btn.active{ background:#344767; color:#fff; }

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

/* Engagement links styling */
.engagement-link-likes,
.engagement-link-comments {
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 6px;
}

.engagement-link-likes:hover {
    background-color: rgba(231, 76, 60, 0.1);
    transform: translateY(-1px);
    text-decoration: none !important;
    color: #e74c3c !important;
}

.engagement-link-comments:hover {
    background-color: rgba(108, 117, 125, 0.1);
    transform: translateY(-1px);
    text-decoration: none !important;
    color: #495057 !important;
}

/* Grid view engagement badges */
.engagement-badge {
    transition: all 0.2s ease;
}

.engagement-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Like and Comment card styling */
.like-card,
.comment-item {
    transition: all 0.2s ease;
    background-color: #fff;
    border: 1px solid #e9ecef !important;
}

.like-card:hover,
.comment-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    transform: translateY(-2px);
    border-color: #d2d6da !important;
}

/* Likes modal styling */
#likesModal .modal-header.bg-gradient-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    border-bottom: none;
}

#likesList {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 8px;
}

#likesList::-webkit-scrollbar {
    width: 8px;
}

#likesList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#likesList::-webkit-scrollbar-thumb {
    background: #e74c3c;
    border-radius: 10px;
}

#likesList::-webkit-scrollbar-thumb:hover {
    background: #c0392b;
}

/* Comments modal styling */
#commentsModal .modal-header.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border-bottom: none;
}

#commentsList {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 8px;
}

#commentsList::-webkit-scrollbar {
    width: 8px;
}

#commentsList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#commentsList::-webkit-scrollbar-thumb {
    background: #17a2b8;
    border-radius: 10px;
}

#commentsList::-webkit-scrollbar-thumb:hover {
    background: #138496;
}

.comment-item {
    border: 1px solid #e9ecef;
    background-color: #fff;
}
</style>
@endpush

@endsection