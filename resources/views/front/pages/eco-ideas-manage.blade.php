@extends('layouts.front')

@section('title', 'Manage: ' . $ecoIdea->title)

@push('styles')
<style>
    /* FORCE DARK NAVBAR TEXT */
    .navbar-nav > li > a,
    .navbar-brand,
    .attr-nav > ul > li > a {
        color: #2c3e50 !important;
        text-shadow: none !important;
    }

    .manage-wrapper { display: flex; gap: 20px; min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #f0fdf4 30%, #ecfdf5 60%, #d1fae5 100%); padding: 120px 20px 60px; margin-top: 0; max-width: 1600px; margin-left: auto; margin-right: auto; }
    .sidebar { width: 240px; background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); position: sticky; top: 100px; height: calc(100vh - 120px); align-self: flex-start; overflow-y: auto; padding: 20px 0; flex-shrink: 0; }
    .sidebar-header { padding: 0 16px 16px; border-bottom: 1px solid #e5e7eb; }
    .project-title { font-size: 15px; font-weight: 700; color: #1a202c; margin-bottom: 8px; }
    .project-status { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .status-idea { background: #dbeafe; color: #1e40af; }
    .status-recruiting { background: #d1fae5; color: #065f46; }
    .status-in_progress { background: #fed7aa; color: #9a3412; }
    .status-completed { background: #bbf7d0; color: #166534; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 16px; color: #6b7280; text-decoration: none; transition: all 0.2s ease; cursor: pointer; font-size: 13px; }
    .nav-item:hover { background: #f3f4f6; color: #10b981; }
    .nav-item.active { background: #f0fdf4; color: #10b981; border-right: 3px solid #10b981; font-weight: 700; }
    .nav-badge { margin-left: auto; background: #ef4444; color: white; padding: 2px 7px; border-radius: 8px; font-size: 10px; font-weight: 700; }
    .nav-item i { font-size: 14px; }
    .main-content { flex: 1; padding: 0; min-width: 0; max-width: 1200px; }
    .content-section { display: none; }
    .content-section.active { display: block; width: 100%; }
    .section { background: white; border-radius: 12px; padding: 18px; margin-bottom: 18px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); width: 100%; box-sizing: border-box; }
    .section-title { font-size: 17px; font-weight: 700; color: #1a202c; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; }
    .section-title i { font-size: 16px; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; }
    .stat-card { text-align: center; padding: 14px; border-radius: 10px; }
    .stat-icon { font-size: 26px; margin-bottom: 8px; }
    .stat-value { font-size: 20px; font-weight: 800; color: #1a202c; }
    .stat-label { font-size: 12px; color: #6b7280; }
    .applications-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .applications-table th { text-align: left; padding: 10px; background: #f9fafb; font-weight: 700; color: #6b7280; font-size: 11px; text-transform: uppercase; }
    .applications-table td { padding: 12px 10px; border-top: 1px solid #e5e7eb; }
    .applicant-info { display: flex; align-items: center; gap: 10px; }
    .applicant-avatar { width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; }
    .btn-sm { padding: 5px 10px; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
    .btn-accept { background: #10b981; color: white; }
    .btn-accept:hover { background: #059669; }
    .btn-reject { background: #ef4444; color: white; }
    .btn-reject:hover { background: #dc2626; }
    .team-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
    .team-member-card { 
        background: white; 
        border: 1px solid #e5e7eb; 
        border-radius: 16px; 
        padding: 24px; 
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .team-member-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        border-color: #10b981;
    }
    .member-header { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
    .btn-remove { width: 100%; background: #ef4444; color: white; padding: 7px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 12px; }
    .task-board { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
    .task-column { background: #f9fafb; border-radius: 10px; padding: 12px; min-height: 350px; }
    .column-header { display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb; font-size: 13px; font-weight: 700; }
    .task-list { min-height: 250px; padding: 8px; border-radius: 8px; transition: background 0.2s ease; }
    .task-card { background: white; border-radius: 8px; padding: 12px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); cursor: grab; transition: all 0.2s ease; word-wrap: break-word; overflow-wrap: break-word; font-size: 13px; }
    .task-card:active { cursor: grabbing; }
    .priority-high { background: #fee2e2; color: #991b1b; padding: 2px 7px; border-radius: 8px; font-size: 10px; font-weight: 700; }
    .priority-medium { background: #fed7aa; color: #9a3412; padding: 2px 7px; border-radius: 8px; font-size: 10px; font-weight: 700; }
    .priority-low { background: #dbeafe; color: #1e40af; padding: 2px 7px; border-radius: 8px; font-size: 10px; font-weight: 700; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-weight: 600; color: #1a202c; margin-bottom: 6px; font-size: 13px; }
    .form-input, .form-select, .form-textarea { width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 13px; }
    .form-textarea { min-height: 80px; }
    .submit-btn { padding: 11px 22px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 13px; }
    .empty-state { text-align: center; padding: 30px; color: #9ca3af; font-size: 14px; }
    @media (max-width: 768px) { 
        .sidebar { position: static; width: 100%; } 
        .main-content { margin-left: 0; } 
        .task-board { grid-template-columns: 1fr; } 
        .stats-grid { grid-template-columns: 1fr 1fr !important; }
    }
    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    /* NASA-Style Animations */
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
        50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6); }
    }
    @keyframes float-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    @keyframes data-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
    }
    .animated-stat { animation: data-pulse 2s ease-in-out infinite; }
    @keyframes loading-sweep {
        0% { left: -100%; }
        100% { left: 200%; }
    }
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
</style>
@endpush

@section('content')
<div class="manage-wrapper">
    <div class="sidebar">
        <div class="sidebar-header">
            <h3 class="project-title">{{ $ecoIdea->title }}</h3>
            <span class="project-status status-{{ $ecoIdea->project_status }}">{{ ucfirst(str_replace('_', ' ', $ecoIdea->project_status)) }}</span>
        </div>
        <div style="padding: 20px 0;">
            <a href="#" class="nav-item active" data-section="overview"><i class="fas fa-chart-line"></i><span>Overview</span></a>
            @if($isCreator)
                <a href="#" class="nav-item" data-section="applications"><i class="fas fa-user-clock"></i><span>Applications</span>
                    @if($ecoIdea->applications->where('status', 'pending')->count() > 0)
                        <span class="nav-badge">{{ $ecoIdea->applications->where('status', 'pending')->count() }}</span>
                    @endif
                </a>
            @endif
            <a href="#" class="nav-item" data-section="team"><i class="fas fa-users"></i><span>Team Members</span></a>
            <a href="#" class="nav-item" data-section="tasks"><i class="fas fa-tasks"></i><span>Task Board</span></a>
            <a href="#" class="nav-item" data-section="chat"><i class="fas fa-comments"></i><span>Chat Room</span></a>
            
            @if($ecoIdea->project_status === 'verified')
                <a href="#" class="nav-item" data-section="certificate" style="background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); border-left: 4px solid #ec4899; margin: 10px 0;">
                    <i class="fas fa-certificate" style="color: #ec4899;"></i>
                    <span style="font-weight: 700; color: #9f1239;">Get Certificate</span>
                    <span style="background: #10b981; color: white; padding: 2px 6px; border-radius: 8px; font-size: 9px; margin-left: auto;">VERIFIED</span>
                </a>
            @endif
            
            @if($isCreator && $ecoIdea->project_status === 'in_progress')
                @php
                    $totalTasks = $ecoIdea->tasks()->count();
                    $completedTasks = $ecoIdea->tasks()->where('status', 'completed')->count();
                    $allTasksCompleted = $totalTasks > 0 && $completedTasks === $totalTasks;
                @endphp
                @if($allTasksCompleted)
                    <a href="#" class="nav-item completion-prompt" data-section="completion" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; margin: 10px 0; animation: glow 2s ease-in-out infinite; position: relative;">
                        <i class="fas fa-star" style="color: #f59e0b;"></i>
                        <span style="font-weight: 700; color: #92400e;">Mark Complete?</span>
                        <span style="position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; background: #f59e0b; border-radius: 50%; animation: pulse-dot 1.5s ease-in-out infinite;"></span>
                    </a>
                    <style>
                        @keyframes glow {
                            0%, 100% { box-shadow: 0 0 5px rgba(245, 158, 11, 0.3); }
                            50% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.6), 0 0 30px rgba(245, 158, 11, 0.4); }
                        }
                        @keyframes pulse-dot {
                            0%, 100% { transform: scale(1); opacity: 1; }
                            50% { transform: scale(1.5); opacity: 0.5; }
                        }
                        .completion-prompt:hover {
                            transform: translateX(5px);
                            box-shadow: 0 0 25px rgba(245, 158, 11, 0.7) !important;
                        }
                    </style>
                @endif
            @endif
            
            @if($isCreator)
                <a href="#" class="nav-item" data-section="settings"><i class="fas fa-cog"></i><span>Settings</span></a>
            @endif
        </div>
        <div style="padding: 0 16px;"><a href="{{ route('front.eco-ideas') }}" class="btn-sm" style="display: block; text-align: center; background: #e5e7eb; color: #1a202c; text-decoration: none; padding: 8px;"><i class="fas fa-arrow-left"></i> Back</a></div>
    </div>

    <div class="main-content">
        <!-- Overview -->
        <div class="content-section active" id="overview-section">
            <!-- Mission Control Header -->
            <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius: 20px; padding: 35px; margin-bottom: 25px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%); border-radius: 50%;"></div>
                <div style="position: relative; z-index: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                        <div>
                            <h2 style="font-size: 28px; font-weight: 900; color: white; margin-bottom: 8px; letter-spacing: -0.5px;">
                                <i class="fas fa-rocket" style="color: #10b981;"></i> PROJECT MISSION CONTROL
                            </h2>
                            <p style="color: #94a3b8; font-size: 14px; font-weight: 500;">Real-time Analytics & Performance Dashboard</p>
                        </div>
                        <div style="background: rgba(16, 185, 129, 0.2); padding: 12px 20px; border-radius: 12px; border: 2px solid #10b981;">
                            <div style="color: #6ee7b7; font-size: 12px; font-weight: 700; margin-bottom: 4px;">STATUS</div>
                            <div style="color: white; font-size: 18px; font-weight: 900;">{{ strtoupper($ecoIdea->project_status) }}</div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px;">
                        <div style="background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; padding: 15px; border-radius: 10px;">
                            <div style="color: #6ee7b7; font-size: 11px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Applications</div>
                            <div style="color: white; font-size: 24px; font-weight: 900;">{{ $ecoIdea->applications->where('status', 'pending')->count() }}</div>
                        </div>
                        <div style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; padding: 15px; border-radius: 10px;">
                            <div style="color: #93c5fd; font-size: 11px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Team Size</div>
                            <div style="color: white; font-size: 24px; font-weight: 900;">{{ $ecoIdea->team->count() + 1 }}/{{ $ecoIdea->team_size_needed ?? 'N/A' }}</div>
                        </div>
                        <div style="background: rgba(245, 158, 11, 0.1); border-left: 4px solid #f59e0b; padding: 15px; border-radius: 10px;">
                            <div style="color: #fbbf24; font-size: 11px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Total Tasks</div>
                            <div style="color: white; font-size: 24px; font-weight: 900;">{{ $ecoIdea->tasks->count() }}</div>
                        </div>
                        <div style="background: rgba(236, 72, 153, 0.1); border-left: 4px solid #ec4899; padding: 15px; border-radius: 10px;">
                            <div style="color: #f9a8d4; font-size: 11px; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Community</div>
                            <div style="color: white; font-size: 24px; font-weight: 900;">{{ $ecoIdea->upvotes ?? 0 }} <span style="font-size: 14px;">❤️</span></div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $todoCount = $ecoIdea->tasks->where('status', 'todo')->count();
                $inProgressCount = $ecoIdea->tasks->where('status', 'in_progress')->count();
                $reviewCount = $ecoIdea->tasks->where('status', 'review')->count();
                $completedCount = $ecoIdea->tasks->where('status', 'completed')->count();
                $totalTasks = $ecoIdea->tasks->count();
                $completionPercent = $totalTasks > 0 ? round(($completedCount / $totalTasks) * 100) : 0;
            @endphp

            <!-- ADVANCED TASK ANALYTICS DASHBOARD -->
            <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 25px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); border: 2px solid #10b981;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <h3 style="font-size: 24px; font-weight: 900; color: #1f2937; margin: 0;">
                        <i class="fas fa-chart-line" style="color: #10b981;"></i> TASK PROGRESS ANALYTICS
                    </h3>
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 12px 24px; border-radius: 50px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                        <span style="color: white; font-size: 22px; font-weight: 900;">{{ $completionPercent }}%</span>
                        <span style="color: #d1fae5; font-size: 12px; margin-left: 8px;">COMPLETE</span>
                    </div>
                </div>

                <!-- Animated Main Progress Bar -->
                <div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border-radius: 20px; padding: 25px; margin-bottom: 25px; position: relative; overflow: hidden; box-shadow: inset 0 2px 8px rgba(0,0,0,0.05);">
                    <div style="position: absolute; top: 0; left: 0; width: {{ $completionPercent }}%; height: 100%; background: linear-gradient(90deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.2) 100%); transition: width 2s ease;"></div>
                    <div style="position: relative; z-index: 1;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <span style="font-size: 16px; font-weight: 800; color: #1f2937;"><i class="fas fa-rocket" style="color: #10b981;"></i> MISSION PROGRESS</span>
                            <span style="font-size: 14px; font-weight: 700; color: #059669;">{{ $completedCount }} / {{ $totalTasks }} Tasks</span>
                        </div>
                        <div style="position: relative; background: #e5e7eb; border-radius: 50px; height: 28px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                            <div style="position: absolute; top: 0; left: 0; height: 100%; width: {{ $completionPercent }}%; background: linear-gradient(90deg, #10b981 0%, #34d399 50%, #6ee7b7 100%); border-radius: 50px; transition: width 2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 12px rgba(16, 185, 129, 0.5); animation: shimmer 2s infinite linear;">
                                <div style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.3) 50%, transparent 100%); animation: loading-sweep 2s infinite;"></div>
                            </div>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 14px; font-weight: 900; text-shadow: 0 2px 4px rgba(0,0,0,0.3); z-index: 10;">{{ $completionPercent }}%</div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Multi-Graph Layout -->
                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px;">
                    <!-- Left: Detailed Task Breakdown -->
                    <div>
                        <h4 style="font-size: 16px; font-weight: 800; color: #1f2937; margin-bottom: 18px;"><i class="fas fa-tasks" style="color: #3b82f6;"></i> Task Status Distribution</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <!-- To Do Card -->
                            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 15px; padding: 18px; border-left: 5px solid #f59e0b; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2); position: relative; overflow: hidden;">
                                <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%); border-radius: 50%;"></div>
                                <div style="position: relative;">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                        <div>
                                            <div style="font-size: 11px; font-weight: 800; color: #92400e; letter-spacing: 1px;">TO DO</div>
                                            <div style="font-size: 32px; font-weight: 900; color: #f59e0b; line-height: 1;">{{ $todoCount }}</div>
                                        </div>
                                        <div style="width: 45px; height: 45px; background: rgba(255,255,255,0.5); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-clipboard-list" style="font-size: 22px; color: #f59e0b;"></i>
                                        </div>
                                    </div>
                                    <div style="background: rgba(245, 158, 11, 0.2); border-radius: 50px; height: 8px; overflow: hidden; margin-bottom: 8px;">
                                        <div style="background: #f59e0b; height: 100%; width: {{ $totalTasks > 0 ? round(($todoCount / $totalTasks) * 100) : 0 }}%; border-radius: 50px; transition: width 1.5s ease; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);"></div>
                                    </div>
                                    <div style="font-size: 11px; color: #92400e; font-weight: 700;">{{ $totalTasks > 0 ? round(($todoCount / $totalTasks) * 100) : 0 }}% of total</div>
                                </div>
                            </div>

                            <!-- In Progress Card -->
                            <div style="background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%); border-radius: 15px; padding: 18px; border-left: 5px solid #3b82f6; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2); position: relative; overflow: hidden;">
                                <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%); border-radius: 50%;"></div>
                                <div style="position: relative;">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                        <div>
                                            <div style="font-size: 11px; font-weight: 800; color: #1e40af; letter-spacing: 1px;">IN PROGRESS</div>
                                            <div style="font-size: 32px; font-weight: 900; color: #3b82f6; line-height: 1;">{{ $inProgressCount }}</div>
                                        </div>
                                        <div style="width: 45px; height: 45px; background: rgba(255,255,255,0.5); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-spinner" style="font-size: 22px; color: #3b82f6;"></i>
                                        </div>
                                    </div>
                                    <div style="background: rgba(59, 130, 246, 0.2); border-radius: 50px; height: 8px; overflow: hidden; margin-bottom: 8px;">
                                        <div style="background: #3b82f6; height: 100%; width: {{ $totalTasks > 0 ? round(($inProgressCount / $totalTasks) * 100) : 0 }}%; border-radius: 50px; transition: width 1.5s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);"></div>
                                    </div>
                                    <div style="font-size: 11px; color: #1e40af; font-weight: 700;">{{ $totalTasks > 0 ? round(($inProgressCount / $totalTasks) * 100) : 0 }}% of total</div>
                                </div>
                            </div>

                            <!-- Review Card -->
                            <div style="background: linear-gradient(135deg, #fce7f3 0%, #f9a8d4 100%); border-radius: 15px; padding: 18px; border-left: 5px solid #ec4899; box-shadow: 0 4px 15px rgba(236, 72, 153, 0.2); position: relative; overflow: hidden;">
                                <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%); border-radius: 50%;"></div>
                                <div style="position: relative;">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                        <div>
                                            <div style="font-size: 11px; font-weight: 800; color: #9f1239; letter-spacing: 1px;">REVIEW</div>
                                            <div style="font-size: 32px; font-weight: 900; color: #ec4899; line-height: 1;">{{ $reviewCount }}</div>
                                        </div>
                                        <div style="width: 45px; height: 45px; background: rgba(255,255,255,0.5); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-search" style="font-size: 22px; color: #ec4899;"></i>
                                        </div>
                                    </div>
                                    <div style="background: rgba(236, 72, 153, 0.2); border-radius: 50px; height: 8px; overflow: hidden; margin-bottom: 8px;">
                                        <div style="background: #ec4899; height: 100%; width: {{ $totalTasks > 0 ? round(($reviewCount / $totalTasks) * 100) : 0 }}%; border-radius: 50px; transition: width 1.5s ease; box-shadow: 0 2px 8px rgba(236, 72, 153, 0.4);"></div>
                                    </div>
                                    <div style="font-size: 11px; color: #9f1239; font-weight: 700;">{{ $totalTasks > 0 ? round(($reviewCount / $totalTasks) * 100) : 0 }}% of total</div>
                                </div>
                            </div>

                            <!-- Completed Card -->
                            <div style="background: linear-gradient(135deg, #d1fae5 0%, #6ee7b7 100%); border-radius: 15px; padding: 18px; border-left: 5px solid #10b981; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2); position: relative; overflow: hidden;">
                                <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%); border-radius: 50%;"></div>
                                <div style="position: relative;">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                        <div>
                                            <div style="font-size: 11px; font-weight: 800; color: #065f46; letter-spacing: 1px;">COMPLETED</div>
                                            <div style="font-size: 32px; font-weight: 900; color: #10b981; line-height: 1;">{{ $completedCount }}</div>
                                        </div>
                                        <div style="width: 45px; height: 45px; background: rgba(255,255,255,0.5); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-check-circle" style="font-size: 22px; color: #10b981;"></i>
                                        </div>
                                    </div>
                                    <div style="background: rgba(16, 185, 129, 0.2); border-radius: 50px; height: 8px; overflow: hidden; margin-bottom: 8px;">
                                        <div style="background: #10b981; height: 100%; width: {{ $totalTasks > 0 ? round(($completedCount / $totalTasks) * 100) : 0 }}%; border-radius: 50px; transition: width 1.5s ease; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4); animation: pulse-glow 2s infinite;"></div>
                                    </div>
                                    <div style="font-size: 11px; color: #065f46; font-weight: 700;">{{ $totalTasks > 0 ? round(($completedCount / $totalTasks) * 100) : 0 }}% of total</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Circular Progress & Stats -->
                    <div>
                        <h4 style="font-size: 16px; font-weight: 800; color: #1f2937; margin-bottom: 18px;"><i class="fas fa-chart-pie" style="color: #8b5cf6;"></i> Completion Overview</h4>
                        <div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border-radius: 15px; padding: 25px; text-align: center; box-shadow: inset 0 2px 8px rgba(0,0,0,0.05);">
                            <!-- Circular Progress -->
                            <div style="position: relative; width: 160px; height: 160px; margin: 0 auto 20px;">
                                <svg width="160" height="160" style="transform: rotate(-90deg);">
                                    <!-- Background Circle -->
                                    <circle cx="80" cy="80" r="70" fill="none" stroke="#e5e7eb" stroke-width="12"></circle>
                                    <!-- Progress Circle -->
                                    <circle cx="80" cy="80" r="70" fill="none" stroke="url(#gradient)" stroke-width="12" stroke-dasharray="{{ 2 * 3.14159 * 70 }}" stroke-dashoffset="{{ 2 * 3.14159 * 70 * (1 - $completionPercent / 100) }}" stroke-linecap="round" style="transition: stroke-dashoffset 2s ease;">
                                        <animate attributeName="stroke-dashoffset" from="{{ 2 * 3.14159 * 70 }}" to="{{ 2 * 3.14159 * 70 * (1 - $completionPercent / 100) }}" dur="2s" fill="freeze" />
                                    </circle>
                                    <defs>
                                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#34d399;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                    <div style="font-size: 36px; font-weight: 900; color: #10b981; line-height: 1;">{{ $completionPercent }}%</div>
                                    <div style="font-size: 11px; color: #6b7280; font-weight: 700; margin-top: 4px;">COMPLETE</div>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 20px;">
                                <div style="background: white; padding: 12px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                    <div style="font-size: 24px; font-weight: 900; color: #3b82f6;">{{ $totalTasks }}</div>
                                    <div style="font-size: 10px; color: #6b7280; font-weight: 700;">TOTAL</div>
                                </div>
                                <div style="background: white; padding: 12px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                    <div style="font-size: 24px; font-weight: 900; color: #10b981;">{{ $completedCount }}</div>
                                    <div style="font-size: 10px; color: #6b7280; font-weight: 700;">DONE</div>
                                </div>
                                <div style="background: white; padding: 12px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                    <div style="font-size: 24px; font-weight: 900; color: #f59e0b;">{{ $totalTasks - $completedCount }}</div>
                                    <div style="font-size: 10px; color: #6b7280; font-weight: 700;">REMAINING</div>
                                </div>
                                <div style="background: white; padding: 12px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                    <div style="font-size: 24px; font-weight: 900; color: #ec4899;">{{ $totalTasks > 0 ? round($completedCount / max($totalTasks, 1) * 100 / 10) : 0 }}/10</div>
                                    <div style="font-size: 10px; color: #6b7280; font-weight: 700;">SCORE</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Intelligence Panel -->
            <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;">
                <h3 style="font-size: 20px; font-weight: 900; color: #1f2937; margin-bottom: 20px;">
                    <i class="fas fa-brain" style="color: #8b5cf6;"></i> PROJECT INTELLIGENCE
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                    <div>
                        <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 15px; padding: 20px; margin-bottom: 15px; border-left: 4px solid #3b82f6;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <div style="width: 50px; height: 50px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);">
                                    <i class="fas fa-recycle" style="font-size: 24px; color: #3b82f6;"></i>
                                </div>
                                <div>
                                    <div style="font-size: 12px; color: #1e40af; font-weight: 700; margin-bottom: 2px;">WASTE TYPE</div>
                                    <div style="font-size: 18px; color: #1e3a8a; font-weight: 900;">{{ ucfirst($ecoIdea->waste_type) }}</div>
                                </div>
                            </div>
                            <p style="font-size: 13px; color: #475569; line-height: 1.6;">{{ $ecoIdea->description }}</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 15px; padding: 20px; border-left: 4px solid #f59e0b;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-size: 12px; color: #92400e; font-weight: 700; margin-bottom: 4px;">DIFFICULTY</div>
                                    <div style="font-size: 20px; color: #78350f; font-weight: 900;">{{ strtoupper($ecoIdea->difficulty_level) }}</div>
                                </div>
                                <div style="display: flex; gap: 4px;">
                                    @for($i = 0; $i < 5; $i++)
                                        <div style="width: 8px; height: 30px; background: {{ $i < ['easy' => 2, 'medium' => 3, 'hard' => 5][$ecoIdea->difficulty_level] ? '#f59e0b' : '#fde68a' }}; border-radius: 4px;"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 15px; padding: 20px; margin-bottom: 15px; border-left: 4px solid #10b981;">
                            <div style="font-size: 12px; color: #065f46; font-weight: 700; margin-bottom: 10px;">CREATOR</div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; font-weight: 900; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">{{ strtoupper(substr($ecoIdea->creator->name, 0, 1)) }}</div>
                                <div>
                                    <div style="font-size: 16px; color: #064e3b; font-weight: 800;">{{ $ecoIdea->creator->name }}</div>
                                    <div style="font-size: 12px; color: #065f46;">{{ $ecoIdea->creator->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div style="background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); border-radius: 15px; padding: 20px; border-left: 4px solid #ec4899;">
                            <div style="font-size: 12px; color: #9f1239; font-weight: 700; margin-bottom: 8px;">PROJECT TIMELINE</div>
                            <div style="font-size: 12px; color: #831843; margin-bottom: 6px;"><i class="fas fa-calendar-plus" style="color: #ec4899;"></i> Created: {{ $ecoIdea->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 12px; color: #831843;"><i class="fas fa-clock" style="color: #ec4899;"></i> Age: {{ $ecoIdea->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
                        <!-- Left: Progress Bars -->
                        <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <div style="margin-bottom: 20px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-weight: 700; color: #1f2937;"><i class="fas fa-check-circle" style="color: #10b981;"></i> Overall Completion</span>
                                    <span style="font-weight: 800; color: #10b981;">{{ $completionPercent }}%</span>
                                </div>
                                <div style="background: #e5e7eb; border-radius: 999px; height: 12px; overflow: hidden;">
                                    <div style="background: linear-gradient(90deg, #10b981 0%, #059669 100%); height: 100%; width: {{ $completionPercent }}%; transition: width 0.5s ease; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);"></div>
                                </div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 25px;">
                                <!-- To Do -->
                                <div style="padding: 15px; background: #fef3c7; border-radius: 12px; border-left: 4px solid #f59e0b;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <span style="font-size: 13px; font-weight: 700; color: #92400e;">TO DO</span>
                                        <span style="font-size: 18px; font-weight: 800; color: #f59e0b;">{{ $todoCount }}</span>
                                    </div>
                                    <div style="background: #fde68a; border-radius: 999px; height: 6px; overflow: hidden;">
                                        <div style="background: #f59e0b; height: 100%; width: {{ $totalTasks > 0 ? round(($todoCount / $totalTasks) * 100) : 0 }}%; transition: width 0.5s ease;"></div>
                                    </div>
                                </div>
                                
                                <!-- In Progress -->
                                <div style="padding: 15px; background: #dbeafe; border-radius: 12px; border-left: 4px solid #3b82f6;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <span style="font-size: 13px; font-weight: 700; color: #1e40af;">IN PROGRESS</span>
                                        <span style="font-size: 18px; font-weight: 800; color: #3b82f6;">{{ $inProgressCount }}</span>
                                    </div>
                                    <div style="background: #93c5fd; border-radius: 999px; height: 6px; overflow: hidden;">
                                        <div style="background: #3b82f6; height: 100%; width: {{ $totalTasks > 0 ? round(($inProgressCount / $totalTasks) * 100) : 0 }}%; transition: width 0.5s ease;"></div>
                                    </div>
                                </div>
                                
                                <!-- Review -->
                                <div style="padding: 15px; background: #fce7f3; border-radius: 12px; border-left: 4px solid #ec4899;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <span style="font-size: 13px; font-weight: 700; color: #9f1239;">REVIEW</span>
                                        <span style="font-size: 18px; font-weight: 800; color: #ec4899;">{{ $reviewCount }}</span>
                                    </div>
                                    <div style="background: #f9a8d4; border-radius: 999px; height: 6px; overflow: hidden;">
                                        <div style="background: #ec4899; height: 100%; width: {{ $totalTasks > 0 ? round(($reviewCount / $totalTasks) * 100) : 0 }}%; transition: width 0.5s ease;"></div>
                                    </div>
                                </div>
                                
                                <!-- Completed -->
                                <div style="padding: 15px; background: #d1fae5; border-radius: 12px; border-left: 4px solid #10b981;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <span style="font-size: 13px; font-weight: 700; color: #065f46;">COMPLETED</span>
                                        <span style="font-size: 18px; font-weight: 800; color: #10b981;">{{ $completedCount }}</span>
                                    </div>
                                    <div style="background: #6ee7b7; border-radius: 999px; height: 6px; overflow: hidden;">
                                        <div style="background: #10b981; height: 100%; width: {{ $totalTasks > 0 ? round(($completedCount / $totalTasks) * 100) : 0 }}%; transition: width 0.5s ease;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right: Activity Chart -->
                        <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <h4 style="font-size: 16px; font-weight: 800; margin-bottom: 15px; color: #1f2937;"><i class="fas fa-chart-line" style="color: #3b82f6;"></i> Recent Activity</h4>
                            
                            @php
                                $recentTasks = $ecoIdea->tasks()->orderBy('created_at', 'desc')->take(7)->get()->reverse();
                                $tasksByDate = $recentTasks->groupBy(fn($t) => $t->created_at->format('M d'));
                                $maxCount = max($tasksByDate->map->count()->toArray() ?: [1]);
                                
                                // Prepare data for line chart
                                $chartData = [];
                                foreach ($tasksByDate as $date => $tasks) {
                                    $chartData[] = [
                                        'date' => $date,
                                        'count' => $tasks->count()
                                    ];
                                }
                            @endphp
                            
                            @if(count($chartData) > 0)
                                <div style="position: relative; height: 180px; padding: 10px; background: linear-gradient(to bottom, #f0f9ff 0%, #ffffff 100%); border-radius: 12px; overflow: hidden;">
                                    <svg width="100%" height="100%" style="position: absolute; top: 0; left: 0;">
                                        <defs>
                                            <!-- Gradient for line -->
                                            <linearGradient id="lineGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                                                <stop offset="50%" style="stop-color:#8b5cf6;stop-opacity:1" />
                                                <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
                                            </linearGradient>
                                            <!-- Gradient for area fill -->
                                            <linearGradient id="areaGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:0.3" />
                                                <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0.05" />
                                            </linearGradient>
                                        </defs>
                                        
                                        @php
                                            $width = 100;
                                            $height = 150;
                                            $padding = 15;
                                            $chartWidth = $width - ($padding * 2);
                                            $chartHeight = $height - ($padding * 2);
                                            $pointCount = count($chartData);
                                            $xStep = $chartWidth / max($pointCount - 1, 1);
                                            
                                            // Build path for line
                                            $linePath = '';
                                            $areaPath = '';
                                            $points = [];
                                            
                                            foreach ($chartData as $index => $data) {
                                                $x = $padding + ($index * $xStep);
                                                $y = $padding + ($chartHeight - (($data['count'] / $maxCount) * $chartHeight));
                                                
                                                $points[] = ['x' => $x, 'y' => $y, 'count' => $data['count']];
                                                
                                                if ($index === 0) {
                                                    $linePath .= "M {$x} {$y}";
                                                    $areaPath .= "M {$x} " . ($padding + $chartHeight) . " L {$x} {$y}";
                                                } else {
                                                    $linePath .= " L {$x} {$y}";
                                                    $areaPath .= " L {$x} {$y}";
                                                }
                                            }
                                            
                                            // Close area path
                                            $lastX = $padding + (($pointCount - 1) * $xStep);
                                            $areaPath .= " L {$lastX} " . ($padding + $chartHeight) . " Z";
                                        @endphp
                                        
                                        <!-- Grid lines -->
                                        @for($i = 0; $i <= 4; $i++)
                                            @php
                                                $gridY = $padding + ($i * ($chartHeight / 4));
                                            @endphp
                                            <line x1="{{ $padding }}%" y1="{{ $gridY }}%" x2="{{ 100 - $padding }}%" y2="{{ $gridY }}%" stroke="#e5e7eb" stroke-width="0.5" stroke-dasharray="2,2" />
                                        @endfor
                                        
                                        <!-- Area fill -->
                                        <path d="{{ $areaPath }}" fill="url(#areaGradient)" />
                                        
                                        <!-- Line -->
                                        <path d="{{ $linePath }}" fill="none" stroke="url(#lineGradient)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.3));" />
                                        
                                        <!-- Points -->
                                        @foreach($points as $point)
                                            <circle cx="{{ $point['x'] }}%" cy="{{ $point['y'] }}%" r="5" fill="white" stroke="#3b82f6" stroke-width="3">
                                                <animate attributeName="r" values="5;7;5" dur="2s" repeatCount="indefinite" />
                                            </circle>
                                            <circle cx="{{ $point['x'] }}%" cy="{{ $point['y'] }}%" r="3" fill="#3b82f6" />
                                        @endforeach
                                    </svg>
                                    
                                    <!-- Date labels -->
                                    <div style="display: flex; justify-content: space-between; align-items: flex-end; height: 100%; padding: 0 {{ $padding }}%;">
                                        @foreach($chartData as $data)
                                            <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                                                <div style="font-size: 13px; font-weight: 800; color: #3b82f6; background: white; padding: 2px 8px; border-radius: 999px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">{{ $data['count'] }}</div>
                                                <div style="font-size: 10px; font-weight: 600; color: #6b7280; margin-top: 5px;">{{ $data['date'] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div style="display: flex; align-items: center; justify-content: center; height: 180px; background: #f9fafb; border-radius: 12px;">
                                    <div style="text-align: center; color: #9ca3af;">
                                        <i class="fas fa-chart-line" style="font-size: 32px; margin-bottom: 10px; opacity: 0.5;"></i>
                                        <p style="font-size: 13px; margin: 0;">No task activity yet</p>
                                    </div>
                                </div>
                            @endif
                            
                            <p style="font-size: 12px; color: #6b7280; margin-top: 12px; text-align: center;"><i class="fas fa-info-circle"></i> Tasks created per day</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications -->
        @if($isCreator)
        <div class="content-section" id="applications-section">
            <div class="section">
                <h2 class="section-title"><i class="fas fa-user-clock"></i>Pending Applications ({{ $ecoIdea->applications->where('status', 'pending')->count() }})</h2>
                @if($ecoIdea->applications->where('status', 'pending')->count() > 0)
                    <table class="applications-table">
                        <thead><tr><th>Applicant</th><th>Message</th><th>Resume</th><th>Applied</th><th>Actions</th></tr></thead>
                        <tbody>
                            @foreach($ecoIdea->applications->where('status', 'pending') as $application)
                                <tr>
                                    <td><div class="applicant-info"><div class="applicant-avatar">{{ strtoupper(substr($application->user->name, 0, 1)) }}</div><div><h4 style="margin:0; font-size:14px;">{{ $application->user->name }}</h4><span style="font-size:13px; color:#6b7280;">{{ $application->user->email }}</span></div></div></td>
                                    <td><div style="max-width:300px; overflow:hidden; text-overflow:ellipsis;">{{ $application->application_message }}</div></td>
                                    <td>
                                        @if($application->resume_path)
                                            <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="btn-sm btn-view" style="text-decoration:none;"><i class="fas fa-file-pdf"></i> View PDF</a>
                                        @else
                                            <span style="color:#9ca3af; font-size:13px;">No resume</span>
                                        @endif
                                    </td>
                                    <td><span style="font-size:13px; color:#6b7280;">{{ $application->created_at->diffForHumans() }}</span></td>
                                    <td>
                                        <form action="{{ route('front.eco-ideas.dashboard.applications.accept', $application) }}" method="POST" style="display:inline;">@csrf<button type="submit" class="btn-sm btn-accept"><i class="fas fa-check"></i> Accept</button></form>
                                        <form action="{{ route('front.eco-ideas.dashboard.applications.reject', $application) }}" method="POST" style="display:inline;">@csrf<button type="submit" class="btn-sm btn-reject"><i class="fas fa-times"></i> Reject</button></form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state"><i class="fas fa-inbox" style="font-size:48px; margin-bottom:15px;"></i><p>No pending applications</p></div>
                @endif
            </div>
        </div>
        @endif

        <!-- Team -->
        <div class="content-section" id="team-section">
            <div class="section">
                <h2 class="section-title"><i class="fas fa-users"></i>Active Members ({{ $ecoIdea->team->count() + 1 }})</h2>
                <div class="team-grid">
                    <!-- Owner Card -->
                    <div class="team-member-card" style="border: 2px solid #10b981; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); position: relative; overflow: hidden;">
                        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%); border-radius: 50%;"></div>
                        <div style="position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 6px 14px; border-radius: 999px; font-size: 10px; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); letter-spacing: 0.5px; z-index: 1;">
                            <i class="fas fa-crown"></i> OWNER
                        </div>
                        <div class="member-header" style="position: relative; z-index: 1;">
                            <div class="applicant-avatar" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 56px; height: 56px; font-size: 24px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">{{ strtoupper(substr($ecoIdea->creator->name, 0, 1)) }}</div>
                            <div style="flex: 1;">
                                <h4 style="margin:0; font-size: 16px; font-weight: 700; color: #1f2937;">{{ $ecoIdea->creator->name }}</h4>
                                <span style="font-size:13px; color:#10b981; font-weight:600; display: inline-block; margin-top: 4px;">
                                    <i class="fas fa-star" style="font-size: 10px;"></i> Project Creator
                                </span>
                            </div>
                        </div>
                        <div style="background: white; border-radius: 10px; padding: 12px; margin-bottom: 16px; border: 1px solid #d1fae5;">
                            <p style="font-size:13px; color:#6b7280; margin:0; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-envelope" style="color: #10b981;"></i> 
                                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $ecoIdea->creator->email }}</span>
                            </p>
                        </div>
                        @if($isCreator)
                            <button onclick="viewMemberDetails('{{ $ecoIdea->creator->id }}', '{{ $ecoIdea->creator->name }}', '{{ $ecoIdea->creator->email }}', 'Owner', 'Project Creator', null)" style="width: 100%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2); font-size: 13px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(16, 185, 129, 0.2)'">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                        @endif
                    </div>
                    
                    <!-- Team Members -->
                    @foreach($ecoIdea->team as $member)
                        <div class="team-member-card" style="position: relative;">
                            <div class="member-header">
                                <div class="applicant-avatar" style="width: 56px; height: 56px; font-size: 24px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">{{ strtoupper(substr($member->user->name, 0, 1)) }}</div>
                                <div style="flex: 1;">
                                    <h4 style="margin:0; font-size: 16px; font-weight: 700; color: #1f2937;">{{ $member->user->name }}</h4>
                                    <span style="font-size:13px; color:#3b82f6; font-weight:600; display: inline-block; margin-top: 4px;">
                                        <i class="fas fa-user-check" style="font-size: 10px;"></i> {{ ucfirst($member->role ?? 'Member') }}
                                    </span>
                                </div>
                            </div>
                            <div style="background: #f9fafb; border-radius: 10px; padding: 12px; margin-bottom: 16px; border: 1px solid #e5e7eb;">
                                <p style="font-size:13px; color:#6b7280; margin:0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-clock" style="color: #3b82f6;"></i> 
                                    <span>Joined {{ $member->joined_at ? $member->joined_at->diffForHumans() : 'recently' }}</span>
                                </p>
                            </div>
                            @if($isCreator)
                                <div style="display: flex; gap: 10px;">
                                    <button onclick="viewMemberDetails('{{ $member->user->id }}', '{{ $member->user->name }}', '{{ $member->user->email }}', '{{ ucfirst($member->role ?? 'Member') }}', '{{ $member->joined_at ? $member->joined_at->diffForHumans() : 'recently' }}', '{{ $member->application->resume_path ?? '' }}')" style="flex: 1; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2); font-size: 13px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(59, 130, 246, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.2)'">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <form id="remove-member-form-{{ $member->id }}" action="{{ route('front.eco-ideas.dashboard.team.remove', $member) }}" method="POST" style="flex: 1;">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmRemoveMember('{{ $member->id }}', '{{ $member->user->name }}')" style="width: 100%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2); font-size: 13px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(239, 68, 68, 0.2)'">
                                            <i class="fas fa-user-minus"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tasks -->
        <div class="content-section" id="tasks-section">
            <div class="section">
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; gap: 10px; flex-wrap: wrap;">
                        <h2 class="section-title" style="margin-bottom: 0;">
                            <i class="fas fa-tasks"></i>Task Board
                            @if($ecoIdea->project_status === 'verified' || $ecoIdea->project_status === 'completed')
                                <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 11px; margin-left: 10px;">
                                    <i class="fas fa-lock"></i> LOCKED
                                </span>
                            @endif
                        </h2>
                        @if($ecoIdea->project_status !== 'verified' && $ecoIdea->project_status !== 'completed')
                            <button onclick="openCreateTaskModal()" style="padding: 12px 24px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                <i class="fas fa-plus"></i> Add Task
                            </button>
                        @endif
                    </div>
                    
                    @if($ecoIdea->project_status === 'verified' || $ecoIdea->project_status === 'completed')
                        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; padding: 18px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-lock" style="font-size: 28px; color: #f59e0b;"></i>
                                <div>
                                    <h4 style="font-size: 15px; font-weight: 800; color: #92400e; margin: 0 0 5px 0;">Task Board Locked</h4>
                                    <p style="font-size: 13px; color: #78350f; margin: 0; line-height: 1.6;">
                                        This project has been {{ $ecoIdea->project_status === 'verified' ? 'verified' : 'completed' }}. Tasks can no longer be edited or created. You can still view task details for reference.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($isCreator && $ecoIdea->project_status === 'in_progress')
                        @php
                            $totalTasks = $ecoIdea->tasks()->count();
                            $completedTasks = $ecoIdea->tasks()->where('status', 'completed')->count();
                            $allTasksCompleted = $totalTasks > 0 && $completedTasks === $totalTasks;
                        @endphp
                        @if($allTasksCompleted)
                            <div onclick="switchSection('completion')" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; padding: 15px; border-radius: 8px; margin-bottom: 15px; cursor: pointer; transition: all 0.3s ease; position: relative;" onmouseover="this.style.transform='translateX(5px)'; this.style.boxShadow='0 4px 20px rgba(245,158,11,0.4)'" onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='none'">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-star" style="font-size: 24px; color: #f59e0b; animation: spin-slow 3s linear infinite;"></i>
                                    <div style="flex: 1;">
                                        <p style="font-size: 14px; color: #92400e; margin: 0; font-weight: 700;">
                                            🎉 All tasks completed! Ready to submit?
                                        </p>
                                        <p style="font-size: 12px; color: #b45309; margin: 5px 0 0 0;">
                                            <i class="fas fa-arrow-right"></i> Click here or check the sidebar to mark project as completed
                                        </p>
                                    </div>
                                    <i class="fas fa-chevron-right" style="color: #f59e0b; font-size: 18px;"></i>
                                </div>
                            </div>
                            <style>
                                @keyframes spin-slow {
                                    from { transform: rotate(0deg); }
                                    to { transform: rotate(360deg); }
                                }
                            </style>
                        @elseif($totalTasks > 0)
                            <div style="background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                                <p style="font-size: 13px; color: #1e40af; margin: 0;">
                                    <i class="fas fa-info-circle"></i> Progress: <strong>{{ $completedTasks }}/{{ $totalTasks }}</strong> tasks completed. Complete all tasks to mark project as done.
                                </p>
                            </div>
                        @endif
                    @endif
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <input type="text" id="taskSearchInput" placeholder="Search tasks..." style="flex: 1; min-width: 200px; padding: 10px 15px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px;">
                        <select id="taskFilterAssignee" style="padding: 10px 15px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; min-width: 150px;">
                            <option value="">All Assignees</option>
                            <option value="{{ $ecoIdea->creator_id }}">{{ $ecoIdea->creator->name }}</option>
                            @foreach($ecoIdea->team as $member)
                                <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
                            @endforeach
                            <option value="unassigned">Unassigned</option>
                        </select>
                        <select id="taskFilterPriority" style="padding: 10px 15px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px;">
                            <option value="">All Priorities</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                        <button onclick="clearFilters()" style="padding: 10px 20px; background: #f3f4f6; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
                <div class="task-board" id="taskBoard">
                    <div class="task-column" data-status="todo">
                        <div class="column-header">
                            <span style="font-size:14px; font-weight:700; color:#6b7280;">📋 TO DO</span>
                            <span class="task-count" style="background:white; color:#6b7280; width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700;">{{ $ecoIdea->tasks->where('status', 'todo')->count() }}</span>
                        </div>
                        <div class="task-list" data-status="todo">
                            @foreach($ecoIdea->tasks->where('status', 'todo') as $task)
                                <div class="task-card" data-task-id="{{ $task->id }}" data-task-title="{{ strtolower($task->title) }}" data-task-assignee="{{ $task->assigned_to ?? 'unassigned' }}" data-task-priority="{{ $task->priority }}" draggable="{{ ($ecoIdea->project_status !== 'verified' && $ecoIdea->project_status !== 'completed') ? 'true' : 'false' }}" style="{{ ($ecoIdea->project_status === 'verified' || $ecoIdea->project_status === 'completed') ? 'opacity: 0.7; cursor: default;' : '' }}">
                                    <div style="font-size:14px; font-weight:700; margin-bottom:8px;">{{ $task->title }}</div>
                                    <div style="font-size:12px; color:#6b7280; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                                        <span class="priority-{{ $task->priority }}">{{ strtoupper($task->priority) }}</span>
                                        @if($task->assignedUser)
                                            <span><i class="fas fa-user"></i> {{ $task->assignedUser->name }}</span>
                                        @else
                                            <span style="color: #9ca3af;"><i class="fas fa-user-slash"></i> Unassigned</span>
                                        @endif
                                        @if($task->due_date)
                                            <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</span>
                                        @endif
                                    </div>
                                    <div style="display: flex; gap: 5px;">
                                        <button onclick="event.stopPropagation(); openViewTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-eye"></i> View</button>
                                        @if($ecoIdea->project_status !== 'verified' && $ecoIdea->project_status !== 'completed')
                                            <button onclick="event.stopPropagation(); openEditTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
                                        @else
                                            <button disabled style="flex: 1; padding: 6px; background: #9ca3af; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: not-allowed; opacity: 0.5;"><i class="fas fa-lock"></i> Locked</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="task-column" data-status="in_progress">
                        <div class="column-header">
                            <span style="font-size:14px; font-weight:700; color:#6b7280;">🔄 IN PROGRESS</span>
                            <span class="task-count" style="background:white; color:#6b7280; width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700;">{{ $ecoIdea->tasks->where('status', 'in_progress')->count() }}</span>
                        </div>
                        <div class="task-list" data-status="in_progress">
                            @foreach($ecoIdea->tasks->where('status', 'in_progress') as $task)
                                <div class="task-card" data-task-id="{{ $task->id }}" data-task-title="{{ strtolower($task->title) }}" data-task-assignee="{{ $task->assigned_to ?? 'unassigned' }}" data-task-priority="{{ $task->priority }}" draggable="{{ ($ecoIdea->project_status !== 'verified' && $ecoIdea->project_status !== 'completed') ? 'true' : 'false' }}" style="{{ ($ecoIdea->project_status === 'verified' || $ecoIdea->project_status === 'completed') ? 'opacity: 0.7; cursor: default;' : '' }}">
                                    <div style="font-size:14px; font-weight:700; margin-bottom:8px;">{{ $task->title }}</div>
                                    <div style="font-size:12px; color:#6b7280; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                                        <span class="priority-{{ $task->priority }}">{{ strtoupper($task->priority) }}</span>
                                        @if($task->assignedUser)
                                            <span><i class="fas fa-user"></i> {{ $task->assignedUser->name }}</span>
                                        @else
                                            <span style="color: #9ca3af;"><i class="fas fa-user-slash"></i> Unassigned</span>
                                        @endif
                                        @if($task->due_date)
                                            <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</span>
                                        @endif
                                    </div>
                                    <div style="display: flex; gap: 5px;">
                                        <button onclick="event.stopPropagation(); openViewTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-eye"></i> View</button>
                                        @if($ecoIdea->project_status !== 'verified' && $ecoIdea->project_status !== 'completed')
                                            <button onclick="event.stopPropagation(); openEditTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
                                        @else
                                            <button disabled style="flex: 1; padding: 6px; background: #9ca3af; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: not-allowed; opacity: 0.5;"><i class="fas fa-lock"></i> Locked</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="task-column" data-status="review">
                        <div class="column-header">
                            <span style="font-size:14px; font-weight:700; color:#6b7280;">👀 REVIEW</span>
                            <span class="task-count" style="background:white; color:#6b7280; width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700;">{{ $ecoIdea->tasks->where('status', 'review')->count() }}</span>
                        </div>
                        <div class="task-list" data-status="review">
                            @foreach($ecoIdea->tasks->where('status', 'review') as $task)
                                <div class="task-card" data-task-id="{{ $task->id }}" data-task-title="{{ strtolower($task->title) }}" data-task-assignee="{{ $task->assigned_to ?? 'unassigned' }}" data-task-priority="{{ $task->priority }}" draggable="{{ ($ecoIdea->project_status !== 'verified' && $ecoIdea->project_status !== 'completed') ? 'true' : 'false' }}" style="{{ ($ecoIdea->project_status === 'verified' || $ecoIdea->project_status === 'completed') ? 'opacity: 0.7; cursor: default;' : '' }}">
                                    <div style="font-size:14px; font-weight:700; margin-bottom:8px;">{{ $task->title }}</div>
                                    <div style="font-size:12px; color:#6b7280; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                                        <span class="priority-{{ $task->priority }}">{{ strtoupper($task->priority) }}</span>
                                        @if($task->assignedUser)
                                            <span><i class="fas fa-user"></i> {{ $task->assignedUser->name }}</span>
                                        @else
                                            <span style="color: #9ca3af;"><i class="fas fa-user-slash"></i> Unassigned</span>
                                        @endif
                                        @if($task->due_date)
                                            <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</span>
                                        @endif
                                    </div>
                                    <div style="display: flex; gap: 5px;">
                                        <button onclick="event.stopPropagation(); openViewTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-eye"></i> View</button>
                                        @if($ecoIdea->project_status !== 'verified' && $ecoIdea->project_status !== 'completed')
                                            <button onclick="event.stopPropagation(); openEditTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
                                        @else
                                            <button disabled style="flex: 1; padding: 6px; background: #9ca3af; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: not-allowed; opacity: 0.5;"><i class="fas fa-lock"></i> Locked</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="task-column" data-status="completed">
                        <div class="column-header">
                            <span style="font-size:14px; font-weight:700; color:#6b7280;">✅ COMPLETED</span>
                            <span class="task-count" style="background:white; color:#6b7280; width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700;">{{ $ecoIdea->tasks->where('status', 'completed')->count() }}</span>
                        </div>
                        <div class="task-list" data-status="completed">
                            @foreach($ecoIdea->tasks->where('status', 'completed') as $task)
                                <div class="task-card" data-task-id="{{ $task->id }}" data-task-title="{{ strtolower($task->title) }}" data-task-assignee="{{ $task->assigned_to ?? 'unassigned' }}" data-task-priority="{{ $task->priority }}" draggable="{{ ($ecoIdea->project_status !== 'verified' && $ecoIdea->project_status !== 'completed') ? 'true' : 'false' }}" style="{{ ($ecoIdea->project_status === 'verified' || $ecoIdea->project_status === 'completed') ? 'opacity: 0.7; cursor: default;' : '' }}">
                                    <div style="font-size:14px; font-weight:700; margin-bottom:8px;">{{ $task->title }}</div>
                                    <div style="font-size:12px; color:#6b7280; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                                        <span class="priority-{{ $task->priority }}">{{ strtoupper($task->priority) }}</span>
                                        @if($task->assignedUser)
                                            <span><i class="fas fa-user"></i> {{ $task->assignedUser->name }}</span>
                                        @else
                                            <span style="color: #9ca3af;"><i class="fas fa-user-slash"></i> Unassigned</span>
                                        @endif
                                        @if($task->due_date)
                                            <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</span>
                                        @endif
                                    </div>
                                    <div style="display: flex; gap: 5px;">
                                        <button onclick="event.stopPropagation(); openViewTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-eye"></i> View</button>
                                        @if($ecoIdea->project_status !== 'verified' && $ecoIdea->project_status !== 'completed')
                                            <button onclick="event.stopPropagation(); openEditTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
                                        @else
                                            <button disabled style="flex: 1; padding: 6px; background: #9ca3af; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: not-allowed; opacity: 0.5;"><i class="fas fa-lock"></i> Locked</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Room -->
        <div class="content-section" id="chat-section">
            <div class="section">
                <h2 class="section-title"><i class="fas fa-comments"></i> Team Chat Room</h2>
                
                <div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden;">
                    <!-- Chat Messages Container -->
                    <div id="chatMessages" style="height: 350px; overflow-y: auto; padding: 20px; background: linear-gradient(to bottom, #f9fafb 0%, #ffffff 100%);">
                        <div style="text-align: center; color: #9ca3af; padding: 40px;">
                            <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                            <p style="font-size: 14px;">Loading messages...</p>
                        </div>
                    </div>
                    
                    <!-- Chat Input -->
                    <div style="border-top: 2px solid #e5e7eb; padding: 20px; background: white;">
                        <form id="teamChatForm" style="display: flex; gap: 12px; align-items: center;">
                            @csrf
                            <input 
                                type="text" 
                                id="teamChatInput" 
                                placeholder="Type your message..." 
                                style="flex: 1; padding: 14px 20px; border: 2px solid #e5e7eb; border-radius: 999px; font-size: 14px; outline: none; transition: all 0.2s ease;"
                                required
                                maxlength="1000"
                            />
                            <button 
                                type="submit" 
                                style="padding: 14px 32px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 999px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); display: flex; align-items: center; gap: 8px;"
                            >
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </form>
                    </div>
                </div>
                
                <p style="font-size: 12px; color: #6b7280; margin-top: 12px; text-align: center;">
                    <i class="fas fa-info-circle"></i> Only team members can view and send messages
                </p>
            </div>
        </div>

        <!-- Waiting for Verification Section -->
        @if($ecoIdea->project_status === 'completed')
        <div class="content-section" id="completion-section">
            <div class="section">
                <div style="text-align: center; padding: 50px 20px;">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 50%; margin: 0 auto 30px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 40px rgba(245, 158, 11, 0.3); animation: pulse 2s ease-in-out infinite;">
                        <i class="fas fa-hourglass-half" style="font-size: 56px; color: #f59e0b;"></i>
                    </div>
                    
                    <h2 style="font-size: 32px; font-weight: 800; color: #1f2937; margin-bottom: 15px;">
                        ⏳ Awaiting Admin Verification
                    </h2>
                    
                    <p style="font-size: 18px; color: #6b7280; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                        Your project has been marked as <strong style="color: #10b981;">completed</strong>! It's now under review by the Waste2Product team.
                    </p>
                    
                    <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 20px; padding: 35px; margin: 30px auto; max-width: 700px; border: 3px solid #3b82f6;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 25px;">
                            <div style="width: 60px; height: 60px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-certificate" style="color: white; font-size: 28px;"></i>
                            </div>
                            <h3 style="font-size: 24px; font-weight: 800; color: #1e40af; margin: 0;">
                                Certificate Pending
                            </h3>
                        </div>
                        
                        <p style="font-size: 15px; color: #1e3a8a; margin-bottom: 25px; line-height: 1.7;">
                            Our admin team is reviewing your project. Once verified, you and your team members will receive <strong>official certificates</strong> recognizing your environmental impact!
                        </p>
                        
                        <div style="background: white; border-radius: 14px; padding: 25px; margin-bottom: 20px;">
                            <h4 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 18px;">
                                <i class="fas fa-tasks" style="color: #10b981;"></i> What's Being Reviewed:
                            </h4>
                            <div style="display: grid; gap: 12px;">
                                <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f9fafb; border-radius: 8px;">
                                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 18px;"></i>
                                    <span style="color: #4b5563; font-size: 14px;">Task completion and quality</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f9fafb; border-radius: 8px;">
                                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 18px;"></i>
                                    <span style="color: #4b5563; font-size: 14px;">Project documentation</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f9fafb; border-radius: 8px;">
                                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 18px;"></i>
                                    <span style="color: #4b5563; font-size: 14px;">Environmental impact</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f9fafb; border-radius: 8px;">
                                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 18px;"></i>
                                    <span style="color: #4b5563; font-size: 14px;">Team collaboration</span>
                                </div>
                            </div>
                        </div>
                        
                        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; padding: 18px; border: 2px solid #fcd34d;">
                            <p style="margin: 0; font-size: 14px; color: #92400e; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-info-circle" style="font-size: 20px;"></i>
                                <span><strong>Good to know:</strong> Your project is now locked and cannot be edited. Check back soon for verification results!</span>
                            </p>
                        </div>
                    </div>
                    
                    <p style="font-size: 13px; color: #9ca3af; margin-top: 25px;">
                        <i class="fas fa-clock"></i> Average verification time: 1-3 business days
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Certificate Section -->
        @if($ecoIdea->project_status === 'verified')
        <div class="content-section" id="certificate-section">
            <div class="section">
                <h2 class="section-title"><i class="fas fa-certificate"></i> Official Completion Certificate</h2>
                
                <div style="margin-bottom: 20px; text-align: center;">
                    <button onclick="downloadCertificateAsPDF()" style="display: inline-block; padding: 14px 40px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px; box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4); transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(16, 185, 129, 0.5)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(16, 185, 129, 0.4)'">
                        <i class="fas fa-download"></i> Download Certificate
                    </button>
                </div>
                
                <!-- Include html2pdf library -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                
                <!-- Certificate Preview (Print-optimized) -->
                <div id="certificate-container" class="certificate-printable">
                <div style="background: white; padding: 25px; margin: 0 auto; max-width: 900px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 12px solid #10b981; border-image: linear-gradient(135deg, #10b981, #059669, #ec4899, #8b5cf6) 1; position: relative;">
                    <!-- Decorative Corner Elements -->
                    <div style="position: absolute; top: 10px; left: 10px; width: 60px; height: 60px; border-top: 4px solid #10b981; border-left: 4px solid #10b981;"></div>
                    <div style="position: absolute; top: 10px; right: 10px; width: 60px; height: 60px; border-top: 4px solid #ec4899; border-right: 4px solid #ec4899;"></div>
                    <div style="position: absolute; bottom: 10px; left: 10px; width: 60px; height: 60px; border-bottom: 4px solid #8b5cf6; border-left: 4px solid #8b5cf6;"></div>
                    <div style="position: absolute; bottom: 10px; right: 10px; width: 60px; height: 60px; border-bottom: 4px solid #f59e0b; border-right: 4px solid #f59e0b;"></div>
                    
                    <div style="position: relative; text-align: center; padding: 20px;">
                        <!-- Header -->
                        <div style="margin-bottom: 25px;">
                            <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 15px;">
                                <img src="https://ui-avatars.com/api/?name=Waste2Product&background=10b981&color=fff&size=60&font-size=0.4" alt="Logo" style="width: 60px; height: 60px; border-radius: 50%; border: 3px solid #10b981;">
                                <div style="text-align: left;">
                                    <h1 style="font-size: 28px; font-weight: 900; color: #1f2937; margin: 0; letter-spacing: -0.5px;">WASTE2PRODUCT</h1>
                                    <p style="font-size: 12px; color: #10b981; font-weight: 700; margin: 0; letter-spacing: 1px;">ENVIRONMENTAL INNOVATION PLATFORM</p>
                                </div>
                            </div>
                            <div style="height: 3px; background: linear-gradient(90deg, transparent, #10b981, #ec4899, #8b5cf6, transparent); margin: 15px 0;"></div>
                            <h2 style="font-size: 32px; font-weight: 900; color: #1f2937; margin: 10px 0 5px 0; letter-spacing: 2px; text-transform: uppercase;">Certificate of Achievement</h2>
                            <p style="font-size: 14px; color: #6b7280; font-weight: 600; margin: 0;">🌍 Eco-Innovation & Sustainable Development 🌿</p>
                        </div>

                        <!-- Recipient -->
                        <div style="margin: 25px 0;">
                            <p style="font-size: 12px; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">This hereby certifies that</p>
                            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 15px 30px; border-radius: 12px; margin: 10px auto; max-width: 600px; border: 2px solid #10b981;">
                                <h2 style="font-size: 28px; font-weight: 900; color: #10b981; margin: 0; font-family: 'Georgia', serif; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">{{ auth()->user()->name }}</h2>
                            </div>
                            <p style="font-size: 13px; color: #6b7280; margin-top: 8px; font-weight: 600;">has successfully completed and verified the eco-innovation project</p>
                        </div>

                        <!-- Project Details -->
                        <div style="background: linear-gradient(135deg, #fafafa 0%, #ffffff 100%); border-radius: 12px; padding: 20px; margin: 20px 0; border: 2px solid #e5e7eb;">
                            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 20px; border-radius: 8px; margin-bottom: 15px;">
                                <h3 style="font-size: 18px; font-weight: 800; margin: 0; text-align: center;">📋 {{ $ecoIdea->title }}</h3>
                            </div>
                            
                            <!-- Project Statistics Grid -->
                            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; margin-bottom: 12px;">
                                <div style="background: #f0f9ff; padding: 8px; border-radius: 8px; text-align: center; border: 1px solid #bae6fd;">
                                    <div style="font-size: 8px; color: #0369a1; font-weight: 700; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.5px;">Waste Type</div>
                                    <div style="font-size: 11px; color: #0c4a6e; font-weight: 800;">{{ ucfirst($ecoIdea->waste_type) }}</div>
                                </div>
                                <div style="background: #fef3c7; padding: 8px; border-radius: 8px; text-align: center; border: 1px solid #fcd34d;">
                                    <div style="font-size: 8px; color: #92400e; font-weight: 700; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.5px;">Difficulty</div>
                                    <div style="font-size: 11px; color: #78350f; font-weight: 800;">{{ ucfirst($ecoIdea->difficulty_level) }}</div>
                                </div>
                                <div style="background: #f0fdf4; padding: 8px; border-radius: 8px; text-align: center; border: 1px solid #86efac;">
                                    <div style="font-size: 8px; color: #15803d; font-weight: 700; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.5px;">Team Size</div>
                                    <div style="font-size: 11px; color: #14532d; font-weight: 800;">{{ $ecoIdea->team()->count() + 1 }}</div>
                                </div>
                                <div style="background: #fce7f3; padding: 8px; border-radius: 8px; text-align: center; border: 1px solid #f9a8d4;">
                                    <div style="font-size: 8px; color: #9f1239; font-weight: 700; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.5px;">Tasks</div>
                                    <div style="font-size: 11px; color: #831843; font-weight: 800;">{{ $ecoIdea->tasks()->where('status', 'completed')->count() }}/{{ $ecoIdea->tasks()->count() }}</div>
                                </div>
                                <div style="background: #ede9fe; padding: 8px; border-radius: 8px; text-align: center; border: 1px solid #c4b5fd;">
                                    <div style="font-size: 8px; color: #5b21b6; font-weight: 700; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.5px;">Duration</div>
                                    @php
                                        $start = $ecoIdea->created_at;
                                        $end = $ecoIdea->completion_date ?? $ecoIdea->updated_at;
                                        $duration = $start->diffInDays($end);
                                    @endphp
                                    <div style="font-size: 11px; color: #4c1d95; font-weight: 800;">{{ $duration }} days</div>
                                </div>
                            </div>
                            
                            <!-- Team Members Section -->
                            @if($ecoIdea->team()->count() > 0)
                            <div style="background: linear-gradient(135deg, #fef9f3 0%, #fff7ed 100%); padding: 10px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #fed7aa;">
                                <div style="font-size: 8px; color: #9a3412; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; text-align: center;">👥 Project Team Members</div>
                                <div style="display: flex; flex-wrap: wrap; gap: 6px; justify-content: center;">
                                    <div style="background: white; padding: 4px 10px; border-radius: 15px; border: 1px solid #fbbf24;">
                                        <span style="font-size: 9px; color: #92400e; font-weight: 700;">🎯 {{ $ecoIdea->creator->name }}</span>
                                        <span style="font-size: 7px; color: #f59e0b; margin-left: 4px;">(Lead)</span>
                                    </div>
                                    @foreach($ecoIdea->team as $member)
                                    <div style="background: white; padding: 4px 10px; border-radius: 15px; border: 1px solid #fcd34d;">
                                        <span style="font-size: 9px; color: #78350f; font-weight: 600;">{{ $member->user->name }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Verification Details Grid -->
                            <div style="background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); padding: 12px; border-radius: 8px; border: 2px solid #8b5cf6;">
                                <div style="text-align: center; margin-bottom: 8px;">
                                    <div style="font-size: 9px; color: #5b21b6; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">✨ Official Verification Details ✨</div>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
                                    <div style="background: white; padding: 8px; border-radius: 6px; text-align: center;">
                                        <div style="font-size: 8px; color: #6b7280; font-weight: 700; margin-bottom: 3px;">Started</div>
                                        <div style="font-size: 10px; color: #1f2937; font-weight: 800;">{{ $ecoIdea->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div style="background: white; padding: 8px; border-radius: 6px; text-align: center; border: 2px solid #f59e0b;">
                                        <div style="font-size: 8px; color: #92400e; font-weight: 700; margin-bottom: 3px;">✓ Completed</div>
                                        <div style="font-size: 10px; color: #7c2d12; font-weight: 800;">{{ $ecoIdea->completion_date ? $ecoIdea->completion_date->format('M d, Y') : $ecoIdea->updated_at->format('M d, Y') }}</div>
                                    </div>
                                    <div style="background: white; padding: 8px; border-radius: 6px; text-align: center; border: 2px solid #8b5cf6;">
                                        <div style="font-size: 8px; color: #5b21b6; font-weight: 700; margin-bottom: 3px;">✓ Verified</div>
                                        <div style="font-size: 10px; color: #4c1d95; font-weight: 800;">{{ $ecoIdea->verification_date ? $ecoIdea->verification_date->format('M d, Y') : $ecoIdea->updated_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                
                                <!-- Impact Metrics if available -->
                                @if($ecoIdea->impact_metrics && is_array($ecoIdea->impact_metrics) && count($ecoIdea->impact_metrics) > 0)
                                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #c4b5fd;">
                                    <div style="font-size: 8px; color: #5b21b6; font-weight: 700; text-align: center; margin-bottom: 6px; text-transform: uppercase;">🌍 Environmental Impact Metrics</div>
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 6px;">
                                        @php
                                            $colors = [
                                                ['bg' => '#ecfdf5', 'border' => '#86efac', 'text' => '#15803d', 'value' => '#14532d'],
                                                ['bg' => '#f0f9ff', 'border' => '#bae6fd', 'text' => '#0369a1', 'value' => '#0c4a6e'],
                                                ['bg' => '#fef3c7', 'border' => '#fcd34d', 'text' => '#92400e', 'value' => '#78350f'],
                                                ['bg' => '#fce7f3', 'border' => '#f9a8d4', 'text' => '#9f1239', 'value' => '#831843'],
                                                ['bg' => '#ede9fe', 'border' => '#c4b5fd', 'text' => '#5b21b6', 'value' => '#4c1d95'],
                                                ['bg' => '#fef9f3', 'border' => '#fed7aa', 'text' => '#9a3412', 'value' => '#7c2d12'],
                                            ];
                                            $colorIndex = 0;
                                        @endphp
                                        @foreach($ecoIdea->impact_metrics as $key => $value)
                                            @php
                                                $color = $colors[$colorIndex % count($colors)];
                                                $colorIndex++;
                                                $label = ucwords(str_replace('_', ' ', $key));
                                            @endphp
                                            <div style="background: {{ $color['bg'] }}; padding: 6px; border-radius: 6px; text-align: center; border: 1px solid {{ $color['border'] }};">
                                                <div style="font-size: 7px; color: {{ $color['text'] }}; font-weight: 600; line-height: 1.2;">{{ $label }}</div>
                                                <div style="font-size: 9px; color: {{ $color['value'] }}; font-weight: 800; margin-top: 2px;">{{ $value }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <div style="margin-top: 8px; text-align: center;">
                                    <div style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 6px 16px; border-radius: 15px;">
                                        <span style="font-size: 8px; color: white; font-weight: 800; letter-spacing: 0.5px;">VERIFICATION STATUS: APPROVED</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Achievement & Impact Section -->
                        <div style="margin: 15px 0;">
                            <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 10px; padding: 12px; border: 2px solid #10b981; margin-bottom: 10px;">
                                <p style="font-size: 11px; line-height: 1.5; color: #065f46; margin: 0; text-align: justify;">
                                    <strong style="color: #047857;">🎉 Congratulations!</strong> This certificate recognizes your outstanding commitment to environmental sustainability. By successfully completing this verified eco-innovation project, you have demonstrated exceptional skills in transforming waste into valuable resources, embodying the principles of circular economy and environmental stewardship.
                                </p>
                            </div>
                            
                            @if($ecoIdea->donated_to_ngo && $ecoIdea->ngo_name)
                            <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 8px; padding: 10px; border: 1px solid #fbbf24;">
                                <div style="display: flex; align-items: center; gap: 8px; justify-content: center;">
                                    <i class="fas fa-hands-helping" style="font-size: 16px; color: #f59e0b;"></i>
                                    <p style="font-size: 10px; color: #92400e; margin: 0; font-weight: 700;">Project donated to: <strong>{{ $ecoIdea->ngo_name }}</strong></p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Verification Seal -->
                        <div style="margin: 15px 0;">
                            <div style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 8px 20px; border-radius: 25px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); border: 2px solid white; outline: 2px solid #10b981;">
                                <i class="fas fa-certificate" style="font-size: 16px;"></i>
                                <span style="font-weight: 900; font-size: 11px; letter-spacing: 1.5px;">OFFICIALLY VERIFIED</span>
                                <i class="fas fa-check-circle" style="font-size: 16px;"></i>
                            </div>
                        </div>

                        <!-- Signatures & Footer -->
                        <div style="margin-top: 20px; padding-top: 15px; border-top: 2px dashed #e5e7eb;">
                            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 15px; align-items: center;">
                                <div style="text-align: center;">
                                    <div style="width: 120px; height: 1px; background: linear-gradient(90deg, transparent, #10b981, transparent); margin: 0 auto 6px;"></div>
                                    <p style="font-size: 9px; color: #6b7280; margin: 0; font-weight: 700; text-transform: uppercase;">Project Lead</p>
                                    <p style="font-size: 11px; color: #1f2937; margin: 3px 0 0 0; font-weight: 800;">{{ $ecoIdea->creator->name }}</p>
                                </div>
                                <div style="text-align: center;">
                                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #ec4899); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-leaf" style="font-size: 24px; color: white;"></i>
                                    </div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="width: 120px; height: 1px; background: linear-gradient(90deg, transparent, #ec4899, transparent); margin: 0 auto 6px;"></div>
                                    <p style="font-size: 9px; color: #6b7280; margin: 0; font-weight: 700; text-transform: uppercase;">Platform Authority</p>
                                    <p style="font-size: 11px; color: #1f2937; margin: 3px 0 0 0; font-weight: 800;">Waste2Product © {{ now()->format('Y') }}</p>
                                </div>
                            </div>
                            
                            <!-- Certificate ID & Verification Details -->
                            <div style="margin-top: 12px; padding: 10px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                                <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center;">
                                    <div style="text-align: center;">
                                        <div style="font-size: 7px; color: #6b7280; font-weight: 600; text-transform: uppercase;">Certificate ID</div>
                                        <div style="font-size: 9px; color: #1f2937; font-weight: 800; font-family: 'Courier New', monospace; margin-top: 2px;">WP-{{ str_pad($ecoIdea->id, 6, '0', STR_PAD_LEFT) }}-{{ now()->format('Y') }}</div>
                                    </div>
                                    <div style="width: 1px; height: 30px; background: #e5e7eb;"></div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 7px; color: #6b7280; font-weight: 600; text-transform: uppercase;">Verified By</div>
                                        <div style="font-size: 9px; color: #1f2937; font-weight: 800; margin-top: 2px;">Waste2Product Admin Team</div>
                                    </div>
                                </div>
                                <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb; text-align: center;">
                                    <p style="font-size: 7px; color: #9ca3af; margin: 0; line-height: 1.4;">
                                        This certificate is electronically verified and can be authenticated at waste2product.com/certificates/{{ $ecoIdea->id }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>
        
        <!-- PDF Download Function -->
        <script>
        function downloadCertificateAsPDF() {
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating PDF...';
            button.disabled = true;
            
            // Get the certificate element
            const element = document.getElementById('certificate-container');
            
            // Clone element to avoid modifying the original
            const clone = element.cloneNode(true);
            clone.style.width = '297mm'; // A4 landscape width
            clone.style.maxWidth = '297mm';
            clone.style.padding = '10mm';
            clone.style.boxSizing = 'border-box';
            
            // Configure PDF options - optimized for one page
            const opt = {
                margin: [5, 5, 5, 5],
                filename: 'Certificate_{{ str_replace(' ', '_', $ecoIdea->title) }}_{{ str_replace(' ', '_', auth()->user()->name) }}.pdf',
                image: { type: 'jpeg', quality: 0.95 },
                html2canvas: { 
                    scale: 1.5,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff',
                    windowWidth: 1400,
                    windowHeight: 900
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'landscape',
                    compress: true
                },
                pagebreak: { 
                    mode: 'avoid-all',
                    avoid: ['div', 'tr', 'td']
                }
            };
            
            // Generate and download PDF
            html2pdf().set(opt).from(clone).save().then(() => {
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
                
                // Show success toast
                showToast('✅ Certificate downloaded successfully!', 'success');
            }).catch(err => {
                console.error('PDF generation error:', err);
                button.innerHTML = originalText;
                button.disabled = false;
                showToast('❌ Error generating PDF. Please try again.', 'error');
            });
        }
        </script>
        
        <!-- Print-Specific CSS -->
        <style>
        @media print {
            /* Reset all margins and padding */
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                width: 100%;
                height: 100%;
            }
            
            /* Hide everything except the certificate */
            body * {
                visibility: hidden;
            }
            
            #certificate-container, #certificate-container * {
                visibility: visible;
            }
            
            #certificate-container {
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                transform: scale(0.75) !important;
                transform-origin: top left !important;
            }
            
            /* Page settings for one-page print */
            @page {
                size: A4 landscape;
                margin: 5mm;
            }
            
            .certificate-printable {
                page-break-inside: avoid !important;
                page-break-after: avoid !important;
                page-break-before: avoid !important;
                max-height: 100vh !important;
                overflow: hidden !important;
            }
            
            /* Adjust certificate container sizing */
            #certificate-container > div {
                max-width: 100% !important;
                padding: 30px !important;
                box-sizing: border-box !important;
            }
            
            /* Remove screen-only elements */
            .section-title,
            button,
            a[href],
            .no-print {
                display: none !important;
            }
            
            /* Optimize colors for print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            /* Reduce padding/margins inside certificate */
            #certificate-container h1 {
                font-size: 24px !important;
            }
            
            #certificate-container h2 {
                font-size: 26px !important;
            }
            
            #certificate-container h3 {
                font-size: 16px !important;
            }
            
            #certificate-container p {
                font-size: 10px !important;
            }
            
            /* Compact grid spacing */
            #certificate-container [style*="grid"] {
                gap: 8px !important;
            }
            
            /* Reduce vertical margins */
            #certificate-container > div > div {
                margin-top: 15px !important;
                margin-bottom: 15px !important;
            }
        }
        
        /* Screen-only optimization */
        @media screen {
            .certificate-printable {
                max-width: 1000px;
                margin: 0 auto;
            }
        }
        </style>
        @endif

        <!-- Project Completion Confirmation -->
        @if($isCreator && $ecoIdea->project_status === 'in_progress')
            @php
                $totalTasks = $ecoIdea->tasks()->count();
                $completedTasks = $ecoIdea->tasks()->where('status', 'completed')->count();
                $allTasksCompleted = $totalTasks > 0 && $completedTasks === $totalTasks;
            @endphp
            @if($allTasksCompleted)
                <div class="content-section" id="completion-section">
                    <div class="section">
                        <div style="text-align: center; padding: 40px 20px;">
                            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 50%; margin: 0 auto 30px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 40px rgba(245, 158, 11, 0.3); animation: bounce 2s ease-in-out infinite;">
                                <i class="fas fa-star" style="font-size: 60px; color: #f59e0b;"></i>
                            </div>
                            
                            <h2 style="font-size: 32px; font-weight: 800; color: #1f2937; margin-bottom: 15px;">
                                🎉 All Tasks Completed!
                            </h2>
                            
                            <p style="font-size: 18px; color: #6b7280; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                                Congratulations! You've completed all <strong>{{ $totalTasks }}</strong> tasks for this project.
                            </p>
                            
                            <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 16px; padding: 30px; margin: 30px auto; max-width: 700px; border: 2px solid #3b82f6;">
                                <h3 style="font-size: 24px; font-weight: 800; color: #1e40af; margin-bottom: 20px;">
                                    <i class="fas fa-question-circle"></i> Is Your Project Completed?
                                </h3>
                                
                                <p style="font-size: 15px; color: #1e3a8a; margin-bottom: 25px; line-height: 1.7;">
                                    By confirming completion, your project will be submitted to <strong>Waste2Product</strong> for verification. 
                                    Our team will review your work and issue an official certificate if everything meets the requirements.
                                </p>
                                
                                <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                                    <h4 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 15px;">
                                        <i class="fas fa-clipboard-check" style="color: #10b981;"></i> Before You Confirm:
                                    </h4>
                                    <ul style="list-style: none; padding: 0; margin: 0; text-align: left;">
                                        <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; color: #4b5563;">
                                            <i class="fas fa-check-circle" style="color: #10b981;"></i> All {{ $totalTasks }} tasks are marked as completed
                                        </li>
                                        <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; color: #4b5563;">
                                            <i class="fas fa-check-circle" style="color: #10b981;"></i> Project goals have been achieved
                                        </li>
                                        <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; color: #4b5563;">
                                            <i class="fas fa-check-circle" style="color: #10b981;"></i> All documentation is ready
                                        </li>
                                        <li style="padding: 8px 0; color: #4b5563;">
                                            <i class="fas fa-check-circle" style="color: #10b981;"></i> Team is satisfied with the results
                                        </li>
                                    </ul>
                                </div>
                                
                                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                                    <form action="{{ route('front.eco-ideas.dashboard.mark-completed', $ecoIdea) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="padding: 16px 40px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4); display: flex; align-items: center; gap: 10px;">
                                            <i class="fas fa-check-double"></i>
                                            <span>Yes, Mark as Completed</span>
                                        </button>
                                    </form>
                                    
                                    <button onclick="switchSection('tasks')" style="padding: 16px 40px; background: #f3f4f6; color: #6b7280; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-times"></i>
                                        <span>Not Yet, Go Back</span>
                                    </button>
                                </div>
                            </div>
                            
                            <p style="font-size: 13px; color: #9ca3af; margin-top: 20px;">
                                <i class="fas fa-info-circle"></i> You can come back to this page anytime as long as all tasks remain completed
                            </p>
                        </div>
                    </div>
                </div>
                
                <style>
                    @keyframes bounce {
                        0%, 100% { transform: translateY(0); }
                        50% { transform: translateY(-20px); }
                    }
                </style>
            @endif
        @endif

        <!-- Settings -->
        @if($isCreator)
        <div class="content-section" id="settings-section">
            <div class="section">
                <h2 class="section-title"><i class="fas fa-cog"></i>Edit Project</h2>
                
                @if(in_array($ecoIdea->project_status, ['completed', 'verified']))
                    <!-- Project Locked Message -->
                    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b; border-radius: 16px; padding: 24px; margin-bottom: 20px; text-align: center;">
                        <div style="width: 64px; height: 64px; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                            <i class="fas fa-lock" style="color: white; font-size: 28px;"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 800; color: #92400e; margin: 0 0 8px 0;">
                            Project Locked
                        </h3>
                        <p style="font-size: 14px; color: #78350f; margin: 0; line-height: 1.6;">
                            This project is <strong>{{ $ecoIdea->project_status === 'verified' ? 'verified' : 'completed' }}</strong> and can no longer be edited. All settings and team management are locked to preserve project integrity.
                        </p>
                        @if($ecoIdea->project_status === 'verified')
                            <div style="margin-top: 16px; padding: 12px; background: rgba(255,255,255,0.5); border-radius: 10px;">
                                <p style="margin: 0; font-size: 13px; color: #92400e;">
                                    <i class="fas fa-certificate" style="color: #10b981;"></i> Your project has been verified! View your certificate in the Completion section.
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
                
                <form action="{{ route('front.eco-ideas.dashboard.update', $ecoIdea) }}" method="POST" enctype="multipart/form-data" @if(in_array($ecoIdea->project_status, ['completed', 'verified'])) onsubmit="return false;" @endif>
                    @csrf @method('PUT')
                    @php $isLocked = in_array($ecoIdea->project_status, ['completed', 'verified']); @endphp
                    <div class="form-group"><label class="form-label">Title</label><input type="text" name="title" class="form-input" value="{{ $ecoIdea->title }}" required @if($isLocked) readonly style="background: #f3f4f6; cursor: not-allowed;" @endif></div>
                    <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea" required @if($isLocked) readonly style="background: #f3f4f6; cursor: not-allowed;" @endif>{{ $ecoIdea->description }}</textarea></div>
                    
                    <!-- Image Upload Section -->
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-image"></i> Project Image</label>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <!-- Current Image Preview -->
                                @if($ecoIdea->image_path)
                                    <div style="flex-shrink: 0;">
                                        <img id="currentImage" src="{{ asset('storage/' . $ecoIdea->image_path) }}" alt="Current Image" style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; border: 3px solid #e5e7eb;">
                                    </div>
                                @endif
                                
                                <!-- Upload Button -->
                                <div style="flex: 1;">
                                    <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;" onchange="previewNewImage(this)">
                                    <label for="imageInput" class="upload-btn" style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 8px; cursor: pointer; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                                        <i class="fas fa-cloud-upload-alt"></i> Choose New Image
                                    </label>
                                    <style>
                                        .upload-btn:hover {
                                            transform: translateY(-2px);
                                            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
                                        }
                                        #currentImage {
                                            transition: all 0.3s ease;
                                        }
                                    </style>
                                    <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
                                        <i class="fas fa-info-circle"></i> Recommended: 800x600px, Max 5MB (JPG, PNG, WEBP)
                                    </p>
                                </div>
                            </div>
                            
                            <!-- New Image Preview -->
                            <div id="newImagePreview" style="display: none; margin-top: 15px; padding: 15px; background: #f0f9ff; border-radius: 12px; border: 2px dashed #3b82f6;">
                                <p style="font-size: 14px; font-weight: 700; color: #1e40af; margin-bottom: 10px;">
                                    <i class="fas fa-eye"></i> New Image Preview:
                                </p>
                                <img id="newImagePreviewImg" src="" alt="New Image" style="max-width: 100%; max-height: 300px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                <button type="button" onclick="removeNewImage()" style="margin-top: 10px; padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">
                                    <i class="fas fa-times"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div class="form-group"><label class="form-label">Waste Type</label><select name="waste_type" class="form-select" required><option value="organic" {{ $ecoIdea->waste_type == 'organic' ? 'selected' : '' }}>Organic</option><option value="plastic" {{ $ecoIdea->waste_type == 'plastic' ? 'selected' : '' }}>Plastic</option><option value="metal" {{ $ecoIdea->waste_type == 'metal' ? 'selected' : '' }}>Metal</option><option value="e-waste" {{ $ecoIdea->waste_type == 'e-waste' ? 'selected' : '' }}>E-Waste</option><option value="paper" {{ $ecoIdea->waste_type == 'paper' ? 'selected' : '' }}>Paper</option><option value="glass" {{ $ecoIdea->waste_type == 'glass' ? 'selected' : '' }}>Glass</option><option value="textile" {{ $ecoIdea->waste_type == 'textile' ? 'selected' : '' }}>Textile</option><option value="mixed" {{ $ecoIdea->waste_type == 'mixed' ? 'selected' : '' }}>Mixed</option></select></div>
                        <div class="form-group"><label class="form-label">Difficulty</label><select name="difficulty_level" class="form-select" required><option value="easy" {{ $ecoIdea->difficulty_level == 'easy' ? 'selected' : '' }}>Easy</option><option value="medium" {{ $ecoIdea->difficulty_level == 'medium' ? 'selected' : '' }}>Medium</option><option value="hard" {{ $ecoIdea->difficulty_level == 'hard' ? 'selected' : '' }}>Hard</option></select></div>
                    </div>
                    
                    <!-- Current Status Display (Read-only) -->
                    <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left: 4px solid #3b82f6; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                @if($ecoIdea->project_status === 'recruiting')
                                    <i class="fas fa-users" style="font-size: 24px; color: #3b82f6;"></i>
                                @elseif($ecoIdea->project_status === 'in_progress')
                                    <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #10b981;"></i>
                                @elseif($ecoIdea->project_status === 'completed')
                                    <i class="fas fa-check-circle" style="font-size: 24px; color: #f59e0b;"></i>
                                @elseif($ecoIdea->project_status === 'verified')
                                    <i class="fas fa-certificate" style="font-size: 24px; color: #8b5cf6;"></i>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 13px; font-weight: 600; color: #6b7280; margin: 0 0 4px 0;">Current Project Status</p>
                                <h3 style="font-size: 18px; font-weight: 800; color: #1f2937; margin: 0; text-transform: capitalize;">
                                    {{ str_replace('_', ' ', $ecoIdea->project_status) }}
                                </h3>
                            </div>
                        </div>
                        <p style="font-size: 12px; color: #6b7280; margin: 10px 0 0 0; padding-top: 10px; border-top: 1px solid rgba(59, 130, 246, 0.2);">
                            <i class="fas fa-magic"></i> <strong>Automatic Status:</strong> Status changes automatically based on team and task completion
                    <!-- Team Size Management -->
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-users"></i> Team Size Needed (Including You)</label>
                        @php
                            $currentTeamCount = $ecoIdea->team()->count() + 1; // +1 for owner
                        @endphp
                        
                        <!-- Beautiful Number Input Control -->
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                            <button 
                                type="button" 
                                id="decrementTeamSize" 
                                onclick="changeTeamSize(-1)"
                                style="width: 50px; height: 50px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; border-radius: 12px; color: white; font-size: 20px; font-weight: 800; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); display: flex; align-items: center; justify-content: center;"
                                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 20px rgba(239, 68, 68, 0.4)'"
                                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'"
                            >
                                <i class="fas fa-minus"></i>
                            </button>
                            
                            <div style="flex: 1; position: relative;">
                                <input 
                                    type="number" 
                                    name="team_size_needed" 
                                    id="teamSizeInput"
                                    class="form-input" 
                                    value="{{ $ecoIdea->team_size_needed ?? $currentTeamCount }}" 
                                    min="{{ $currentTeamCount }}" 
                                    readonly
                                    style="text-align: center; font-size: 24px; font-weight: 800; color: #1f2937; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 3px solid #3b82f6; padding: 12px; cursor: default; box-shadow: inset 0 2px 8px rgba(59, 130, 246, 0.1);"
                                >
                                <div style="position: absolute; top: 50%; right: 16px; transform: translateY(-50%); display: flex; flex-direction: column; gap: 2px; pointer-events: none;">
                                    <i class="fas fa-chevron-up" style="font-size: 10px; color: #3b82f6; opacity: 0.5;"></i>
                                    <i class="fas fa-chevron-down" style="font-size: 10px; color: #3b82f6; opacity: 0.5;"></i>
                                </div>
                            </div>
                            
                            <button 
                                type="button" 
                                id="incrementTeamSize" 
                                onclick="changeTeamSize(1)"
                                style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 12px; color: white; font-size: 20px; font-weight: 800; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); display: flex; align-items: center; justify-content: center;"
                                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 20px rgba(16, 185, 129, 0.4)'"
                                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)'"
                            >
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <script>
                        const minTeamSize = {{ $currentTeamCount }};
                        const maxTeamSize = 20;
                        
                        function changeTeamSize(delta) {
                            const input = document.getElementById('teamSizeInput');
                            let currentValue = parseInt(input.value) || minTeamSize;
                            let newValue = currentValue + delta;
                            
                            // Enforce constraints
                            if (newValue < minTeamSize) {
                                newValue = minTeamSize;
                                // Visual feedback for minimum reached
                                input.style.animation = 'shake 0.5s ease';
                                setTimeout(() => input.style.animation = '', 500);
                            }
                            if (newValue > maxTeamSize) {
                                newValue = maxTeamSize;
                                // Visual feedback for maximum reached
                                input.style.animation = 'shake 0.5s ease';
                                setTimeout(() => input.style.animation = '', 500);
                            }
                            
                            input.value = newValue;
                            
                            // Update button states
                            updateButtonStates(newValue);
                            
                            // Animate value change
                            input.style.transform = 'scale(1.1)';
                            setTimeout(() => input.style.transform = 'scale(1)', 200);
                        }
                        
                        function updateButtonStates(value) {
                            const decrementBtn = document.getElementById('decrementTeamSize');
                            const incrementBtn = document.getElementById('incrementTeamSize');
                            
                            // Disable decrement if at minimum
                            if (value <= minTeamSize) {
                                decrementBtn.style.opacity = '0.5';
                                decrementBtn.style.cursor = 'not-allowed';
                                decrementBtn.disabled = true;
                            } else {
                                decrementBtn.style.opacity = '1';
                                decrementBtn.style.cursor = 'pointer';
                                decrementBtn.disabled = false;
                            }
                            
                            // Disable increment if at maximum
                            if (value >= maxTeamSize) {
                                incrementBtn.style.opacity = '0.5';
                                incrementBtn.style.cursor = 'not-allowed';
                                incrementBtn.disabled = true;
                            } else {
                                incrementBtn.style.opacity = '1';
                                incrementBtn.style.cursor = 'pointer';
                                incrementBtn.disabled = false;
                            }
                        }
                        
                        // Support arrow keys
                        document.getElementById('teamSizeInput').addEventListener('keydown', function(e) {
                            if (e.key === 'ArrowUp') {
                                e.preventDefault();
                                changeTeamSize(1);
                            } else if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                changeTeamSize(-1);
                            }
                        });
                        
                        // Initialize button states
                        updateButtonStates(parseInt(document.getElementById('teamSizeInput').value) || minTeamSize);
                        </script>
                        
                        <style>
                        @keyframes shake {
                            0%, 100% { transform: translateX(0) scale(1); }
                            25% { transform: translateX(-5px) scale(1.05); }
                            75% { transform: translateX(5px) scale(1.05); }
                        }
                        
                        #teamSizeInput {
                            transition: transform 0.2s ease, box-shadow 0.3s ease;
                        }
                        
                        #teamSizeInput:focus {
                            outline: none;
                            border-color: #2563eb;
                            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2), inset 0 2px 8px rgba(59, 130, 246, 0.1);
                        }
                        </style>
                        <div style="margin-top: 10px; padding: 12px; background: {{ $currentTeamCount >= ($ecoIdea->team_size_needed ?? 0) && $ecoIdea->team_size_needed > 0 ? '#fef2f2' : '#f0fdf4' }}; border-left: 4px solid {{ $currentTeamCount >= ($ecoIdea->team_size_needed ?? 0) && $ecoIdea->team_size_needed > 0 ? '#ef4444' : '#10b981' }}; border-radius: 8px;">
                            <p style="font-size: 14px; color: {{ $currentTeamCount >= ($ecoIdea->team_size_needed ?? 0) && $ecoIdea->team_size_needed > 0 ? '#991b1b' : '#065f46' }}; margin: 0; font-weight: 600;">
                                <i class="fas {{ $currentTeamCount >= ($ecoIdea->team_size_needed ?? 0) && $ecoIdea->team_size_needed > 0 ? 'fa-users-slash' : 'fa-user-check' }}"></i>
                                Current Team: <strong>{{ $currentTeamCount }}</strong> / <strong>{{ $ecoIdea->team_size_needed ?? 0 }}</strong> members
                            </p>
                            <p style="font-size: 13px; color: #6b7280; margin: 8px 0 0 0;">
                                @if($currentTeamCount >= ($ecoIdea->team_size_needed ?? 0) && $ecoIdea->team_size_needed > 0)
                                    <i class="fas fa-check-circle" style="color: #ef4444;"></i> Team is full! Recruitment automatically closed.
                                @elseif($ecoIdea->team_size_needed > 0)
                                    <i class="fas fa-users" style="color: #10b981;"></i> Waiting for <strong>{{ ($ecoIdea->team_size_needed ?? 0) - $currentTeamCount }}</strong> more member(s). Recruitment is open!
                                @else
                                    <i class="fas fa-info-circle"></i> Set team size to open recruitment. Enter total team size including yourself.
                                @endif
                            </p>
                            <p style="font-size: 12px; color: #9ca3af; margin: 8px 0 0 0; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                                <i class="fas fa-lightbulb"></i> <strong>Note:</strong> Team size includes you as owner. Current: {{ $currentTeamCount }} (You + {{ $currentTeamCount - 1 }} member(s))
                            </p>
                        </div>
                        @error('team_size_needed')
                            <div style="margin-top: 12px; padding: 14px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 2px solid #ef4444; border-radius: 12px; animation: slideIn 0.3s ease;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 36px; height: 36px; background: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-exclamation-triangle" style="color: white; font-size: 16px;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <p style="margin: 0; color: #991b1b; font-weight: 700; font-size: 13px;">Validation Error</p>
                                        <p style="margin: 4px 0 0 0; color: #7f1d1d; font-size: 13px;">{{ $message }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <style>
                            @keyframes slideIn {
                                from {
                                    opacity: 0;
                                    transform: translateY(-10px);
                                }
                                to {
                                    opacity: 1;
                                    transform: translateY(0);
                                }
                            }
                            </style>
                        @enderror
                    </div>
                    @if(!$isLocked)
                        <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Save Changes</button>
                    @else
                        <button type="button" class="submit-btn" disabled style="background: #9ca3af; cursor: not-allowed; opacity: 0.6;"><i class="fas fa-lock"></i> Project Locked</button>
                    @endif
                </form>
            </div>

            <!-- Danger Zone -->
            @if(!$isLocked)
            <div class="section" style="border: 2px solid #fee2e2; background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);">
                <h2 class="section-title" style="color: #dc2626;">
                    <i class="fas fa-exclamation-triangle"></i> Danger Zone
                </h2>
                <div style="background: white; padding: 16px; border-radius: 10px; border: 2px dashed #fca5a5;">
                    <p style="color: #6b7280; margin-bottom: 12px; font-size: 13px;">
                        <i class="fas fa-info-circle"></i> <strong>Warning:</strong> Deleting this project is permanent and cannot be undone. All team members, applications, tasks, and data will be permanently deleted.
                    </p>
                    <button type="button" onclick="confirmDeleteProject()" style="padding: 10px 20px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 13px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 38, 38, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(220, 38, 38, 0.3)'">
                        <i class="fas fa-trash-alt"></i> Delete Project
                    </button>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease; overflow: hidden;">
        <!-- Warning Header -->
        <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); padding: 20px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; color: white;"></i>
            </div>
            <div>
                <h3 style="margin: 0; color: white; font-size: 20px; font-weight: 800;">⚠️ WARNING: DELETE PROJECT?</h3>
                <p style="margin: 4px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 13px;">This action cannot be undone</p>
            </div>
        </div>

        <!-- Content -->
        <div style="padding: 25px;">
            <p style="font-size: 15px; color: #1f2937; margin-bottom: 15px; font-weight: 600;">
                Are you sure you want to permanently delete "<span id="deleteProjectTitle" style="color: #dc2626;"></span>"?
            </p>

            <div style="background: #fef2f2; border-left: 4px solid #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p style="font-size: 13px; color: #991b1b; margin: 0 0 10px 0; font-weight: 700;">
                    <i class="fas fa-info-circle"></i> This action will:
                </p>
                <ul style="margin: 0; padding-left: 20px; color: #7f1d1d; font-size: 13px; line-height: 1.8;">
                    <li>Delete all team members</li>
                    <li>Delete all applications</li>
                    <li>Delete all tasks</li>
                    <li>Delete all project data</li>
                </ul>
            </div>

            <div style="background: #fffbeb; border: 2px solid #fbbf24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p style="font-size: 13px; color: #92400e; margin: 0 0 10px 0; font-weight: 700;">
                    <i class="fas fa-shield-alt"></i> Type <strong>"DELETE"</strong> to confirm:
                </p>
                <input type="text" id="deleteConfirmInput" placeholder="Type DELETE here" style="width: 100%; padding: 10px 14px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; font-weight: 600; text-transform: uppercase;" />
                <p id="deleteInputError" style="display: none; color: #dc2626; font-size: 12px; margin: 8px 0 0 0;">
                    <i class="fas fa-times-circle"></i> You must type "DELETE" exactly to confirm
                </p>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeDeleteModal()" style="padding: 12px 24px; background: white; color: #6b7280; border: 2px solid #e5e7eb; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 14px; transition: all 0.3s ease;">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" onclick="executeDelete()" style="padding: 12px 24px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 14px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
                    <i class="fas fa-trash-alt"></i> Delete Forever
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
    20%, 40%, 60%, 80% { transform: translateX(8px); }
}

#deleteConfirmModal {
    display: none !important;
}

#deleteConfirmModal.show {
    display: flex !important;
}

