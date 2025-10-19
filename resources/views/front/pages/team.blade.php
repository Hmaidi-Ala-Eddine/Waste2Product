@extends('layouts.front')

@section('title', 'Community Posts')

@section('content')
    <div class="team-style-two-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Community Posts</h4>
                        <h2 class="title split-text">Discover inspiring stories from our community</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                @forelse($posts as $post)
                    <div class="col-lg-4 col-md-6 team-style-two wow fadeInUp" data-wow-delay="{{ ($loop->index * 0.1) }}s">
                        <div class="team-style-two-item post-card" style="background-image: url({{ asset('assets/front/img/shape/15.webp') }});">
                            <div class="thumb">
                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}" 
                                         alt="{{ $post->title }}"
                                         onerror="this.src='{{ asset('assets/front/img/demo/home-1.jpg') }}'; this.onerror=null;">
                                @else
                                    <img src="{{ asset('assets/front/img/demo/home-1.jpg') }}" alt="Default Post Image">
                                @endif
                                
                                <!-- Like Button -->
                                <button class="like-btn {{ $post->is_liked ? 'liked' : '' }}" onclick="toggleLike({{ $post->id }})" data-post-id="{{ $post->id }}" data-liked="{{ $post->is_liked ? '1' : '0' }}">
                                    <i class="fas fa-heart" id="like-icon-{{ $post->id }}"></i>
                                    <span id="like-count-{{ $post->id }}">{{ $post->likes }}</span>
                                </button>
                            </div>
                            <div class="info">
                                <h4><a href="#">{{ Str::limit($post->title, 30) }}</a></h4>
                                <p class="description">{{ Str::limit($post->description, 80) }}</p>
                                <div class="post-meta">
                                    <small class="text-muted">By {{ $post->user->name }}</small>
                                    <span class="date">{{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="post-actions mt-3">
                                    <button class="btn btn-comment" onclick="openCommentModal({{ $post->id }})">
                                        <i class="fas fa-comment"></i> 
                                        <span id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span> Comments
                                    </button>
                                    <button class="btn btn-share" onclick="sharePost({{ $post->id }})">
                                        <i class="fas fa-share-alt"></i> Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <div class="no-posts">
                            <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                            <h4>No posts available</h4>
                            <p class="text-muted">Check back later for community updates!</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="row">
                    <div class="col-12 text-center mt-4">
                        <div class="pagination-wrapper">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Comment Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">
                        <i class="fas fa-comments me-2"></i>Post Comments
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Post Info -->
                    <div class="post-info mb-4">
                        <h6 id="modal-post-title" class="fw-bold"></h6>
                        <p id="modal-post-description" class="text-muted"></p>
                    </div>
                    
                    <!-- Add Comment Form -->
                    <div class="add-comment-section mb-4">
                        <h6>Add a Comment</h6>
                        <form id="commentForm" onsubmit="submitComment(event)">
                            <div class="mb-3">
                                <label for="commentText" class="form-label">Your Comment</label>
                                <textarea class="form-control" id="commentText" rows="3" placeholder="Share your thoughts..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="commenterName" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="commenterName" placeholder="Enter your name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Post Comment
                            </button>
                        </form>
                    </div>
                    
                    <!-- Comments List -->
                    <div class="comments-section">
                        <h6>Comments <span id="modal-comment-count" class="badge bg-secondary"></span></h6>
                        <div id="comments-list">
                            <!-- Comments will be loaded here -->
                        </div>
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
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.post-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.post-card .thumb {
    position: relative;
    overflow: hidden;
}

.post-card .thumb img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.post-card:hover .thumb img {
    transform: scale(1.05);
}

/* Like Button */
.like-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50px;
    padding: 8px 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 5px;
}

.like-btn:hover {
    background: rgba(255, 255, 255, 1);
    transform: scale(1.05);
}

.like-btn.liked {
    background: #ff4757;
    color: white;
}

.like-btn i {
    font-size: 14px;
}

/* Info Section */
.post-card .info {
    padding: 20px;
}

.post-card .info h4 {
    margin-bottom: 10px;
    font-size: 18px;
    line-height: 1.4;
}

.post-card .info h4 a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.post-card .info h4 a:hover {
    color: #4a90e2;
}

.description {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.post-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 12px;
}

.post-meta .date {
    color: #999;
}

.post-actions {
    display: flex;
    gap: 10px;
}

.post-actions .btn {
    flex: 1;
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.btn-comment {
    background: #4a90e2;
    color: white;
}

.btn-comment:hover {
    background: #357abd;
    transform: translateY(-1px);
}

.btn-share {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #e9ecef;
}

.btn-share:hover {
    background: #e9ecef;
    transform: translateY(-1px);
}

/* No Posts Styling */
.no-posts {
    padding: 60px 20px;
}

/* Modal Styling */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.modal-header {
    background: linear-gradient(135deg, #4a90e2, #5aa3f0);
    color: white;
    border-bottom: none;
    border-radius: 15px 15px 0 0;
}

.modal-header .btn-close {
    filter: invert(1);
}

.post-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #4a90e2;
}

.add-comment-section {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.comments-section {
    max-height: 400px;
    overflow-y: auto;
}

.comment-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    border-left: 3px solid #4a90e2;
}

.comment-author {
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.comment-text {
    color: #666;
    margin-bottom: 5px;
}

.comment-date {
    font-size: 11px;
    color: #999;
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
    .post-actions {
        flex-direction: column;
    }
    
    .post-actions .btn {
        margin-bottom: 5px;
    }
    
    .post-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentPostId = null;

// Toggle Like Function
function toggleLike(postId) {
    const likeBtn = document.querySelector(`[data-post-id="${postId}"]`);
    const likeIcon = document.getElementById(`like-icon-${postId}`);
    const likeCount = document.getElementById(`like-count-${postId}`);
    
    // Add loading state
    likeBtn.classList.add('loading');
    
    fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.status === 401) { window.location.href = '/login'; throw new Error('Unauthenticated'); }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            likeCount.textContent = data.likes;
            if (data.liked) {
                likeBtn.classList.add('liked');
            } else {
                likeBtn.classList.remove('liked');
            }
            // Animation effect
            likeIcon.style.transform = 'scale(1.3)';
            setTimeout(() => { likeIcon.style.transform = 'scale(1)'; }, 200);
        } else {
            alert('Error: ' + (data.message || 'Unable to like post'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error liking post');
    })
    .finally(() => {
        likeBtn.classList.remove('loading');
    });
}

// Open Comment Modal
function openCommentModal(postId) {
    currentPostId = postId;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('commentModal'));
    modal.show();
    
    // Load post data and comments
    loadPostComments(postId);
}

// Load Post Comments
function loadPostComments(postId) {
    fetch(`/posts/${postId}/comments`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update modal content
            document.getElementById('modal-post-title').textContent = data.post.title;
            document.getElementById('modal-post-description').textContent = data.post.description;
            document.getElementById('modal-comment-count').textContent = data.comments.length;
            
            // Load comments
            const commentsList = document.getElementById('comments-list');
            commentsList.innerHTML = '';
            
            if (data.comments.length === 0) {
                commentsList.innerHTML = '<p class="text-muted text-center py-3">No comments yet. Be the first to comment!</p>';
            } else {
                data.comments.forEach(comment => {
                    const commentHtml = `
                        <div class="comment-item">
                            <div class="comment-author">${comment.user_name}</div>
                            <div class="comment-text">${comment.comment}</div>
                            <div class="comment-date">${new Date(comment.created_at).toLocaleDateString()}</div>
                        </div>
                    `;
                    commentsList.innerHTML += commentHtml;
                });
            }
        }
    })
    .catch(error => {
        console.error('Error loading comments:', error);
        document.getElementById('comments-list').innerHTML = '<p class="text-danger text-center">Error loading comments</p>';
    });
}

