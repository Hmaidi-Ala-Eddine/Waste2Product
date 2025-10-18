@extends('layouts.back')

@section('title', 'Eco Ideas')
@section('page-title', 'Eco Ideas')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0">Eco Ideas Management</h6>
            <div class="text-white">
              <small>Total: {{ $ideas->total() }} ideas</small>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Filter Ideas</h6>
          <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addIdeaModal">
            <i class="material-symbols-rounded me-1">add</i>Add New Idea
          </button>
        </div>

        <form method="GET" action="{{ route('admin.eco-ideas') }}" class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="input-group input-group-outline">
              <label class="form-label">Search ideas...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="waste_type">
                <option value="all" {{ request('waste_type') == 'all' ? 'selected' : '' }}>All Waste Types</option>
                @foreach(['organic','plastic','metal','e-waste','paper','glass','textile','mixed'] as $type)
                  <option value="{{ $type }}" {{ request('waste_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="difficulty_level">
                <option value="all" {{ request('difficulty_level') == 'all' ? 'selected' : '' }}>All Difficulty</option>
                @foreach(['easy','medium','hard'] as $lvl)
                  <option value="{{ $lvl }}" {{ request('difficulty_level') == $lvl ? 'selected' : '' }}>{{ ucfirst($lvl) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group input-group-outline">
              <select class="form-control" name="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                @foreach(['pending','approved','rejected'] as $st)
                  <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn bg-gradient-dark mb-0 w-100">
              <i class="material-symbols-rounded me-1">search</i>Search
            </button>
          </div>
        </form>
      </div>

      <div class="card-body px-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Waste</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Difficulty</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Upvotes</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Owner</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ideas as $idea)
                <tr>
                  <td class="align-middle text-center">
                    @if($idea->image_path && file_exists(public_path('storage/' . $idea->image_path)))
                      <img src="{{ asset('storage/' . $idea->image_path) }}" 
                           class="avatar avatar-lg border-radius-lg" 
                           alt="Eco idea image"
                           onerror="this.src='{{ asset('assets/back/img/team-2.jpg') }}'; this.onerror=null;">
                    @else
                      <img src="{{ asset('assets/back/img/team-2.jpg') }}" 
                           class="avatar avatar-lg border-radius-lg opacity-6" 
                           alt="Default image">
                    @endif
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <h6 class="mb-0 text-sm">{{ $idea->title }}</h6>
                      <p class="text-xs text-secondary mb-0">{{ Str::limit($idea->description, 60) }}</p>
                    </div>
                  </td>
                  <td>{{ ucfirst($idea->waste_type) }}</td>
                  <td class="text-center">{{ ucfirst($idea->difficulty_level) }}</td>
                  <td class="text-center">{{ ucfirst($idea->status) }}</td>
                  <td class="text-center">{{ $idea->upvotes }}</td>
                  <td class="text-center">{{ $idea->user->name ?? 'Unknown' }}</td>
                  <td class="text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2" onclick="editIdea({{ $idea->id }})">Edit</a>
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs" onclick="deleteIdea({{ $idea->id }}, '{{ $idea->title }}')">Delete</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-4">
                    <p class="text-secondary mb-0">No ideas found</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($ideas->hasPages())
          <div class="card-footer px-3 py-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted">Showing {{ $ideas->firstItem() }} to {{ $ideas->lastItem() }} of {{ $ideas->total() }} results</small>
              </div>
              <div>
                {{ $ideas->links('back.partials.pagination') }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Add Idea Modal -->
<div class="modal fade" id="addIdeaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">add</i>Add Eco Idea</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addIdeaForm" method="POST" action="{{ route('admin.eco-ideas.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Basic Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Owner *</label>
                <select class="form-control" name="user_id" id="idea_owner_id" required>
                  <option value="">Select Owner</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Title *</label>
                <input class="form-control" type="text" name="title" required placeholder="Enter idea title" />
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Waste Type *</label>
                <select class="form-control" name="waste_type" required>
                  @foreach(['organic','plastic','metal','e-waste','paper','glass','textile','mixed'] as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Difficulty *</label>
                <select class="form-control" name="difficulty_level" required>
                  @foreach(['easy','medium','hard'] as $lvl)
                    <option value="{{ $lvl }}">{{ ucfirst($lvl) }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status *</label>
                <select class="form-control" name="status" required>
                  @foreach(['pending','approved','rejected'] as $st)
                    <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Additional Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Additional Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Image</label>
                <input type="file" class="form-control" name="image" accept="image/*" />
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Upvotes</label>
                <input class="form-control" type="number" name="upvotes" value="0" min="0" placeholder="Enter upvotes count" />
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Full Width Fields -->
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control" name="description" rows="3" required placeholder="Enter detailed description of the eco idea"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">AI Suggestion</label>
                <textarea class="form-control" name="ai_generated_suggestion" rows="2" placeholder="Enter AI-generated suggestions or improvements"></textarea>
                <div class="invalid-feedback"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn bg-gradient-success">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Idea Modal -->
<div class="modal fade" id="editIdeaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">edit</i>Edit Eco Idea</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editIdeaForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Basic Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Owner *</label>
                <select class="form-control" name="user_id" id="edit_idea_owner_id" required>
                  <option value="">Select Owner</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Title *</label>
                <input class="form-control" type="text" name="title" id="edit_title" required placeholder="Enter idea title" />
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Waste Type *</label>
                <select class="form-control" name="waste_type" id="edit_waste_type" required>
                  @foreach(['organic','plastic','metal','e-waste','paper','glass','textile','mixed'] as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Difficulty *</label>
                <select class="form-control" name="difficulty_level" id="edit_difficulty_level" required>
                  @foreach(['easy','medium','hard'] as $lvl)
                    <option value="{{ $lvl }}">{{ ucfirst($lvl) }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status *</label>
                <select class="form-control" name="status" id="edit_status" required>
                  @foreach(['pending','approved','rejected'] as $st)
                    <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Additional Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Additional Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Replace Image</label>
                <div id="current_idea_image_preview" class="mb-2"></div>
                <input type="file" class="form-control" name="image" accept="image/*" />
                <small class="text-muted">Leave empty to keep current image. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Upvotes</label>
                <input class="form-control" type="number" name="upvotes" id="edit_upvotes" min="0" placeholder="Enter upvotes count" />
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Full Width Fields -->
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control" name="description" id="edit_description" rows="3" required placeholder="Enter detailed description of the eco idea"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">AI Suggestion</label>
                <textarea class="form-control" name="ai_generated_suggestion" id="edit_ai_suggestion" rows="2" placeholder="Enter AI-generated suggestions or improvements"></textarea>
                <div class="invalid-feedback"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn bg-gradient-dark">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Idea Modal -->
<div class="modal fade" id="deleteIdeaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Idea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete "<strong id="deleteIdeaTitle"></strong>"?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteIdeaForm" method="POST" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('styles')
<style>
/* Clean Form Controls Styling - Match User Forms */
.form-control {
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
    background-color: #fff;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
}

.form-control::placeholder {
    color: #6c757d;
    opacity: 0.7;
}

select.form-control {
    cursor: pointer;
}

/* Simple and Clean Modal Form Styling for Add and Edit */
#addIdeaModal .form-label,
#editIdeaModal .form-label {
    font-weight: 500;
    color: #344767;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

#addIdeaModal .form-control,
#editIdeaModal .form-control {
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
    background-color: #fff;
}

#addIdeaModal .form-control:focus,
#editIdeaModal .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
}

#addIdeaModal .form-control::placeholder,
#editIdeaModal .form-control::placeholder {
    color: #6c757d;
    opacity: 0.7;
}

#addIdeaModal select.form-control,
#editIdeaModal select.form-control {
    cursor: pointer;
}

#addIdeaModal textarea.form-control,
#editIdeaModal textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Form section headers */
#addIdeaModal h6,
#editIdeaModal h6 {
    color: #344767;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

/* Form validation feedback */
#addIdeaModal .form-control.is-invalid,
#editIdeaModal .form-control.is-invalid {
    border-color: #dc3545;
}

#addIdeaModal .form-control.is-valid,
#editIdeaModal .form-control.is-valid {
    border-color: #28a745;
}

#addIdeaModal .invalid-feedback,
#editIdeaModal .invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

#addIdeaModal .valid-feedback,
#editIdeaModal .valid-feedback {
    display: block;
    color: #28a745;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  loadUsers();
});

function loadUsers(){
  fetch('/admin/products/users')
    .then(r => r.json())
    .then(users => {
      const selects = ['idea_owner_id','edit_idea_owner_id'];
      selects.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = '<option value="">Select Owner</option>';
        users.forEach(u => el.innerHTML += `<option value="${u.id}">${u.name} (${u.email})</option>`);
      });
    });
}

function editIdea(id){
  fetch(`/admin/eco-ideas/${id}/data`).then(r=>r.json()).then(idea =>{
    document.getElementById('edit_idea_owner_id').value = idea.user_id;
    document.getElementById('edit_title').value = idea.title;
    document.getElementById('edit_description').value = idea.description;
    document.getElementById('edit_waste_type').value = idea.waste_type;
    document.getElementById('edit_difficulty_level').value = idea.difficulty_level;
    document.getElementById('edit_status').value = idea.status;
    document.getElementById('edit_upvotes').value = idea.upvotes || 0;
    document.getElementById('edit_ai_suggestion').value = idea.ai_generated_suggestion || '';
    
    // Show current image preview
    const imagePreview = document.getElementById('current_idea_image_preview');
    if (idea.image_path) {
        imagePreview.innerHTML = `<img src="/storage/${idea.image_path}" class="img-thumbnail" style="max-height: 100px;" alt="Current image">`;
    } else {
        imagePreview.innerHTML = '<p class="text-muted">No image uploaded</p>';
    }
    
    document.getElementById('editIdeaForm').action = `/admin/eco-ideas/${id}`;
    new bootstrap.Modal(document.getElementById('editIdeaModal')).show();
  });
}

function deleteIdea(id, title){
  document.getElementById('deleteIdeaTitle').textContent = title;
  document.getElementById('deleteIdeaForm').action = `/admin/eco-ideas/${id}`;
  new bootstrap.Modal(document.getElementById('deleteIdeaModal')).show();
}
</script>
@endpush

@endsection






