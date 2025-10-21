@extends('layouts.front')

@section('title', 'My Eco Ideas Dashboard')

@push('styles')
<style>
    .dashboard-wrapper {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        min-height: 100vh;
        padding: 120px 0 80px;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .back-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: #f9fafb;
        border-color: #10b981;
        color: #10b981;
        transform: translateX(-5px);
    }

    .dashboard-title {
        font-size: 36px;
        font-weight: 800;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .dashboard-title i {
        color: #10b981;
    }

    .create-btn {
        padding: 14px 28px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .ideas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }

    .idea-dashboard-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .idea-dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-content {
        padding: 20px;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 10px;
    }

    .card-stats {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #6b7280;
    }

    .stat-item i {
        color: #10b981;
    }

    .stat-badge {
        background: #f0fdf4;
        color: #10b981;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 13px;
    }

    .card-actions {
        display: flex;
        gap: 8px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .action-btn {
        flex: 1;
        min-width: calc(50% - 4px);
        padding: 10px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 13px;
        text-decoration: none;
        text-align: center;
        display: inline-block;
    }

    .btn-manage {
        background: #10b981;
        color: white;
    }

    .btn-manage:hover {
        background: #059669;
    }

    .btn-edit {
        background: #3b82f6;
        color: white;
    }

    .btn-edit:hover {
        background: #2563eb;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 24px;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #9ca3af;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 30px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .modal-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a202c;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 24px;
        color: #6b7280;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 8px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .submit-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }

        .dashboard-header > div {
            flex-direction: column;
            width: 100%;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }

        .dashboard-title {
            font-size: 28px;
        }

        .create-btn {
            width: 100%;
            justify-content: center;
        }

        .ideas-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div style="display: flex; align-items: center; gap: 20px;">
                <a href="{{ route('front.eco-ideas') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Eco Ideas</span>
                </a>
                <h1 class="dashboard-title">
                    <i class="fas fa-lightbulb"></i>
                    My Eco Ideas
                </h1>
            </div>
            <button class="create-btn" onclick="openCreateModal()">
                <i class="fas fa-plus-circle"></i>
                <span>Create New Eco Idea</span>
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 30px;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- My Created Eco Ideas -->
        <h2 style="font-size: 24px; font-weight: 700; color: #1a202c; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-crown" style="color: #f59e0b;"></i>
            My Created Ideas ({{ $userIdeas->count() }})
        </h2>

        @if($userIdeas->count() > 0)
            <div class="ideas-grid">
                @foreach($userIdeas as $idea)
                    <div class="idea-dashboard-card">
                        @if($idea->image_path && file_exists(public_path('storage/' . $idea->image_path)))
                            <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}" class="card-image">
                        @else
                            <img src="https://via.placeholder.com/350x200/10b981/ffffff?text=Eco+Idea" alt="{{ $idea->title }}" class="card-image">
                        @endif

                        <div class="card-content">
                            <h3 class="card-title">{{ $idea->title }}</h3>

                            <div class="card-stats">
                                @if($idea->applications_count > 0)
                                    <span class="stat-badge">
                                        <i class="fas fa-user-clock"></i> {{ $idea->applications_count }} Pending
                                    </span>
                                @endif
                                <span class="stat-item">
                                    <i class="fas fa-users"></i> {{ $idea->team_count }} Members
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-tasks"></i> {{ $idea->tasks_count }} Tasks
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-heart"></i> {{ $idea->upvotes ?? 0 }}
                                </span>
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('front.eco-ideas.dashboard.manage', $idea) }}" class="action-btn btn-manage">
                                    <i class="fas fa-cog"></i> Manage
                                </a>
                                <a href="{{ route('front.eco-ideas.show', $idea) }}" class="action-btn btn-edit" target="_blank">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form id="delete-form-{{ $idea->id }}" action="{{ route('front.eco-ideas.dashboard.delete', $idea) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="action-btn btn-delete" onclick="openDeleteModal({{ $idea->id }}, '{{ $idea->title }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-lightbulb"></i>
                <h3>No Eco Ideas Yet</h3>
                <p>Start making a difference by creating your first eco idea!</p>
            </div>
        @endif

        <!-- Joined Eco Ideas -->
        @if($joinedIdeas->count() > 0)
            <h2 style="font-size: 24px; font-weight: 700; color: #1a202c; margin: 50px 0 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-users" style="color: #3b82f6;"></i>
                Joined Ideas ({{ $joinedIdeas->count() }})
            </h2>

            <div class="ideas-grid">
                @foreach($joinedIdeas as $idea)
                    <div class="idea-dashboard-card" style="border: 2px solid #3b82f6;">
                        @if($idea->image_path && file_exists(public_path('storage/' . $idea->image_path)))
                            <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}" class="card-image">
                        @else
                            <img src="https://via.placeholder.com/350x200/3b82f6/ffffff?text=Eco+Idea" alt="{{ $idea->title }}" class="card-image">
                        @endif

                        <div class="card-content">
                            <h3 class="card-title">{{ $idea->title }}</h3>
                            <p style="font-size: 13px; color: #6b7280; margin-bottom: 10px;">
                                <i class="fas fa-user"></i> Created by: <strong>{{ $idea->creator->name }}</strong>
                            </p>

                            <div class="card-stats">
                                <span class="stat-item">
                                    <i class="fas fa-users"></i> {{ $idea->team_count }} Members
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-tasks"></i> {{ $idea->tasks_count }} Tasks
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-heart"></i> {{ $idea->upvotes ?? 0 }}
                                </span>
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('front.eco-ideas.dashboard.manage', $idea) }}" class="action-btn btn-manage">
                                    <i class="fas fa-tasks"></i> View Tasks
                                </a>
                                <a href="{{ route('front.eco-ideas.show', $idea) }}" class="action-btn btn-edit" target="_blank">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Create Eco Idea Modal -->