#deleteConfirmInput:focus {
    outline: none;
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
}

#deleteConfirmModal button:hover {
    transform: translateY(-2px);
}

#deleteConfirmModal button:active {
    transform: translateY(0);
}

#removeMemberModal {
    display: none !important;
}

#removeMemberModal.show {
    display: flex !important;
}

#removeMemberModal button:hover {
    transform: translateY(-2px);
}

#removeMemberModal button:active {
    transform: translateY(0);
}
</style>

<!-- Remove Member Confirmation Modal -->
<div id="removeMemberModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; max-width: 480px; width: 90%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease; overflow: hidden;">
        <!-- Warning Header -->
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 20px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-minus" style="font-size: 24px; color: white;"></i>
            </div>
            <div>
                <h3 style="margin: 0; color: white; font-size: 20px; font-weight: 800;">Remove Team Member?</h3>
                <p style="margin: 4px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 13px;">This action will remove the member</p>
            </div>
        </div>

        <!-- Content -->
        <div style="padding: 25px;">
            <p style="font-size: 15px; color: #1f2937; margin-bottom: 15px; font-weight: 600;">
                Are you sure you want to remove "<span id="removeMemberName" style="color: #f59e0b;"></span>" from the team?
            </p>

            <div style="background: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p style="font-size: 13px; color: #92400e; margin: 0 0 10px 0; font-weight: 700;">
                    <i class="fas fa-info-circle"></i> What happens:
                </p>
                <ul style="margin: 0; padding-left: 20px; color: #78350f; font-size: 13px; line-height: 1.8;">
                    <li>Member will be removed from the project</li>
                    <li>They can reapply if needed</li>
                    <li>Recruitment may reopen if team becomes unfull</li>
                </ul>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeRemoveMemberModal()" style="padding: 12px 24px; background: white; color: #6b7280; border: 2px solid #e5e7eb; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 14px; transition: all 0.3s ease;">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" onclick="executeRemoveMember()" style="padding: 12px 24px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 14px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-user-minus"></i> Remove Member
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div class="modal" id="createTaskModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 30px; max-width: 500px; width: 90%; word-wrap: break-word; overflow-wrap: break-word;">
        <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 20px;"><i class="fas fa-plus-circle" style="color: #10b981;"></i> Create New Task</h3>
        <form id="createTaskForm">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 700; margin-bottom: 5px;">Title *</label>
                <input type="text" name="title" required style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 700; margin-bottom: 5px;">Description</label>
                <textarea name="description" rows="3" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: 700; margin-bottom: 5px;">Priority *</label>
                    <select name="priority" required style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; margin-bottom: 5px;">Status *</label>
                    <select name="status" required style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="todo" selected>To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 700; margin-bottom: 5px;">Assign To</label>
                    <select name="assigned_to" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="">Unassigned</option>
                        <option value="{{ $ecoIdea->creator_id }}">{{ $ecoIdea->creator->name }} (Creator)</option>
                        @foreach($ecoIdea->team as $member)
                            <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; margin-bottom: 5px;">Due Date</label>
                    <input type="date" name="due_date" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeCreateTaskModal()" style="flex: 1; padding: 12px; background: #f3f4f6; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Cancel</button>
                <button type="submit" style="flex: 1; padding: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Create Task</button>
            </div>
        </form>
    </div>
