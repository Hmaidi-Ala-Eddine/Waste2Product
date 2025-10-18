@extends('layouts.back')

@section('title', 'Events')
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

      <div class="card-body px-3 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 text-dark">Manage Events</h6>
          <div>
            <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addEventModal">
              <i class="material-symbols-rounded me-1">add</i>Add New Event
            </button>
          </div>
        </div>

        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th>Subject</th>
                <th class="text-center">Date & Time</th>
                <th class="text-center">Image</th>
                <th class="text-center">Engagement</th>
                <th class="text-center">Created</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($events as $event)
                <tr>
                  <td>
                    <p class="text-sm mb-0">{{ Str::limit($event->subject, 60) }}</p>
                    <small class="text-muted">By {{ optional($event->user)->name ?? 'System' }}</small>
                  </td>
                  <td class="text-center">
                    {{ $event->date_time ? $event->date_time->format('d/m/Y H:i') : 'N/A' }}
                  </td>
                  <td class="text-center">
                    @if($event->image)
                      <img src="{{ asset('storage/' . $event->image) }}" class="avatar avatar-sm border-radius-lg" style="width:64px;height:48px;object-fit:cover;" alt="event">
                    @else
                      <span class="text-muted">No image</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div>{{ $event->likes }} Likes</div>
                    <div>{{ $event->participants_count }} Participants</div>
                  </td>
                  <td class="text-center">{{ $event->created_at->format('d/m/Y') }}</td>
                  <td class="text-center">
                    <a href="javascript:;" onclick="editEvent({{ $event->id }})">Edit</a> |
                    <a href="javascript:;" onclick="deleteEvent({{ $event->id }}, '{{ addslashes($event->subject) }}')">Delete</a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center">No events found</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($events->hasPages())
          <div class="card-footer px-3 py-3">
            {{ $events->appends(request()->query())->links('back.partials.pagination') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-success">
        <h5 class="modal-title text-white">Add New Event</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="addEventForm" method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Subject *</label>
                <input type="text" name="subject" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
              </div>
              <div class="mb-3">
                <label class="form-label">Author</label>
                <select name="user_id" id="event_author_id" class="form-control">
                  <option value="">Select Author</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Date & Time *</label>
                <input type="datetime-local" name="date_time" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="6"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn bg-gradient-success">Create Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit & Delete Modals -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-dark">
        <h5 class="modal-title text-white">Edit Event</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="editEventForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Subject *</label>
                <input type="text" name="subject" id="edit_subject" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Image</label>
                <div id="edit_image_preview" class="mb-2"></div>
                <input type="file" name="image" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Date & Time *</label>
                <input type="datetime-local" name="date_time" id="edit_date_time" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" id="edit_description" class="form-control" rows="6"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn bg-gradient-dark">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteEventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete "<strong id="deleteEventTitle"></strong>"?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteEventForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete Event</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  loadEventUsers();
});

function loadEventUsers(){
  fetch('{{ route("admin.events.users") }}')
    .then(r => r.json())
    .then(users => {
      const sel = document.getElementById('event_author_id');
      if(!sel) return;
      sel.innerHTML = '<option value="">Select Author</option>';
      users.forEach(u => sel.innerHTML += `<option value="${u.id}">${u.name} (${u.email})</option>`);
    })
    .catch(e => console.error(e));
}

function editEvent(id){
  fetch(`/admin/events/${id}/data`)
    .then(r=>r.json())
    .then(event => {
      document.getElementById('edit_subject').value = event.subject;
      document.getElementById('edit_description').value = event.description || '';
      if(event.date_time) document.getElementById('edit_date_time').value = new Date(event.date_time).toISOString().slice(0,16);
      const prev = document.getElementById('edit_image_preview');
      if(event.image){ prev.innerHTML = `<img src="/storage/${event.image}" class="img-thumbnail" style="max-height:100px;" />`; } else { prev.innerHTML = '<p class="text-muted">No image</p>'; }
      document.getElementById('editEventForm').action = `/admin/events/${event.id}`;
      new bootstrap.Modal(document.getElementById('editEventModal')).show();
    }).catch(e=>{ console.error(e); alert('Error loading event'); });
}

function deleteEvent(id, title){
  document.getElementById('deleteEventTitle').textContent = title;
  document.getElementById('deleteEventForm').action = `/admin/events/${id}`;
  new bootstrap.Modal(document.getElementById('deleteEventModal')).show();
}
</script>
@endpush

@endsection
