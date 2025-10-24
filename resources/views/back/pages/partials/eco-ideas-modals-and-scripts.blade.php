<!-- Add Idea Modal -->
<div class="modal fade" id="addIdeaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">add</i>Add Eco Idea</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addIdeaForm" method="POST" action="{{ route('admin.eco-ideas.store') }}" enctype="multipart/form-data" onsubmit="return validateIdeaForm(event, 'addIdeaForm')" novalidate>
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3">Basic Information</h6>
              
              <!-- Hidden field for creator ID -->
              <input type="hidden" name="creator_id" value="{{ auth()->id() }}">
              
              <div class="mb-3">
                <label class="form-label text-dark">Creator</label>
                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                <small class="text-muted">You are the creator of this idea</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Title *</label>
                <input class="form-control" type="text" name="title" id="title" placeholder="Enter idea title" />
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Waste Type *</label>
                <select class="form-control" name="waste_type" id="waste_type">
                  @foreach(['organic','plastic','metal','e-waste','paper','glass','textile','mixed'] as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Difficulty *</label>
                <select class="form-control" name="difficulty_level" id="difficulty_level">
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
                <input class="form-control" type="number" id="team_size_needed" name="team_size_needed" min="1" max="20" placeholder="How many team members needed?" />
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Team Requirements</label>
                <textarea class="form-control" id="team_requirements" name="team_requirements" rows="3" placeholder="Describe what skills/roles are needed (e.g., Engineers, Designers, AI Specialists)"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Application Description</label>
                <textarea class="form-control" name="application_description" rows="2" placeholder="Instructions for applicants"></textarea>
                <div class="invalid-feedback"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Image</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*" onchange="validateImage(this)" />
                <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max: 2MB)</small>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            
            <!-- Full Width Fields -->
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label text-dark">Description *</label>
                <textarea class="form-control" name="description" id="description" rows="4" placeholder="Enter detailed description of the eco idea"></textarea>
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
              
              <!-- Hidden field for creator ID -->
              <input type="hidden" name="creator_id" id="edit_idea_creator_id" value="{{ auth()->id() }}">
              
              <div class="mb-3">
                <label class="form-label text-dark">Creator</label>
                <input type="text" class="form-control" id="edit_creator_name" value="{{ auth()->user()->name }}" disabled>
                <small class="text-muted">Original creator of this idea</small>
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
            <label class="form-label text-dark">Impact Metrics</label>
            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label text-dark">Waste Reduced</label>
                  <input class="form-control" type="text" name="impact_metrics[waste_reduced]" placeholder="e.g., 50kg, 2 tons" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label text-dark">CO2 Saved</label>
                  <input class="form-control" type="text" name="impact_metrics[co2_saved]" placeholder="e.g., 100kg CO2, 2.5 tons" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label text-dark">People Impacted</label>
                  <input class="form-control" type="number" name="impact_metrics[people_impacted]" placeholder="e.g., 25" min="0" max="1000000" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-dark">Energy Saved</label>
                  <input class="form-control" type="text" name="impact_metrics[energy_saved]" placeholder="e.g., 200kWh, 1.5 MWh" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-dark">Money Saved</label>
                  <input class="form-control" type="text" name="impact_metrics[money_saved]" placeholder="e.g., $500, €1000, 2500 TND" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label text-dark">Donate to NGO</label>
            <div class="form-check">
              <input type="hidden" name="donated_to_ngo" value="0">
              <input class="form-check-input" type="checkbox" name="donated_to_ngo" id="donated_to_ngo" value="1">
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