<div class="modal" id="createModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Create New Eco Idea</h2>
            <button class="close-modal" onclick="closeCreateModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('front.eco-ideas.dashboard.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-textarea" required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Waste Type *</label>
                <select name="waste_type" class="form-select" required>
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

            <div class="form-group">
                <label class="form-label">Difficulty Level *</label>
                <select name="difficulty_level" class="form-select" required>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Team Size Needed</label>
                <input type="number" name="team_size_needed" class="form-input" min="1" placeholder="e.g., 5">
            </div>

            <div class="form-group">
                <label class="form-label">Team Requirements</label>
                <textarea name="team_requirements" class="form-textarea" placeholder="Skills needed: coding, design, marketing..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Application Instructions</label>
                <textarea name="application_description" class="form-textarea" placeholder="Tell applicants what you're looking for..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-input" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_recruiting" value="1" checked>
                    <span>Open for recruitment</span>
                </label>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-plus-circle"></i> Create Eco Idea
            </button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content" style="max-width: 500px;">
        <div style="text-align: center; padding: 20px 0;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #dc2626;"></i>
            </div>
            <h2 style="font-size: 24px; font-weight: 800; color: #1a202c; margin-bottom: 10px;">Delete Eco Idea?</h2>
            <p style="font-size: 16px; color: #6b7280; margin-bottom: 10px;">Are you sure you want to delete</p>
            <p style="font-size: 18px; font-weight: 700; color: #dc2626; margin-bottom: 20px;" id="deleteIdeaTitle"></p>
            <div style="background: #fef2f2; border-left: 4px solid #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 30px; text-align: left;">
                <p style="font-size: 14px; color: #991b1b; margin: 0;">
                    <i class="fas fa-info-circle"></i> <strong>Warning:</strong> This action cannot be undone. All applications, team members, and tasks will be permanently deleted.
                </p>
            </div>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <button onclick="closeDeleteModal()" style="min-width: 180px; padding: 16px 32px; background: #f3f4f6; color: #1a202c; border: 2px solid #e5e7eb; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);" onmouseover="this.style.background='#e5e7eb'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.1)'" onmouseout="this.style.background='#f3f4f6'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.05)'">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button onclick="confirmDelete()" style="min-width: 180px; padding: 16px 32px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: 2px solid #dc2626; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 38, 38, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(220, 38, 38, 0.3)'">
                    <i class="fas fa-trash-alt"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentDeleteFormId = null;

    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.remove('active');
    }

    function openDeleteModal(ideaId, ideaTitle) {
        currentDeleteFormId = ideaId;
        document.getElementById('deleteIdeaTitle').textContent = ideaTitle;
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
        currentDeleteFormId = null;
    }

    function confirmDelete() {
        if (currentDeleteFormId) {
            document.getElementById('delete-form-' + currentDeleteFormId).submit();
        }
    }

    // Close modals on outside click
    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeDeleteModal();
        }
    });
</script>
@endpush