</div>

<!-- View Task Modal -->
<div class="modal" id="viewTaskModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 30px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; word-wrap: break-word; overflow-wrap: break-word;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 24px; font-weight: 800; margin: 0;"><i class="fas fa-eye" style="color: #3b82f6;"></i> Task Details</h3>
            <button onclick="closeViewTaskModal()" style="background: none; border: none; font-size: 24px; color: #6b7280; cursor: pointer;">&times;</button>
        </div>
        <div id="viewTaskContent"></div>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button onclick="closeViewTaskModal(); openEditTaskModalFromView();" style="flex: 1; padding: 12px; background: #f59e0b; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;"><i class="fas fa-edit"></i> Edit Task</button>
            <button onclick="closeViewTaskModal()" style="flex: 1; padding: 12px; background: #f3f4f6; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Close</button>
        </div>
    </div>
</div>

<!-- Project Completion Confirmation Modal -->
<div class="modal" id="completionConfirmationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(10px); z-index: 10001; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 24px; padding: 0; max-width: 520px; width: 90%; box-shadow: 0 25px 80px rgba(0,0,0,0.4); animation: bounceIn 0.5s ease-out; overflow: hidden;">
        <!-- Celebration Header -->
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 30px; text-align: center; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 1;">
                <div style="width: 80px; height: 80px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); animation: pulse 2s ease-in-out infinite;">
                    <i class="fas fa-trophy" style="font-size: 36px; color: #10b981;"></i>
                </div>
                <h3 style="font-size: 26px; font-weight: 900; margin: 0 0 8px 0; color: white;">🎉 All Tasks Completed!</h3>
                <p style="font-size: 14px; color: rgba(255,255,255,0.9); margin: 0;">Congratulations on finishing all your project tasks!</p>
            </div>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px;">
            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 16px; padding: 20px; margin-bottom: 24px; border: 2px solid #fcd34d;">
                <div style="display: flex; align-items: start; gap: 14px;">
                    <div style="width: 44px; height: 44px; background: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-question-circle" style="color: white; font-size: 22px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 800; color: #92400e;">Mark Project as Completed?</h4>
                        <p style="margin: 0; font-size: 13px; color: #78350f; line-height: 1.6;">
                            By marking this project as completed, you acknowledge that all work is done. The project will be locked and submitted for admin verification to potentially earn your certificate.
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="background: #f9fafb; border-radius: 14px; padding: 16px; margin-bottom: 24px;">
                <p style="font-size: 13px; color: #6b7280; margin: 0; line-height: 1.6;">
                    <i class="fas fa-info-circle" style="color: #3b82f6;"></i> <strong>What happens next:</strong>
                </p>
                <ul style="margin: 12px 0 0 0; padding-left: 24px; font-size: 13px; color: #6b7280; line-height: 1.8;">
                    <li>Project status changes to <strong style="color: #10b981;">Completed</strong></li>
                    <li>Task board will be <strong>locked</strong> (no more edits)</li>
                    <li>Project settings will be <strong>locked</strong></li>
                    <li>Admin will review and may <strong style="color: #8b5cf6;">verify</strong> your project</li>
                    <li>If verified, you and your team earn <strong style="color: #f59e0b;">certificates!</strong></li>
                </ul>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button onclick="closeCompletionModal()" style="flex: 1; padding: 14px; background: #e5e7eb; color: #1f2937; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; font-size: 14px;" onmouseover="this.style.background='#d1d5db'" onmouseout="this.style.background='#e5e7eb'">
                    <i class="fas fa-times"></i> Not Yet
                </button>
                <button onclick="confirmProjectCompletion()" style="flex: 1; padding: 14px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3); font-size: 14px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(16, 185, 129, 0.3)'">
                    <i class="fas fa-check-circle"></i> Yes, Complete Project!
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}
</style>

