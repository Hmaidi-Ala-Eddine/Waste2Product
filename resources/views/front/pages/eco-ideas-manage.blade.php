@extends('layouts.front')

@section('title', 'Manage: ' . $ecoIdea->title)

@push('styles')
<style>
    .manage-wrapper { display: flex; min-height: 100vh; background: #f9fafb; padding-top: 80px; }
    .sidebar { width: 280px; background: white; border-right: 1px solid #e5e7eb; position: fixed; top: 80px; bottom: 0; overflow-y: auto; padding: 30px 0; }
    .sidebar-header { padding: 0 25px 25px; border-bottom: 1px solid #e5e7eb; }
    .project-title { font-size: 18px; font-weight: 700; color: #1a202c; margin-bottom: 10px; }
    .project-status { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
    .status-idea { background: #dbeafe; color: #1e40af; }
    .status-recruiting { background: #d1fae5; color: #065f46; }
    .status-in_progress { background: #fed7aa; color: #9a3412; }
    .status-completed { background: #bbf7d0; color: #166534; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 25px; color: #6b7280; text-decoration: none; transition: all 0.2s ease; cursor: pointer; }
    .nav-item:hover { background: #f3f4f6; color: #10b981; }
    .nav-item.active { background: #f0fdf4; color: #10b981; border-right: 3px solid #10b981; font-weight: 700; }
    .nav-badge { margin-left: auto; background: #ef4444; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: 700; }
    .main-content { margin-left: 280px; flex: 1; padding: 30px; }
    .content-section { display: none; }
    .content-section.active { display: block; }
    .section { background: white; border-radius: 16px; padding: 25px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
    .section-title { font-size: 20px; font-weight: 700; color: #1a202c; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
    .stat-card { text-align: center; padding: 20px; border-radius: 12px; }
    .stat-icon { font-size: 32px; margin-bottom: 10px; }
    .stat-value { font-size: 24px; font-weight: 800; color: #1a202c; }
    .stat-label { font-size: 14px; color: #6b7280; }
    .applications-table { width: 100%; border-collapse: collapse; }
    .applications-table th { text-align: left; padding: 12px; background: #f9fafb; font-weight: 700; color: #6b7280; font-size: 13px; text-transform: uppercase; }
    .applications-table td { padding: 15px 12px; border-top: 1px solid #e5e7eb; }
    .applicant-info { display: flex; align-items: center; gap: 12px; }
    .applicant-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; }
    .btn-sm { padding: 6px 12px; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
    .btn-accept { background: #10b981; color: white; }
    .btn-accept:hover { background: #059669; }
    .btn-reject { background: #ef4444; color: white; }
    .btn-reject:hover { background: #dc2626; }
    .team-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; }
    .team-member-card { background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 12px; padding: 20px; }
    .member-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .btn-remove { width: 100%; background: #ef4444; color: white; padding: 8px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
    .task-board { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .task-column { background: #f9fafb; border-radius: 12px; padding: 15px; min-height: 400px; }
    .column-header { display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; }
    .task-list { min-height: 300px; padding: 10px; border-radius: 8px; transition: background 0.2s ease; }
    .task-card { background: white; border-radius: 8px; padding: 15px; margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); cursor: grab; transition: all 0.2s ease; word-wrap: break-word; overflow-wrap: break-word; }
    .task-card:active { cursor: grabbing; }
    .priority-high { background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 700; }
    .priority-medium { background: #fed7aa; color: #9a3412; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 700; }
    .priority-low { background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 700; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-weight: 600; color: #1a202c; margin-bottom: 8px; }
    .form-input, .form-select, .form-textarea { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; }
    .form-textarea { min-height: 100px; }
    .submit-btn { padding: 14px 28px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; }
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }
    @media (max-width: 768px) { 
        .sidebar { position: static; width: 100%; } 
        .main-content { margin-left: 0; } 
        .task-board { grid-template-columns: 1fr; } 
        .stats-grid { grid-template-columns: 1fr 1fr !important; }
    }
    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
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
            @if($isCreator)
                <a href="#" class="nav-item" data-section="settings"><i class="fas fa-cog"></i><span>Settings</span></a>
            @endif
        </div>
        <div style="padding: 0 25px;"><a href="{{ route('front.eco-ideas.dashboard') }}" class="btn-sm" style="display: block; text-align: center; background: #e5e7eb; color: #1a202c; text-decoration: none;"><i class="fas fa-arrow-left"></i> Back</a></div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <!-- Overview -->
        <div class="content-section active" id="overview-section">
            <div class="section">
                <div class="stats-grid">
                    <div class="stat-card" style="background: #f0fdf4;"><i class="fas fa-user-clock stat-icon" style="color: #10b981;"></i><div class="stat-value">{{ $ecoIdea->applications->where('status', 'pending')->count() }}</div><div class="stat-label">Pending Applications</div></div>
                    <div class="stat-card" style="background: #eff6ff;"><i class="fas fa-users stat-icon" style="color: #3b82f6;"></i><div class="stat-value">{{ $ecoIdea->team->count() + 1 }}</div><div class="stat-label">Team Members</div></div>
                    <div class="stat-card" style="background: #fef3c7;"><i class="fas fa-tasks stat-icon" style="color: #f59e0b;"></i><div class="stat-value">{{ $ecoIdea->tasks->count() }}</div><div class="stat-label">Total Tasks</div></div>
                    <div class="stat-card" style="background: #fce7f3;"><i class="fas fa-heart stat-icon" style="color: #ec4899;"></i><div class="stat-value">{{ $ecoIdea->upvotes ?? 0 }}</div><div class="stat-label">Upvotes</div></div>
                </div>
                
                @php
                    $todoCount = $ecoIdea->tasks->where('status', 'todo')->count();
                    $inProgressCount = $ecoIdea->tasks->where('status', 'in_progress')->count();
                    $reviewCount = $ecoIdea->tasks->where('status', 'review')->count();
                    $completedCount = $ecoIdea->tasks->where('status', 'completed')->count();
                    $totalTasks = $ecoIdea->tasks->count();
                    $completionPercent = $totalTasks > 0 ? round(($completedCount / $totalTasks) * 100) : 0;
                @endphp
                
                <!-- Task Progress Section -->
                <div style="margin-top: 30px;">
                    <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 20px; color: #1f2937;"><i class="fas fa-chart-pie" style="color: #10b981;"></i> Task Progress Overview</h3>
                    
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
                    <div class="team-member-card" style="border: 2px solid #10b981; position: relative;">
                        <div style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 800; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);"><i class="fas fa-crown"></i> OWNER</div>
                        <div class="member-header">
                            <div class="applicant-avatar" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">{{ strtoupper(substr($ecoIdea->creator->name, 0, 1)) }}</div>
                            <div>
                                <h4 style="margin:0;">{{ $ecoIdea->creator->name }}</h4>
                                <span style="font-size:13px; color:#10b981; font-weight:600;">Project Creator</span>
                            </div>
                        </div>
                        <p style="font-size:13px; color:#6b7280; margin-bottom:12px;"><i class="fas fa-envelope"></i> {{ $ecoIdea->creator->email }}</p>
                        @if($isCreator)
                            <button onclick="viewMemberDetails('{{ $ecoIdea->creator->id }}', '{{ $ecoIdea->creator->name }}', '{{ $ecoIdea->creator->email }}', 'Owner', 'Project Creator', null)" style="width: 100%; background: #10b981; color: white; padding: 8px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;"><i class="fas fa-eye"></i> View Details</button>
                        @endif
                    </div>
                    
                    <!-- Team Members -->
                    @foreach($ecoIdea->team as $member)
                        <div class="team-member-card">
                            <div class="member-header">
                                <div class="applicant-avatar">{{ strtoupper(substr($member->user->name, 0, 1)) }}</div>
                                <div>
                                    <h4 style="margin:0;">{{ $member->user->name }}</h4>
                                    <span style="font-size:13px; color:#10b981; font-weight:600;">{{ ucfirst($member->role ?? 'Member') }}</span>
                                </div>
                            </div>
                            <p style="font-size:13px; color:#6b7280; margin-bottom:12px;">Joined {{ $member->joined_at ? $member->joined_at->diffForHumans() : 'recently' }}</p>
                            @if($isCreator)
                                <div style="display: flex; gap: 8px;">
                                    <button onclick="viewMemberDetails('{{ $member->user->id }}', '{{ $member->user->name }}', '{{ $member->user->email }}', '{{ ucfirst($member->role ?? 'Member') }}', '{{ $member->joined_at ? $member->joined_at->diffForHumans() : 'recently' }}', '{{ $member->application->resume_path ?? '' }}')" style="flex: 1; background: #3b82f6; color: white; padding: 8px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;"><i class="fas fa-eye"></i> View</button>
                                    <form action="{{ route('front.eco-ideas.dashboard.team.remove', $member) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Remove this member?');">@csrf @method('DELETE')<button type="submit" class="btn-remove" style="margin: 0;"><i class="fas fa-user-minus"></i> Remove</button></form>
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
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h2 class="section-title" style="margin-bottom: 0;"><i class="fas fa-tasks"></i>Task Board</h2>
                        <button onclick="openCreateTaskModal()" style="padding: 12px 24px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>
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
                                <div class="task-card" data-task-id="{{ $task->id }}" data-task-title="{{ strtolower($task->title) }}" data-task-assignee="{{ $task->assigned_to ?? 'unassigned' }}" data-task-priority="{{ $task->priority }}" draggable="true">
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
                                        <button onclick="event.stopPropagation(); openEditTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
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
                                <div class="task-card" data-task-id="{{ $task->id }}" data-task-title="{{ strtolower($task->title) }}" data-task-assignee="{{ $task->assigned_to ?? 'unassigned' }}" data-task-priority="{{ $task->priority }}" draggable="true">
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
                                        <button onclick="event.stopPropagation(); openEditTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
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
                                <div class="task-card" data-task-id="{{ $task->id }}" data-task-title="{{ strtolower($task->title) }}" data-task-assignee="{{ $task->assigned_to ?? 'unassigned' }}" data-task-priority="{{ $task->priority }}" draggable="true">
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
                                        <button onclick="event.stopPropagation(); openEditTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
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
                                <div class="task-card" data-task-id="{{ $task->id }}" data-task-title="{{ strtolower($task->title) }}" data-task-assignee="{{ $task->assigned_to ?? 'unassigned' }}" data-task-priority="{{ $task->priority }}" draggable="true">
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
                                        <button onclick="event.stopPropagation(); openEditTaskModal({{ $task->id }})" style="flex: 1; padding: 6px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
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
                    <div id="chatMessages" style="height: 500px; overflow-y: auto; padding: 20px; background: linear-gradient(to bottom, #f9fafb 0%, #ffffff 100%);">
                        <div style="text-align: center; color: #9ca3af; padding: 40px;">
                            <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                            <p style="font-size: 14px;">Loading messages...</p>
                        </div>
                    </div>
                    
                    <!-- Chat Input -->
                    <div style="border-top: 2px solid #e5e7eb; padding: 20px; background: white;">
                        <form id="chatForm" style="display: flex; gap: 12px; align-items: center;">
                            @csrf
                            <input 
                                type="text" 
                                id="chatInput" 
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

        <!-- Settings -->
        @if($isCreator)
        <div class="content-section" id="settings-section">
            <div class="section">
                <h2 class="section-title"><i class="fas fa-cog"></i>Edit Project</h2>
                <form action="{{ route('front.eco-ideas.dashboard.update', $ecoIdea) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="form-group"><label class="form-label">Title</label><input type="text" name="title" class="form-input" value="{{ $ecoIdea->title }}" required></div>
                    <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea" required>{{ $ecoIdea->description }}</textarea></div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div class="form-group"><label class="form-label">Waste Type</label><select name="waste_type" class="form-select" required><option value="organic" {{ $ecoIdea->waste_type == 'organic' ? 'selected' : '' }}>Organic</option><option value="plastic" {{ $ecoIdea->waste_type == 'plastic' ? 'selected' : '' }}>Plastic</option><option value="metal" {{ $ecoIdea->waste_type == 'metal' ? 'selected' : '' }}>Metal</option><option value="e-waste" {{ $ecoIdea->waste_type == 'e-waste' ? 'selected' : '' }}>E-Waste</option><option value="paper" {{ $ecoIdea->waste_type == 'paper' ? 'selected' : '' }}>Paper</option><option value="glass" {{ $ecoIdea->waste_type == 'glass' ? 'selected' : '' }}>Glass</option><option value="textile" {{ $ecoIdea->waste_type == 'textile' ? 'selected' : '' }}>Textile</option><option value="mixed" {{ $ecoIdea->waste_type == 'mixed' ? 'selected' : '' }}>Mixed</option></select></div>
                        <div class="form-group"><label class="form-label">Difficulty</label><select name="difficulty_level" class="form-select" required><option value="easy" {{ $ecoIdea->difficulty_level == 'easy' ? 'selected' : '' }}>Easy</option><option value="medium" {{ $ecoIdea->difficulty_level == 'medium' ? 'selected' : '' }}>Medium</option><option value="hard" {{ $ecoIdea->difficulty_level == 'hard' ? 'selected' : '' }}>Hard</option></select></div>
                    </div>
                    <div class="form-group"><label class="form-label">Project Status</label><select name="project_status" class="form-select" required><option value="idea" {{ $ecoIdea->project_status == 'idea' ? 'selected' : '' }}>Idea</option><option value="recruiting" {{ $ecoIdea->project_status == 'recruiting' ? 'selected' : '' }}>Recruiting</option><option value="in_progress" {{ $ecoIdea->project_status == 'in_progress' ? 'selected' : '' }}>In Progress</option><option value="completed" {{ $ecoIdea->project_status == 'completed' ? 'selected' : '' }}>Completed</option></select></div>
                    <div class="form-group"><label style="display:flex; align-items:center; gap:10px;"><input type="checkbox" name="is_recruiting" value="1" {{ $ecoIdea->is_recruiting ? 'checked' : '' }}><span>Open for recruitment</span></label></div>
                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Save Changes</button>
                </form>
            </div>
        </div>
        @endif
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

<!-- Toast Notification Container -->
<div id="toastContainer" style="position: fixed; top: 100px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 10px;"></div>

<!-- Member Details Modal -->
<div class="modal" id="memberDetailsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 30px; max-width: 500px; width: 90%; word-wrap: break-word; overflow-wrap: break-word;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 22px; font-weight: 800; margin: 0;"><i class="fas fa-user-circle" style="color: #3b82f6;"></i> Member Details</h3>
            <button onclick="closeMemberDetailsModal()" style="background: none; border: none; font-size: 24px; color: #6b7280; cursor: pointer;">&times;</button>
        </div>
        
        <div id="memberDetailsContent" style="margin-top: 20px;">
            <!-- Content will be dynamically loaded -->
        </div>
        
        <div style="margin-top: 20px;">
            <button onclick="closeMemberDetailsModal()" style="width: 100%; padding: 12px; background: #f3f4f6; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Close</button>
        </div>
    </div>
</div>

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

// Navigation with Chat Support
let chatRefreshInterval = null;

console.log('Navigation script loading...');

const navItems = document.querySelectorAll('.nav-item');
console.log('Found nav items:', navItems.length);

navItems.forEach(item => {
    console.log('Attaching listener to:', item.dataset.section);
    item.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Nav item clicked:', this.dataset.section);
        
        // Switch active nav item
        document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        // Switch active section
        const section = this.dataset.section;
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
    });
});

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
    const content = `
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 12px; padding: 20px; margin-bottom: 20px; color: white; text-align: center;">
            <div style="width: 80px; height: 80px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 36px; font-weight: 800; color: #3b82f6; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                ${name.charAt(0).toUpperCase()}
            </div>
            <h4 style="margin: 0; font-size: 22px; font-weight: 800;">${name}</h4>
            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">${role}</p>
        </div>
        
        <div style="background: #f9fafb; border-radius: 12px; padding: 20px; margin-bottom: 15px;">
            <div style="margin-bottom: 15px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <i class="fas fa-envelope" style="color: #3b82f6; width: 20px;"></i>
                    <span style="font-weight: 700; color: #1f2937;">Email</span>
                </div>
                <p style="margin: 0; color: #6b7280; padding-left: 30px;">${email}</p>
            </div>
            
            <div style="margin-bottom: 15px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <i class="fas fa-user-tag" style="color: #10b981; width: 20px;"></i>
                    <span style="font-weight: 700; color: #1f2937;">Role</span>
                </div>
                <p style="margin: 0; color: #6b7280; padding-left: 30px;">${role}</p>
            </div>
            
            ${role !== 'Owner' ? `
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <i class="fas fa-calendar-alt" style="color: #f59e0b; width: 20px;"></i>
                    <span style="font-weight: 700; color: #1f2937;">Joined</span>
                </div>
                <p style="margin: 0; color: #6b7280; padding-left: 30px;">${joined}</p>
            </div>
            ` : ''}
        </div>
        
        ${resumePath && resumePath.trim() !== '' ? `
        <div style="background: #fef3c7; border-radius: 12px; padding: 15px; margin-bottom: 15px; border-left: 4px solid #f59e0b;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <i class="fas fa-file-pdf" style="color: #f59e0b; font-size: 20px;"></i>
                <span style="font-weight: 700; color: #92400e;">Resume Available</span>
            </div>
            <a href="/storage/${resumePath}" target="_blank" style="display: inline-block; padding: 10px 20px; background: #f59e0b; color: white; text-decoration: none; border-radius: 8px; font-weight: 700; transition: all 0.2s ease;">
                <i class="fas fa-download"></i> View Resume (PDF)
            </a>
        </div>
        ` : role !== 'Owner' ? `
        <div style="background: #f3f4f6; border-radius: 12px; padding: 15px; text-align: center; color: #6b7280;">
            <i class="fas fa-file-slash" style="font-size: 24px; margin-bottom: 8px;"></i>
            <p style="margin: 0; font-size: 13px;">No resume uploaded</p>
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
document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const input = document.getElementById('chatInput');
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
</script>
@endpush
