@extends('layouts.back')

@section('title', 'Eco Ideas Management')
@section('page-title', 'Eco Ideas Management')

@push('styles')
<style>
  /* Fix dropdown menu visibility in table */
  .eco-ideas-table {
    overflow: visible !important;
    position: relative;
  }
  
  .eco-ideas-table .table-responsive {
    overflow: visible !important;
  }
  
  /* Ensure dropdown menus appear above table content and are fully visible */
  .eco-ideas-table .dropdown-menu {
    position: absolute !important;
    z-index: 1050 !important;
    min-width: 180px !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    border: 1px solid rgba(0, 0, 0, 0.15) !important;
    border-radius: 0.375rem !important;
  }
  
  /* Dropup positioning - opens upward */
  .eco-ideas-table .dropup .dropdown-menu {
    bottom: 100% !important;
    top: auto !important;
    margin-bottom: 0.125rem !important;
  }
  
  /* Ensure table container allows overflow */
  .eco-ideas-table .table {
    margin-bottom: 0 !important;
  }
  
  /* Ensure dropdowns are not clipped by parent containers */
  .card-body {
    overflow: visible !important;
  }
  
  .card {
    overflow: visible !important;
  }
  
  /* Additional spacing for better visibility */
  .eco-ideas-table tbody tr {
    position: relative;
  }
  
  /* Ensure dropdown button has proper spacing */
  .eco-ideas-table .dropdown-toggle {
    margin: 0.25rem 0 !important;
  }
  
  /* DISABLE FLOATING LABELS ONLY IN SEARCH FORM - NOT IN MODALS */
  .card-body form .input-group-outline,
  .card-body form .input-group-outline *,
  .card-body form .form-label,
  .card-body form .form-label * {
    animation: none !important;
    transition: none !important;
    transform: none !important;
  }
  
  /* Force search form inputs to be normal */
  .card-body form .form-control {
    border: 1px solid #d2d6da !important;
    border-radius: 0.375rem !important;
    background: #fff !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.875rem !important;
    line-height: 1.4 !important;
  }
  
  .card-body form .form-control:focus {
    border-color: #e91e63 !important;
    box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25) !important;
    outline: none !important;
  }
  
  /* Hide floating labels ONLY IN SEARCH FORM */
  .card-body form .form-label {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    height: 0 !important;
    width: 0 !important;
    position: absolute !important;
    left: -9999px !important;
  }
  
  /* Override Bootstrap floating label classes ONLY IN SEARCH FORM */
  .card-body form .input-group-outline .form-control:focus ~ .form-label,
  .card-body form .input-group-outline .form-control:not(:placeholder-shown) ~ .form-label,
  .card-body form .input-group-outline .form-control.filled ~ .form-label {
    display: none !important;
    transform: none !important;
    font-size: 0 !important;
    top: auto !important;
    left: auto !important;
    position: absolute !important;
    left: -9999px !important;
  }
  
  /* Force normal select styling in search form */
  .card-body form select.form-control {
    appearance: auto !important;
    -webkit-appearance: auto !important;
    -moz-appearance: auto !important;
  }
</style>
@endpush

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
            <div class="input-group">
              <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search ideas...">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <select class="form-control" name="waste_type">
                <option value="all" {{ request('waste_type') == 'all' ? 'selected' : '' }}>All Waste Types</option>
                @foreach(['organic','plastic','metal','e-waste','paper','glass','textile','mixed'] as $type)
                  <option value="{{ $type }}" {{ request('waste_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <select class="form-control" name="difficulty_level">
                <option value="all" {{ request('difficulty_level') == 'all' ? 'selected' : '' }}>All Difficulty</option>
                @foreach(['easy','medium','hard'] as $lvl)
                  <option value="{{ $lvl }}" {{ request('difficulty_level') == $lvl ? 'selected' : '' }}>{{ ucfirst($lvl) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
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
        <div class="table-responsive p-0 eco-ideas-table">
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
                    <div class="dropdown dropup">
                      <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:;" onclick="viewIdeaDetails({{ $idea->id }})">
                          <i class="material-symbols-rounded me-1">visibility</i>View Details
                        </a></li>
                        <li><a class="dropdown-item" href="javascript:;" onclick="manageTeam({{ $idea->id }})">
                          <i class="material-symbols-rounded me-1">group</i>Manage Team
                        </a></li>
                        <li><a class="dropdown-item" href="javascript:;" onclick="editIdea({{ $idea->id }})">
                          <i class="material-symbols-rounded me-1">edit</i>Edit
                        </a></li>
                        @if($idea->project_status === 'verified')
                          <li><a class="dropdown-item" href="javascript:;" onclick="editVerification({{ $idea->id }})">
                            <i class="material-symbols-rounded me-1">edit</i>Edit Verification
                          </a></li>
                        @else
                          <li><a class="dropdown-item" href="javascript:;" onclick="verifyProject({{ $idea->id }})">
                            <i class="material-symbols-rounded me-1">verified</i>Verify Project
                          </a></li>
                        @endif
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

<!-- Modals and scripts trimmed for brevity -->
@include('back.pages.partials.eco-ideas-modals-and-scripts')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Disable floating labels ONLY in search form, not in modals
  const searchForm = document.querySelector('.card-body form');
  if (searchForm) {
    const inputs = searchForm.querySelectorAll('.form-control');
    inputs.forEach(input => {
      // Remove any floating label classes
      input.classList.remove('form-control-outline');
      input.classList.remove('form-control-filled');
      
      // Force normal styling
      input.style.border = '1px solid #d2d6da';
      input.style.borderRadius = '0.375rem';
      input.style.background = '#fff';
      input.style.padding = '0.75rem 1rem';
      
      // Remove any floating label elements in search form only
      const labels = input.parentNode.querySelectorAll('.form-label');
      labels.forEach(label => {
        label.style.display = 'none';
        label.remove();
      });
      
      // Remove input-group-outline class from parent
      const inputGroup = input.closest('.input-group');
      if (inputGroup) {
        inputGroup.classList.remove('input-group-outline');
      }
    });
  }
  
  // Force remove floating label animations ONLY in search form
  const style = document.createElement('style');
  style.textContent = `
    .card-body form .form-label { display: none !important; }
    .card-body form .input-group-outline .form-label { display: none !important; }
    .card-body form .form-control:focus ~ .form-label { display: none !important; }
    .card-body form .form-control:not(:placeholder-shown) ~ .form-label { display: none !important; }
  `;
  document.head.appendChild(style);
});
</script>
@endpush

@endsection