// Submit Comment
function submitComment(event) {
    event.preventDefault();
    
    const commentText = document.getElementById('commentText').value;
    const commenterName = document.getElementById('commenterName').value;
    const submitBtn = event.target.querySelector('button[type="submit"]');
    
    if (!commentText.trim() || !commenterName.trim()) {
        alert('Please fill in all fields');
        return;
    }
    
    // Add loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Posting...';
    
    fetch(`/posts/${currentPostId}/comments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            comment: commentText,
            user_name: commenterName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear form
            document.getElementById('commentText').value = '';
            document.getElementById('commenterName').value = '';
            
            // Reload comments
            loadPostComments(currentPostId);
            
            // Update comment count in main page
            const mainCommentCount = document.getElementById(`comment-count-${currentPostId}`);
            if (mainCommentCount) {
                mainCommentCount.textContent = parseInt(mainCommentCount.textContent) + 1;
            }
            
            // Show success message
            alert('Comment added successfully!');
        } else {
            alert('Error: ' + (data.message || 'Unable to add comment'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding comment');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Post Comment';
    });
}

// Share Post Function
function sharePost(postId) {
    const url = window.location.origin + `/posts/${postId}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Check out this post',
            url: url
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Post link copied to clipboard!');
        }).catch(() => {
            prompt('Copy this link:', url);
        });
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token to meta tag if not present
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }
});
</script>
@endpush

@endsection


