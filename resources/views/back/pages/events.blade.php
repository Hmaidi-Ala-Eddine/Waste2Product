@extends('layouts.back')

@section('title', 'Events Management')
@section('page-title', 'Events')

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card my-4">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
          <div class="d-flex justify-content-between align-items-center px-3">
            <h6 class="text-white text-capitalize mb-0">Events Management</h6>
            <div class="text-white">
              <small>Total: {{ $events->total() }} events</small>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Search Section -->
      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Search & Manage Events</h6>
          <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="material-symbols-rounded me-1">add</i>Add New Event
          </button>
        </div>
        
        <form method="GET" action="{{ route('admin.events') }}" class="row g-3 mb-3">
          <div class="col-md-8">
            <div class="input-group input-group-outline">
              <label class="form-label">Search events...</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn bg-gradient-dark mb-0 w-100">
              <i class="material-symbols-rounded me-1">search</i>Search
            </button>
          </div>
          <div class="col-md-2">
            <a href="{{ route('admin.events') }}" class="btn btn-outline-secondary mb-0 w-100">
              <i class="material-symbols-rounded me-1">refresh</i>Reset
            </a>
          </div>
        </form>
        
        @if(request()->has('search'))
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <small class="text-muted">
                Showing {{ $events->count() }} of {{ $events->total() }} results
                @if(request('search'))
                  for "<strong>{{ request('search') }}</strong>"
                @endif
              </small>
            </div>
            <a href="{{ route('admin.events') }}" class="btn btn-outline-secondary btn-sm">
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
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subject</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date & Time</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Engagement</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($events as $event)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ $event->author->profile_picture_url }}" class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $event->author->name }}" style="object-fit: cover;">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $event->subject }}</h6>
                        <p class="text-xs text-secondary mb-0">By: {{ $event->author->name }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $event->formatted_date_time }}</p>
                    <p class="text-xs text-secondary mb-0">
                      @if($event->date_time->isFuture())
                        <span class="badge badge-sm bg-gradient-info">Upcoming</span>
                      @else
                        <span class="badge badge-sm bg-gradient-secondary">Past</span>
                      @endif
                    </p>
                  </td>
                  <td class="align-middle text-center">
                    @if($event->image)
                      <img src="{{ $event->image_url }}" alt="{{ $event->subject }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                    @else
                      <span class="text-secondary text-xs">No image</span>
                    @endif
                  </td>
                  <td class="align-middle text-center">
                    <a href="javascript:;" class="text-primary text-xs font-weight-bold engagement-link" 
                       onclick="showParticipants({{ $event->id }}, '{{ $event->subject }}')"
                       data-toggle="tooltip" 
                       data-original-title="View participants"
                       style="cursor: pointer; text-decoration: none;">
                      <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle;">group</i>
                      {{ $event->engagement }}
                    </a>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">
                      {{ $event->created_at ? $event->created_at->format('d/m/y') : 'N/A' }}
                    </span>
                  </td>
                  <td class="align-middle text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs me-2 action-edit" 
                       onclick="editEvent({{ $event->id }})" 
                       data-toggle="tooltip" data-original-title="Edit event">
                      Edit
                    </a>
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs action-delete" 
                       onclick="deleteEvent({{ $event->id }}, '{{ $event->subject }}')" 
                       data-toggle="tooltip" data-original-title="Delete event">
                      Delete
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-4">
                    <p class="text-secondary mb-0">No events found</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination Section -->
        @if($events->hasPages())
          <div class="card-footer px-3 py-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted">
                  Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} results
                </small>
              </div>
              <div>
                {{ $events->links('back.partials.pagination') }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Add New Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white" id="addEventModalLabel">
          <i class="material-symbols-rounded me-2">add</i>Add New Event
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addEventForm" method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <!-- Event Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3 text-uppercase">Event Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Subject *</label>
                <input type="text" class="form-control border" name="subject" required placeholder="Enter event subject" style="background-color: #f8f9fa;">
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Date & Time *</label>
                <input type="datetime-local" class="form-control border" name="date_time" required style="background-color: #f8f9fa;">
              </div>
              
              <!-- Author is automatically set to logged-in user -->
              <input type="hidden" name="author_id" value="{{ auth()->id() }}">
              
              <div class="mb-3">
                <label class="form-label text-dark">Author</label>
                <input type="text" class="form-control border" value="{{ auth()->user()->name }}" disabled style="background-color: #e9ecef;">
                <small class="text-muted d-block mt-1">You will be set as the author of this event</small>
              </div>
            </div>
            
            <!-- Details & Media -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3 text-uppercase">Details & Media</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Event Image</label>
                <div class="custom-file-upload">
                  <input type="file" name="image" id="event_image" accept="image/*" onchange="displayFileName(this, 'event_file_name')">
                  <label for="event_image">
                    <i class="material-symbols-rounded">image</i>
                    Choose Picture
                  </label>
                  <div id="event_file_name" class="file-name-display"></div>
                </div>
                <small class="text-muted d-block mt-2">Optional - Max 2MB (JPG, PNG, GIF)</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Description</label>
                <textarea class="form-control border" name="description" rows="7" placeholder="Enter event description, agenda, location details..." style="background-color: #f8f9fa;"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="material-symbols-rounded me-1">close</i>Cancel
          </button>
          <button type="submit" class="btn bg-gradient-success">
            <i class="material-symbols-rounded me-1">add</i>Create Event
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white" id="editEventModalLabel">
          <i class="material-symbols-rounded me-2">edit</i>Edit Event
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editEventForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <!-- Event Information -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3 text-uppercase">Event Information</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Subject *</label>
                <input type="text" class="form-control border" name="subject" id="edit_subject" required placeholder="Enter event subject" style="background-color: #f8f9fa;">
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Date & Time *</label>
                <input type="datetime-local" class="form-control border" name="date_time" id="edit_date_time" required style="background-color: #f8f9fa;">
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Author *</label>
                <select class="form-control border" name="author_id" id="edit_author_id" required style="background-color: #f8f9fa;">
                  <option value="">Select Author</option>
                </select>
              </div>
            </div>
            
            <!-- Details & Media -->
            <div class="col-md-6">
              <h6 class="text-dark font-weight-bold mb-3 text-uppercase">Details & Media</h6>
              
              <div class="mb-3">
                <label class="form-label text-dark">Event Image</label>
                <div class="custom-file-upload">
                  <input type="file" name="image" id="edit_event_image" accept="image/*" onchange="displayFileName(this, 'edit_event_file_name')">
                  <label for="edit_event_image">
                    <i class="material-symbols-rounded">image</i>
                    Choose Picture
                  </label>
                  <div id="edit_event_file_name" class="file-name-display"></div>
                </div>
                <small class="text-muted d-block mt-2">Leave empty to keep current image</small>
                <div id="current_image_preview" class="mt-2"></div>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-dark">Description</label>
                <textarea class="form-control border" name="description" id="edit_description" rows="7" placeholder="Enter event description, agenda, location details..." style="background-color: #f8f9fa;"></textarea>
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

<!-- Delete Event Modal -->
<div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteEventModalLabel">Delete Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the event "<strong id="deleteEventSubject"></strong>"?</p>
        <p class="text-danger">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteEventForm" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete Event</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Participants Modal -->
<div class="modal fade" id="participantsModal" tabindex="-1" aria-labelledby="participantsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-primary">
        <h5 class="modal-title text-white" id="participantsModalLabel">
          <i class="material-symbols-rounded me-2">group</i>Event Participants
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
          <h6 class="text-dark font-weight-bold mb-2">Event: <span id="participantsEventTitle"></span></h6>
          <p class="text-sm text-secondary mb-0">Total Participants: <span id="participantsCount" class="font-weight-bold text-primary">0</span></p>
        </div>
        <hr>
        <div id="participantsList" class="mt-3">
          <!-- Loading spinner -->
          <div class="text-center py-4" id="participantsLoading">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2">Loading participants...</p>
          </div>
          <!-- Participants will be loaded here -->
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

/* Engagement link styling */
.engagement-link {
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 4px 8px;
  border-radius: 6px;
}

.engagement-link:hover {
  background-color: rgba(13, 110, 253, 0.1);
  transform: translateY(-1px);
  text-decoration: none !important;
}

/* Participant card styling */
.participant-card {
  transition: all 0.2s ease;
  background-color: #fff;
  border: 1px solid #e9ecef !important;
}

.participant-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
  transform: translateY(-2px);
  border-color: #d2d6da !important;
}

