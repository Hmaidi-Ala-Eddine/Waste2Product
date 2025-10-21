@extends('layouts.front')

@section('title', 'Events')

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

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 40px;
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
            <h1>Community Event</h1>
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
