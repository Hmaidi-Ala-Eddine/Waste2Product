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
        padding-top: 100px;
        padding-bottom: 50px;
    }

    .details-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        color: #6b7280;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        margin-bottom: 18px;
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
        font-size: 14px;
        transition: transform 0.3s ease;
    }

    .back-button:hover i {
        transform: translateX(-3px);
    }

    .idea-hero {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .hero-image {
        width: 100%;
        height: 350px;
        object-fit: cover;
    }

    .hero-content {
        padding: 25px;
    }

    .hero-badges {
        display: flex;
        gap: 8px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .hero-badge {
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
        font-size: 26px;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 12px;
        line-height: 1.3;
    }

    .hero-meta {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .creator-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .creator-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #10b981;
    }

    .creator-details h4 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }

    .creator-details span {
        font-size: 12px;
        color: #6b7280;
    }

    .meta-stats {
        display: flex;
        gap: 15px;
    }

    .stat {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #6b7280;
        font-weight: 600;
    }

    .stat i {
        color: #10b981;
        font-size: 14px;
    }

    .stat strong {
        color: #1f2937;
    }

    .hero-description {
        font-size: 14px;
        line-height: 1.7;
        color: #4b5563;
        margin-bottom: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 10px 22px;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
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
        padding: 10px 22px;
        border-radius: 10px;
        font-weight: 700;
        border: 2px solid #10b981;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
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
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 18px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #10b981;
        font-size: 20px;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 14px;
    }

    .team-member {
        text-align: center;
        padding: 14px;
        background: #f9fafb;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .team-member:hover {
        background: #f0fdf4;
        transform: translateY(-4px);
    }

    .team-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 10px;
        border: 2px solid #10b981;
    }

    .team-name {
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .team-role {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .team-badge {
        display: inline-block;
        padding: 3px 8px;
        background: #d1fae5;
        color: #065f46;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 700;
    }

    .apply-form {
        background: #f9fafb;
        padding: 20px;
        border-radius: 12px;
        margin-top: 16px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    textarea.form-control {
        min-height: 90px;
        resize: vertical;
    }

    .review-item {
        padding: 14px;
        background: #f9fafb;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .review-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
    }

    .review-info h5 {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
    }

    .review-info span {
        font-size: 11px;
        color: #9ca3af;
    }

    .review-content {
        font-size: 13px;
        line-height: 1.6;
        color: #4b5563;
    }

    .impact-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
    }

    .metric-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 14px;
        border-radius: 12px;
        text-align: center;
    }

    .metric-icon {
        font-size: 24px;
        color: #10b981;
        margin-bottom: 8px;
    }

    .metric-value {
        font-size: 18px;
        font-weight: 800;
        color: #065f46;
        margin-bottom: 3px;
    }

    .metric-label {
        font-size: 11px;
        color: #047857;
        font-weight: 600;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 13px;
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
                            <strong id="header-like-count">{{ $ecoIdea->upvotes ?? 0 }}</strong> Likes
                        </div>
                        <div class="stat">
                            <i class="fas fa-users"></i>
                            <strong>{{ $ecoIdea->team()->count() + 1 }}/{{ $ecoIdea->team_size_needed ?? 0 }}</strong> Team
                        </div>
                        @if($ecoIdea->project_status === 'recruiting')
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

                        @if($ecoIdea->project_status === 'recruiting' && !$hasApplied && !$isTeamMember && $ecoIdea->creator_id !== auth()->id())
                            <button onclick="document.getElementById('apply-section').scrollIntoView({behavior: 'smooth'})" class="btn-primary">
                                <i class="fas fa-user-plus"></i>
                                Apply to Join
                            </button>
                        @elseif($hasApplied)
                            <span class="btn-secondary" style="cursor: not-allowed; opacity: 0.7;">
                                <i class="fas fa-check"></i>
                                Application Submitted
                            </span>
                        @elseif($isTeamMember)
                            <span class="btn-secondary" style="cursor: not-allowed; opacity: 1; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                                <i class="fas fa-users"></i>
                                You're a Team Member
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
        <div class="content-section">
            <h2 class="section-title">
                <i class="fas fa-users"></i>
                Team Members ({{ $ecoIdea->team->count() + 1 }} including owner)
            </h2>
            <div class="team-grid">
                <!-- Owner (Always First) -->
                <div class="team-member">
                    <img src="{{ $ecoIdea->creator->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($ecoIdea->creator->name ?? 'Owner') . '&background=f59e0b&color=fff' }}" 
                         alt="{{ $ecoIdea->creator->name ?? 'Owner' }}" 
                         class="team-avatar">
                    <div class="team-name">{{ $ecoIdea->creator->name ?? 'Owner' }}</div>
                    <div class="team-role">Project Owner</div>
                    <span class="team-badge" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="fas fa-crown"></i> Owner
                    </span>
                </div>
                
                <!-- Team Members -->
                @foreach($ecoIdea->team as $member)
                    <div class="team-member">
                        <img src="{{ $member->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->user->name ?? 'U') . '&background=10b981&color=fff' }}" 
                             alt="{{ $member->user->name ?? 'Member' }}" 
                             class="team-avatar">
                        <div class="team-name">{{ $member->user->name ?? 'Member' }}</div>
                        <div class="team-role">{{ $member->specialization ?? ucfirst($member->role) }}</div>
                        @if($member->role === 'leader')
                            <span class="team-badge">
                                <i class="fas fa-star"></i> Leader
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

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
            @php
                $currentTeamCount = $ecoIdea->team()->count() + 1; // +1 for owner
                $teamSizeNeeded = $ecoIdea->team_size_needed ?? 0;
                $spotsAvailable = max(0, $teamSizeNeeded - $currentTeamCount);
                $teamIsFull = $teamSizeNeeded > 0 && $currentTeamCount >= $teamSizeNeeded;
            @endphp
            
            @if($teamIsFull && $ecoIdea->creator_id !== auth()->id() && !$hasApplied && !$isTeamMember)
                <!-- Team Full Message -->
                <div class="content-section" id="apply-section">
                    <div style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 4px solid #ef4444; padding: 25px; border-radius: 12px; text-align: center;">
                        <i class="fas fa-users-slash" style="font-size: 48px; color: #dc2626; margin-bottom: 15px;"></i>
                        <h3 style="font-size: 22px; font-weight: 800; color: #991b1b; margin-bottom: 10px;">
                            Team is Full!
                        </h3>
                        <p style="font-size: 16px; color: #7f1d1d; margin: 0;">
                            All <strong>{{ $teamSizeNeeded }} positions</strong> have been filled (including owner). This project is no longer accepting applications.
                        </p>
                        <div style="margin-top: 15px; padding: 12px; background: rgba(255,255,255,0.5); border-radius: 8px;">
                            <p style="font-size: 14px; color: #991b1b; margin: 0;">
                                <i class="fas fa-check-circle"></i> Current Team: <strong>{{ $currentTeamCount }}/{{ $teamSizeNeeded }}</strong> members
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($ecoIdea->project_status === 'recruiting' && !$hasApplied && !$isTeamMember && $ecoIdea->creator_id !== auth()->id())
                <div class="content-section" id="apply-section">
                    <h2 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Apply to Join This Project
                    </h2>
                    @if($teamSizeNeeded > 0)
                        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 4px solid #10b981; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <p style="font-size: 14px; color: #065f46; margin: 0; font-weight: 600;">
                                <i class="fas fa-users"></i> Waiting for <strong>{{ $spotsAvailable }}</strong> more member(s) out of {{ $teamSizeNeeded }} total
                            </p>
                            <p style="font-size: 13px; color: #047857; margin: 5px 0 0 0;">
                                Current team: {{ $currentTeamCount }}/{{ $teamSizeNeeded }} members
                            </p>
                        </div>
                    @endif
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
                                    <form id="delete-form-{{ $review->id }}" action="{{ route('front.eco-ideas.review.delete', $review->id) }}" method="POST" style="margin-left: auto;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="showDeleteConfirm({{ $review->id }})" 
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
                <form id="reviewForm" action="{{ route('front.eco-ideas.review', $ecoIdea->id) }}" method="POST" class="apply-form">
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

<!-- Custom Delete Confirmation Modal -->
<div id="deleteConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 16px; padding: 30px; max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: modalFadeIn 0.3s ease;">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                <i class="fas fa-trash-alt" style="font-size: 32px; color: #ef4444;"></i>
            </div>
            <h3 style="font-size: 22px; font-weight: 700; color: #1f2937; margin: 0 0 10px 0;">Delete this review?</h3>
            <p style="color: #6b7280; font-size: 14px; margin: 0; line-height: 1.5;">This action cannot be undone.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="closeDeleteConfirm()" style="flex: 1; padding: 12px; background: #f3f4f6; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; color: #374151; transition: all 0.2s;">
                Cancel
            </button>
            <button onclick="confirmDelete()" style="flex: 1; padding: 12px; background: #ef4444; color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                Delete
            </button>
        </div>
    </div>
</div>

<style>
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

@endsection

@push('scripts')
<script>
    let currentDeleteFormId = null;

    function showDeleteConfirm(reviewId) {
        currentDeleteFormId = 'delete-form-' + reviewId;
        document.getElementById('deleteConfirmModal').style.display = 'flex';
    }

    function closeDeleteConfirm() {
        document.getElementById('deleteConfirmModal').style.display = 'none';
        currentDeleteFormId = null;
    }

    function confirmDelete() {
        if (currentDeleteFormId) {
            document.getElementById(currentDeleteFormId).submit();
        }
    }

    // Close modal when clicking outside
    document.getElementById('deleteConfirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteConfirm();
        }
    });

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

            // Update both like counts
            document.getElementById('like-count').textContent = data.upvotes;
            document.getElementById('header-like-count').textContent = data.upvotes;

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