/* Participants modal styling */
#participantsModal .modal-header.bg-gradient-primary {
  background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
  border-bottom: none;
}

#participantsList {
  max-height: 500px;
  overflow-y: auto;
  padding-right: 8px;
}

#participantsList::-webkit-scrollbar {
  width: 8px;
}

#participantsList::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

#participantsList::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

#participantsList::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
@endpush

@push('scripts')
<script>
// Load authors when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadAuthors();
});

// Load authors for dropdowns
function loadAuthors() {
    fetch('{{ route("admin.events.authors") }}')
        .then(response => response.json())
        .then(authors => {
            const authorSelects = ['author_id', 'edit_author_id'];
            authorSelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Select Author</option>';
                    authors.forEach(author => {
                        select.innerHTML += `<option value="${author.id}">${author.name} (${author.email})</option>`;
                    });
                }
            });
        })
        .catch(error => console.error('Error loading authors:', error));
}

// Edit event function
function editEvent(id) {
    fetch(`/admin/events/${id}/data`)
        .then(response => response.json())
        .then(event => {
            document.getElementById('edit_subject').value = event.subject;
            document.getElementById('edit_date_time').value = event.date_time.replace(' ', 'T').substring(0, 16);
            document.getElementById('edit_description').value = event.description || '';
            document.getElementById('edit_author_id').value = event.author_id;
            
            // Show current image if exists
            const imagePreview = document.getElementById('current_image_preview');
            if (event.image) {
                imagePreview.innerHTML = `<img src="${event.image_url}" alt="Current image" style="max-width: 200px; border-radius: 8px;">`;
            } else {
                imagePreview.innerHTML = '';
            }
            
            document.getElementById('editEventForm').action = `/admin/events/${id}`;
            
            const modal = new bootstrap.Modal(document.getElementById('editEventModal'));
            modal.show();
        })
        .catch(error => console.error('Error loading event:', error));
}