<!-- Toast Notification Container -->
<div id="toastContainer" style="position: fixed; top: 100px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 10px;"></div>

<!-- Member Details Modal -->
<div class="modal" id="memberDetailsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 24px; padding: 0; max-width: 540px; width: 90%; word-wrap: break-word; overflow-wrap: break-word; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease-out; overflow: hidden;">
        <!-- Modal Header with Gradient -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1;">
                <h3 style="font-size: 24px; font-weight: 800; margin: 0; color: white; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user-circle" style="font-size: 28px;"></i> Member Details
                </h3>
                <button onclick="closeMemberDetailsModal()" style="background: rgba(255,255,255,0.2); border: none; font-size: 24px; color: white; cursor: pointer; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='rotate(90deg)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='rotate(0deg)'">&times;</button>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div style="padding: 30px;">
            <div id="memberDetailsContent">
                <!-- Content will be dynamically loaded -->
            </div>
            
            <div style="margin-top: 24px;">
                <button onclick="closeMemberDetailsModal()" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px; font-weight: 700; cursor: pointer; color: white; font-size: 15px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)'">
                    <i class="fas fa-check-circle"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<!-- Edit Task Modal -->
<div class="modal" id="editTaskModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 30px; max-width: 500px; width: 90%; word-wrap: break-word; overflow-wrap: break-word;">
        <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 20px;"><i class="fas fa-edit" style="color: #3b82f6;"></i> Edit Task</h3>
        <form id="editTaskForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="editTaskId" name="task_id">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 700; margin-bottom: 5px;">Title *</label>
                <input type="text" id="editTaskTitle" name="title" required style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 700; margin-bottom: 5px;">Description</label>
                <textarea id="editTaskDescription" name="description" rows="3" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: 700; margin-bottom: 5px;">Priority *</label>
                    <select id="editTaskPriority" name="priority" required style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; margin-bottom: 5px;">Status *</label>
                    <select id="editTaskStatus" name="status" required style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 700; margin-bottom: 5px;">Assign To</label>
                    <select id="editTaskAssignedTo" name="assigned_to" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="">Unassigned</option>
                        <option value="{{ $ecoIdea->creator_id }}">{{ $ecoIdea->creator->name }} (Creator)</option>
                        @foreach($ecoIdea->team as $member)
                            <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; margin-bottom: 5px;">Due Date</label>
                    <input type="date" id="editTaskDueDate" name="due_date" style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;">
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="deleteTask()" style="padding: 12px 20px; background: #ef4444; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;"><i class="fas fa-trash"></i> Delete</button>
                <button type="button" onclick="closeEditTaskModal()" style="flex: 1; padding: 12px; background: #f3f4f6; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Cancel</button>
                <button type="submit" style="flex: 1; padding: 12px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ecoIdeaId = {{ $ecoIdea->id }};