<!-- Edit Verification Modal -->
<div class="modal fade" id="editVerificationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-warning">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">edit</i>Edit Verification</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editVerificationForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label text-dark">Final Description *</label>
            <textarea class="form-control" name="final_description" id="edit_verification_final_description" rows="4" required placeholder="Describe the final outcome of the project"></textarea>
            <div class="invalid-feedback"></div>
          </div>
          
          <div class="mb-3">
            <label class="form-label text-dark">Impact Metrics</label>
            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label text-dark">Waste Reduced</label>
                  <input class="form-control" type="text" name="impact_metrics[waste_reduced]" id="edit_verification_waste_reduced" placeholder="e.g., 50kg, 2 tons" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label text-dark">CO2 Saved</label>
                  <input class="form-control" type="text" name="impact_metrics[co2_saved]" id="edit_verification_co2_saved" placeholder="e.g., 100kg CO2, 2.5 tons" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label text-dark">People Impacted</label>
                  <input class="form-control" type="number" name="impact_metrics[people_impacted]" id="edit_verification_people_impacted" placeholder="e.g., 25" min="0" max="1000000" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-dark">Energy Saved</label>
                  <input class="form-control" type="text" name="impact_metrics[energy_saved]" id="edit_verification_energy_saved" placeholder="e.g., 200kWh, 1.5 MWh" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-dark">Money Saved</label>
                  <input class="form-control" type="text" name="impact_metrics[money_saved]" id="edit_verification_money_saved" placeholder="e.g., $500, €1000, 2500 TND" />
                  <div class="invalid-feedback"></div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label text-dark">Donate to NGO</label>
            <div class="form-check">
              <input type="hidden" name="donated_to_ngo" value="0">
              <input class="form-check-input" type="checkbox" name="donated_to_ngo" id="edit_verification_donated_to_ngo" value="1">
              <label class="form-check-label" for="edit_verification_donated_to_ngo">
                This project will be donated to an NGO
              </label>
            </div>
          </div>
          
          <div class="mb-3" id="edit_verification_ngo_name_field" style="display: none;">
            <label class="form-label text-dark">NGO Name</label>
            <input class="form-control" type="text" name="ngo_name" id="edit_verification_ngo_name" placeholder="Enter NGO name">
            <div class="invalid-feedback"></div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger me-2" onclick="deleteVerification()">
            <i class="material-symbols-rounded me-1">delete</i>Delete Verification
          </button>
          <button type="submit" class="btn bg-gradient-warning">
            <i class="material-symbols-rounded me-1">save</i>Update Verification
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Idea Modal -->
<div class="modal fade" id="deleteIdeaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white"><i class="material-symbols-rounded me-2">delete</i>Delete Eco Idea</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
.modal {
    z-index: 9999 !important;
}

.modal-backdrop {
    z-index: 9998 !important;
}

.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: translate(0, -50px);
}

.modal.show .modal-dialog {
    transform: none;
}

.modal-header.bg-gradient-success,
.modal-header.bg-gradient-info,
.modal-header.bg-gradient-warning,
.modal-header.bg-gradient-dark {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
}

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

.modal.fade {
    transition: opacity 0.3s ease-in-out;
}

.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    transform: translate(0, -50px) scale(0.9);
    opacity: 0;
}

.modal.show .modal-dialog {
    transform: translate(0, 0) scale(1);
    opacity: 1;
}

.eco-ideas-table {
    overflow-x: auto;
    overflow-y: visible;
}

.eco-ideas-table .dropdown-menu {
    z-index: 1050;
}

.modal-backdrop {
    transition: opacity 0.3s ease-in-out;
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-control:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
}

/* Team Management Styles */
.team-remove-btn {
    background: linear-gradient(45deg, #dc3545, #c82333) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 0.5rem 0.75rem !important;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3) !important;
    transition: all 0.3s ease !important;
    position: relative !important;
    overflow: hidden !important;
}

.team-remove-btn:hover {
    background: linear-gradient(45deg, #c82333, #bd2130) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4) !important;
}

.team-remove-btn:active {
    transform: translateY(0) !important;
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3) !important;
}

.team-remove-btn i {
    font-size: 18px !important;
    color: white !important;
}

/* Creator/Leader Badge Styles */
.badge.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
    font-size: 0.75rem !important;
    padding: 0.4rem 0.8rem !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
}

.badge.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #fd7e14) !important;
    font-size: 0.75rem !important;
    padding: 0.4rem 0.8rem !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
}

/* Creator row highlighting */
.list-group-item.bg-light.border-start.border-success.border-3 {
    background: linear-gradient(90deg, rgba(40, 167, 69, 0.1) 0%, rgba(255, 255, 255, 0.9) 100%) !important;
    border-left: 4px solid #28a745 !important;
}

/* Lock icon for non-removable members */
.text-muted i {
    font-size: 20px !important;
    opacity: 0.6 !important;
}

/* Impact Metrics Validation Styling */
.form-control.is-valid {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
}

.form-control.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15) !important;
}

