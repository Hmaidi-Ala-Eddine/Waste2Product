@extends('layouts.front')

@section('title', 'Eco Ideas - Join Sustainable Projects')

@push('styles')
<style>
    /* Navbar text color fix for eco ideas page */
    .navbar-nav > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .navbar-nav > li > a:hover,
    .navbar-nav > li > a:focus {
        color: #10b981 !important;
    }

    .navbar.white .navbar-nav > li > a,
    .navbar.navbar-scrolled .navbar-nav > li > a {
        color: #2c3e50 !important;
    }

    .eco-ideas-wrapper {
        background: linear-gradient(135deg, #10b98115 0%, #059669220 20%, #ffffff 50%, #f8f9fa 100%);
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
        position: relative;
        overflow: hidden;
    }

    .eco-ideas-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .eco-ideas-wrapper::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(5, 150, 105, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .eco-ideas-header {
        text-align: center;
        margin-bottom: 50px;
        padding: 0 20px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 30px;
    }

    .header-content > div {
        flex: 1;
    }

    .eco-ideas-header h1 {
        font-size: 48px;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .eco-ideas-header p {
        font-size: 18px;
        color: #7f8c8d;
        max-width: 600px;
    }

    .dashboard-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 32px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        white-space: nowrap;
    }

    .dashboard-cta-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .dashboard-cta-btn i {
        font-size: 20px;
    }

    .eco-ideas-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
        position: relative;
        z-index: 1;
    }

    /* Search & Filters Container - Products Style */
    .search-filters-container {
        background: #f0fdf4;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 40px;
        box-shadow: 0 2px 12px rgba(16, 185, 129, 0.08);
    }

    .filters-grid {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1fr 1fr 1.2fr 0.8fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .filter-column {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-label {
        font-size: 11px;
        font-weight: 700;
        color: #059669;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .search-wrapper {
        position: relative;
    }

    .filter-input {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #d1fae5;
        border-radius: 8px;
        font-size: 14px;
        color: #1f2937;
        background: white;
        transition: all 0.2s ease;
    }

    .filter-input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .filter-input:hover {
        border-color: #10b981;
    }

    .search-icon-right {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #10b981;
        font-size: 14px;
        pointer-events: none;
    }

    select.filter-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2310b981' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 35px;
    }

    .filter-actions {
        justify-content: flex-end;
    }

    .reset-btn {
        padding: 11px 20px;
        background: #10b981;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 19px;
    }

    .reset-btn:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
    }

    .reset-btn i {
        font-size: 12px;
    }

    .bottom-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #d1fae5;
    }

    .view-toggle-left {
        display: flex;
        gap: 8px;
    }

    .view-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #6b7280;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .view-btn:hover {
        background: #d1fae5;
        color: #10b981;
    }

    .view-btn.active {
        background: #10b981;
        color: white;
    }

    .results-count {
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
    }

    .results-count span {
        color: #10b981;
        font-weight: 800;
        font-size: 16px;
    }

    /* Grid View */
    .ideas-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        align-items: start;
        transition: all 0.4s ease;
    }

    /* List View */
    .ideas-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .ideas-list .idea-card {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 0;
        max-width: 100%;
    }

    .ideas-list .idea-image-wrapper {
        height: 100%;
        min-height: 280px;
    }

    .ideas-list .idea-content-wrapper {
        display: grid;
        grid-template-rows: auto 1fr auto;
    }

    .ideas-list .idea-body {
        padding-bottom: 15px;
    }

    .ideas-list .idea-title {
        font-size: 24px;
        -webkit-line-clamp: 2;
    }

    .ideas-list .idea-description {
        font-size: 15px;
        -webkit-line-clamp: 4;
    }

    /* Fade in animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .idea-card {
        animation: fadeInUp 0.4s ease forwards;
    }

    .idea-card:nth-child(1) { animation-delay: 0.05s; }
    .idea-card:nth-child(2) { animation-delay: 0.1s; }
    .idea-card:nth-child(3) { animation-delay: 0.15s; }
    .idea-card:nth-child(4) { animation-delay: 0.2s; }
    .idea-card:nth-child(5) { animation-delay: 0.25s; }
    .idea-card:nth-child(6) { animation-delay: 0.3s; }

    .idea-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(16, 185, 129, 0.05);
        display: flex;
        flex-direction: column;
        height: auto;
        border: 1px solid rgba(16, 185, 129, 0.08);
    }

    .idea-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(16, 185, 129, 0.25), 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: rgba(16, 185, 129, 0.15);
    }

    .idea-image-wrapper {
        position: relative;
        width: 100%;
        height: 250px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        cursor: pointer;
    }

    .idea-image-wrapper::before {
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

    .idea-card:hover .idea-image-wrapper::before {
        opacity: 1;
    }

    .idea-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .idea-card:hover .idea-image {
        transform: scale(1.05);
    }

    .idea-badges {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .idea-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .badge-waste {
        background: rgba(59, 130, 246, 0.9);
        color: white;
    }

    .badge-difficulty {
        background: rgba(249, 115, 22, 0.9);
        color: white;
    }

    .badge-status {
        background: rgba(16, 185, 129, 0.9);
        color: white;
    }

    .badge-verified {
        background: rgba(139, 92, 246, 0.9);
        color: white;
    }

    .idea-content-wrapper {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .idea-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .idea-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2.5px solid #10b981;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    .idea-creator-info h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #2c3e50;
    }

    .idea-creator-info span {
        font-size: 12px;
        color: #95a5a6;
        font-weight: 500;
    }

    .idea-body {
        flex: 1;
    }

    .idea-title {
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

    .idea-description {
        font-size: 14px;
        color: #5a6c7d;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .idea-meta {
        display: flex;
        gap: 15px;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #f0f2f5;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #7f8c8d;
        font-weight: 600;
    }

    .meta-item i {
        color: #10b981;
        font-size: 16px;
    }

    .idea-actions {
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
        text-decoration: none;
    }

    .action-btn:hover {
        border-color: #10b981;
        background: #f0fdf4;
        color: #10b981;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .action-btn.liked {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-color: #ef4444;
        color: white;
    }

    .action-btn.liked:hover {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    }

    .action-btn.primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
        color: white;
    }

    .action-btn.primary:hover {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    }

    .action-btn i {
        font-size: 16px;
    }

    .action-btn .count {
        font-weight: 700;
    }

    /* Responsive Design */
    @media (max-width: 1400px) {
        .filters-grid {
            grid-template-columns: 2fr 1.5fr 1fr 1fr 1fr;
        }

        .filter-column.filter-actions {
            grid-column: span 1;
        }
    }

    @media (max-width: 1200px) {
        .ideas-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .ideas-list .idea-card {
            grid-template-columns: 300px 1fr;
        }

        .filters-grid {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .filter-column:nth-child(1) {
            grid-column: span 3;
        }

        .filter-column.filter-actions {
            grid-column: span 3;
        }

        .reset-btn {
            width: 100%;
            justify-content: center;
            margin-top: 0;
        }
    }

    @media (max-width: 768px) {
        .ideas-grid {
            grid-template-columns: 1fr;
        }

        .ideas-list .idea-card {
            grid-template-columns: 1fr;
        }

        .ideas-list .idea-image-wrapper {
            min-height: 250px;
        }

        .eco-ideas-header h1 {
            font-size: 36px;
        }

        .eco-ideas-wrapper {
            padding-top: 100px;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-content > div {
            width: 100%;
        }

        .eco-ideas-header h1 {
            justify-content: center;
        }

        .eco-ideas-header p {
            margin: 0 auto;
        }

        .dashboard-cta-btn {
            width: 100%;
            justify-content: center;
        }

        .search-filters-container {
            padding: 20px;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }

        .filter-column:nth-child(1) {
            grid-column: span 1;
        }

        .filter-column.filter-actions {
            grid-column: span 1;
        }

        .bottom-bar {
            flex-direction: column;
            gap: 15px;
        }

        .view-toggle-left {
            justify-content: center;
        }

        .results-count {
            text-align: center;
        }
    }

    .no-ideas {
        text-align: center;
        padding: 80px 20px;
        color: #7f8c8d;
    }

    .no-ideas i {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 20px;
    }

    .no-ideas h3 {
        font-size: 24px;
        color: #4b5563;
        margin-bottom: 10px;
    }

    .no-ideas p {
        font-size: 16px;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')
<div class="eco-ideas-wrapper">
    <div class="eco-ideas-container">
        <div class="eco-ideas-header">
            <div class="header-content">
                <div>
                    <h1><i class="fas fa-lightbulb" style="color: #10b981;"></i> Eco Ideas</h1>
                    <p>Discover and join sustainable projects that make a difference</p>
                </div>
                @auth
                    <a href="{{ route('front.eco-ideas.dashboard') }}" class="dashboard-cta-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Create & Manage Your Eco Ideas</span>
                    </a>
                @endauth
            </div>
        </div>

        <!-- Advanced Search & Filters - Products Style -->
        <div class="search-filters-container">
            <div class="filters-grid">
                <div class="filter-column">
                    <label class="filter-label">SEARCH</label>
                    <div class="search-wrapper">
                        <input type="text" id="search-input" class="filter-input" placeholder="Search eco ideas...">
                        <i class="fas fa-search search-icon-right"></i>
                    </div>
                </div>

                <div class="filter-column">
                    <label class="filter-label">WASTE TYPE</label>
                    <select id="waste-type-filter" class="filter-input">
                        <option value="all">All Waste Types</option>
                        <option value="organic">Organic</option>
                        <option value="plastic">Plastic</option>
                        <option value="metal">Metal</option>
                        <option value="e-waste">E-Waste</option>
                        <option value="paper">Paper</option>
                        <option value="glass">Glass</option>
                        <option value="textile">Textile</option>
                        <option value="mixed">Mixed</option>
                    </select>
                </div>

                <div class="filter-column">
                    <label class="filter-label">DIFFICULTY</label>
                    <select id="difficulty-filter" class="filter-input">
                        <option value="all">All Difficulties</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <div class="filter-column">
                    <label class="filter-label">STATUS</label>
                    <select id="status-filter" class="filter-input">
                        <option value="all">All Status</option>
                        <option value="idea">Idea</option>
                        <option value="recruiting">Recruiting</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="verified">Verified</option>
                    </select>
                </div>

                <div class="filter-column">
                    <label class="filter-label">SORT BY</label>
                    <select id="sort-by" class="filter-input">
                        <option value="latest">Latest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="most-liked">Most Liked</option>
                        <option value="least-liked">Least Liked</option>
                    </select>
                </div>

                <div class="filter-column filter-actions">
                    <button class="reset-btn" id="reset-filters">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>

            <div class="bottom-bar">
                <div class="view-toggle-left">
                    <button class="view-btn active" data-view="grid">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
                <div class="results-count">
                    <span id="results-count">{{ $ideas->total() }}</span> ideas found
                </div>
            </div>
        </div>

        @if($ideas->count() > 0)
            <div class="ideas-grid">
                @foreach($ideas as $idea)
                    <div class="idea-card">
                        <div class="idea-image-wrapper" onclick="window.location.href='{{ route('front.eco-ideas.show', $idea->id) }}'">
                            @if($idea->image_path && file_exists(public_path('storage/' . $idea->image_path)))
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}" class="idea-image">
                            @else
                                <img src="{{ asset('assets/front/img/default-eco.jpg') }}" alt="{{ $idea->title }}" class="idea-image" onerror="this.src='https://via.placeholder.com/400x250/10b981/ffffff?text=Eco+Idea'">
                            @endif
                            
                            <div class="idea-badges">
                                <span class="idea-badge badge-waste">{{ ucfirst($idea->waste_type) }}</span>
                                <span class="idea-badge badge-difficulty">{{ ucfirst($idea->difficulty_level) }}</span>
                                @if($idea->project_status === 'verified')
                                    <span class="idea-badge badge-verified"><i class="fas fa-check-circle"></i> Verified</span>
                                @else
                                    <span class="idea-badge badge-status">{{ ucfirst(str_replace('_', ' ', $idea->project_status ?? 'idea')) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="idea-content-wrapper">
                            <div class="idea-header">
                                <img src="{{ $idea->creator->profile_picture_url ?? asset('assets/front/img/default-avatar.jpg') }}" 
                                     alt="{{ $idea->creator->name ?? 'Creator' }}" 
                                     class="idea-avatar"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($idea->creator->name ?? 'U') }}&background=10b981&color=fff'">
                                <div class="idea-creator-info">
                                    <h4>{{ $idea->creator->name ?? 'Unknown Creator' }}</h4>
                                    <span>{{ $idea->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="idea-body">
                                <h3 class="idea-title">{{ $idea->title }}</h3>
                                <p class="idea-description">{{ Str::limit($idea->description, 150) }}</p>
                                
                                <div class="idea-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-heart"></i>
                                        <span>{{ $idea->upvotes ?? 0 }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $idea->team_size_current ?? 1 }}/{{ $idea->team_size_needed ?? 0 }}</span>
                                    </div>
                                    @if($idea->is_recruiting)
                                        <div class="meta-item">
                                            <i class="fas fa-user-plus"></i>
                                            <span>Recruiting</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="idea-actions">
                            @auth
                                <button onclick="likeIdea({{ $idea->id }}, this)" 
                                        class="action-btn {{ $idea->interactions->where('user_id', auth()->id())->where('type', 'like')->count() > 0 ? 'liked' : '' }}" 
                                        style="flex: 1; border: none;">
                                    <i class="fas fa-heart"></i>
                                    <span class="count">{{ $idea->upvotes ?? 0 }}</span>
                                </button>
                            @else
                                <div class="action-btn" style="flex: 1;">
                                    <i class="fas fa-heart"></i>
                                    <span class="count">{{ $idea->upvotes ?? 0 }}</span>
                                </div>
                            @endauth
                            
                            <a href="{{ route('front.eco-ideas.show', $idea->id) }}" class="action-btn primary">
                                <i class="fas fa-eye"></i>
                                <span>View Details</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($ideas->hasPages())
                <div class="pagination-wrapper" style="margin-top: 50px; display: flex; justify-content: center;">
                    {{ $ideas->links() }}
                </div>
            @endif
        @else
            <div class="no-ideas">
                <i class="fas fa-lightbulb"></i>
                <h3>No Eco Ideas Yet</h3>
                <p>Be the first to create an eco idea and inspire others!</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeFilters();
        initializeViewToggle();
    });

    let allIdeas = [];

    function initializeFilters() {
        // Store all ideas for filtering
        const ideaCards = document.querySelectorAll('.idea-card');
        ideaCards.forEach(card => {
            allIdeas.push({
                element: card,
                title: card.querySelector('.idea-title').textContent.toLowerCase(),
                description: card.querySelector('.idea-description').textContent.toLowerCase(),
                creator: card.querySelector('.idea-creator-info h4').textContent.toLowerCase(),
                wasteType: card.querySelector('.badge-waste').textContent.toLowerCase().trim(),
                difficulty: card.querySelector('.badge-difficulty').textContent.toLowerCase().trim(),
                status: card.querySelector('.badge-status, .badge-verified').textContent.toLowerCase().trim(),
                upvotes: parseInt(card.querySelector('.meta-item .count') ? card.querySelector('.meta-item span').textContent : 0),
                timestamp: card.querySelector('.idea-creator-info span').textContent
            });
        });

        // Search input
        const searchInput = document.getElementById('search-input');
        
        searchInput.addEventListener('input', function() {
            applyFilters();
        });

        // Filter selects
        document.getElementById('waste-type-filter').addEventListener('change', applyFilters);
        document.getElementById('difficulty-filter').addEventListener('change', applyFilters);
        document.getElementById('status-filter').addEventListener('change', applyFilters);
        document.getElementById('sort-by').addEventListener('change', applyFilters);

        // Reset button
        document.getElementById('reset-filters').addEventListener('click', function() {
            searchInput.value = '';
            document.getElementById('waste-type-filter').value = 'all';
            document.getElementById('difficulty-filter').value = 'all';
            document.getElementById('status-filter').value = 'all';
            document.getElementById('sort-by').value = 'latest';
            applyFilters();
        });
    }

    function applyFilters() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const wasteType = document.getElementById('waste-type-filter').value;
        const difficulty = document.getElementById('difficulty-filter').value;
        const status = document.getElementById('status-filter').value;
        const sortBy = document.getElementById('sort-by').value;

        let filteredIdeas = allIdeas.filter(idea => {
            // Search filter
            const matchesSearch = searchTerm === '' || 
                idea.title.includes(searchTerm) || 
                idea.description.includes(searchTerm) ||
                idea.creator.includes(searchTerm);

            // Waste type filter
            const matchesWaste = wasteType === 'all' || idea.wasteType === wasteType;

            // Difficulty filter
            const matchesDifficulty = difficulty === 'all' || idea.difficulty === difficulty;

            // Status filter
            const matchesStatus = status === 'all' || idea.status.includes(status.replace('_', ' '));

            return matchesSearch && matchesWaste && matchesDifficulty && matchesStatus;
        });

        // Sort filtered ideas
        filteredIdeas.sort((a, b) => {
            if (sortBy === 'most-liked') return b.upvotes - a.upvotes;
            if (sortBy === 'least-liked') return a.upvotes - b.upvotes;
            if (sortBy === 'oldest') return 1; // Keep original order
            return -1; // Latest first (default)
        });

        // Hide all cards
        allIdeas.forEach(idea => {
            idea.element.style.display = 'none';
        });

        // Show filtered cards with animation
        filteredIdeas.forEach((idea, index) => {
            idea.element.style.display = '';
            idea.element.style.animation = 'none';
            setTimeout(() => {
                idea.element.style.animation = `fadeInUp 0.4s ease ${index * 0.05}s forwards`;
            }, 10);
        });

        // Update results count
        document.getElementById('results-count').textContent = filteredIdeas.length;
    }

    function initializeViewToggle() {
        const viewButtons = document.querySelectorAll('.view-btn');
        const ideasContainer = document.querySelector('.ideas-grid');

        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.dataset.view;
                
                // Update active button
                viewButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Switch view
                if (view === 'list') {
                    ideasContainer.classList.remove('ideas-grid');
                    ideasContainer.classList.add('ideas-list');
                } else {
                    ideasContainer.classList.remove('ideas-list');
                    ideasContainer.classList.add('ideas-grid');
                }
            });
        });
    }

    function likeIdea(ideaId, buttonElement) {
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
            const countSpan = buttonElement.querySelector('.count');
            countSpan.textContent = data.upvotes;

            // Toggle the liked class
            if (data.liked) {
                buttonElement.classList.add('liked');
            } else {
                buttonElement.classList.remove('liked');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endpush