const csrfToken = '{{ csrf_token() }}';
let currentViewTaskId = null;
let allTasksData = [];

// Beautiful Toast Notification System
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const colors = {
        success: {bg: '#10b981', icon: 'check-circle'},
        error: {bg: '#ef4444', icon: 'exclamation-circle'},
        info: {bg: '#3b82f6', icon: 'info-circle'},
        warning: {bg: '#f59e0b', icon: 'exclamation-triangle'}
    };
    const config = colors[type];
    
    toast.style.cssText = `
        background: ${config.bg};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 300px;
        animation: slideIn 0.3s ease-out;
        font-weight: 600;
    `;
    
    toast.innerHTML = `<i class="fas fa-${config.icon}" style="font-size: 20px;"></i><span>${message}</span>`;
    document.getElementById('toastContainer').appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add animation keyframes
if (!document.getElementById('toastAnimations')) {
    const style = document.createElement('style');
    style.id = 'toastAnimations';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

// Navigation with Chat Support and Tab Memory
let chatRefreshInterval = null;

console.log('Navigation script loading...');

const navItems = document.querySelectorAll('.nav-item');
console.log('Found nav items:', navItems.length);

// Function to switch to a specific tab
function switchToTab(section) {
    // Switch active nav item
    document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
    const targetNavItem = document.querySelector(`.nav-item[data-section="${section}"]`);
    if (targetNavItem) {
        targetNavItem.classList.add('active');
    }
    
    // Switch active section
    document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
    const targetSection = document.getElementById(section + '-section');
    if (targetSection) {
        targetSection.classList.add('active');
        console.log('✅ Switched to section:', section);
    } else {
        console.error('❌ Section not found:', section + '-section');
    }
    
    // Clear existing chat refresh interval
    if (chatRefreshInterval) {
        clearInterval(chatRefreshInterval);
        chatRefreshInterval = null;
    }
    
    // If chat section is opened, load messages and start auto-refresh
    if (section === 'chat') {
        loadChatMessages();
        chatRefreshInterval = setInterval(loadChatMessages, 3000);
    }
    
    // Save to localStorage
    localStorage.setItem('ecoIdea_activeTab_{{ $ecoIdea->id }}', section);
}

// Attach click listeners to nav items
navItems.forEach(item => {
    console.log('Attaching listener to:', item.dataset.section);
    item.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Nav item clicked:', this.dataset.section);
        switchToTab(this.dataset.section);
    });
});