.invalid-feedback {
    display: block !important;
    color: #dc3545 !important;
    font-size: 0.75rem !important;
    margin-top: 0.25rem !important;
}

/* Impact Metrics Input Styling */
.impact-metrics-input {
    transition: all 0.15s ease-in-out !important;
}

.impact-metrics-input:focus {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
    outline: none !important;
}

/* Impact Metrics Input Styling */
.input-group-outline .form-control {
    border: 1px solid #d2d6da !important;
    border-radius: 0.5rem !important;
    padding: 0.75rem 1rem !important;
    background-color: #fff !important;
    transition: all 0.15s ease-in-out !important;
}

.input-group-outline .form-control:focus {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
    outline: none !important;
}

.input-group-outline .form-label {
    position: absolute !important;
    top: 0.75rem !important;
    left: 1rem !important;
    font-size: 0.875rem !important;
    color: #6c757d !important;
    background-color: #fff !important;
    padding: 0 0.25rem !important;
    transition: all 0.15s ease-in-out !important;
    pointer-events: none !important;
    z-index: 1 !important;
}

.input-group-outline .form-control:focus ~ .form-label,
.input-group-outline .form-control:not(:placeholder-shown) ~ .form-label {
    top: -0.5rem !important;
    left: 0.75rem !important;
    font-size: 0.75rem !important;
    color: #28a745 !important;
    font-weight: 500 !important;
}

.input-group-outline {
    position: relative !important;
    margin-bottom: 1rem !important;
}
</style>
@endpush

@push('scripts')
<script>
// Form Validation Functions
function showError(element, message) {
    const formGroup = element.closest('.mb-3') || element.closest('.form-group');
    if (!formGroup) return;
    
    element.classList.add('is-invalid');
    element.classList.remove('is-valid');
    
    let errorElement = formGroup.querySelector('.invalid-feedback');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'invalid-feedback';
        formGroup.appendChild(errorElement);
    }
    errorElement.textContent = message;
    return false;
}

function showSuccess(element) {
    const formGroup = element.closest('.mb-3') || element.closest('.form-group');
    if (!formGroup) return;
    
    element.classList.remove('is-invalid');
    element.classList.add('is-valid');
    
    const errorElement = formGroup.querySelector('.invalid-feedback');
    if (errorElement) {
        errorElement.textContent = '';
    }
    return true;
}

function validateRequiredField(fieldId, fieldName) {
    const field = document.getElementById(fieldId);
    if (!field) return true; // Skip if field doesn't exist
    
    const value = field.value.trim();
    if (value === '') {
        return showError(field, `${fieldName} is required`);
    }
    return showSuccess(field);
}

function validateMinLength(fieldId, fieldName, minLength) {
    const field = document.getElementById(fieldId);
    if (!field) return true; // Skip if field doesn't exist
    
    const value = field.value.trim();
    if (value !== '' && value.length < minLength) {
        return showError(field, `${fieldName} must be at least ${minLength} characters`);
    }
    return true;
}

function validateImage(input) {
    if (!input.files || input.files.length === 0) {
        return true; // No file is selected, which is optional
    }
    
    const file = input.files[0];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    const maxSize = 2 * 1024 * 1024; // 2MB
    
    if (!allowedTypes.includes(file.type)) {
        return showError(input, 'Only JPG, PNG, and GIF files are allowed');
    }
    
    if (file.size > maxSize) {
        return showError(input, 'Image size must be less than 2MB');
    }
    
    return showSuccess(input);
}

function validateTeamSize() {
    const teamSizeField = document.getElementById('team_size_needed');
    if (!teamSizeField) return true; // Skip if field doesn't exist
    
    const value = teamSizeField.value.trim();
    if (value === '') return true; // Optional field
    
    const size = parseInt(value, 10);
    if (isNaN(size) || size < 1 || size > 20) {
        return showError(teamSizeField, 'Team size must be between 1 and 20');
    }
    return showSuccess(teamSizeField);
}

