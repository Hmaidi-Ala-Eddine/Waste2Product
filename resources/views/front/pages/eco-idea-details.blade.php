@extends('layouts.front')

@section('title', $ecoIdea->title . ' - Eco Idea')

@push('styles')
<style>
    /* Navbar fix */
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .details-wrapper {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
    }

    .details-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        color: #6b7280;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .back-button:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
        color: white;
        transform: translateX(-5px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .back-button i {
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .back-button:hover i {
        transform: translateX(-3px);
    }

    .idea-hero {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .hero-image {
        width: 100%;
        height: 450px;
        object-fit: cover;
    }

    .hero-content {
        padding: 40px;
    }

    .hero-badges {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .hero-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-waste {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-difficulty {
        background: #fed7aa;
        color: #9a3412;
    }

    .badge-status {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-verified {
        background: #e9d5ff;
        color: #6b21a8;
    }

    .hero-title {
        font-size: 36px;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .hero-meta {
        display: flex;
        align-items: center;
        gap: 25px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .creator-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .creator-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #10b981;
    }

    .creator-details h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
    }

    .creator-details span {
        font-size: 13px;
        color: #6b7280;
    }

    .meta-stats {
        display: flex;
        gap: 20px;
    }

    .stat {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
    }

    .stat i {
        color: #10b981;
        font-size: 18px;
    }

    .stat strong {
        color: #1f2937;
    }

    .hero-description {
        font-size: 16px;
        line-height: 1.8;
        color: #4b5563;
        margin-bottom: 30px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 14px 30px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-secondary {
        background: white;
        color: #10b981;
        padding: 14px 30px;
        border-radius: 12px;
        font-weight: 700;
        border: 2px solid #10b981;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: #f0fdf4;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .btn-liked {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
        border: 2px solid #dc2626 !important;
    }

    .btn-liked:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        border-color: #b91c1c !important;
        color: white !important;
    }

    .content-section {
        background: white;
        border-radius: 20px;
        padding: 35px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 24px;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: #10b981;
        font-size: 28px;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .team-member {
        text-align: center;
        padding: 20px;
        background: #f9fafb;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .team-member:hover {
        background: #f0fdf4;
        transform: translateY(-4px);
    }

    .team-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 12px;
        border: 3px solid #10b981;
    }

    .team-name {
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .team-role {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .team-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #d1fae5;
        color: #065f46;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
    }

    .apply-form {
        background: #f9fafb;
        padding: 30px;
        border-radius: 16px;
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .review-item {
        padding: 20px;
        background: #f9fafb;
        border-radius: 16px;
        margin-bottom: 15px;
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .review-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }

    .review-info h5 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
    }

    .review-info span {
        font-size: 12px;
        color: #9ca3af;
    }

    .review-content {
        font-size: 14px;
        line-height: 1.6;
        color: #4b5563;
    }

    .impact-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .metric-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 20px;
        border-radius: 16px;
        text-align: center;
    }

    .metric-icon {
        font-size: 32px;
        color: #10b981;
        margin-bottom: 10px;
    }

    .metric-value {
        font-size: 24px;
        font-weight: 800;
        color: #065f46;
        margin-bottom: 4px;
    }

    .metric-label {
        font-size: 13px;
        color: #047857;
        font-weight: 600;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
    }

    .alert-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e40af;
    }

    .delete-review-btn:hover {
        background: #dc2626 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 28px;
        }

        .details-wrapper {
            padding-top: 100px;
        }

        .hero-content {
            padding: 25px;
        }

        .content-section {
            padding: 25px;
        }
    }
</style>
@endpush

@section('content')
<div class="details-wrapper">
    <div class="details-container">
        <!-- Back Button -->
        <a href="{{ route('front.eco-ideas') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Eco Ideas</span>
        </a>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Hero Section -->
        <div class="idea-hero">
            @if($ecoIdea->image_path && file_exists(public_path('storage/' . $ecoIdea->image_path)))
                <img src="{{ asset('storage/' . $ecoIdea->image_path) }}" alt="{{ $ecoIdea->title }}" class="hero-image">
            @else
                <img src="https://via.placeholder.com/1200x450/10b981/ffffff?text={{ urlencode($ecoIdea->title) }}" alt="{{ $ecoIdea->title }}" class="hero-image">
            @endif

            <div class="hero-content">
                <div class="hero-badges">
                    <span class="hero-badge badge-waste">
                        <i class="fas fa-recycle"></i> {{ ucfirst($ecoIdea->waste_type) }}
                    </span>
                    <span class="hero-badge badge-difficulty">
                        <i class="fas fa-layer-group"></i> {{ ucfirst($ecoIdea->difficulty_level) }}
                    </span>
                    @if($ecoIdea->project_status === 'verified')
                        <span class="hero-badge badge-verified">
                            <i class="fas fa-check-circle"></i> Verified Project
                        </span>
                    @else
                        <span class="hero-badge badge-status">
                            <i class="fas fa-tasks"></i> {{ ucfirst(str_replace('_', ' ', $ecoIdea->project_status ?? 'idea')) }}
                        </span>
                    @endif
                    @auth
                        @if($ecoIdea->team->where('user_id', auth()->id())->count() > 0)
                            <span class="hero-badge" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                                <i class="fas fa-user-check"></i> You're a Team Member
                            </span>
                        @endif
                    @endauth
                </div>

                <h1 class="hero-title">{{ $ecoIdea->title }}</h1>

                <div class="hero-meta">
                    <div class="creator-info">
                        <img src="{{ $ecoIdea->creator->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($ecoIdea->creator->name ?? 'U') . '&background=10b981&color=fff' }}" 
                             alt="{{ $ecoIdea->creator->name ?? 'Creator' }}" 
                             class="creator-avatar">
                        <div class="creator-details">
                            <h4>{{ $ecoIdea->creator->name ?? 'Unknown Creator' }}</h4>
                            <span>Created {{ $ecoIdea->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="meta-stats">
                        <div class="stat">
                            <i class="fas fa-heart"></i>
                            <strong>{{ $ecoIdea->upvotes ?? 0 }}</strong> Likes
                        </div>
                        <div class="stat">
                            <i class="fas fa-users"></i>
                            <strong>{{ $ecoIdea->team_size_current ?? 1 }}/{{ $ecoIdea->team_size_needed ?? 0 }}</strong> Team
                        </div>
                        @if($ecoIdea->is_recruiting)
                            <div class="stat">
                                <i class="fas fa-user-plus"></i>
                                <span style="color: #10b981; font-weight: 700;">Recruiting Now!</span>
                            </div>
                        @endif
                    </div>
                </div>

                <p class="hero-description">{{ $ecoIdea->description }}</p>

                <div class="action-buttons">
                    @auth
                        <button onclick="likeIdeaDetails({{ $ecoIdea->id }}, this)" 
                                class="btn-secondary {{ $hasLiked ? 'btn-liked' : '' }}"
                                id="like-btn">
                            <i class="fas fa-heart"></i>
                            <span id="like-text">{{ $hasLiked ? 'Unlike' : 'Like' }}</span> (<span id="like-count">{{ $ecoIdea->upvotes ?? 0 }}</span>)
                        </button>

                        @if($ecoIdea->is_recruiting && !$hasApplied && $ecoIdea->project_status !== 'completed' && $ecoIdea->project_status !== 'verified' && $ecoIdea->creator_id !== auth()->id())
                            <button onclick="document.getElementById('apply-section').scrollIntoView({behavior: 'smooth'})" class="btn-primary">
                                <i class="fas fa-user-plus"></i>
                                Apply to Join
                            </button>
                        @elseif($hasApplied)
                            <span class="btn-secondary" style="cursor: not-allowed; opacity: 0.7;">
                                <i class="fas fa-check"></i>
                                Application Submitted
                            </span>
                        @elseif($ecoIdea->creator_id === auth()->id())
                            <span class="btn-secondary" style="cursor: not-allowed; opacity: 0.7;">
                                <i class="fas fa-crown"></i>
                                Your Eco Idea
                            </span>
                        @endif
                    @else
                        <a href="{{ route('front.login') }}" class="btn-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            Login to Like & Apply
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Team Section -->
        @if($ecoIdea->team && $ecoIdea->team->count() > 0)
            <div class="content-section">
                <h2 class="section-title">
                    <i class="fas fa-users"></i>
                    Team Members ({{ $ecoIdea->team->count() }})
                </h2>
                <div class="team-grid">
                    @foreach($ecoIdea->team as $member)
                        <div class="team-member">
                            <img src="{{ $member->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->user->name ?? 'U') . '&background=10b981&color=fff' }}" 
                                 alt="{{ $member->user->name ?? 'Member' }}" 
                                 class="team-avatar">
                            <div class="team-name">{{ $member->user->name ?? 'Member' }}</div>
                            <div class="team-role">{{ $member->specialization ?? $member->role }}</div>
                            @if($member->role === 'leader')
                                <span class="team-badge">Leader</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Requirements Section -->
        @if($ecoIdea->team_requirements)
            <div class="content-section">
                <h2 class="section-title">
                    <i class="fas fa-clipboard-list"></i>
                    Team Requirements
                </h2>
                <p style="font-size: 15px; line-height: 1.8; color: #4b5563;">{{ $ecoIdea->team_requirements }}</p>
            </div>
        @endif

        <!-- Impact Metrics (for verified projects) -->
        @if($ecoIdea->project_status === 'verified' && $ecoIdea->impact_metrics)
            <div class="content-section">
                <h2 class="section-title">
                    <i class="fas fa-chart-line"></i>
                    Impact Achieved
                </h2>
                @if($ecoIdea->final_description)
                    <p style="font-size: 15px; line-height: 1.8; color: #4b5563; margin-bottom: 25px;">{{ $ecoIdea->final_description }}</p>
                @endif
                <div class="impact-metrics">
                    @if(isset($ecoIdea->impact_metrics['waste_reduced']))
                        <div class="metric-card">
                            <div class="metric-icon"><i class="fas fa-recycle"></i></div>
                            <div class="metric-value">{{ $ecoIdea->impact_metrics['waste_reduced'] }}</div>
                            <div class="metric-label">Waste Reduced</div>
                        </div>
                    @endif
                    @if(isset($ecoIdea->impact_metrics['co2_saved']))
                        <div class="metric-card">
                            <div class="metric-icon"><i class="fas fa-leaf"></i></div>
                            <div class="metric-value">{{ $ecoIdea->impact_metrics['co2_saved'] }}</div>
                            <div class="metric-label">CO2 Saved</div>
                        </div>
                    @endif
                    @if(isset($ecoIdea->impact_metrics['people_impacted']))
                        <div class="metric-card">
                            <div class="metric-icon"><i class="fas fa-users"></i></div>
                            <div class="metric-value">{{ $ecoIdea->impact_metrics['people_impacted'] }}</div>
                            <div class="metric-label">People Impacted</div>
                        </div>
                    @endif
                    @if(isset($ecoIdea->impact_metrics['energy_saved']))
                        <div class="metric-card">
                            <div class="metric-icon"><i class="fas fa-bolt"></i></div>
                            <div class="metric-value">{{ $ecoIdea->impact_metrics['energy_saved'] }}</div>
                            <div class="metric-label">Energy Saved</div>
                        </div>
                    @endif
                    @if(isset($ecoIdea->impact_metrics['money_saved']))
                        <div class="metric-card">
                            <div class="metric-icon"><i class="fas fa-dollar-sign"></i></div>
                            <div class="metric-value">{{ $ecoIdea->impact_metrics['money_saved'] }}</div>
                            <div class="metric-label">Money Saved</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Application Form -->
        @auth
            @if($ecoIdea->is_recruiting && !$hasApplied && $ecoIdea->project_status !== 'completed' && $ecoIdea->project_status !== 'verified' && $ecoIdea->creator_id !== auth()->id())
                <div class="content-section" id="apply-section">
                    <h2 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Apply to Join This Project
                    </h2>
                    @if($ecoIdea->application_description)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ $ecoIdea->application_description }}
                        </div>
                    @endif
                    <form action="{{ route('front.eco-ideas.apply', $ecoIdea->id) }}" method="POST" enctype="multipart/form-data" class="apply-form">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Why do you want to join this project? *</label>
                            <textarea name="application_message" class="form-control" required 
                                      placeholder="Tell us about your skills, experience, and motivation..."></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Upload Your Resume (PDF) *</label>
                            <input type="file" name="resume" class="form-control" accept=".pdf" required 
                                   style="padding: 10px; border: 2px dashed #10b981; background: #f0fdf4; border-radius: 12px;">
                            <small style="color: #6b7280; font-size: 13px; display: block; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> PDF only, max 5MB
                            </small>
                        </div>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Submit Application
                        </button>
                    </form>
                </div>
            @endif
        @endauth

        <!-- Reviews Section (Using Interactions with type='comment') -->
        @if($ecoIdea->interactions->where('type', 'comment')->count() > 0)
            <div class="content-section">
                <h2 class="section-title">
                    <i class="fas fa-comments"></i>
                    Community Reviews ({{ $ecoIdea->interactions->where('type', 'comment')->count() }})
                </h2>
                @foreach($ecoIdea->interactions->where('type', 'comment') as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <img src="{{ $review->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name ?? 'U') . '&background=10b981&color=fff' }}" 
                                 alt="{{ $review->user->name ?? 'User' }}" 
                                 class="review-avatar">
                            <div class="review-info">
                                <h5>{{ $review->user->name ?? 'Anonymous' }}</h5>
                                <span>{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @auth
                                @if($ecoIdea->creator_id === auth()->id())
                                    <form action="{{ route('front.eco-ideas.review.delete', $review->id) }}" method="POST" style="margin-left: auto;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this review?')" 
                                                class="delete-review-btn"
                                                style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                        <div class="review-content">{{ $review->content }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Add Review Form -->
        @auth
            <div class="content-section">
                <h2 class="section-title">
                    <i class="fas fa-pen"></i>
                    Share Your Thoughts
                </h2>
                <form action="{{ route('front.eco-ideas.review', $ecoIdea->id) }}" method="POST" class="apply-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Write a review *</label>
                        <textarea name="content" class="form-control" required 
                                  placeholder="Share your thoughts about this eco idea..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Post Review
                    </button>
                </form>
            </div>
        @endauth
    </div>
</div>
@endsection

@push('scripts')
<script>
    function likeIdeaDetails(ideaId, buttonElement) {
        fetch(`/eco-ideas/${ideaId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                window.location.href = '/login';
                return;
            }

            // Update the like count
            document.getElementById('like-count').textContent = data.upvotes;

            // Update the text
            const likeText = document.getElementById('like-text');
            likeText.textContent = data.liked ? 'Unlike' : 'Like';

            // Toggle the liked class (keep btn-secondary for styling)
            if (data.liked) {
                buttonElement.classList.add('btn-liked');
            } else {
                buttonElement.classList.remove('btn-liked');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endpush
