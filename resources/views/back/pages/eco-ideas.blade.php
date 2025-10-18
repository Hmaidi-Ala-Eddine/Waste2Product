@extends('layouts.back')

@section('title', 'Eco Ideas Management')
@section('page-title', 'Eco Ideas Management')

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
          <div class="col-md-3">
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
              <select class="form-control" name="project_status">
                <option value="all" {{ request('project_status') == 'all' ? 'selected' : '' }}>All Status</option>
                @foreach(['idea','recruiting','in_progress','completed','verified','donated'] as $st)
                  <option value="{{ $st }}" {{ request('project_status') == $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-1">
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
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Team</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Creator</th>
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
                  <td class="text-center">
                    <span class="badge badge-sm bg-gradient-{{ $idea->project_status == 'completed' ? 'success' : ($idea->project_status == 'in_progress' ? 'warning' : 'secondary') }}">
                      {{ ucfirst(str_replace('_', ' ', $idea->project_status ?? 'idea')) }}
                    </span>
                  </td>
                  <td class="text-center">
                    <small class="text-muted">{{ $idea->team_size_current ?? 0 }}/{{ $idea->team_size_needed ?? 0 }}</small>
                  </td>
                  <td class="text-center">{{ $idea->creator->name ?? 'Unknown' }}</td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Actions
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:;" onclick="viewIdeaDetails({{ $idea->id }})">
                          <i class="material-symbols-rounded me-1">visibility</i>View Details
                        </a></li>
                        <li><a class="dropdown-item" href="javascript:;" onclick="manageTeam({{ $idea->id }})">
                          <i class="material-symbols-rounded me-1">group</i>Manage Team
                        </a></li>
                        <li><a class="dropdown-item" href="javascript:;" onclick="editIdea({{ $idea->id }})">
                          <i class="material-symbols-rounded me-1">edit</i>Edit
                        </a></li>
                        <li><a class="dropdown-item" href="javascript:;" onclick="verifyProject({{ $idea->id }})">
                          <i class="material-symbols-rounded me-1">verified</i>Verify Project
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="javascript:;" onclick="deleteIdea({{ $idea->id }}, '{{ $idea->title }}')">
                          <i class="material-symbols-rounded me-1">delete</i>Delete
                        </a></li>
                      </ul>
                    </div>
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
  <div class="modal-dialog modal-xl">
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
                <label class="form-label text-dark">Creator *</label>
                <select class="form-control" name="creator_id" id="idea_creator_id" required>
                  <option value="">Select Creator</option>
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
            </div>
            
            <!-- Team Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Team Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Team Size Needed</label>
                <input class="form-control" type="number" name="team_size_needed" min="1" max="20" placeholder="How many team members needed?" />
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Team Requirements</label>
                <textarea class="form-control" name="team_requirements" rows="3" placeholder="Describe what skills/roles are needed (e.g., Engineers, Designers, AI Specialists)"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Application Description</label>
                <textarea class="form-control" name="application_description" rows="2" placeholder="Instructions for applicants"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Image</label>
                <input type="file" class="form-control" name="image" accept="image/*" />
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Full Width Fields -->
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control" name="description" rows="4" required placeholder="Enter detailed description of the eco idea"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">AI Generated Suggestion</label>
                <textarea class="form-control" name="ai_generated_suggestion" rows="2" placeholder="AI-generated suggestions or improvements"></textarea>
                <div class="invalid-feedback"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn bg-gradient-success">Create Idea</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Idea Details Modal -->
<div class="modal fade" id="viewIdeaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">visibility</i>Eco Idea Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div id="ideaDetailsContent">
          <!-- Content will be loaded here -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Manage Team Modal -->
<div class="modal fade" id="manageTeamModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-gradient-warning">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">group</i>Manage Team</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div id="teamManagementContent">
          <!-- Content will be loaded here -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Idea Modal -->
<div class="modal fade" id="editIdeaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
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
                <label class="form-label text-dark">Creator *</label>
                <select class="form-control" name="creator_id" id="edit_idea_creator_id" required>
                  <option value="">Select Creator</option>
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
                <label class="form-label text-dark">Project Status</label>
                <select class="form-control" name="project_status" id="edit_project_status">
                  @foreach(['idea','recruiting','in_progress','completed','verified','donated'] as $st)
                    <option value="{{ $st }}">{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Team Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Team Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Team Size Needed</label>
                <input class="form-control" type="number" name="team_size_needed" id="edit_team_size_needed" min="1" max="20" />
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Team Requirements</label>
                <textarea class="form-control" name="team_requirements" id="edit_team_requirements" rows="3"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Application Description</label>
                <textarea class="form-control" name="application_description" id="edit_application_description" rows="2"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Replace Image</label>
                <div id="current_idea_image_preview" class="mb-2"></div>
                <input type="file" class="form-control" name="image" accept="image/*" />
                <small class="text-muted">Leave empty to keep current image</small>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Full Width Fields -->
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control" name="description" id="edit_description" rows="4" required></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">AI Generated Suggestion</label>
                <textarea class="form-control" name="ai_generated_suggestion" id="edit_ai_suggestion" rows="2"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Final Description (for completed projects)</label>
                <textarea class="form-control" name="final_description" id="edit_final_description" rows="3"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Impact Metrics (JSON format)</label>
                <textarea class="form-control" name="impact_metrics" id="edit_impact_metrics" rows="2" placeholder='{"waste_reduced": "50kg", "co2_saved": "100kg"}'></textarea>
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

<!-- Verify Project Modal -->
<div class="modal fade" id="verifyProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">verified</i>Verify Project</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="verifyProjectForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label text-dark">Final Description *</label>
            <textarea class="form-control" name="final_description" rows="4" required placeholder="Describe the final outcome of the project"></textarea>
            <div class="invalid-feedback"></div>
          </div>
          
          <div class="mb-3">
            <label class="form-label text-dark">Impact Metrics (JSON format)</label>
            <textarea class="form-control" name="impact_metrics" rows="3" placeholder='{"waste_reduced": "50kg", "co2_saved": "100kg", "people_impacted": 25}'></textarea>
            <div class="invalid-feedback"></div>
          </div>
          
          <div class="mb-3">
            <label class="form-label text-dark">Donate to NGO</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="donated_to_ngo" id="donated_to_ngo">
              <label class="form-check-label" for="donated_to_ngo">
                This project will be donated to an NGO
              </label>
            </div>
          </div>
          
          <div class="mb-3" id="ngo_name_field" style="display: none;">
            <label class="form-label text-dark">NGO Name</label>
            <input class="form-control" type="text" name="ngo_name" placeholder="Enter NGO name">
            <div class="invalid-feedback"></div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn bg-gradient-success">Verify & Complete</button>
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
        <p class="text-danger"><small>This action cannot be undone and will delete all related data (team, tasks, certificates, etc.)</small></p>
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

.form-label {
    font-weight: 500;
    color: #344767;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

h6 {
    color: #344767;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control.is-valid {
    border-color: #28a745;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.valid-feedback {
    display: block;
    color: #28a745;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.badge {
    font-size: 0.75rem;
}

.dropdown-menu {
    font-size: 0.875rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
}

.dropdown-item i {
    font-size: 1rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  loadUsers();
  
  // Show/hide NGO name field based on checkbox
  document.getElementById('donated_to_ngo').addEventListener('change', function() {
    const ngoField = document.getElementById('ngo_name_field');
    if (this.checked) {
      ngoField.style.display = 'block';
    } else {
      ngoField.style.display = 'none';
    }
  });
});

function loadUsers(){
  fetch('/admin/products/users')
    .then(r => r.json())
    .then(users => {
      const selects = ['idea_creator_id','edit_idea_creator_id'];
      selects.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = '<option value="">Select Creator</option>';
        users.forEach(u => el.innerHTML += `<option value="${u.id}">${u.name} (${u.email})</option>`);
      });
    });
}

function viewIdeaDetails(id){
  fetch(`/admin/eco-ideas/${id}/data`)
    .then(r => r.json())
    .then(idea => {
      const content = `
        <div class="row">
          <div class="col-md-4">
            <div class="text-center mb-3">
              ${idea.image_path ? 
                `<img src="/storage/${idea.image_path}" class="img-fluid rounded" style="max-height: 200px;" alt="Idea image">` : 
                '<div class="bg-light rounded p-4"><i class="material-symbols-rounded text-muted" style="font-size: 3rem;">image</i></div>'
              }
            </div>
          </div>
          <div class="col-md-8">
            <h4 class="text-dark mb-3">${idea.title}</h4>
            <p class="text-muted mb-3">${idea.description}</p>
            
            <div class="row mb-3">
              <div class="col-md-6">
                <strong>Waste Type:</strong> ${idea.waste_type ? idea.waste_type.charAt(0).toUpperCase() + idea.waste_type.slice(1) : 'N/A'}<br>
                <strong>Difficulty:</strong> ${idea.difficulty_level ? idea.difficulty_level.charAt(0).toUpperCase() + idea.difficulty_level.slice(1) : 'N/A'}<br>
                <strong>Status:</strong> <span class="badge bg-gradient-secondary">${idea.project_status ? idea.project_status.replace('_', ' ').charAt(0).toUpperCase() + idea.project_status.slice(1) : 'Idea'}</span>
              </div>
              <div class="col-md-6">
                <strong>Creator:</strong> ${idea.creator ? idea.creator.name : 'Unknown'}<br>
                <strong>Team Size:</strong> ${idea.team_size_current || 0}/${idea.team_size_needed || 0}<br>
                <strong>Upvotes:</strong> ${idea.upvotes || 0}
              </div>
            </div>
            
            ${idea.team_requirements ? `<div class="mb-3"><strong>Team Requirements:</strong><br><small class="text-muted">${idea.team_requirements}</small></div>` : ''}
            ${idea.ai_generated_suggestion ? `<div class="mb-3"><strong>AI Suggestions:</strong><br><small class="text-info">${idea.ai_generated_suggestion}</small></div>` : ''}
            ${idea.final_description ? `<div class="mb-3"><strong>Final Description:</strong><br><small class="text-success">${idea.final_description}</small></div>` : ''}
            ${idea.impact_metrics ? `<div class="mb-3"><strong>Impact Metrics:</strong><br><small class="text-warning">${idea.impact_metrics}</small></div>` : ''}
          </div>
        </div>
      `;
      document.getElementById('ideaDetailsContent').innerHTML = content;
      new bootstrap.Modal(document.getElementById('viewIdeaModal')).show();
    });
}

function manageTeam(id){
  fetch(`/admin/eco-ideas/${id}/team`)
    .then(r => r.json())
    .then(data => {
      const content = `
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-dark mb-3">Current Team Members</h6>
            <div class="list-group">
              ${data.team && data.team.length > 0 ? 
                data.team.map(member => `
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <strong>${member.user.name}</strong><br>
                      <small class="text-muted">${member.role} - ${member.specialization || 'No specialization'}</small>
                    </div>
                    <div>
                      <span class="badge bg-gradient-${member.status === 'active' ? 'success' : 'secondary'}">${member.status}</span>
                      <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeTeamMember(${member.id})">
                        <i class="material-symbols-rounded">remove</i>
                      </button>
                    </div>
                  </div>
                `).join('') : 
                '<div class="text-muted">No team members yet</div>'
              }
            </div>
          </div>
          <div class="col-md-6">
            <h6 class="text-dark mb-3">Pending Applications</h6>
            <div class="list-group">
              ${data.applications && data.applications.length > 0 ? 
                data.applications.map(app => `
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <strong>${app.user.name}</strong><br>
                      <small class="text-muted">${app.application_message || 'No message'}</small>
                    </div>
                    <div>
                      <button class="btn btn-sm btn-success me-1" onclick="acceptApplication(${app.id})">
                        <i class="material-symbols-rounded">check</i>
                      </button>
                      <button class="btn btn-sm btn-danger" onclick="rejectApplication(${app.id})">
                        <i class="material-symbols-rounded">close</i>
                      </button>
                    </div>
                  </div>
                `).join('') : 
                '<div class="text-muted">No pending applications</div>'
              }
            </div>
          </div>
        </div>
      `;
      document.getElementById('teamManagementContent').innerHTML = content;
      new bootstrap.Modal(document.getElementById('manageTeamModal')).show();
    });
}

function editIdea(id){
  fetch(`/admin/eco-ideas/${id}/data`).then(r=>r.json()).then(idea =>{
    document.getElementById('edit_idea_creator_id').value = idea.creator_id;
    document.getElementById('edit_title').value = idea.title;
    document.getElementById('edit_description').value = idea.description;
    document.getElementById('edit_waste_type').value = idea.waste_type;
    document.getElementById('edit_difficulty_level').value = idea.difficulty_level;
    document.getElementById('edit_project_status').value = idea.project_status || 'idea';
    document.getElementById('edit_team_size_needed').value = idea.team_size_needed || '';
    document.getElementById('edit_team_requirements').value = idea.team_requirements || '';
    document.getElementById('edit_application_description').value = idea.application_description || '';
    document.getElementById('edit_ai_suggestion').value = idea.ai_generated_suggestion || '';
    document.getElementById('edit_final_description').value = idea.final_description || '';
    document.getElementById('edit_impact_metrics').value = idea.impact_metrics || '';
    
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

function verifyProject(id){
  document.getElementById('verifyProjectForm').action = `/admin/eco-ideas/${id}/verify`;
  new bootstrap.Modal(document.getElementById('verifyProjectModal')).show();
}

function deleteIdea(id, title){
  document.getElementById('deleteIdeaTitle').textContent = title;
  document.getElementById('deleteIdeaForm').action = `/admin/eco-ideas/${id}`;
  new bootstrap.Modal(document.getElementById('deleteIdeaModal')).show();
}

function removeTeamMember(memberId){
  if(confirm('Are you sure you want to remove this team member?')){
    fetch(`/admin/eco-idea-teams/${memberId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }).then(() => {
      location.reload();
    });
  }
}

function acceptApplication(appId){
  if(confirm('Accept this application?')){
    fetch(`/admin/eco-idea-applications/${appId}/accept`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }).then(() => {
      location.reload();
    });
  }
}

function rejectApplication(appId){
  if(confirm('Reject this application?')){
    fetch(`/admin/eco-idea-applications/${appId}/reject`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }).then(() => {
      location.reload();
    });
  }
}
</script>
@endpush

@endsection