function validateIdeaForm(event, formId) {
    event.preventDefault();
    
    // Reset all validation states
    const form = document.getElementById(formId);
    if (!form) return false;
    
    // Remove all previous validation states
    form.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
        el.classList.remove('is-invalid', 'is-valid');
    });
    
    // Validate all required fields
    const isTitleValid = validateRequiredField('title', 'Title');
    const isWasteTypeValid = validateRequiredField('waste_type', 'Waste type');
    const isDifficultyValid = validateRequiredField('difficulty_level', 'Difficulty level');
    const isDescriptionValid = validateRequiredField('description', 'Description') && 
                              validateMinLength('description', 'Description', 20);
    const isTeamSizeValid = validateTeamSize();
    
    // Validate image if file is selected
    const imageInput = form.querySelector('input[type="file"]');
    let isImageValid = true;
    if (imageInput && imageInput.files.length > 0) {
        isImageValid = validateImage(imageInput);
    }
    
    // If all validations pass, submit the form
    if (isTitleValid && isWasteTypeValid && isDifficultyValid && 
        isDescriptionValid && isTeamSizeValid && isImageValid) {
        form.submit();
    } else {
        // Scroll to the first error
        const firstError = form.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
    
    return false;
}

// Add event listeners for real-time validation
document.addEventListener('DOMContentLoaded', function() {
    // Add input event listeners for real-time validation
    const titleField = document.getElementById('title');
    if (titleField) {
        titleField.addEventListener('input', () => validateRequiredField('title', 'Title'));
        titleField.addEventListener('blur', () => validateRequiredField('title', 'Title'));
    }
    
    const descriptionField = document.getElementById('description');
    if (descriptionField) {
        descriptionField.addEventListener('input', () => {
            validateRequiredField('description', 'Description');
            validateMinLength('description', 'Description', 20);
        });
        descriptionField.addEventListener('blur', () => {
            validateRequiredField('description', 'Description');
            validateMinLength('description', 'Description', 20);
        });
    }
    
    const teamSizeField = document.getElementById('team_size_needed');
    if (teamSizeField) {
        teamSizeField.addEventListener('input', validateTeamSize);
        teamSizeField.addEventListener('blur', validateTeamSize);
    }
    
    const imageInput = document.querySelector('input[type="file"]');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            validateImage(this);
        });
    }
});

document.addEventListener('DOMContentLoaded', function(){
  loadUsers();
  

  // Setup Impact Metrics validation
  setupImpactMetricsValidation();
  
  // Setup form validation
  setupFormValidation();
  
  // Setup NGO checkbox for edit verification modal
  setupNgoCheckbox();

  const modals = ['addIdeaModal', 'viewIdeaModal', 'manageTeamModal', 'editIdeaModal', 'verifyProjectModal', 'editVerificationModal', 'deleteIdeaModal'];
  
  modals.forEach(modalId => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.addEventListener('show.bs.modal', function() {
        this.style.zIndex = '9999';
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
          backdrop.style.zIndex = '9998';
        }
        
        const dialog = this.querySelector('.modal-dialog');
        if (dialog) {
          dialog.style.transform = 'translate(0, -50px) scale(0.9)';
          dialog.style.opacity = '0';
          
          setTimeout(() => {
            dialog.style.transform = 'translate(0, 0) scale(1)';
            dialog.style.opacity = '1';
          }, 10);
        }
      });

      modal.addEventListener('hide.bs.modal', function() {
        const dialog = this.querySelector('.modal-dialog');
        if (dialog) {
          dialog.style.transform = 'translate(0, -50px) scale(0.9)';
          dialog.style.opacity = '0';
        }
      });
    }
  });
});

function setupImpactMetricsValidation() {
  // Setup validation for all impact metrics inputs
  const impactInputs = document.querySelectorAll('input[name*="impact_metrics"]');
  
  impactInputs.forEach(input => {
    // Add input event listeners for real-time validation
    input.addEventListener('input', function() {
      validateImpactInput(this);
    });
    
    // Add blur event for final validation
    input.addEventListener('blur', function() {
      validateImpactInput(this);
    });
    
    // Add focus event for better UX
    input.addEventListener('focus', function() {
      this.classList.remove('is-invalid');
      removeErrorMessage(this);
    });
  });
}