// Helper function for programmatic section switching
function switchSection(sectionName) {
    switchToTab(sectionName);
}

// Restore last active tab on page load
const savedTab = localStorage.getItem('ecoIdea_activeTab_{{ $ecoIdea->id }}');

// Check if all tasks are completed and auto-show completion prompt
@if($isCreator && $ecoIdea->project_status === 'in_progress')
    @php
        $totalTasks = $ecoIdea->tasks()->count();
        $completedTasks = $ecoIdea->tasks()->where('status', 'completed')->count();
        $allTasksCompleted = $totalTasks > 0 && $completedTasks === $totalTasks;
    @endphp
    @if($allTasksCompleted)
        const allTasksCompleted = true;
        // Check if user just completed the last task (coming from tasks section)
        if (savedTab === 'tasks' || !savedTab) {
            console.log('🌟 All tasks completed! Showing completion prompt...');
            switchToTab('completion');
        } else if (savedTab) {
            switchToTab(savedTab);
        } else {
            switchToTab('completion');
        }
    @else
        if (savedTab) {
            console.log('📌 Restoring saved tab:', savedTab);
            switchToTab(savedTab);
        } else {
            console.log('📌 No saved tab, using default (overview)');
            switchToTab('overview');
        }
    @endif
@else
    if (savedTab) {
        console.log('📌 Restoring saved tab:', savedTab);
        switchToTab(savedTab);
    } else {
        console.log('📌 No saved tab, using default (overview)');
        switchToTab('overview');
    }
