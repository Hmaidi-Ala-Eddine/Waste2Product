@extends('layouts.front')

@section('title', 'Community Posts')

@push('styles')
<style>
    /* Navbar text color fix for posts page */
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .navbar-nav > li > a:hover,
    .navbar-nav > li > a:focus {
        color: #667eea !important;
    }

    .navbar.white .navbar-nav > li > a,
    .navbar.navbar-scrolled .navbar-nav > li > a {
        color: #2c3e50 !important;
    }

    .posts-wrapper {
        background: linear-gradient(135deg, #667eea15 0%, #764ba220 20%, #ffffff 50%, #f8f9fa 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
        position: relative;
        overflow: hidden;
    }

    .posts-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .posts-wrapper::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(118, 75, 162, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .posts-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .posts-header h1 {
        font-size: 48px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 12px;
        letter-spacing: -1px;
    }

    .posts-header p {
        font-size: 18px;
        color: #7f8c8d;
        font-weight: 400;
    }

    .posts-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
        position: relative;
        z-index: 1;
    }

    .view-controls {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 30px;
        gap: 15px;
    }

    .view-toggle {
        display: flex;
        gap: 5px;
        background: rgba(255, 255, 255, 0.95);
        padding: 5px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08), 0 2px 6px rgba(102, 126, 234, 0.1);
        backdrop-filter: blur(10px);
    }

    .view-btn {
        padding: 10px 18px;
        border: none;
        background: transparent;
        color: #7f8c8d;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 600;
    }

    .view-btn:hover {
        color: #667eea;
    }

    .view-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
    }

    .view-btn i {
        margin-right: 5px;
    }

    /* Grid View Styles */
    .posts-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        align-items: start;
    }

    /* List View Styles - Centered like Facebook */
    .posts-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 680px;
        margin: 0 auto;
    }

    .posts-list .post-card {
        display: flex;
        flex-direction: column;
        max-width: 100%;
    }

    .posts-list .post-image-wrapper {
        width: 100%;
        height: auto;
        min-height: 400px;
        max-height: 680px;
        cursor: pointer;
    }

    .posts-list .post-content-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Grid View - Post Card */
    .post-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(102, 126, 234, 0.05);
        display: flex;
        flex-direction: column;
        height: auto;
        border: 1px solid rgba(102, 126, 234, 0.08);
    }

    .post-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(102, 126, 234, 0.25), 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: rgba(102, 126, 234, 0.15);
    }

    .post-image-wrapper {
        position: relative;
        width: 100%;
        height: 250px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        cursor: pointer;
    }

    .post-image-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.4) 100%);
        z-index: 1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .post-card:hover .post-image-wrapper::before {
        opacity: 1;
    }

    .post-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .post-card:hover .post-image {
        transform: scale(1.05);
    }

    .post-content-wrapper {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .post-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .post-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2.5px solid #667eea;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
    }

    .post-author-info h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #2c3e50;
    }

    .post-author-info span {
        font-size: 12px;
        color: #95a5a6;
        font-weight: 500;
    }

    .post-body {
        flex: 1;
    }

    .post-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .post-description {
        font-size: 14px;
        color: #5a6c7d;
        line-height: 1.6;
        margin-bottom: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* List view adjustments */
    .posts-list .post-title {
        font-size: 24px;
        -webkit-line-clamp: 2;
    }

    .posts-list .post-description {
        font-size: 15px;
        -webkit-line-clamp: 4;
    }

    .post-actions {
        padding: 18px 25px;
        border-top: 1px solid #f0f2f5;
        display: flex;
        gap: 15px;
        background: #fafbfc;
    }

    .action-btn {
        flex: 1;
        background: white;
        border: 1.5px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
        color: #7f8c8d;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 10px 15px;
        border-radius: 10px;
        font-weight: 600;
    }

    .action-btn:hover {
        border-color: #667eea;
        background: #f8f9ff;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .action-btn.liked {
        background: linear-gradient(135deg, #ff6b9d 0%, #c7398c 100%);
        border-color: #ff6b9d;
        color: white;
    }

    .action-btn.liked:hover {
        background: linear-gradient(135deg, #ff8cb0 0%, #d64fa4 100%);
    }

    .action-btn i {
        font-size: 16px;
    }

    .action-btn .count {
        font-weight: 700;
    }

    /* Guest User Action Section */
    .guest-actions-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .action-stats {
        display: flex;
        gap: 15px;
        padding: 10px 16px;
        background: white;
        border-radius: 10px;
        border: 1.5px solid #e9ecef;
        flex-shrink: 0;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #7f8c8d;
        font-weight: 600;
        font-size: 14px;
    }

    .stat-item i {
        font-size: 16px;
        color: #95a5a6;
    }

    .stat-item .count {
        font-weight: 700;
        color: #7f8c8d;
    }

    .login-prompt {
        flex: 1;
        text-align: center;
        padding: 10px 20px;
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        border-radius: 10px;
        color: #856404;
        font-size: 14px;
        font-weight: 600;
        border: 1.5px solid #ffeaa7;
    }

    .login-prompt a {
        color: #667eea;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        border-bottom: 2px solid transparent;
    }

    .login-prompt a:hover {
        color: #764ba2;
        border-bottom-color: #764ba2;
    }

    .comments-section {
        padding: 0;
        background: white;
        border-top: 1px solid #e9ecef;
        display: none;
    }

    .comments-section.active {
        display: block;
    }

    .comments-list {
        max-height: 400px;
        overflow-y: auto;
        padding: 20px 25px;
    }

    .comment-item {
        display: flex;
        gap: 12px;
        margin-bottom: 18px;
    }

    .comment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .comment-content {
        flex: 1;
    }

    .comment-bubble {
        background: #f0f2f5;
        padding: 10px 14px;
        border-radius: 18px;
        display: inline-block;
        max-width: 100%;
    }

    .comment-author {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 3px;
        font-size: 13px;
    }

    .comment-text {
        color: #1c1e21;
        font-size: 14px;
        line-height: 1.4;
        word-wrap: break-word;
    }

    .comment-time {
        font-size: 12px;
        color: #65676b;
        margin-top: 4px;
        margin-left: 14px;
    }

    .comment-form {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 25px 20px;
        border-top: 1px solid #e9ecef;
        background: #fafbfc;
    }

    .comment-user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .comment-input-wrapper {
        flex: 1;
        position: relative;
    }

    .comment-input {
        width: 100%;
        padding: 10px 45px 10px 16px;
        border: none;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
        background: #f0f2f5;
        transition: all 0.3s ease;
    }

    .comment-input:focus {
        background: #e4e6eb;
    }

    .comment-submit {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        padding: 6px 12px;
        background: transparent;
        color: #667eea;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .comment-submit:hover {
        background: rgba(102, 126, 234, 0.1);
    }

    .comment-submit:disabled {
        color: #bdc3c7;
        cursor: not-allowed;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(102, 126, 234, 0.05);
        border: 1px solid rgba(102, 126, 234, 0.1);
        backdrop-filter: blur(10px);
    }

    .empty-state i {
        font-size: 64px;
        color: #e0e0e0;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: #7f8c8d;
        font-size: 20px;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #95a5a6;
        font-size: 15px;
    }

    .pagination {
        margin-top: 40px;
    }

    /* Image Lightbox */
    .image-lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .image-lightbox.active {
        display: flex;
    }

    .lightbox-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
    }

    .lightbox-image {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
    }

    .lightbox-close {
        position: absolute;
        top: -50px;
        right: 0;
        background: transparent;
        border: none;
        color: white;
        font-size: 36px;
        cursor: pointer;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .lightbox-close:hover {
        transform: rotate(90deg);
    }

    @media (max-width: 1200px) {
        .posts-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }
    }

    @media (max-width: 768px) {
        .posts-wrapper {
            padding-top: 100px;
        }

        .posts-header h1 {
            font-size: 36px;
        }

        .posts-header p {
            font-size: 16px;
        }

        .posts-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .posts-list .post-card {
            flex-direction: column;
        }

        .posts-list .post-image-wrapper {
            flex: none;
            height: 220px;
        }

        .post-image-wrapper {
            height: 220px;
        }

        .view-controls {
            justify-content: center;
        }

        .view-btn {
            padding: 8px 14px;
            font-size: 13px;
        }

        .post-content-wrapper {
            padding: 20px;
        }

        .post-title {
            font-size: 18px;
        }

        .post-description {
            font-size: 13px;
        }
    }
</style>
@endpush

@section('content')
<div class="posts-wrapper">
    <div class="posts-container">
        <!-- Posts Header -->
        <div class="posts-header">
            <h1>Community Posts</h1>
            <p>Share ideas, updates, and connect with the Waste2Product community</p>
        </div>

        <!-- View Controls -->
        <div class="view-controls">
            <div class="view-toggle">
                <button class="view-btn" data-view="grid">
                    <i class="fas fa-th"></i> Grid
                </button>
                <button class="view-btn active" data-view="list">
                    <i class="fas fa-list"></i> List
                </button>
            </div>
        </div>

        <!-- Posts Grid/List -->
        @if($posts->count() > 0)
            <div class="posts-list" id="postsContainer">
                @foreach($posts as $post)
                <div class="post-card" data-post-id="{{ $post->id }}">
                    <!-- Post Image -->
                    @if($post->image)
                    <div class="post-image-wrapper">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="post-image">
                    </div>
                    @endif

                    <!-- Post Content -->
                    <div class="post-content-wrapper">
                        <!-- Post Header -->
                        <div class="post-header">
                            <img src="{{ $post->user->profile_picture_url }}" alt="{{ $post->user->name }}" class="post-avatar">
                            <div class="post-author-info">
                                <h4>{{ $post->user->name }}</h4>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Post Body -->
                        <div class="post-body">
                            <h2 class="post-title">{{ $post->title }}</h2>
                            <p class="post-description">{{ $post->description }}</p>
                        </div>

                        <!-- Post Actions -->
                        <div class="post-actions">
                            @auth
                                <button class="action-btn like-btn" data-post-id="{{ $post->id }}">
                                    <i class="fas fa-heart"></i>
                                    <span class="count likes-count">{{ $post->likes }}</span>
                                </button>
                                <button class="action-btn comment-toggle-btn">
                                    <i class="fas fa-comment"></i>
                                    <span class="count">{{ $post->comments->count() }}</span>
                                </button>
                            @else
                                <div class="guest-actions-wrapper">
                                    <div class="action-stats">
                                        <span class="stat-item">
                                            <i class="fas fa-heart"></i>
                                            <span class="count">{{ $post->likes }}</span>
                                        </span>
                                        <span class="stat-item">
                                            <i class="fas fa-comment"></i>
                                            <span class="count">{{ $post->comments->count() }}</span>
                                        </span>
                                    </div>
                                    <div class="login-prompt">
                                        <a href="{{ route('front.login') }}">Login</a> to like and comment
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Comments Section (Hidden by default) -->
                    @auth
                        <div class="comments-section">
                            <div class="comments-list">
                                @foreach($post->comments()->latest()->take(5)->get() as $comment)
                                <div class="comment-item">
                                    <img src="{{ $comment->user->profile_picture_url ?? auth()->user()->profile_picture_url }}" alt="{{ $comment->user_name }}" class="comment-avatar">
                                    <div class="comment-content">
                                        <div class="comment-bubble">
                                            <div class="comment-author">{{ $comment->user_name }}</div>
                                            <div class="comment-text">{{ $comment->comment }}</div>
                                        </div>
                                        <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Add Comment Form -->
                            <form class="comment-form" data-post-id="{{ $post->id }}">
                                @csrf
                                <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}" class="comment-user-avatar">
                                <div class="comment-input-wrapper">
                                    <input type="hidden" name="user_name" value="{{ auth()->user()->name }}">
                                    <input type="text" name="comment" class="comment-input" placeholder="Write a comment..." required>
                                    <button type="submit" class="comment-submit" disabled>
                                        Post
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endauth
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $posts->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Posts Yet</h3>
                <p>Be the first to share something with the community!</p>
            </div>
        @endif
    </div>
</div>

<!-- Image Lightbox -->
<div class="image-lightbox" id="imageLightbox">
    <div class="lightbox-content">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="fas fa-times"></i>
        </button>
        <img src="" alt="" class="lightbox-image" id="lightboxImage">
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Toggle (Grid/List)
    const viewBtns = document.querySelectorAll('.view-btn');
    const postsContainer = document.getElementById('postsContainer');
    
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active button
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Switch view
            if (view === 'grid') {
                postsContainer.className = 'posts-grid';
            } else {
                postsContainer.className = 'posts-list';
            }
            
            // Save preference to localStorage
            localStorage.setItem('postsView', view);
        });
    });
    
    // Load saved view preference (default to list)
    const savedView = localStorage.getItem('postsView') || 'list';
    const savedViewBtn = document.querySelector(`.view-btn[data-view="${savedView}"]`);
    if (savedViewBtn) {
        savedViewBtn.click();
    }

    // Image Lightbox
    const lightbox = document.getElementById('imageLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    
    document.querySelectorAll('.post-image-wrapper').forEach(wrapper => {
        wrapper.addEventListener('click', function() {
            const img = this.querySelector('.post-image');
            if (img) {
                lightboxImage.src = img.src;
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });
    
    window.closeLightbox = function() {
        lightbox.classList.remove('active');
        document.body.style.overflow = 'auto';
    };
    
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });

    // Toggle comments section
    document.querySelectorAll('.comment-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.post-card');
            const commentsSection = card.querySelector('.comments-section');
            commentsSection.classList.toggle('active');
        });
    });
    
    // Enable/disable post button based on input
    document.querySelectorAll('.comment-input').forEach(input => {
        input.addEventListener('input', function() {
            const submitBtn = this.closest('.comment-input-wrapper').querySelector('.comment-submit');
            submitBtn.disabled = this.value.trim() === '';
        });
    });

    // Like/Unlike button functionality - Facebook style (unique users only)
    let likedPosts = JSON.parse(localStorage.getItem('likedPosts') || '[]');
    
    // Mark already liked posts and set correct counts
    likedPosts.forEach(postId => {
        const btn = document.querySelector(`.like-btn[data-post-id="${postId}"]`);
        if (btn) {
            btn.classList.add('liked');
        }
    });
    
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const likesCountSpan = this.querySelector('.likes-count');
            const currentCount = parseInt(likesCountSpan.textContent);
            const isLiked = this.classList.contains('liked');
            
            // Prevent multiple rapid clicks
            if (this.disabled) return;
            this.disabled = true;
            
            // Call API for both like and unlike
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update count from backend
                    likesCountSpan.textContent = data.likes;
                    
                    // Update UI and localStorage based on backend action
                    if (data.action === 'liked') {
                        this.classList.add('liked');
                        if (!likedPosts.includes(postId)) {
                            likedPosts.push(postId);
                        }
                    } else {
                        this.classList.remove('liked');
                        const index = likedPosts.indexOf(postId);
                        if (index > -1) {
                            likedPosts.splice(index, 1);
                        }
                    }
                    
                    localStorage.setItem('likedPosts', JSON.stringify(likedPosts));
                }
                this.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert on error
                likesCountSpan.textContent = currentCount;
                if (isLiked) {
                    this.classList.add('liked');
                } else {
                    this.classList.remove('liked');
                }
                this.disabled = false;
            });
        });
    });

    // Comment form submission
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const postId = this.dataset.postId;
            const commentInput = this.querySelector('input[name="comment"]');
            const userName = this.querySelector('input[name="user_name"]').value;
            const comment = commentInput.value;
            const submitBtn = this.querySelector('.comment-submit');
            
            if (!comment.trim()) return;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Posting...';
            
            fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_name: userName,
                    comment: comment
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentsList = this.closest('.comments-section').querySelector('.comments-list');
                    const newComment = document.createElement('div');
                    newComment.className = 'comment-item';
                    newComment.innerHTML = `
                        <img src="${data.comment.user_avatar}" alt="${data.comment.user_name}" class="comment-avatar">
                        <div class="comment-content">
                            <div class="comment-bubble">
                                <div class="comment-author">${data.comment.user_name}</div>
                                <div class="comment-text">${data.comment.comment}</div>
                            </div>
                            <div class="comment-time">Just now</div>
                        </div>
                    `;
                    commentsList.insertBefore(newComment, commentsList.firstChild);
                    
                    // Clear input
                    commentInput.value = '';
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Post';
                    
                    // Update comment count
                    const card = this.closest('.post-card');
                    const commentBtn = card.querySelector('.comment-toggle-btn .count');
                    commentBtn.textContent = parseInt(commentBtn.textContent) + 1;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Post';
            });
        });
    });
});
</script>
@endpush