function validateImpactInput(input) {
  const fieldName = input.name;
  const value = input.value.trim();
  
  // Remove previous validation classes
  input.classList.remove('is-valid', 'is-invalid');
  
  // Skip validation if empty
  if (!value) {
    return true;
  }
  
  let isValid = true;
  let errorMessage = '';
  
  if (fieldName.includes('waste_reduced')) {
    // Waste validation - should contain numbers and weight units
    const wasteRegex = /^[\d\s\.,]+(kg|g|tons?|tonnes?|pounds?|lbs?|kilograms?|grams?)\s*$/i;
    if (!wasteRegex.test(value)) {
      isValid = false;
      errorMessage = 'Please enter valid waste amount with unit (e.g., 50kg, 2 tons, 1500g)';
    }
  } else if (fieldName.includes('co2_saved')) {
    // CO2 validation - should contain numbers and CO2 units
    const co2Regex = /^[\d\s\.,]+(kg|g|tons?|tonnes?|pounds?|lbs?|kilograms?|grams?)\s*(co2|co₂)?\s*$/i;
    if (!co2Regex.test(value)) {
      isValid = false;
      errorMessage = 'Please enter valid CO2 amount with unit (e.g., 100kg CO2, 2.5 tons, 500g)';
    }
  } else if (fieldName.includes('people_impacted')) {
    // People validation - should be reasonable number
    const numValue = parseInt(value);
    if (isNaN(numValue) || numValue < 0 || numValue > 1000000) {
      isValid = false;
      errorMessage = 'Please enter a valid number of people (0 to 1,000,000)';
    }
  } else if (fieldName.includes('energy_saved')) {
    // Energy validation - should contain numbers and energy units
    const energyRegex = /^[\d\s\.,]+(kwh|mwh|wh|joules?|j|btu|calories?|cal)\s*$/i;
    if (!energyRegex.test(value)) {
      isValid = false;
      errorMessage = 'Please enter valid energy amount with unit (e.g., 200kWh, 1.5 MWh, 500 J)';
    }
  } else if (fieldName.includes('money_saved')) {
    // Money validation - should contain numbers and currency symbols
    const moneyRegex = /^[\d\s\.,]+(\$|€|£|¥|TND|USD|EUR|GBP|JPY|dollars?|euros?|pounds?|yen|dinars?)\s*$/i;
    if (!moneyRegex.test(value)) {
      isValid = false;
      errorMessage = 'Please enter valid amount with currency (e.g., $500, €1000, 2500 TND)';
    }
  }
  
  // Apply validation classes
  if (isValid) {
    input.classList.add('is-valid');
    removeErrorMessage(input);
  } else {
    input.classList.add('is-invalid');
    showErrorMessage(input, errorMessage);
  }
  
  return isValid;
}

function showErrorMessage(input, message) {
  removeErrorMessage(input);
  
  const errorDiv = document.createElement('div');
  errorDiv.className = 'invalid-feedback d-block';
  errorDiv.textContent = message;
  
  input.parentNode.appendChild(errorDiv);
}

function removeErrorMessage(input) {
  const existingError = input.parentNode.querySelector('.invalid-feedback');
  if (existingError) {
    existingError.remove();
  }
}

function setupFormValidation() {
  // Add form submission validation for edit and verify forms
  const editForm = document.getElementById('editIdeaForm');
  const verifyForm = document.getElementById('verifyProjectForm');
  const editVerificationForm = document.getElementById('editVerificationForm');
  
  if (editForm) {
    editForm.addEventListener('submit', function(e) {
      if (!validateAllImpactMetrics()) {
        e.preventDefault();
        showFormError('Please fix the validation errors in Impact Metrics before submitting.');
      }
    });
  }
  
  if (verifyForm) {
    verifyForm.addEventListener('submit', function(e) {
      if (!validateAllImpactMetrics()) {
        e.preventDefault();
        showFormError('Please fix the validation errors in Impact Metrics before submitting.');
      }
    });
  }
  
  if (editVerificationForm) {
    editVerificationForm.addEventListener('submit', function(e) {
      if (!validateAllImpactMetrics()) {
        e.preventDefault();
        showFormError('Please fix the validation errors in Impact Metrics before submitting.');
      }
    });
  }
}

