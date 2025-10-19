@extends('layouts.front')

@section('title', 'Events')

<<<<<<< HEAD
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
=======
@push('styles')
<style>
    /* Navbar text color fix for events page */
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .navbar-nav > li > a:hover,
    .navbar-nav > li > a:focus {
        color: #4CAF50 !important;
    }

    .events-wrapper {
        background: linear-gradient(135deg, #e8f5e9 0%, #f1f8f4 30%, #ffffff 60%, #f8f9fa 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
        position: relative;
        overflow: hidden;
    }

    .events-wrapper::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -15%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .events-wrapper::after {
        content: '';
        position: absolute;
        bottom: -20%;
        left: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(102, 187, 106, 0.06) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .events-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
        position: relative;
        z-index: 1;
    }

    .events-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .events-header h1 {
        font-size: 48px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 12px;
        letter-spacing: -1px;
    }

    .events-header p {
        font-size: 18px;
        color: #7f8c8d;
        font-weight: 400;
    }

    .events-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        max-width: 500px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 14px 50px 14px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
    }

    .search-box input:focus {
        outline: none;
        border-color: #4CAF50;
        box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
    }

    .search-box button {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-box button:hover {
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .add-event-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.25);
        text-decoration: none;
    }

    .add-event-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.35);
        color: white;
    }

    .add-event-btn i {
        font-size: 18px;
    }

    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .event-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .event-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    }

    .event-content {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .event-date-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 15px;
        width: fit-content;
    }

    .event-title {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
        line-height: 1.3;
    }

    .event-description {
        font-size: 14px;
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 20px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .event-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        margin-bottom: 20px;
    }

    .event-author {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .author-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .author-name {
        font-size: 13px;
        color: #2c3e50;
        font-weight: 600;
    }

    .engagement-count {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #4CAF50;
        font-weight: 600;
        font-size: 14px;
    }

    .engagement-count i {
        font-size: 16px;
    }

    .event-actions {
        display: flex;
        gap: 10px;
    }

    .event-btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .participate-btn {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
    }

    .participate-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(33, 150, 243, 0.3);
        color: white;
    }

    .participate-btn.participated {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    }

    .participate-btn:disabled {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .participate-btn:disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .event-ended-badge {
        flex: 1;
        text-align: center;
        padding: 12px 20px;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .share-btn {
        background: white;
        color: #4CAF50;
        border: 2px solid #4CAF50;
    }

    .share-btn:hover {
        background: #4CAF50;
        color: white;
        transform: translateY(-2px);
    }

    .login-prompt {
        text-align: center;
        padding: 12px 20px;
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        border-radius: 10px;
        color: #856404;
        font-size: 14px;
        font-weight: 600;
    }

    .login-prompt a {
        color: #4CAF50;
        text-decoration: underline;
        font-weight: 700;
    }

    .login-prompt a:hover {
        color: #45a049;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 80px;
        color: #e0e0e0;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 100px;
        right: 30px;
        background: white;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 10000;
        transform: translateX(400px);
        transition: transform 0.4s ease;
        min-width: 320px;
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-notification.success {
        border-left: 4px solid #4CAF50;
    }

    .toast-notification.error {
        border-left: 4px solid #f44336;
    }

    .toast-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-notification.success .toast-icon {
        background: #e8f5e9;
        color: #4CAF50;
    }

    .toast-notification.error .toast-icon {
        background: #ffebee;
        color: #f44336;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
        font-size: 15px;
    }

    .toast-message {
        color: #7f8c8d;
        font-size: 14px;
    }

    .toast-close {
        background: none;
        border: none;
        color: #7f8c8d;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .toast-close:hover {
        background: #f0f0f0;
        color: #2c3e50;
    }

    /* Add Event Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideDown 0.3s ease;
    }

    .modal-header {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 20px 20px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 0;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #4CAF50;
        box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .image-upload {
        border: 2px dashed #e0e0e0;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .image-upload:hover {
        border-color: #4CAF50;
        background: rgba(76, 175, 80, 0.02);
    }

    .image-upload i {
        font-size: 48px;
        color: #4CAF50;
        margin-bottom: 10px;
    }

    .image-upload input {
        display: none;
    }

    .image-upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .image-preview {
        display: none;
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .image-preview.active {
        display: block;
    }

    .image-filename {
        display: none;
        color: #2c3e50;
        font-weight: 600;
        font-size: 14px;
        word-break: break-word;
        max-width: 100%;
        padding: 8px 12px;
        background: #e8f5e9;
        border-radius: 8px;
        border-left: 3px solid #4CAF50;
    }

    .image-filename.active {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .image-filename i {
        color: #4CAF50;
        font-size: 16px;
    }

    .change-image-btn {
        display: none;
        background: none;
        border: none;
        color: #667eea;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0;
        text-decoration: underline;
        text-decoration-color: transparent;
        margin-top: 5px;
    }

    .change-image-btn.active {
        display: inline-block;
    }

    .change-image-btn:hover {
        color: #764ba2;
        text-decoration-color: #764ba2;
    }

    .change-image-btn i {
        font-size: 12px;
    }

    .upload-icon-text {
        transition: all 0.3s ease;
    }

    .upload-icon-text.hidden {
        display: none;
    }

    .submit-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @media (max-width: 768px) {
        .events-header h1 {
            font-size: 36px;
        }

        .events-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .events-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="events-wrapper">
    <div class="events-container">
        <!-- Events Header -->
        <div class="events-header">
            <h1>Community Events</h1>
            <p>Join our community events and make a difference</p>
        </div>

        <!-- Events Controls -->
        <div class="events-controls">
            <!-- Search Box -->
            <form method="GET" action="{{ route('front.events') }}" class="search-box">
                <input type="text" name="search" placeholder="Search events..." value="{{ request('search') }}">
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <!-- Add Event Button (Admin Only) -->
            @if(auth()->check() && auth()->user()->role === 'admin')
                <button class="add-event-btn" onclick="openAddEventModal()">
                    <i class="fas fa-plus-circle"></i>
                    <span>Create Event</span>
                </button>
            @endif
        </div>

        <!-- Events Grid -->
        @if($events->count() > 0)
            <div class="events-grid">
                @foreach($events as $event)
                <div class="event-card">
                    <!-- Event Image -->
                    <img src="{{ $event->image_url }}" alt="{{ $event->subject }}" class="event-image">

                    <!-- Event Content -->
                    <div class="event-content">
                        <!-- Date Badge -->
                        <div class="event-date-badge">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $event->date_time->format('M d, Y - H:i') }}</span>
                        </div>

                        <!-- Event Title -->
                        <h3 class="event-title">{{ $event->subject }}</h3>

                        <!-- Event Description -->
                        <p class="event-description">{{ $event->description }}</p>

                        <!-- Event Meta -->
                        <div class="event-meta">
                            <div class="event-author">
                                <img src="{{ $event->author->profile_picture_url }}" alt="{{ $event->author->name }}" class="author-avatar">
                                <span class="author-name">{{ $event->author->name }}</span>
                            </div>
                            <div class="engagement-count">
                                <i class="fas fa-users"></i>
                                <span>{{ $event->engagement ?? 0 }}</span>
                            </div>
                        </div>

                        <!-- Event Actions -->
                        @auth
                            @php
                                $isParticipated = $event->isParticipatedBy(auth()->id());
                                $isPastEvent = $event->date_time < now();
                            @endphp
                            
                            @if($isPastEvent)
                                <!-- Past Event Badge -->
                                <div class="event-ended-badge">
                                    <i class="fas fa-calendar-times"></i>
                                    <span>Event Ended</span>
                                </div>
                            @else
                                <!-- Active Event Actions -->
                                <div class="event-actions">
                                    <button class="event-btn participate-btn {{ $isParticipated ? 'participated' : '' }}" 
                                            data-event-id="{{ $event->id }}" 
                                            data-participated="{{ $isParticipated ? 'true' : 'false' }}" 
                                            onclick="toggleParticipation({{ $event->id }}, this)">
                                        @if($isParticipated)
                                            <i class="fas fa-check"></i>
                                            <span>Registered</span>
                                        @else
                                            <i class="fas fa-hand-paper"></i>
                                            <span>Participate</span>
                                        @endif
                                    </button>
                                    <button class="event-btn share-btn" onclick="shareEvent({{ $event->id }})">
                                        <i class="fas fa-share-alt"></i>
                                        <span>Share</span>
                                    </button>
                                </div>
                            @endif
                        @else
                            @if($event->date_time < now())
                                <!-- Past Event Badge for Guests -->
                                <div class="event-ended-badge">
                                    <i class="fas fa-calendar-times"></i>
                                    <span>Event Ended</span>
                                </div>
                            @else
                                <!-- Login Prompt for Guests -->
                                <div class="login-prompt">
                                    <a href="{{ route('front.login') }}">Login</a> to participate and share
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $events->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>No Events Found</h3>
                <p>Check back later for upcoming events!</p>
            </div>
        @endif
    </div>
</div>

<!-- Add Event Modal (Admin Only) -->
@if(auth()->check() && auth()->user()->role === 'admin')
<div class="modal-overlay" id="addEventModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create New Event</h2>
            <button class="modal-close" onclick="closeAddEventModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('front.events.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="subject">Event Title *</label>
                    <input type="text" id="subject" name="subject" required>
                </div>

                <div class="form-group">
                    <label for="date_time">Date & Time *</label>
                    <input type="datetime-local" id="date_time" name="date_time" required>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label>Event Image</label>
                    <div class="image-upload" id="imageUploadArea">
                        <div class="image-upload-content">
                            <img id="imagePreview" class="image-preview" src="" alt="Preview">
                            <div class="upload-icon-text" id="uploadIconText">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Click to upload image</p>
                            </div>
                            <span id="imageFilename" class="image-filename"></span>
                            <button type="button" class="change-image-btn" id="changeImageBtn">
                                <i class="fas fa-edit"></i> Change
                            </button>
                        </div>
                        <input type="file" id="image" name="image" accept="image/*" onchange="previewEventImage(this)">
                    </div>
                </div>

                <button type="submit" class="submit-btn">Create Event</button>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// Preview Event Image
function previewEventImage(input) {
    const preview = document.getElementById('imagePreview');
    const filename = document.getElementById('imageFilename');
    const uploadIconText = document.getElementById('uploadIconText');
    const changeBtn = document.getElementById('changeImageBtn');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (2MB)
        if (file.size > 2048000) {
            showToast('Error', 'Image size should not exceed 2MB', 'error');
            input.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image.*')) {
            showToast('Error', 'Please select a valid image file', 'error');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.add('active');
            filename.innerHTML = '<i class="fas fa-check-circle"></i> ' + file.name;
            filename.classList.add('active');
            uploadIconText.classList.add('hidden');
            changeBtn.classList.add('active');
        };
        
        reader.readAsDataURL(file);
    }
}

// Handle click on upload area
document.getElementById('imageUploadArea')?.addEventListener('click', function(e) {
    if (!e.target.closest('.change-image-btn')) {
        document.getElementById('image').click();
    }
});

// Handle change image button
document.getElementById('changeImageBtn')?.addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('image').click();
});

// Toast Notification
function showToast(title, message, type = 'success') {
    // Remove existing toast if any
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(toast);

    // Show toast
    setTimeout(() => toast.classList.add('show'), 100);

    // Auto remove after 4 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

// Open Add Event Modal
function openAddEventModal() {
    document.getElementById('addEventModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Close Add Event Modal
function closeAddEventModal() {
    document.getElementById('addEventModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    
    // Reset form and image preview
    const form = document.querySelector('#addEventModal form');
    if (form) form.reset();
    
    const preview = document.getElementById('imagePreview');
    const filename = document.getElementById('imageFilename');
    const uploadIconText = document.getElementById('uploadIconText');
    const changeBtn = document.getElementById('changeImageBtn');
    
    preview?.classList.remove('active');
    filename?.classList.remove('active');
    uploadIconText?.classList.remove('hidden');
    changeBtn?.classList.remove('active');
}

// Close modal on outside click
document.getElementById('addEventModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddEventModal();
    }
});

// Toggle Participate/Cancel
function toggleParticipation(eventId, button) {
    const isParticipated = button.dataset.participated === 'true';
    
    button.disabled = true;

    fetch(`/events/${eventId}/participate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = button.closest('.event-card');
            const engagementSpan = card.querySelector('.engagement-count span');
            
            if (!isParticipated) {
                // Register
                button.classList.add('participated');
                button.innerHTML = '<i class="fas fa-check"></i><span>Registered</span>';
                button.dataset.participated = 'true';
                showToast('Success!', 'You have successfully registered for this event', 'success');
            } else {
                // Cancel
                button.classList.remove('participated');
                button.innerHTML = '<i class="fas fa-hand-paper"></i><span>Participate</span>';
                button.dataset.participated = 'false';
                showToast('Cancelled', 'Your participation has been cancelled', 'success');
            }
            
            engagementSpan.textContent = data.engagement;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        showToast('Error', 'Failed to update participation. Please try again.', 'error');
    });
}

// Share Event
function shareEvent(eventId) {
    const url = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: 'Check out this event!',
            url: url
        }).catch(console.error);
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            showToast('Copied!', 'Event link copied to clipboard', 'success');
        }).catch(() => {
            showToast('Error', 'Failed to copy link', 'error');
        });
    }
}
</script>
@endpush
>>>>>>> f9010f8c69c255961685a1f7f6ae09de76e960f8
