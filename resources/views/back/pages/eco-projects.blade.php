@extends('layouts.back')

@section('title', 'Eco Projects')
@section('page-title', 'Eco Projects')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0">Eco Projects Management</h6>
            <div class="text-white">
              <small>Total: {{ $projects->total() }} projects</small>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Filter Projects</h6>
          <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addProjectModal">
            <i class="material-symbols-rounded me-1">add</i>Add New Project
          </button>
        </div>

        <form method="GET" action="{{ route('admin.eco-projects') }}" class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="input-group input-group-outline">
              <label class="form-label">Search projects...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-4">
            <div class="input-group input-group-outline">
              <select class="form-control" name="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                @foreach(['prototype','testing','completed','failed'] as $st)
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
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Idea</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Owner</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Completion</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($projects as $project)
                <tr>
                  <td class="align-middle text-center">
                    @if($project->image_path && file_exists(public_path('storage/' . $project->image_path)))
                      <img src="{{ asset('storage/' . $project->image_path) }}" 
                           class="avatar avatar-lg border-radius-lg" 
                           alt="Eco project image"
                           onerror="this.src='{{ asset('assets/back/img/team-2.jpg') }}'; this.onerror=null;">
                    @else
                      <img src="{{ asset('assets/back/img/team-2.jpg') }}" 
                           class="avatar avatar-lg border-radius-lg opacity-6" 
                           alt="Default image">
                    @endif
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <h6 class="mb-0 text-sm">{{ $project->name }}</h6>
                      <p class="text-xs text-secondary mb-0">{{ Str::limit($project->description, 60) }}</p>
                    </div>
                  </td>
                  <td>{{ $project->ecoIdea->title ?? 'N/A' }}</td>
                  <td class="text-center">{{ ucfirst($project->status) }}</td>
                  <td class="text-center">{{ $project->user->name ?? 'Unknown' }}</td>
                  <td class="text-center">{{ $project->completion_date ?: '—' }}</td>
                  <td class="text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2" onclick="editProject({{ $project->id }})">Edit</a>
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs" onclick="deleteProject({{ $project->id }}, '{{ $project->name }}')">Delete</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4"><p class="text-secondary mb-0">No projects found</p></td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($projects->hasPages())
          <div class="card-footer px-3 py-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted">Showing {{ $projects->firstItem() }} to {{ $projects->lastItem() }} of {{ $projects->total() }} results</small>
              </div>
              <div>
                {{ $projects->links('back.partials.pagination') }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">add</i>Add Eco Project</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addProjectForm" method="POST" action="{{ route('admin.eco-projects.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Basic Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Idea *</label>
                <select class="form-control" name="eco_idea_id" id="project_idea_id" required>
                  <option value="">Select Idea</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Owner *</label>
                <select class="form-control" name="user_id" id="project_owner_id" required>
                  <option value="">Select Owner</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Name *</label>
                <input class="form-control" type="text" name="name" required placeholder="Enter project name" />
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status *</label>
                <select class="form-control" name="status" required>
                  @foreach(['prototype','testing','completed','failed'] as $st)
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
                <label class="form-label text-dark">Completion Date</label>
                <input class="form-control" type="date" name="completion_date" placeholder="Select completion date" />
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Full Width Fields -->
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control" name="description" rows="3" required placeholder="Enter detailed description of the eco project"></textarea>
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

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">edit</i>Edit Eco Project</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editProjectForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Basic Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Idea *</label>
                <select class="form-control" name="eco_idea_id" id="edit_project_idea_id" required>
                  <option value="">Select Idea</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Owner *</label>
                <select class="form-control" name="user_id" id="edit_project_owner_id" required>
                  <option value="">Select Owner</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Name *</label>
                <input class="form-control" type="text" name="name" id="edit_name" required placeholder="Enter project name" />
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status *</label>
                <select class="form-control" name="status" id="edit_status" required>
                  @foreach(['prototype','testing','completed','failed'] as $st)
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
                <div id="current_project_image_preview" class="mb-2"></div>
                <input type="file" class="form-control" name="image" accept="image/*" />
                <small class="text-muted">Leave empty to keep current image. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Completion Date</label>
                <input class="form-control" type="date" name="completion_date" id="edit_completion_date" placeholder="Select completion date" />
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Full Width Fields -->
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control" name="description" id="edit_description" rows="3" required placeholder="Enter detailed description of the eco project"></textarea>
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

<!-- Delete Project Modal -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete "<strong id="deleteProjectName"></strong>"?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteProjectForm" method="POST" style="display:inline;">
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
#addProjectModal .form-label,
#editProjectModal .form-label {
    font-weight: 500;
    color: #344767;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

#addProjectModal .form-control,
#editProjectModal .form-control {
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
    background-color: #fff;
}