@endif

console.log('Navigation initialized successfully!');

// Modal functions
function openCreateTaskModal() {
    document.getElementById('createTaskModal').style.display = 'flex';
}

function closeCreateTaskModal() {
    document.getElementById('createTaskModal').style.display = 'none';
    document.getElementById('createTaskForm').reset();
}

function openEditTaskModal(taskId) {
    event.stopPropagation();
    fetch(`/eco-ideas-dashboard/{{ $ecoIdea->id }}/tasks`)
        .then(res => res.json())
        .then(data => {
            const task = data.find(t => t.id == taskId);
            if (task) {
                document.getElementById('editTaskId').value = task.id;
                document.getElementById('editTaskTitle').value = task.title;
                document.getElementById('editTaskDescription').value = task.description || '';
                document.getElementById('editTaskPriority').value = task.priority;
                document.getElementById('editTaskStatus').value = task.status;
                document.getElementById('editTaskAssignedTo').value = task.assigned_to || '';
                
                // Format date properly for HTML date input (YYYY-MM-DD)
                if (task.due_date) {
                    const date = new Date(task.due_date);
                    const formattedDate = date.toISOString().split('T')[0];
                    document.getElementById('editTaskDueDate').value = formattedDate;
                } else {
                    document.getElementById('editTaskDueDate').value = '';
                }
                
                document.getElementById('editTaskModal').style.display = 'flex';
            }
        });
}

function closeEditTaskModal() {
    document.getElementById('editTaskModal').style.display = 'none';
}

// Create Task - NO PAGE RELOAD!
document.getElementById('createTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    button.disabled = true;
    
    fetch(`/eco-ideas-dashboard/${ecoIdeaId}/tasks`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                const errorMessage = errorData.message || errorData.error || 
                    (errorData.errors ? Object.values(errorData.errors).flat().join(', ') : 'Unknown error');
                throw new Error(errorMessage);
            });
        }
        return response.json();
    })
    .then(data => {
        showToast('✨ Task created successfully!', 'success');
        closeCreateTaskModal();
        this.reset();
        button.disabled = false;
        // Reload tasks without page refresh
        refreshTaskBoard();
    })
    .catch(err => {
        showToast('❌ Error: ' + err.message, 'error');
        button.disabled = false;
    });
});