function showFormError(message) {
  // Remove existing error alerts
  const existingAlerts = document.querySelectorAll('.alert-danger');
  existingAlerts.forEach(alert => alert.remove());
  
  // Create new error alert
  const alertDiv = document.createElement('div');
  alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
  alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
  alertDiv.innerHTML = `
    <i class="material-symbols-rounded me-2">error</i>
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  document.body.appendChild(alertDiv);
  
  // Auto-dismiss after 5 seconds
  setTimeout(() => {
    if(alertDiv.parentNode) {
      alertDiv.remove();
    }
  }, 5000);
}

function setupNgoCheckbox() {
  // Setup NGO checkbox for verify project modal
  const verifyNgoCheckbox = document.getElementById('donated_to_ngo');
  if (verifyNgoCheckbox) {
    verifyNgoCheckbox.addEventListener('change', function() {
      const ngoField = document.getElementById('ngo_name_field');
      if (ngoField) {
        ngoField.style.display = this.checked ? 'block' : 'none';
      }
    });
  }
  
  // Setup NGO checkbox for edit verification modal
  const editNgoCheckbox = document.getElementById('edit_verification_donated_to_ngo');
  if (editNgoCheckbox) {
    editNgoCheckbox.addEventListener('change', function() {
      const ngoField = document.getElementById('edit_verification_ngo_name_field');
      if (ngoField) {
        ngoField.style.display = this.checked ? 'block' : 'none';
      }
    });
  }
}

function validateAllImpactMetrics() {
  const impactInputs = document.querySelectorAll('input[name*="impact_metrics"]');
  let allValid = true;
  
  impactInputs.forEach(input => {
    if (!validateImpactInput(input)) {
      allValid = false;
    }
  });
  
  return allValid;
}

function showFormError(message) {
  // Remove existing error alerts
  const existingAlerts = document.querySelectorAll('.form-validation-alert');
  existingAlerts.forEach(alert => alert.remove());
  
  // Create new error alert
  const alertDiv = document.createElement('div');
  alertDiv.className = 'alert alert-danger alert-dismissible fade show form-validation-alert position-fixed';
  alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
  alertDiv.innerHTML = `
    <i class="material-symbols-rounded me-2">error</i>
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  
  document.body.appendChild(alertDiv);
  
  // Auto-dismiss after 5 seconds
  setTimeout(() => {
    if (alertDiv.parentNode) {
      alertDiv.remove();
    }
  }, 5000);
}

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
            ${idea.impact_metrics ? `
              <div class="mb-3">
                <strong>Impact Metrics:</strong>
                <div class="row mt-2">
                  ${idea.impact_metrics.waste_reduced ? `<div class="col-md-6"><small class="text-warning"><i class="material-symbols-rounded me-1" style="font-size: 16px;">recycling</i><strong>Waste Reduced:</strong> ${idea.impact_metrics.waste_reduced}</small></div>` : ''}
                  ${idea.impact_metrics.co2_saved ? `<div class="col-md-6"><small class="text-info"><i class="material-symbols-rounded me-1" style="font-size: 16px;">eco</i><strong>CO2 Saved:</strong> ${idea.impact_metrics.co2_saved}</small></div>` : ''}
                  ${idea.impact_metrics.people_impacted ? `<div class="col-md-6"><small class="text-success"><i class="material-symbols-rounded me-1" style="font-size: 16px;">group</i><strong>People Impacted:</strong> ${idea.impact_metrics.people_impacted}</small></div>` : ''}
                  ${idea.impact_metrics.energy_saved ? `<div class="col-md-6"><small class="text-primary"><i class="material-symbols-rounded me-1" style="font-size: 16px;">bolt</i><strong>Energy Saved:</strong> ${idea.impact_metrics.energy_saved}</small></div>` : ''}
                  ${idea.impact_metrics.money_saved ? `<div class="col-md-6"><small class="text-secondary"><i class="material-symbols-rounded me-1" style="font-size: 16px;">attach_money</i><strong>Money Saved:</strong> ${idea.impact_metrics.money_saved}</small></div>` : ''}
                </div>
              </div>
            ` : ''}
          </div>
        </div>
      `;
      document.getElementById('ideaDetailsContent').innerHTML = content;
      
      const modal = new bootstrap.Modal(document.getElementById('viewIdeaModal'));
      modal.show();
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
                data.team.map(member => {
                  const isCreator = member.user_id === data.creator_id;
                  const isLeader = member.role === 'leader';
                  const canRemove = !isCreator && !isLeader;
                  
                  return `
                    <div class="list-group-item d-flex justify-content-between align-items-center ${isCreator ? 'bg-light border-start border-success border-3' : ''}">
                      <div>
                        <div class="d-flex align-items-center">
                          <strong>${member.user.name}</strong>
                          ${isCreator ? '<span class="badge bg-gradient-success ms-2"><i class="material-symbols-rounded me-1" style="font-size: 14px;">star</i>Creator</span>' : ''}
                          ${isLeader && !isCreator ? '<span class="badge bg-gradient-warning ms-2"><i class="material-symbols-rounded me-1" style="font-size: 14px;">group</i>Leader</span>' : ''}
                        </div>
                        <small class="text-muted">${member.role} - ${member.specialization || 'No specialization'}</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <span class="badge bg-gradient-${member.status === 'active' ? 'success' : 'secondary'}">${member.status}</span>
                        ${canRemove ? `
                          <button class="btn btn-sm btn-danger ms-2 team-remove-btn" onclick="removeTeamMember(${member.id})" title="Remove team member">
                            <i class="material-symbols-rounded">person_remove</i>
                          </button>
                        ` : `
                          <span class="ms-2 text-muted" title="${isCreator ? 'Creator cannot be removed' : 'Leader cannot be removed'}">
                            <i class="material-symbols-rounded">lock</i>
                          </span>
                        `}
                      </div>
                    </div>
                  `;
                }).join('') : 
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
      
      // Add data attribute to track current idea ID
      document.getElementById('manageTeamModal').setAttribute('data-idea-id', id);
      
      const modal = new bootstrap.Modal(document.getElementById('manageTeamModal'));
      modal.show();
    });
}

function editIdea(id){
  fetch(`/admin/eco-ideas/${id}/data`).then(r=>r.json()).then(idea =>{
    const creatorSelect = document.getElementById('edit_idea_creator_id');
    if (creatorSelect) {
      creatorSelect.value = idea.creator_id;
    }
    document.getElementById('edit_title').value = idea.title;
    document.getElementById('edit_description').value = idea.description;
    document.getElementById('edit_waste_type').value = idea.waste_type;
    document.getElementById('edit_difficulty_level').value = idea.difficulty_level;
    document.getElementById('edit_project_status').value = idea.project_status || 'idea';
    document.getElementById('edit_team_size_needed').value = idea.team_size_needed || '';
    document.getElementById('edit_team_requirements').value = idea.team_requirements || '';
    document.getElementById('edit_application_description').value = idea.application_description || '';
    document.getElementById('edit_ai_suggestion').value = idea.ai_generated_suggestion || '';
    
    // Only set final_description if the field exists (it might not be in the Edit Idea modal)
    const finalDescField = document.getElementById('edit_final_description');
    if (finalDescField) {
      finalDescField.value = idea.final_description || '';
    }
    
    // Populate impact metrics fields only if they exist (they might not be in the Edit Idea modal)
    if (idea.impact_metrics) {
      const wasteReducedField = document.getElementById('edit_waste_reduced');
      const co2SavedField = document.getElementById('edit_co2_saved');
      const peopleImpactedField = document.getElementById('edit_people_impacted');
      const energySavedField = document.getElementById('edit_energy_saved');
      const moneySavedField = document.getElementById('edit_money_saved');
      
      if (wasteReducedField) wasteReducedField.value = idea.impact_metrics.waste_reduced || '';
      if (co2SavedField) co2SavedField.value = idea.impact_metrics.co2_saved || '';
      if (peopleImpactedField) peopleImpactedField.value = idea.impact_metrics.people_impacted || '';
      if (energySavedField) energySavedField.value = idea.impact_metrics.energy_saved || '';
      if (moneySavedField) moneySavedField.value = idea.impact_metrics.money_saved || '';
    }
    
    const imagePreview = document.getElementById('current_idea_image_preview');
    if (imagePreview) {
      if (idea.image_path) {
        imagePreview.innerHTML = `<img src="/storage/${idea.image_path}" class="img-thumbnail" style="max-height: 100px;" alt="Current image">`;
      } else {
        imagePreview.innerHTML = '<p class="text-muted">No image uploaded</p>';
      }
    }
    
    document.getElementById('editIdeaForm').action = `/admin/eco-ideas/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('editIdeaModal'));
    modal.show();
  });
}

