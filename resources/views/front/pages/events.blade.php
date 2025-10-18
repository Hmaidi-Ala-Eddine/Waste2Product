@extends('layouts.front')

@section('title', 'Events')

@section('content')
    <div class="team-style-two-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Upcoming Events</h4>
                        <h2 class="title split-text">Join community events and activities</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
        @forelse($events as $event)
          @php
            $fallbackImage = asset('assets/front/img/demo/home-1.jpg');
            // Prefer public disk stored file when available
            $imageUrl = $fallbackImage;
            try {
              if (!empty($event->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->image)) {
                $imageUrl = asset('storage/' . $event->image);
              }
            } catch (\Throwable $e) {
              // fallback to default if Storage check fails for any reason
              $imageUrl = $fallbackImage;
            }
          @endphp
                    <div class="col-lg-4 col-md-6 team-style-two wow fadeInUp" data-wow-delay="{{ ($loop->index * 0.1) }}s">
                        <div class="team-style-two-item post-card" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                            <div class="thumb">
                <img src="{{ $imageUrl }}" alt="{{ $event->subject }}" loading="lazy" onerror="this.src='{{ $fallbackImage }}'; this.onerror=null;" style="display:block;width:100%;height:220px;object-fit:cover;">

                <button class="like-btn {{ $event->is_liked ? 'liked' : '' }}" onclick="toggleLikeEvent({{ $event->id }})" data-event-id="{{ $event->id }}" data-liked="{{ $event->is_liked ? '1' : '0' }}">
                  <i class="fas fa-heart" id="like-icon-event-{{ $event->id }}"></i>
                  <span id="like-count-event-{{ $event->id }}">{{ $event->likes }}</span>
                </button>
                            </div>
                            <div class="info">
                                <h4><a href="#">{{ Str::limit($event->subject, 40) }}</a></h4>
                                <p class="description">{{ Str::limit($event->description, 80) }}</p>
                                <div class="post-meta">
                                    <small class="text-muted">By {{ optional($event->user)->name ?? 'System' }}</small>
                                    <span class="date">{{ $event->date_time ? $event->date_time->format('M d, Y H:i') : 'TBA' }}</span>
                                </div>

                                <div class="post-actions mt-3">
                  <button class="btn btn-comment participate-btn-fixed {{ $event->is_participating ? 'btn-success' : '' }}" onclick="openEventModal({{ $event->id }})" id="participate-button-{{ $event->id }}" data-participating="{{ $event->is_participating ? '1' : '0' }}">
                    <i class="fas fa-users me-1"></i>
                    <span id="participant-count-{{ $event->id }}">{{ $event->participants_count }}</span>
                    <span class="participate-label">{{ $event->is_participating ? 'Cancel' : 'Participate' }}</span>
                  </button>
                                    <button class="btn btn-share" onclick="sharePost({{ $event->id }})"><i class="fas fa-share"></i> Share</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <div class="no-posts">
                            <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                            <h4>No events available</h4>
                            <p class="text-muted">Check back later for upcoming events!</p>
                        </div>
                    </div>
                @endforelse
            </div>
            @if($events->hasPages())
                <div class="row">
                    <div class="col-12 text-center mt-4">
                        <div class="pagination-wrapper">
                            {{ $events->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Event Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Event Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <h6 id="modal-event-subject" class="fw-bold"></h6>
            <p id="modal-event-description" class="text-muted"></p>
            <p><strong>Date:</strong> <span id="modal-event-date"></span></p>

            <div class="d-flex gap-2">
              <button class="btn btn-primary participate-btn-fixed" id="participateBtn" onclick="participateEvent()"><i class="fas fa-user-plus me-1"></i> Participate</button>
              <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>

@push('styles')
<style>
/* Post Card Styling */
.post-card {
  transition: all 0.3s ease;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  background: #fff;
}

.post-card .thumb { position: relative; overflow: hidden; }
.post-card .thumb img { width:100%; height:220px; object-fit:cover; transition:transform 0.3s ease; display:block; }
.post-card:hover .thumb img { transform:scale(1.03); }

/* Like Button */
.like-btn {
  position: absolute;
  top: 12px;
  right: 12px;
  background: rgba(255,255,255,0.95);
  border: none;
  border-radius: 50px;
  padding: 6px 10px;
  cursor: pointer;
  display:flex; align-items:center; gap:6px;
}
.like-btn.liked { background:#ff4757; color:#fff; }

.info { padding:16px; }
.info h4 { margin-bottom:8px; font-size:18px; }
.description { color:#666; font-size:14px; margin-bottom:12px; }
.post-meta { display:flex; justify-content:space-between; align-items:center; font-size:13px; color:#999; }
.post-actions { display:flex; gap:8px; margin-top:12px; }
.post-actions .btn { padding:8px 12px; border-radius:8px; font-size:13px; }
.btn-comment {
  background: #2c3e50; /* dark background so white text is readable */
  color: #fff !important; /* always white text as requested */
  border: 1px solid rgba(0,0,0,0.08);
}
.btn-comment.btn-success {
  background: #28a745 !important;
  color: #fff !important;
  border-color: #28a745 !important;
}
.btn-share { background:#f8f9fa; color:#666; border:1px solid #e9ecef; }

/* Ensure modal participate button text is white as well */
#participateBtn { color: #fff !important; }

/* Strong rule to force participate buttons to white text and enforce backgrounds */
.participate-btn-fixed {
  background: #2c3e50 !important;
  color: #fff !important;
  border-color: rgba(0,0,0,0.08) !important;
}
.participate-btn-fixed.btn-success {
  background: #28a745 !important;
  color: #fff !important;
  border-color: #28a745 !important;
}

.no-posts { padding:40px 0; }

/* Responsive tweaks */
@media (max-width:768px){ .post-card .thumb img { height:180px; } }
</style>
@endpush

@push('scripts')
<script>
let currentEventId = null;

function toggleLikeEvent(eventId){
  const likeBtn = document.querySelector(`[data-event-id="${eventId}"]`);
  const likeIcon = document.getElementById(`like-icon-event-${eventId}`);
  const likeCount = document.getElementById(`like-count-event-${eventId}`);
  likeBtn.classList.add('loading');

  fetch(`/events/${eventId}/like`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
    .then(r => {
      if (r.status === 401) {
        // not authenticated
        window.location.href = '/login';
        throw new Error('Unauthenticated');
      }
      return r.json();
    })
    .then(data=>{
      if(data.success){
        likeCount.textContent = data.likes;
        if (data.liked) {
          likeBtn.classList.add('liked');
          likeBtn.setAttribute('data-liked','1');
        } else {
          likeBtn.classList.remove('liked');
          likeBtn.setAttribute('data-liked','0');
        }
        likeIcon.style.transform = 'scale(1.18)';
        setTimeout(()=> likeIcon.style.transform='scale(1)', 180);
      } else {
        alert(data.message || 'Error');
      }
    }).catch(e=>{ console.error(e); if (e.message !== 'Unauthenticated') alert('Error liking event'); })
    .finally(()=> likeBtn.classList.remove('loading'));
}

function openEventModal(eventId){
  currentEventId = eventId;
  const modal = new bootstrap.Modal(document.getElementById('eventModal'));
  modal.show();
  fetch(`/events/${eventId}/details`).then(r=>r.json()).then(data=>{
    if(data.success){
      document.getElementById('modal-event-subject').textContent = data.event.subject;
      document.getElementById('modal-event-description').textContent = data.event.description || '';
      document.getElementById('modal-event-date').textContent = data.event.date_time || '';
      // Update modal participate button to reflect current state
      const modalBtn = document.getElementById('participateBtn');
      if(modalBtn){
        modalBtn.setAttribute('data-event-id', eventId);
        if(data.event.is_participating){
          modalBtn.classList.add('btn-success');
          modalBtn.style.background = '#28a745';
          modalBtn.style.color = '#fff';
          modalBtn.textContent = '';
          modalBtn.innerHTML = '<i class="fas fa-user-minus me-1"></i> Cancel Participation';
        } else {
          modalBtn.classList.remove('btn-success');
          modalBtn.style.background = '#2c3e50';
          modalBtn.style.color = '#fff';
          modalBtn.textContent = '';
          modalBtn.innerHTML = '<i class="fas fa-user-plus me-1"></i> Participate';
        }
      }
    }
  }).catch(e=>console.error(e));
}

function participateEvent(){
  if(!currentEventId) return;
  const btn = document.getElementById('participateBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing';

  fetch(`/events/${currentEventId}/participate`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
    .then(r => {
      if (r.status === 401) { window.location.href = '/login'; throw new Error('Unauthenticated'); }
      return r.json();
    })
    .then(data=>{
      if(data.success){
        const countEl = document.getElementById(`participant-count-${currentEventId}`);
        if(countEl) countEl.textContent = data.participants_count;
        // Update any participate button for this event
        const cardBtn = document.getElementById(`participate-button-${currentEventId}`);
        if (cardBtn) {
          cardBtn.setAttribute('data-participating', data.participating ? '1' : '0');
          const label = cardBtn.querySelector('.participate-label');
          if(label) label.textContent = data.participating ? 'Cancel' : 'Participate';
          if(data.participating) {
            cardBtn.classList.add('btn-success');
            cardBtn.style.background = '#28a745';
            cardBtn.style.color = '#fff';
          } else {
            cardBtn.classList.remove('btn-success');
            cardBtn.style.background = '#2c3e50';
            cardBtn.style.color = '#fff';
          }
        }

        const modal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
        if(modal) modal.hide();
      } else {
        alert('Error: ' + (data.message || 'Unable to process participation'));
      }
    }).catch(e=>{ console.error(e); if (e.message !== 'Unauthenticated') alert('Error participating'); })
    .finally(()=>{ btn.disabled = false; btn.innerHTML = '<i class="fas fa-user-plus me-1"></i> Participate'; });
}

document.addEventListener('DOMContentLoaded', function() {
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }
});

// Simple share handler (uses Web Share API when available)
function sharePost(eventId){
  const url = window.location.origin + '/events#' + eventId;
  const title = document.querySelector(`#modal-event-subject`)?.textContent || document.querySelector(`.post-card [data-event-id='${eventId}']`)?.closest('.post-card')?.querySelector('h4 a')?.textContent || 'Event';
  if (navigator.share) {
    navigator.share({ title, url }).catch(()=>{});
  } else {
    // fallback: copy url to clipboard
    navigator.clipboard?.writeText(url).then(()=> alert('Event link copied to clipboard'))
      .catch(()=> window.prompt('Copy this link', url));
  }
}
</script>
@endpush

@endsection