#addProjectModal .form-control:focus,
#editProjectModal .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    outline: none;
}

#addProjectModal .form-control::placeholder,
#editProjectModal .form-control::placeholder {
    color: #6c757d;
    opacity: 0.7;
}

#addProjectModal select.form-control,
#editProjectModal select.form-control {
    cursor: pointer;
}

#addProjectModal textarea.form-control,
#editProjectModal textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Form section headers */
#addProjectModal h6,
#editProjectModal h6 {
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
#addProjectModal .form-control.is-invalid,
#editProjectModal .form-control.is-invalid {
    border-color: #dc3545;
}

#addProjectModal .form-control.is-valid,
#editProjectModal .form-control.is-valid {
    border-color: #28a745;
}

#addProjectModal .invalid-feedback,
#editProjectModal .invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

#addProjectModal .valid-feedback,
#editProjectModal .valid-feedback {
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
  loadUsersAndIdeas();
});

function loadUsersAndIdeas(){
  // Users for owner selects
  fetch('/admin/products/users').then(r=>r.json()).then(users =>{
    ['project_owner_id','edit_project_owner_id'].forEach(id=>{
      const el = document.getElementById(id); if(!el) return;
      el.innerHTML = '<option value="">Select Owner</option>';
      users.forEach(u => el.innerHTML += `<option value="${u.id}">${u.name} (${u.email})</option>`);
    });
  });
  // Ideas for project relation
  fetch('/admin/eco-ideas?page=1')
    .then(()=> fetch('/api/eco-ideas'))
    .then(r=>r.json())
    .then(paged => {
      const ideas = paged.data || [];
      ['project_idea_id','edit_project_idea_id'].forEach(id=>{
        const el = document.getElementById(id); if(!el) return;
        el.innerHTML = '<option value="">Select Idea</option>';
        ideas.forEach(i => el.innerHTML += `<option value="${i.id}">${i.title}</option>`);
      });
    });
}

function editProject(id){
  fetch(`/admin/eco-projects/${id}/data`).then(r=>r.json()).then(project =>{
    document.getElementById('edit_project_owner_id').value = project.user_id;
    document.getElementById('edit_project_idea_id').value = project.eco_idea_id;
    document.getElementById('edit_name').value = project.name;
    document.getElementById('edit_status').value = project.status;
    document.getElementById('edit_description').value = project.description;
    document.getElementById('edit_completion_date').value = project.completion_date || '';
    
    // Show current image preview
    const imagePreview = document.getElementById('current_project_image_preview');
    if (project.image_path) {
        imagePreview.innerHTML = `<img src="/storage/${project.image_path}" class="img-thumbnail" style="max-height: 100px;" alt="Current image">`;
    } else {
        imagePreview.innerHTML = '<p class="text-muted">No image uploaded</p>';
    }
    
    document.getElementById('editProjectForm').action = `/admin/eco-projects/${id}`;
    new bootstrap.Modal(document.getElementById('editProjectModal')).show();
  });
}

function deleteProject(id, name){
  document.getElementById('deleteProjectName').textContent = name;
  document.getElementById('deleteProjectForm').action = `/admin/eco-projects/${id}`;
  new bootstrap.Modal(document.getElementById('deleteProjectModal')).show();
}
</script>
@endpush

@endsection