// Delete event function
function deleteEvent(id, subject) {
    document.getElementById('deleteEventSubject').textContent = subject;
    document.getElementById('deleteEventForm').action = `/admin/events/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteEventModal'));
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

// Reset modal when closed
document.addEventListener('DOMContentLoaded', function() {
    const participantsModal = document.getElementById('participantsModal');
    if (participantsModal) {
        participantsModal.addEventListener('hidden.bs.modal', function () {
            // Reset modal content
            document.getElementById('participantsEventTitle').textContent = '';
            document.getElementById('participantsCount').textContent = '0';
            document.getElementById('participantsList').innerHTML = '<div class="text-center py-4" id="participantsLoading"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="text-muted mt-2">Loading participants...</p></div>';
        });
    }
});

// Show participants function
function showParticipants(eventId, eventTitle) {
    // Set event title
    document.getElementById('participantsEventTitle').textContent = eventTitle;
    
    // Show loading state
    document.getElementById('participantsLoading').style.display = 'block';
    document.getElementById('participantsList').innerHTML = '<div class="text-center py-4" id="participantsLoading"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="text-muted mt-2">Loading participants...</p></div>';
    
    // Open modal
    const modalElement = document.getElementById('participantsModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
    
    // Fetch participants data
    fetch(`/admin/events/${eventId}/participants`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load participants');
            }
            return response.json();
        })
        .then(data => {
            const participants = data.participants || [];
            const count = participants.length;
            
            // Update count
            document.getElementById('participantsCount').textContent = count;
            
            // Clear loading and display participants
            const participantsList = document.getElementById('participantsList');
            
            if (count === 0) {
                participantsList.innerHTML = `
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded text-secondary" style="font-size: 4rem;">group_off</i>
                        <p class="text-muted mt-3">No participants yet</p>
                    </div>
                `;
            } else {
                let participantsHTML = '<div class="row g-3">';
                
                participants.forEach(participant => {
                    participantsHTML += `
                        <div class="col-12 col-md-6">
                            <div class="participant-card d-flex align-items-center p-3 border rounded-3 shadow-sm">
                                <div class="me-3">
                                    <img src="${participant.profile_picture_url || '/default-avatar.png'}" 
                                         class="rounded-circle" 
                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                         alt="${participant.name}">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-dark font-weight-bold">${participant.name}</h6>
                                    <p class="mb-0 text-sm text-secondary">${participant.email}</p>
                                    ${participant.phone ? `<p class="mb-0 text-xs text-muted"><i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">phone</i> ${participant.phone}</p>` : ''}
                                </div>
                                ${participant.registered_at ? `<div class="text-end"><small class="text-muted">Joined: ${participant.registered_at}</small></div>` : ''}
                            </div>
                        </div>
                    `;
                });
                
                participantsHTML += '</div>';
                participantsList.innerHTML = participantsHTML;
            }
        })
        .catch(error => {
            console.error('Error loading participants:', error);
            document.getElementById('participantsList').innerHTML = `
                <div class="alert alert-danger text-center" role="alert">
                    <i class="material-symbols-rounded me-2">error</i>
                    Failed to load participants. Please try again.
                </div>
            `;
        });
}
</script>
@endpush
