@extends('layouts.back')

@section('title', 'Eco Ideas Management')
@section('page-title', 'Eco Ideas')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3">Eco Ideas Management</h6>
          </div>
        </div>
        
        <!-- Search Section -->
        <div class="card-body px-3 pt-3 pb-0">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-dark">Search & Manage Eco Ideas</h6>
            <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addIdeaModal">
              <i class="material-symbols-rounded me-1">add</i>Add New Eco Idea
            </button>
          </div>
          
          <form method="GET" action="{{ route('admin.eco-ideas') }}" class="row g-3 mb-3">
            <div class="col-md-3">
              <div class="input-group input-group-outline">
                <label class="form-label">Search ideas...</label>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}">
              </div>
            </div>
            <div class="col-md-2">
              <select class="form-control" name="waste_type">
                <option value="all" {{ request('waste_type') == 'all' ? 'selected' : '' }}>All Waste Types</option>
                @foreach(\App\Models\EcoIdea::getWasteTypes() as $key => $value)
                  <option value="{{ $key }}" {{ request('waste_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <select class="form-control" name="difficulty">
                <option value="all" {{ request('difficulty') == 'all' ? 'selected' : '' }}>All Difficulty</option>
                @foreach(\App\Models\EcoIdea::getDifficulties() as $key => $value)
                  <option value="{{ $key }}" {{ request('difficulty') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <select class="form-control" name="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                @foreach(\App\Models\EcoIdea::getStatuses() as $key => $value)
                  <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn bg-gradient-dark mb-0 w-100">
                <i class="material-symbols-rounded me-1">search</i>Search
              </button>
            </div>
            <div class="col-md-1">
              <a href="{{ route('admin.eco-ideas') }}" class="btn btn-outline-secondary mb-0 w-100">
                <i class="material-symbols-rounded me-1">refresh</i>
              </a>
            </div>
          </form>
          
          @if(request()->hasAny(['search', 'waste_type', 'difficulty', 'status']))
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <small class="text-muted">
                  Showing {{ $ecoIdeas->count() }} of {{ $ecoIdeas->total() }} results
                  @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                  @endif
                </small>
              </div>
              <a href="{{ route('admin.eco-ideas') }}" class="btn btn-outline-secondary btn-sm">
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
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">IMAGE</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">TITLE</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">WASTE</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DIFFICULTY</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STATUS</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TEAM</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">CREATOR</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACTIONS</th>
                </tr>
              </thead>
              <tbody>
                @forelse($ecoIdeas as $idea)
                  <tr>
                    <td class="align-middle text-center">
                      @if($idea->image)
                        <img src="{{ $idea->image_url }}" alt="{{ $idea->title }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                      @else
                        <div style="width: 50px; height: 50px; border: 2px dashed #ccc; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                          <i class="material-symbols-rounded text-secondary" style="font-size: 24px;">image</i>
                        </div>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ $idea->title }}</h6>
                          <p class="text-xs text-secondary mb-0">{{ Str::limit($idea->description, 50) }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-info">{{ $idea->waste_type_formatted }}</span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm {{ $idea->difficulty_badge_class }}">{{ $idea->difficulty_formatted }}</span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm {{ $idea->status_badge_class }}">{{ $idea->status_formatted }}</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">{{ $idea->team_size_needed ?? 'N/A' }}</span>
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1 justify-content-center">
                        <div>
                          <img src="{{ $idea->creator->profile_picture_url }}" class="avatar avatar-sm me-2 border-radius-lg" alt="{{ $idea->creator->name }}" style="object-fit: cover;">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ $idea->creator->name }}</h6>
                        </div>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2 action-edit" 
                         onclick="editIdea({{ $idea->id }})" 
                         data-toggle="tooltip" data-original-title="Edit idea">
                        Edit
                      </a>
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs action-delete" 
                         onclick="deleteIdea({{ $idea->id }}, '{{ $idea->title }}')" 
                         data-toggle="tooltip" data-original-title="Delete idea">
                        Delete
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center py-4">
                      <p class="text-secondary mb-0">No eco ideas found</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        
          <!-- Pagination Section -->
          @if($ecoIdeas->hasPages())
            <div class="card-footer px-3 py-3">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <small class="text-muted">
                    Showing {{ $ecoIdeas->firstItem() }} to {{ $ecoIdeas->lastItem() }} of {{ $ecoIdeas->total() }} results
                  </small>
                </div>
                <div>
                  {{ $ecoIdeas->links('back.partials.pagination') }}
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add New Eco Idea Modal -->
<div class="modal fade" id="addIdeaModal" tabindex="-1" aria-labelledby="addIdeaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white" id="addIdeaModalLabel">
          <i class="material-symbols-rounded me-2">add</i>Add Eco Idea
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.eco-ideas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Idea Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3 text-uppercase">Idea Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Creator *</label>
                <select class="form-control border" name="creator_id" id="creator_id" required style="background-color: #f8f9fa;">
                  <option value="">Select Creator</option>
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Title *</label>
                <input type="text" class="form-control border" name="title" required placeholder="Enter idea title" style="background-color: #f8f9fa;">
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Waste Type *</label>
                <select class="form-control border" name="waste_type" required style="background-color: #f8f9fa;">
                  <option value="">Select waste type</option>
                  @foreach(\App\Models\EcoIdea::getWasteTypes() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Difficulty *</label>
                <select class="form-control border" name="difficulty" required style="background-color: #f8f9fa;">
                  <option value="">Select difficulty</option>
                  @foreach(\App\Models\EcoIdea::getDifficulties() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control border" name="description" rows="5" required placeholder="Enter detailed description of the eco idea" style="background-color: #f8f9fa;"></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">AI Suggestion</label>
                <textarea class="form-control border" name="ai_suggestion" rows="3" placeholder="AI-generated suggestions or improvements" style="background-color: #f8f9fa;"></textarea>
              </div>
            </div>
            
            <!-- Team & Configuration -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3 text-uppercase">Team & Configuration</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Team Size Needed</label>
                <input type="number" class="form-control border" name="team_size_needed" min="1" max="100" placeholder="How many team members needed?" style="background-color: #f8f9fa;">
                <small class="text-muted">Leave empty if no team required</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Team Requirements</label>
                <textarea class="form-control border" name="team_requirements" rows="3" placeholder="Describe what skills/roles are needed" style="background-color: #f8f9fa;"></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Application Description</label>
                <textarea class="form-control border" name="application_description" rows="3" placeholder="Instructions for applicants" style="background-color: #f8f9fa;"></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Idea Image</label>
                <div class="custom-file-upload">
                  <input type="file" name="image" id="idea_image" accept="image/*" onchange="displayFileName(this, 'idea_file_name')">
                  <label for="idea_image">
                    <i class="material-symbols-rounded">image</i>
                    Choose Picture
                  </label>
                  <div id="idea_file_name" class="file-name-display"></div>
                </div>
                <small class="text-muted d-block mt-2">Optional - Max 2MB (JPG, PNG, GIF)</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status</label>
                <select class="form-control border" name="status" style="background-color: #f8f9fa;">
                  @foreach(\App\Models\EcoIdea::getStatuses() as $key => $value)
                    <option value="{{ $key }}" {{ $key === 'pending' ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="material-symbols-rounded me-1">close</i>Cancel
          </button>
          <button type="submit" class="btn bg-gradient-success">
            <i class="material-symbols-rounded me-1">add</i>Create Idea
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Eco Idea Modal -->
<div class="modal fade" id="editIdeaModal" tabindex="-1" aria-labelledby="editIdeaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white" id="editIdeaModalLabel">
          <i class="material-symbols-rounded me-2">edit</i>Edit Eco Idea
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editIdeaForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <!-- Idea Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3 text-uppercase">Idea Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Creator *</label>
                <select class="form-control border" name="creator_id" id="edit_creator_id" required style="background-color: #f8f9fa;">
                  <option value="">Select Creator</option>
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Title *</label>
                <input type="text" class="form-control border" name="title" id="edit_title" required placeholder="Enter idea title" style="background-color: #f8f9fa;">
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Waste Type *</label>
                <select class="form-control border" name="waste_type" id="edit_waste_type" required style="background-color: #f8f9fa;">
                  <option value="">Select waste type</option>
                  @foreach(\App\Models\EcoIdea::getWasteTypes() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Difficulty *</label>
                <select class="form-control border" name="difficulty" id="edit_difficulty" required style="background-color: #f8f9fa;">
                  <option value="">Select difficulty</option>
                  @foreach(\App\Models\EcoIdea::getDifficulties() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control border" name="description" id="edit_description" rows="5" required placeholder="Enter detailed description of the eco idea" style="background-color: #f8f9fa;"></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">AI Suggestion</label>
                <textarea class="form-control border" name="ai_suggestion" id="edit_ai_suggestion" rows="3" placeholder="AI-generated suggestions or improvements" style="background-color: #f8f9fa;"></textarea>
              </div>
            </div>
            
            <!-- Team & Configuration -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3 text-uppercase">Team & Configuration</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Team Size Needed</label>
                <input type="number" class="form-control border" name="team_size_needed" id="edit_team_size_needed" min="1" max="100" placeholder="How many team members needed?" style="background-color: #f8f9fa;">
                <small class="text-muted">Leave empty if no team required</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Team Requirements</label>
                <textarea class="form-control border" name="team_requirements" id="edit_team_requirements" rows="3" placeholder="Describe what skills/roles are needed" style="background-color: #f8f9fa;"></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Application Description</label>
                <textarea class="form-control border" name="application_description" id="edit_application_description" rows="3" placeholder="Instructions for applicants" style="background-color: #f8f9fa;"></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Idea Image</label>
                <div class="custom-file-upload">
                  <input type="file" name="image" id="edit_idea_image" accept="image/*" onchange="displayFileName(this, 'edit_idea_file_name')">
                  <label for="edit_idea_image">
                    <i class="material-symbols-rounded">image</i>
                    Choose Picture
                  </label>
                  <div id="edit_idea_file_name" class="file-name-display"></div>
                </div>
                <small class="text-muted d-block mt-2">Leave empty to keep current image</small>
                <div id="edit_current_image_preview" class="mt-2"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Status *</label>
                <select class="form-control border" name="status" id="edit_status" required style="background-color: #f8f9fa;">
                  @foreach(\App\Models\EcoIdea::getStatuses() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
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

<!-- Delete Eco Idea Modal -->
<div class="modal fade" id="deleteIdeaModal" tabindex="-1" aria-labelledby="deleteIdeaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteIdeaModalLabel">Delete Eco Idea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the eco idea "<strong id="deleteIdeaTitle"></strong>"?</p>
        <p class="text-danger">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteIdeaForm" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete Idea</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced form control styling */
.modal .form-control {
  padding: 0.75rem 1rem !important;
  font-size: 0.9375rem;
  line-height: 1.5;
  border-radius: 0.5rem !important;
}

.modal .form-control::placeholder {
  color: #9ca3af;
  font-weight: 400;
}

.modal textarea.form-control {
  padding: 0.875rem 1rem !important;
}

/* Custom focus and hover effects for form inputs */
.modal .form-control:focus {
  border-color: #28a745 !important;
  box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
  background-color: #ffffff !important;
}

.modal .form-control:hover:not(:disabled) {
  border-color: #28a745;
}

/* Custom file upload button styling */
.custom-file-upload {
  position: relative;
  display: inline-block;
  width: 100%;
}

.custom-file-upload input[type="file"] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.custom-file-upload label {
  display: inline-block;
  padding: 0.625rem 1.5rem;
  background-color: white;
  color: #28a745;
  border: 2px solid #28a745;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
  text-align: center;
  width: auto;
  margin-bottom: 0;
  font-size: 0.9375rem;
}

.custom-file-upload label:hover {
  background-color: #28a745;
  color: white;
}

.custom-file-upload label i {
  margin-right: 8px;
}

.file-name-display {
  margin-top: 0.625rem;
  font-size: 0.875rem;
  color: #6c757d;
  font-weight: 400;
}

.modal .form-label {
  margin-bottom: 0.5rem;
  font-weight: 500;
  font-size: 0.875rem;
}

/* Action button hover effects */
.action-edit {
  transition: color 0.2s ease;
}

.action-edit:hover {
  color: #1e90ff !important;
  text-decoration: none;
}

.action-delete {
  transition: color 0.2s ease;
}

.action-delete:hover {
  color: #dc3545 !important;
  text-decoration: none;
}
</style>
@endpush

@push('scripts')
<script>
// Load creators when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadCreators();
});

// Load creators for dropdowns
function loadCreators() {
    fetch('{{ route("admin.eco-ideas.creators") }}')
        .then(response => response.json())
        .then(creators => {
            const creatorSelects = ['creator_id', 'edit_creator_id'];
            creatorSelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Select Creator</option>';
                    creators.forEach(creator => {
                        select.innerHTML += `<option value="${creator.id}">${creator.name} (${creator.email})</option>`;
                    });
                }
            });
        })
        .catch(error => console.error('Error loading creators:', error));
}

// Edit idea function
function editIdea(id) {
    fetch(`/admin/eco-ideas/${id}/data`)
        .then(response => response.json())
        .then(idea => {
            document.getElementById('edit_creator_id').value = idea.creator_id;
            document.getElementById('edit_title').value = idea.title;
            document.getElementById('edit_waste_type').value = idea.waste_type;
            document.getElementById('edit_difficulty').value = idea.difficulty;
            document.getElementById('edit_description').value = idea.description || '';
            document.getElementById('edit_ai_suggestion').value = idea.ai_suggestion || '';
            document.getElementById('edit_team_size_needed').value = idea.team_size_needed || '';
            document.getElementById('edit_team_requirements').value = idea.team_requirements || '';
            document.getElementById('edit_application_description').value = idea.application_description || '';
            document.getElementById('edit_status').value = idea.status;
            
            // Show current image if exists
            const imagePreview = document.getElementById('edit_current_image_preview');
            if (idea.image) {
                imagePreview.innerHTML = `<img src="${idea.image_url}" alt="Current image" style="max-width: 200px; border-radius: 8px;">`;
            } else {
                imagePreview.innerHTML = '';
            }
            
            document.getElementById('editIdeaForm').action = `/admin/eco-ideas/${id}`;
            
            const modal = new bootstrap.Modal(document.getElementById('editIdeaModal'));
            modal.show();
        })
        .catch(error => console.error('Error loading idea:', error));
}

// Delete idea function
function deleteIdea(id, title) {
    document.getElementById('deleteIdeaTitle').textContent = title;
    document.getElementById('deleteIdeaForm').action = `/admin/eco-ideas/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteIdeaModal'));
    modal.show();
}

// Display selected file name
function displayFileName(input, displayId) {
    const display = document.getElementById(displayId);
    if (input.files && input.files[0]) {
        display.textContent = '✓ ' + input.files[0].name;
        display.style.color = '#28a745';
    } else {
        display.textContent = '';
    }
}
</script>
@endpush