function verifyProject(id){
  document.getElementById('verifyProjectForm').action = `/admin/eco-ideas/${id}/verify`;
  const modal = new bootstrap.Modal(document.getElementById('verifyProjectModal'));
  modal.show();
}

function editVerification(id){
  fetch(`/admin/eco-ideas/${id}/data`)
    .then(r => r.json())
    .then(idea => {
      // Populate verification fields
      document.getElementById('edit_verification_final_description').value = idea.final_description || '';
      document.getElementById('edit_verification_donated_to_ngo').checked = idea.donated_to_ngo || false;
      document.getElementById('edit_verification_ngo_name').value = idea.ngo_name || '';
      
      // Show/hide NGO name field based on checkbox
      const ngoField = document.getElementById('edit_verification_ngo_name_field');
      if (idea.donated_to_ngo) {
        ngoField.style.display = 'block';
      } else {
        ngoField.style.display = 'none';
      }
      
      // Populate impact metrics fields
      if (idea.impact_metrics) {
        document.getElementById('edit_verification_waste_reduced').value = idea.impact_metrics.waste_reduced || '';
        document.getElementById('edit_verification_co2_saved').value = idea.impact_metrics.co2_saved || '';
        document.getElementById('edit_verification_people_impacted').value = idea.impact_metrics.people_impacted || '';
        document.getElementById('edit_verification_energy_saved').value = idea.impact_metrics.energy_saved || '';
        document.getElementById('edit_verification_money_saved').value = idea.impact_metrics.money_saved || '';
      }
      
      // Set form action
      document.getElementById('editVerificationForm').action = `/admin/eco-ideas/${id}/verification`;
      
      // Store idea ID for delete verification function
      document.getElementById('editVerificationForm').setAttribute('data-idea-id', id);
      
      const modal = new bootstrap.Modal(document.getElementById('editVerificationModal'));
      modal.show();
    });
}