// Update Task - NO PAGE RELOAD!
document.getElementById('editTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const taskId = document.getElementById('editTaskId').value;
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    button.disabled = true;
    
    fetch(`/eco-ideas-dashboard/tasks/${taskId}/update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-HTTP-Method-Override': 'PUT',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showToast('✅ Task updated successfully!', 'success');
        closeEditTaskModal();
        button.disabled = false;
        refreshTaskBoard();
    })
    .catch(err => {
        showToast('❌ Error updating task', 'error');
        button.disabled = false;
    });
});

// Delete Task - NO PAGE RELOAD!
function deleteTask() {
    const taskId = document.getElementById('editTaskId').value;
    showToast('🗑️ Deleting task...', 'info');
    
    fetch(`/eco-ideas-dashboard/tasks/${taskId}/delete`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        showToast('✅ Task deleted successfully!', 'success');
        closeEditTaskModal();
        refreshTaskBoard();
    })
    .catch(err => showToast('❌ Error deleting task', 'error'));
}

// View Task Modal
function openViewTaskModal(taskId) {
    currentViewTaskId = taskId;
    fetch(`/eco-ideas-dashboard/${ecoIdeaId}/tasks`)
        .then(res => res.json())
        .then(tasks => {
            const task = tasks.find(t => t.id == taskId);
            if (task) {
                const assignedUser = task.assigned_user ? task.assigned_user.name : 'Unassigned';
                const content = `
                    <div style="background: #f9fafb; padding: 20px; border-radius: 12px; margin-bottom: 15px;">
                        <h4 style="font-size: 20px; font-weight: 800; margin-bottom: 15px; word-wrap: break-word; word-break: break-word;">${task.title}</h4>
                        <p style="color: #6b7280; line-height: 1.6; word-wrap: break-word; word-break: break-word; overflow-wrap: break-word;">${task.description || 'No description'}</p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div style="background: #f0fdf4; padding: 15px; border-radius: 10px;">
                            <div style="font-size: 12px; color: #10b981; font-weight: 700; margin-bottom: 5px;">STATUS</div>
                            <div style="font-size: 16px; font-weight: 700; text-transform: uppercase;">${task.status.replace('_', ' ')}</div>
                        </div>
                        <div style="background: #fef3c7; padding: 15px; border-radius: 10px;">
                            <div style="font-size: 12px; color: #f59e0b; font-weight: 700; margin-bottom: 5px;">PRIORITY</div>
                            <div style="font-size: 16px; font-weight: 700; text-transform: uppercase;">${task.priority}</div>
                        </div>
                        <div style="background: #eff6ff; padding: 15px; border-radius: 10px;">
                            <div style="font-size: 12px; color: #3b82f6; font-weight: 700; margin-bottom: 5px;">ASSIGNED TO</div>
                            <div style="font-size: 16px; font-weight: 700;">${assignedUser}</div>
                        </div>
                        <div style="background: #fce7f3; padding: 15px; border-radius: 10px;">
                            <div style="font-size: 12px; color: #ec4899; font-weight: 700; margin-bottom: 5px;">DUE DATE</div>
                            <div style="font-size: 16px; font-weight: 700;">${task.due_date ? new Date(task.due_date).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'}) : 'No due date'}</div>
                        </div>
                    </div>
                `;
                document.getElementById('viewTaskContent').innerHTML = content;
                document.getElementById('viewTaskModal').style.display = 'flex';
            }
        });
}

function closeViewTaskModal() {
    document.getElementById('viewTaskModal').style.display = 'none';
}

function openEditTaskModalFromView() {
    if (currentViewTaskId) {
        openEditTaskModal(currentViewTaskId);
    }
}

// Refresh Task Board without page reload
function refreshTaskBoard() {
    fetch(`/eco-ideas-dashboard/${ecoIdeaId}/tasks`)
        .then(res => res.json())
        .then(tasks => {
            allTasksData = tasks;
            updateTaskBoardDOM(tasks);
            
            // Check if all tasks are completed and project is still in progress
            @if($isCreator && $ecoIdea->project_status === 'in_progress')
            const totalTasks = tasks.length;
            const completedTasks = tasks.filter(t => t.status === 'completed').length;
            
            if (totalTasks > 0 && completedTasks === totalTasks) {
                // All tasks completed! Show completion confirmation modal
                console.log('🎉 All tasks completed! Showing completion modal...');
                setTimeout(() => {
                    showCompletionModal();
                }, 500); // Small delay for better UX
            }
            @endif
        });
}

function updateTaskBoardDOM(tasks) {
    // Clear all columns
    document.querySelectorAll('.task-list').forEach(list => list.innerHTML = '');
    
    // Group tasks by status
    const tasksByStatus = {
        todo: tasks.filter(t => t.status === 'todo'),
        in_progress: tasks.filter(t => t.status === 'in_progress'),
        review: tasks.filter(t => t.status === 'review'),
        completed: tasks.filter(t => t.status === 'completed')
    };
    
    // Update each column
    Object.entries(tasksByStatus).forEach(([status, statusTasks]) => {
        const list = document.querySelector(`.task-list[data-status="${status}"]`);
        statusTasks.forEach(task => {
            const assignedName = task.assigned_user ? task.assigned_user.name : 'Unassigned';
            const dueDate = task.due_date ? new Date(task.due_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric'}) : '';
            
            const taskCard = document.createElement('div');
            taskCard.className = 'task-card';
            taskCard.setAttribute('data-task-id', task.id);
            taskCard.setAttribute('data-task-title', task.title.toLowerCase());
            taskCard.setAttribute('data-task-assignee', task.assigned_to || 'unassigned');
            taskCard.setAttribute('data-task-priority', task.priority);
            taskCard.setAttribute('draggable', 'true');
            taskCard.innerHTML = `
                <div style="font-size:14px; font-weight:700; margin-bottom:8px;">${task.title}</div>
                <div style="font-size:12px; color:#6b7280; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                    <span class="priority-${task.priority}">${task.priority.toUpperCase()}</span>
                    <span style="${task.assigned_to ? '' : 'color: #9ca3af;'}"><i class="fas fa-${task.assigned_to ? 'user' : 'user-slash'}"></i> ${assignedName}</span>
                    ${dueDate ? `<span><i class="fas fa-calendar"></i> ${dueDate}</span>` : ''}
                </div>
                <div style="display: flex; gap: 5px;">
                    <button onclick="event.stopPropagation(); openViewTaskModal(${task.id})" style="flex: 1; padding: 6px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-eye"></i> View</button>
                    <button onclick="event.stopPropagation(); openEditTaskModal(${task.id})" style="flex: 1; padding: 6px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
                </div>
            `;
            
            list.appendChild(taskCard);
        });
        
        // Update count
        const column = list.closest('.task-column');
        column.querySelector('.task-count').textContent = statusTasks.length;
    });
}

// Search and Filter
document.getElementById('taskSearchInput').addEventListener('input', applyFilters);
document.getElementById('taskFilterAssignee').addEventListener('change', applyFilters);
document.getElementById('taskFilterPriority').addEventListener('change', applyFilters);

function applyFilters() {
    const searchTerm = document.getElementById('taskSearchInput').value.toLowerCase();
    const filterAssignee = document.getElementById('taskFilterAssignee').value;
    const filterPriority = document.getElementById('taskFilterPriority').value;
    
    document.querySelectorAll('.task-card').forEach(card => {
        const title = card.getAttribute('data-task-title');
        const assignee = card.getAttribute('data-task-assignee');
        const priority = card.getAttribute('data-task-priority');
        
        const matchesSearch = !searchTerm || title.includes(searchTerm);
        const matchesAssignee = !filterAssignee || assignee === filterAssignee;
        const matchesPriority = !filterPriority || priority === filterPriority;
        
        card.style.display = (matchesSearch && matchesAssignee && matchesPriority) ? 'block' : 'none';
    });
}

function clearFilters() {
    document.getElementById('taskSearchInput').value = '';
    document.getElementById('taskFilterAssignee').value = '';
    document.getElementById('taskFilterPriority').value = '';
    applyFilters();
}

// Drag and Drop - BULLETPROOF VERSION
let draggedElement = null;

// Use event delegation on the task board container
document.addEventListener('DOMContentLoaded', function() {
    const taskBoard = document.getElementById('taskBoard');
    
    if (!taskBoard) {
        console.error('Task board not found!');
        return;
    }
    
    // Drag start - on the board container
    taskBoard.addEventListener('dragstart', function(e) {
        if (e.target.classList.contains('task-card')) {
            draggedElement = e.target;
            e.target.style.opacity = '0.5';
            console.log('Drag started:', e.target.dataset.taskId);
        }
    });
    
    // Drag end - on the board container
    taskBoard.addEventListener('dragend', function(e) {
        if (e.target.classList.contains('task-card')) {
            e.target.style.opacity = '1';
            console.log('Drag ended');
        }
    });
    
    // Drag over - MUST prevent default to allow drop
    taskBoard.addEventListener('dragover', function(e) {
        e.preventDefault(); // CRITICAL: Must always preventDefault to allow drop
        
        const list = e.target.classList.contains('task-list') ? e.target : e.target.closest('.task-list');
        
        if (list) {
            list.style.background = '#f0fdf4';
        }
    });
    
    // Drag leave
    taskBoard.addEventListener('dragleave', function(e) {
        if (e.target.classList.contains('task-list')) {
            e.target.style.background = '';
        }
    });
    
    // Drop - on task lists
    taskBoard.addEventListener('drop', function(e) {
        e.preventDefault();
        
        const list = e.target.classList.contains('task-list') ? e.target : e.target.closest('.task-list');
        
        if (list && draggedElement) {
            list.style.background = '';
            
            const newStatus = list.dataset.status;
            const taskId = draggedElement.dataset.taskId;
            const oldList = draggedElement.closest('.task-list');
            const oldStatus = oldList ? oldList.dataset.status : null;
            
            console.log('Dropped! Moving from', oldStatus, 'to', newStatus);
            
            if (newStatus === oldStatus) {
                console.log('Same column, ignoring');
                return;
            }
            
            showToast('🔄 Moving task...', 'info');
            
            // Update task status via AJAX
            fetch(`/eco-ideas-dashboard/tasks/${taskId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-HTTP-Method-Override': 'PUT',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => {
                console.log('Update response:', res.status);
                return res.json();
            })
            .then(data => {
                console.log('Task updated:', data);
                showToast('✅ Task moved to ' + newStatus.toUpperCase().replace('_', ' ') + '!', 'success');
                refreshTaskBoard();
            })
            .catch(err => {
                console.error('Error updating task:', err);
                showToast('❌ Error moving task: ' + err.message, 'error');
                refreshTaskBoard();
            });
        }
    });
    
    console.log('Drag and drop initialized successfully!');
});

// Close modals on outside click
document.getElementById('createTaskModal').addEventListener('click', function(e) {
    if (e.target === this) closeCreateTaskModal();
});

document.getElementById('editTaskModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditTaskModal();
});

document.getElementById('viewTaskModal').addEventListener('click', function(e) {
    if (e.target === this) closeViewTaskModal();
});

// Member Details Modal Functions
function viewMemberDetails(userId, name, email, role, joined, resumePath) {
    const roleColor = role === 'Owner' ? '#10b981' : '#667eea';
    const content = `
        <!-- Profile Card -->
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, ${roleColor} 0%, ${role === 'Owner' ? '#059669' : '#764ba2'} 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 42px; font-weight: 900; color: white; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); position: relative;">
                ${name.charAt(0).toUpperCase()}
                <div style="position: absolute; bottom: 3px; right: 3px; width: 24px; height: 24px; background: ${role === 'Owner' ? '#10b981' : '#667eea'}; border: 3px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas ${role === 'Owner' ? 'fa-crown' : 'fa-check'}" style="font-size: 10px; color: white;"></i>
                </div>
            </div>
            <h4 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800; color: #1f2937;">${name}</h4>
            <span style="display: inline-block; padding: 6px 16px; background: linear-gradient(135deg, ${roleColor}15 0%, ${roleColor}25 100%); color: ${roleColor}; border-radius: 20px; font-size: 13px; font-weight: 700; border: 2px solid ${roleColor}30;">
                <i class="fas ${role === 'Owner' ? 'fa-crown' : 'fa-user-check'}"></i> ${role}
            </span>
        </div>
        
        <!-- Info Cards -->
        <div style="display: grid; gap: 12px; margin-bottom: 20px;">
            <!-- Email Card -->
            <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 14px; padding: 16px; border: 1px solid #bae6fd; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);">
                        <i class="fas fa-envelope" style="color: white; font-size: 18px;"></i>
                    </div>
                    <div style="flex: 1; overflow: hidden;">
                        <p style="margin: 0 0 4px 0; font-size: 11px; font-weight: 700; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px;">Email Address</p>
                        <p style="margin: 0; color: #0c4a6e; font-weight: 600; font-size: 14px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${email}</p>
                    </div>
                </div>
            </div>
            
            ${role !== 'Owner' ? `
            <!-- Joined Date Card -->
            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 14px; padding: 16px; border: 1px solid #fcd34d; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                        <i class="fas fa-calendar-check" style="color: white; font-size: 18px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="margin: 0 0 4px 0; font-size: 11px; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px;">Member Since</p>
                        <p style="margin: 0; color: #78350f; font-weight: 600; font-size: 14px;">${joined}</p>
                    </div>
                </div>
            </div>
            ` : ''}
        </div>
        
        <!-- Resume Section -->
        ${resumePath && resumePath.trim() !== '' ? `
        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 14px; padding: 20px; border: 2px solid #86efac; text-align: center;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);">
                <i class="fas fa-file-pdf" style="color: white; font-size: 24px;"></i>
            </div>
            <p style="margin: 0 0 14px 0; font-size: 14px; font-weight: 700; color: #065f46;">Resume Document Available</p>
            <a href="/storage/${resumePath}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none; border-radius: 10px; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)'">
                <i class="fas fa-download"></i> Download Resume
            </a>
        </div>
        ` : role !== 'Owner' ? `
        <div style="background: #f9fafb; border-radius: 14px; padding: 24px; text-align: center; border: 2px dashed #d1d5db;">
            <div style="width: 56px; height: 56px; background: #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <i class="fas fa-file-slash" style="font-size: 24px; color: #9ca3af;"></i>
            </div>
            <p style="margin: 0; font-size: 14px; color: #6b7280; font-weight: 600;">No resume uploaded</p>
        </div>
        ` : ''}
    `;
    
    document.getElementById('memberDetailsContent').innerHTML = content;
    document.getElementById('memberDetailsModal').style.display = 'flex';
}

function closeMemberDetailsModal() {
    document.getElementById('memberDetailsModal').style.display = 'none';
}

document.getElementById('memberDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) closeMemberDetailsModal();
});

// ========== PROJECT COMPLETION CONFIRMATION ==========
function showCompletionModal() {
    document.getElementById('completionConfirmationModal').style.display = 'flex';
}

function closeCompletionModal() {
    document.getElementById('completionConfirmationModal').style.display = 'none';
}

function confirmProjectCompletion() {
    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    fetch(`/eco-ideas-dashboard/${ecoIdeaId}/mark-completed`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('🎉 Project marked as completed! Redirecting...', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast('❌ Error: ' + (data.message || 'Failed to complete project'), 'error');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check-circle"></i> Yes, Complete Project!';
        }
    })
    .catch(err => {
        showToast('❌ Error: ' + err.message, 'error');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-check-circle"></i> Yes, Complete Project!';
    });
}

// Close modal on outside click
document.getElementById('completionConfirmationModal').addEventListener('click', function(e) {
    if (e.target === this) closeCompletionModal();
});

// ========== CHAT ROOM FUNCTIONALITY ==========

// Load chat messages
function loadChatMessages() {
    fetch(`/eco-ideas-dashboard/${ecoIdeaId}/messages`)
        .then(res => res.json())
        .then(messages => {
            const container = document.getElementById('chatMessages');
            
            if (messages.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; color: #9ca3af; padding: 40px;">
                        <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                        <p style="font-size: 14px;">No messages yet. Start the conversation!</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = '';
            const currentUserId = {{ auth()->id() }};
            
            messages.forEach(msg => {
                const isOwn = msg.user_id === currentUserId;
                const messageEl = document.createElement('div');
                messageEl.style.cssText = `
                    display: flex;
                    gap: 12px;
                    margin-bottom: 20px;
                    align-items: flex-start;
                    ${isOwn ? 'flex-direction: row-reverse;' : ''}
                `;
                
                const avatarColor = isOwn ? '#10b981' : '#3b82f6';
                const bgColor = isOwn ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : '#f3f4f6';
                const textColor = isOwn ? 'white' : '#1f2937';
                const alignment = isOwn ? 'flex-end' : 'flex-start';
                
                messageEl.innerHTML = `
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: ${avatarColor}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px; flex-shrink: 0;">
                        ${msg.user.name.charAt(0).toUpperCase()}
                    </div>
                    <div style="flex: 1; max-width: 70%; display: flex; flex-direction: column; align-items: ${alignment};">
                        <div style="font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px; ${isOwn ? 'text-align: right;' : ''}">
                            ${msg.user.name}
                        </div>
                        <div style="background: ${bgColor}; color: ${textColor}; padding: 12px 16px; border-radius: 16px; ${isOwn ? 'border-bottom-right-radius: 4px;' : 'border-bottom-left-radius: 4px;'} word-wrap: break-word; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            ${msg.message}
                        </div>
                        <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">
                            ${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
                        </div>
                    </div>
                `;
                
                container.appendChild(messageEl);
            });
            
            // Auto scroll to bottom
            container.scrollTop = container.scrollHeight;
        })
        .catch(err => {
            console.error('Error loading messages:', err);
        });
}

// Send message
document.getElementById('teamChatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const input = document.getElementById('teamChatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    fetch(`/eco-ideas-dashboard/${ecoIdeaId}/messages`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
    .then(res => res.json())
    .then(data => {
        input.value = '';
        loadChatMessages(); // Reload messages
    })
    .catch(err => {
        console.error('Error sending message:', err);
        showToast('❌ Failed to send message', 'error');
    });
});

// ========== IMAGE UPLOAD PREVIEW ==========
function previewNewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('⚠️ Image size must be less than 5MB!');
            input.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('⚠️ Please upload a valid image (JPG, PNG, or WEBP)!');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('newImagePreviewImg').src = e.target.result;
            document.getElementById('newImagePreview').style.display = 'block';
            
            // Fade out current image slightly
            const currentImg = document.getElementById('currentImage');
            if (currentImg) {
                currentImg.style.opacity = '0.4';
                currentImg.style.filter = 'grayscale(50%)';
            }
        }
        
        reader.readAsDataURL(file);
    }
}

function removeNewImage() {
    document.getElementById('imageInput').value = '';
    document.getElementById('newImagePreview').style.display = 'none';
    
    // Restore current image opacity
    const currentImg = document.getElementById('currentImage');
    if (currentImg) {
        currentImg.style.opacity = '1';
        currentImg.style.filter = 'none';
    }
}

// Delete Project Confirmation - Custom Modal
function confirmDeleteProject() {
    const projectTitle = '{{ $ecoIdea->title }}';
    
    // Set project title in modal
    document.getElementById('deleteProjectTitle').textContent = projectTitle;
    
    // Clear input and error
    document.getElementById('deleteConfirmInput').value = '';
    document.getElementById('deleteInputError').style.display = 'none';
    
    // Show modal
    const modal = document.getElementById('deleteConfirmModal');
    modal.classList.add('show');
    
    // Focus input
    setTimeout(() => {
        document.getElementById('deleteConfirmInput').focus();
    }, 300);
    
    // Allow Enter key to submit
    document.getElementById('deleteConfirmInput').onkeypress = function(e) {
        if (e.key === 'Enter') {
            executeDelete();
        }
    };
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.classList.remove('show');
    document.getElementById('deleteConfirmInput').value = '';
    document.getElementById('deleteInputError').style.display = 'none';
}

function executeDelete() {
    const input = document.getElementById('deleteConfirmInput');
    const errorMsg = document.getElementById('deleteInputError');
    
    if (input.value.trim() === 'DELETE') {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('front.eco-ideas.dashboard.delete', $ecoIdea) }}';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    } else {
        // Show error
        errorMsg.style.display = 'block';
        input.style.borderColor = '#dc2626';
        input.focus();
        
        // Shake animation
        input.style.animation = 'shake 0.5s';
        setTimeout(() => {
            input.style.animation = '';
        }, 500);
    }
}

// Close modal on background click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('deleteConfirmModal');
    if (e.target === modal) {
        closeDeleteModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeRemoveMemberModal();
    }
});

// Remove Member Modal Functions
let currentRemoveMemberId = null;

function confirmRemoveMember(memberId, memberName) {
    // Store member ID
    currentRemoveMemberId = memberId;
    
    // Set member name in modal
    document.getElementById('removeMemberName').textContent = memberName;
    
    // Show modal
    const modal = document.getElementById('removeMemberModal');
    modal.classList.add('show');
}

function closeRemoveMemberModal() {
    const modal = document.getElementById('removeMemberModal');
    modal.classList.remove('show');
    currentRemoveMemberId = null;
}

function executeRemoveMember() {
    if (currentRemoveMemberId) {
        // Submit the form
        const form = document.getElementById('remove-member-form-' + currentRemoveMemberId);
        if (form) {
            form.submit();
        }
    }
}

// Close remove member modal on background click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('removeMemberModal');
    if (e.target === modal) {
        closeRemoveMemberModal();
    }
});
</script>
@endpush