function deleteVerification(){
  const form = document.getElementById('editVerificationForm');
  const ideaId = form.getAttribute('data-idea-id');
  
  if(confirm('Are you sure you want to delete the verification? This will change the project status back to its previous state.')){
    fetch(`/admin/eco-ideas/${ideaId}/verification`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }).then(response => {
      if(response.ok){
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editVerificationModal'));
        modal.hide();
        
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
          <i class="material-symbols-rounded me-2">check_circle</i>
          Verification deleted successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Auto-dismiss after 3 seconds
        setTimeout(() => {
          if(alertDiv.parentNode) {
            alertDiv.remove();
          }
        }, 3000);
        
        // Reload page to update the interface
        location.reload();
      } else {
        alert('Failed to delete verification. Please try again.');
      }
    }).catch(error => {
      console.error('Error:', error);
      alert('An error occurred while deleting the verification.');
    });
  }
}

function deleteIdea(id, title){
  document.getElementById('deleteIdeaTitle').textContent = title;
  document.getElementById('deleteIdeaForm').action = `/admin/eco-ideas/${id}`;
  const modal = new bootstrap.Modal(document.getElementById('deleteIdeaModal'));
  modal.show();
}

function removeTeamMember(memberId){
  // Double confirmation for team member removal
  if(confirm('Are you sure you want to remove this team member?')){
    if(confirm('This action cannot be undone. The team member will lose access to this project.')){
      fetch(`/admin/eco-idea-teams/${memberId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      }).then(response => {
        if(response.ok){
          // Show success message
          const alertDiv = document.createElement('div');
          alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
          alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
          alertDiv.innerHTML = `
            <i class="material-symbols-rounded me-2">check_circle</i>
            Team member removed successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          `;
          document.body.appendChild(alertDiv);
          
          // Auto-dismiss after 3 seconds
          setTimeout(() => {
            if(alertDiv.parentNode) {
              alertDiv.remove();
            }
          }, 3000);
          
          // Reload the modal content
          const currentIdeaId = document.getElementById('manageTeamModal')?.getAttribute('data-idea-id');
          if(currentIdeaId) {
            manageTeam(currentIdeaId);
          } else {
            location.reload();
          }
        } else {
          alert('Failed to remove team member. Please try again.');
        }
      }).catch(error => {
        console.error('Error:', error);
        alert('An error occurred while removing the team member.');
      });
    }
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
