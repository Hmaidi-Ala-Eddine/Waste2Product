<?php

namespace App\Http\Controllers;

use App\Models\EcoIdea;
use App\Models\EcoIdeaApplication;
use App\Models\EcoIdeaTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EcoIdeaController extends Controller
{
    // ===== API (stateless JSON) =====
    public function index()
    {
        return response()->json(EcoIdea::with(['creator', 'applications', 'team', 'tasks', 'certificates', 'interactions'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'creator_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'image_path' => 'nullable|string',
            'ai_generated_suggestion' => 'nullable|string',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'upvotes' => 'nullable|integer',
            'status' => 'nullable|in:pending,approved,rejected',
            'team_requirements' => 'nullable|array',
            'team_size_needed' => 'nullable|integer',
            'application_description' => 'nullable|string',
        ]);

        $ecoIdea = EcoIdea::create($data);
        return response()->json($ecoIdea, 201);
    }

    public function show(EcoIdea $ecoIdea)
    {
        return response()->json($ecoIdea->load(['creator', 'applications.user', 'team.user', 'tasks.assignedUser', 'certificates.user', 'interactions.user']));
    }

    public function update(Request $request, EcoIdea $ecoIdea)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'waste_type' => 'sometimes|required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'image_path' => 'sometimes|nullable|string',
            'ai_generated_suggestion' => 'sometimes|nullable|string',
            'difficulty_level' => 'sometimes|required|in:easy,medium,hard',
            'upvotes' => 'sometimes|integer',
            'status' => 'sometimes|in:pending,approved,rejected',
            'team_requirements' => 'sometimes|nullable|array',
            'team_size_needed' => 'sometimes|nullable|integer',
            'application_description' => 'sometimes|nullable|string',
            'project_status' => 'sometimes|in:idea,recruiting,in_progress,completed,verified,donated',
            'final_description' => 'sometimes|nullable|string',
            'impact_metrics' => 'sometimes|nullable|array',
            'donated_to_ngo' => 'sometimes|boolean',
            'ngo_name' => 'sometimes|nullable|string',
        ]);

        $ecoIdea->update($data);
        return response()->json($ecoIdea);
    }

    public function destroy(EcoIdea $ecoIdea)
    {
        $ecoIdea->delete();
        return response()->json(['message' => 'Deleted']);
    }

    // ===== Admin Blade (pages) =====
    public function adminIndex(Request $request)
    {
        $query = EcoIdea::with('creator');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('waste_type') && $request->waste_type !== 'all') {
            $query->where('waste_type', $request->waste_type);
        }
        if ($request->filled('difficulty_level') && $request->difficulty_level !== 'all') {
            $query->where('difficulty_level', $request->difficulty_level);
        }
        if ($request->filled('project_status') && $request->project_status !== 'all') {
            $query->where('project_status', $request->project_status);
        }
        if ($request->filled('creator_id') && $request->creator_id !== 'all') {
            $query->where('creator_id', $request->creator_id);
        }

        $ideas = $query->orderByDesc('created_at')->paginate(10);
        $ideas->appends($request->query());
        return view('back.pages.eco-ideas', compact('ideas'));
    }

    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'creator_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'image' => 'nullable|image|max:2048',
            'ai_generated_suggestion' => 'nullable|string',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'team_size_needed' => 'nullable|integer|min:1|max:20',
            'team_requirements' => 'nullable|string',
            'application_description' => 'nullable|string',
        ]);

        $idea = new EcoIdea();
        $idea->creator_id = $data['creator_id'];
        $idea->title = $data['title'];
        $idea->description = $data['description'];
        $idea->waste_type = $data['waste_type'];
        $idea->ai_generated_suggestion = $data['ai_generated_suggestion'] ?? null;
        $idea->difficulty_level = $data['difficulty_level'];
        $idea->team_size_needed = $data['team_size_needed'] ?? null;
        $idea->team_requirements = $data['team_requirements'] ? $data['team_requirements'] : null;
        $idea->application_description = $data['application_description'] ?? null;
        $idea->project_status = 'idea';
        $idea->status = 'approved';
        $idea->team_size_current = 1;
        $idea->is_recruiting = $data['team_size_needed'] ? true : false;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('eco_ideas', 'public');
            $idea->image_path = $imagePath;
        }

        $idea->save();

        EcoIdeaTeam::create([
            'eco_idea_id' => $idea->id,
            'user_id' => $idea->creator_id,
            'role' => 'leader',
            'specialization' => 'Project Creator',
            'status' => 'active'
        ]);

        return redirect()->route('admin.eco-ideas')->with('success', 'Eco idea created successfully!');
    }

    public function adminGetData($id)
    {
        $idea = EcoIdea::with('creator')->findOrFail($id);
        return response()->json($idea);
    }

    public function adminUpdate(Request $request, $id)
    {
        $idea = EcoIdea::findOrFail($id);
        $data = $request->validate([
            'creator_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'image' => 'nullable|image|max:2048',
            'ai_generated_suggestion' => 'nullable|string',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'team_size_needed' => 'nullable|integer|min:1|max:20',
            'team_requirements' => 'nullable|string',
            'application_description' => 'nullable|string',
            'project_status' => 'nullable|in:idea,recruiting,in_progress,completed,verified,donated',
            'final_description' => 'nullable|string',
            'impact_metrics' => 'nullable|array',
        ]);

        $idea->creator_id = $data['creator_id'];
        $idea->title = $data['title'];
        $idea->description = $data['description'];
        $idea->waste_type = $data['waste_type'];
        $idea->ai_generated_suggestion = $data['ai_generated_suggestion'] ?? null;
        $idea->difficulty_level = $data['difficulty_level'];
        $idea->team_size_needed = $data['team_size_needed'] ?? null;
        $idea->team_requirements = $data['team_requirements'] ? $data['team_requirements'] : null;
        $idea->application_description = $data['application_description'] ?? null;
        $idea->project_status = $data['project_status'] ?? $idea->project_status;
        $idea->final_description = $data['final_description'] ?? null;
        $idea->impact_metrics = $data['impact_metrics'] ?? null;

        if ($request->hasFile('image')) {
            if ($idea->image_path) {
                Storage::disk('public')->delete($idea->image_path);
            }
            
            $imagePath = $request->file('image')->store('eco_ideas', 'public');
            $idea->image_path = $imagePath;
        }

        $idea->save();
        return redirect()->route('admin.eco-ideas')->with('success', 'Eco idea updated successfully!');
    }

    public function adminDestroy($id)
    {
        $idea = EcoIdea::findOrFail($id);
        
        if ($idea->image_path) {
            Storage::disk('public')->delete($idea->image_path);
        }
        
        $idea->delete();
        return redirect()->route('admin.eco-ideas')->with('delete_success', 'Eco idea deleted successfully!');
    }

    // ===== Team Management =====
    public function getTeamData($id)
    {
        $idea = EcoIdea::with(['team.user', 'applications.user', 'creator'])->findOrFail($id);
        
        return response()->json([
            'team' => $idea->team,
            'applications' => $idea->applications->where('status', 'pending'),
            'creator' => $idea->creator,
            'creator_id' => $idea->creator_id
        ]);
    }

    public function verifyProject(Request $request, $id)
    {
        $idea = EcoIdea::findOrFail($id);
        
        $data = $request->validate([
            'final_description' => 'required|string',
            'impact_metrics' => 'nullable|array',
            'donated_to_ngo' => 'required|in:0,1',
            'ngo_name' => 'nullable|string',
        ]);

        $donatedToNgo = $data['donated_to_ngo'] === '1';

        $idea->update([
            'project_status' => 'verified',
            'final_description' => $data['final_description'],
            'impact_metrics' => $data['impact_metrics'],
            'donated_to_ngo' => $donatedToNgo,
            'ngo_name' => $donatedToNgo ? ($data['ngo_name'] ?? null) : null,
            'verification_date' => now(),
        ]);

        return redirect()->route('admin.eco-ideas')->with('success', 'Project verified successfully!');
    }

    public function updateVerification(Request $request, $id)
    {
        $idea = EcoIdea::findOrFail($id);
        
        // Only allow updating if project is verified
        if ($idea->project_status !== 'verified') {
            return redirect()->route('admin.eco-ideas')->with('error', 'Only verified projects can have their verification updated.');
        }
        
        $data = $request->validate([
            'final_description' => 'required|string',
            'impact_metrics' => 'nullable|array',
            'impact_metrics.waste_reduced' => 'nullable|string|max:50|regex:/^[\d\s\w\-\/\.]+$/',
            'impact_metrics.co2_saved' => 'nullable|string|max:50|regex:/^[\d\s\w\-\/\.]+$/',
            'impact_metrics.people_impacted' => 'nullable|integer|min:0|max:10000',
            'impact_metrics.energy_saved' => 'nullable|string|max:50|regex:/^[\d\s\w\-\/\.]+$/',
            'impact_metrics.money_saved' => 'nullable|string|max:50|regex:/^[\d\s\w\-\/\.\$€£¥]+$/',
            'donated_to_ngo' => 'required|in:0,1',
            'ngo_name' => 'nullable|string|max:255',
        ]);

        $donatedToNgo = $data['donated_to_ngo'] === '1';

        $idea->update([
            'final_description' => $data['final_description'],
            'impact_metrics' => $data['impact_metrics'] ?? null,
            'donated_to_ngo' => $donatedToNgo,
            'ngo_name' => $donatedToNgo ? ($data['ngo_name'] ?? null) : null,
        ]);

        return redirect()->route('admin.eco-ideas')->with('success', 'Verification updated successfully!');
    }

    public function deleteVerification($id)
    {
        $idea = EcoIdea::findOrFail($id);
        
        // Only allow deleting if project is verified
        if ($idea->project_status !== 'verified') {
            return response()->json(['error' => 'Only verified projects can have their verification deleted.'], 403);
        }
        
        // Reset verification fields
        $idea->update([
            'final_description' => null,
            'impact_metrics' => null,
            'donated_to_ngo' => false,
            'ngo_name' => null,
            'verification_date' => null,
            'project_status' => 'completed', // Reset to completed status
        ]);

        return response()->json(['message' => 'Verification deleted successfully']);
    }

    public function removeVerification($id)
    {
        $idea = EcoIdea::findOrFail($id);
        
        $idea->update([
            'project_status' => 'completed',
            'verification_date' => null,
        ]);

        return redirect()->route('admin.eco-ideas')->with('success', 'Verification removed successfully!');
    }

    // ===== Frontend Methods =====
    public function frontendIndex()
    {
        $updated = EcoIdea::where(function($query) {
            $query->whereNull('status')
                  ->orWhere('status', '')
                  ->orWhere('status', 'pending')
                  ->orWhere('status', 'rejected');
        })->update(['status' => 'approved']);
        
        if ($updated > 0) {
            \Log::info("Updated {$updated} EcoIdeas to approved status");
        }
        
        $ideas = EcoIdea::with(['creator', 'team', 'applications', 'interactions'])
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('front.pages.eco-ideas', compact('ideas'));
    }

    public function frontendShow(EcoIdea $ecoIdea)
    {
        $ecoIdea->load(['creator', 'team.user', 'applications.user', 'interactions.user', 'tasks.assignedUser']);
        
        $hasApplied = false;
        if (auth()->check()) {
            $hasApplied = $ecoIdea->applications()
                ->where('user_id', auth()->id())
                ->where('status', 'pending')
                ->exists();
        }

        $hasLiked = false;
        if (auth()->check()) {
            $hasLiked = $ecoIdea->interactions()
                ->where('user_id', auth()->id())
                ->where('type', 'like')
                ->exists();
        }

        return view('front.pages.eco-idea-details', compact('ecoIdea', 'hasApplied', 'hasLiked'));
    }

    public function applyToIdea(Request $request, EcoIdea $ecoIdea)
    {
        if (!auth()->check()) {
            return redirect()->route('front.login')->with('error', 'Please login to apply for this idea.');
        }

        // Prevent creator from applying to their own idea
        if ($ecoIdea->creator_id === auth()->id()) {
            return back()->with('error', 'You cannot apply to your own eco idea.');
        }

        $data = $request->validate([
            'application_message' => 'required|string|max:1000',
            'resume' => 'required|file|mimes:pdf|max:5120', // 5MB max
        ]);

        $existingApplication = $ecoIdea->applications()
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'You have already applied for this idea. Please wait for a response.');
        }

        if ($ecoIdea->project_status !== 'recruiting' && $ecoIdea->project_status !== 'idea') {
            return back()->with('error', 'This idea is no longer accepting applications.');
        }

        // Handle resume upload
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        // Check if user has any previous application (rejected, removed, etc.)
        $previousApplication = $ecoIdea->applications()
            ->where('user_id', auth()->id())
            ->first();

        if ($previousApplication) {
            // Update existing application instead of creating new one
            $previousApplication->update([
                'application_message' => $data['application_message'],
                'resume_path' => $resumePath,
                'status' => 'pending',
            ]);
        } else {
            // Create new application for first-time applicants
            $ecoIdea->applications()->create([
                'user_id' => auth()->id(),
                'application_message' => $data['application_message'],
                'resume_path' => $resumePath,
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Your application has been submitted successfully!');
    }

    public function likeIdea(EcoIdea $ecoIdea)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login to like this idea.'], 401);
        }

        $existingInteraction = $ecoIdea->interactions()
            ->where('user_id', auth()->id())
            ->where('type', 'like')
            ->first();

        if ($existingInteraction) {
            $existingInteraction->delete();
            $ecoIdea->decrement('upvotes');
            return response()->json([
                'liked' => false,
                'upvotes' => $ecoIdea->upvotes
            ]);
        } else {
            $ecoIdea->interactions()->create([
                'user_id' => auth()->id(),
                'type' => 'like',
            ]);
            $ecoIdea->increment('upvotes');
            return response()->json([
                'liked' => true,
                'upvotes' => $ecoIdea->upvotes
            ]);
        }
    }

    public function addReview(Request $request, EcoIdea $ecoIdea)
    {
        if (!auth()->check()) {
            return redirect()->route('front.login')->with('error', 'Please login to add a review.');
        }

        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $ecoIdea->interactions()->create([
            'user_id' => auth()->id(),
            'type' => 'comment',
            'content' => $data['content'],
        ]);

        return back()->with('success', 'Your review has been posted successfully!');
    }

    /**
     * Delete a review (Only eco idea owner can delete reviews on their project)
     */
    public function deleteReview(\App\Models\EcoIdeaInteraction $interaction)
    {
        $ecoIdea = $interaction->ecoIdea;

        // Check if current user is the eco idea owner
        if ($ecoIdea->creator_id !== auth()->id()) {
            abort(403, 'Unauthorized. Only the eco idea owner can delete reviews.');
        }

        $interaction->delete();

        return back()->with('success', 'Review deleted successfully!');
    }

    /**
     * Get creators for dropdown
     */
    public function getCreators()
    {
        $creators = \App\Models\User::where('is_active', true)
                       ->select('id', 'name', 'email')
                       ->get();
        
        return response()->json($creators);
    }

    // ===== ECO IDEAS DASHBOARD (Frontend Creator Management) =====

    /**
     * Show the creator's dashboard with their eco ideas
     */
    public function dashboard()
    {
        // Get eco ideas created by the user
        $userIdeas = EcoIdea::where('creator_id', auth()->id())
            ->withCount(['applications' => function($query) {
                $query->where('status', 'pending');
            }])
            ->withCount('team')
            ->withCount('tasks')
            ->latest()
            ->get();

        // Get eco ideas the user has joined as a team member (excluding their own created ideas)
        $joinedIdeas = EcoIdea::whereHas('team', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('creator_id', '!=', auth()->id()) // Exclude ideas created by the user
            ->with(['creator', 'team', 'tasks'])
            ->withCount('team')
            ->withCount('tasks')
            ->latest()
            ->get();

        return view('front.pages.eco-ideas-dashboard', compact('userIdeas', 'joinedIdeas'));
    }

    /**
     * Create eco idea from dashboard
     */
    public function createFromDashboard(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'team_size_needed' => 'nullable|integer|min:1',
            'team_requirements' => 'nullable|string',
            'application_description' => 'nullable|string',
            'is_recruiting' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data['creator_id'] = auth()->id();
        $data['project_status'] = 'idea';
        $data['status'] = 'approved'; // Auto-approve user-created ideas
        
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('eco-ideas', 'public');
        }

        $ecoIdea = EcoIdea::create($data);

        return redirect()->route('front.eco-ideas.dashboard.manage', $ecoIdea)
            ->with('success', 'Eco idea created successfully!');
    }

    /**
     * Show project management page
     */
    public function manageProject(EcoIdea $ecoIdea)
    {
        // Check if user is the creator OR a team member
        $isCreator = $ecoIdea->creator_id === auth()->id();
        $isTeamMember = $ecoIdea->team()->where('user_id', auth()->id())->exists();
        
        if (!$isCreator && !$isTeamMember) {
            abort(403, 'Unauthorized access. You must be the creator or a team member.');
        }

        $ecoIdea->load([
            'creator',
            'applications' => function($query) {
                $query->latest();
            },
            'applications.user',
            'team.user',
            'tasks' => function($query) {
                $query->orderBy('status')->orderBy('priority', 'desc')->latest();
            },
            'tasks.assignedUser'
        ]);

        // Manually attach application data to each team member
        foreach ($ecoIdea->team as $teamMember) {
            $teamMember->application = $ecoIdea->applications()
                ->where('user_id', $teamMember->user_id)
                ->first();
        }

        return view('front.pages.eco-ideas-manage', compact('ecoIdea', 'isCreator'));
    }

    /**
     * Update eco idea from dashboard
     */
    public function updateFromDashboard(Request $request, EcoIdea $ecoIdea)
    {
        if ($ecoIdea->creator_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'project_status' => 'required|in:idea,recruiting,in_progress,completed,verified',
            'team_size_needed' => 'nullable|integer|min:1',
            'is_recruiting' => 'boolean',
            'team_requirements' => 'nullable|string',
            'application_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($ecoIdea->image_path) {
                Storage::disk('public')->delete($ecoIdea->image_path);
            }
            $data['image_path'] = $request->file('image')->store('eco-ideas', 'public');
        }

        $ecoIdea->update($data);

        return back()->with('success', 'Eco idea updated successfully!');
    }

    /**
     * Delete eco idea from dashboard
     */
    public function deleteFromDashboard(EcoIdea $ecoIdea)
    {
        if ($ecoIdea->creator_id !== auth()->id()) {
            abort(403);
        }

        if ($ecoIdea->image_path) {
            Storage::disk('public')->delete($ecoIdea->image_path);
        }

        $ecoIdea->delete();

        return redirect()->route('front.eco-ideas.dashboard')
            ->with('success', 'Eco idea deleted successfully!');
    }

    /**
     * Get applications for an eco idea
     */
    public function getApplications(EcoIdea $ecoIdea)
    {
        if ($ecoIdea->creator_id !== auth()->id()) {
            abort(403);
        }

        $applications = $ecoIdea->applications()
            ->with('user')
            ->latest()
            ->get();

        return response()->json($applications);
    }

    /**
     * Accept an application
     */
    public function acceptApplication(EcoIdeaApplication $application)
    {
        $ecoIdea = $application->ecoIdea;
        
        if ($ecoIdea->creator_id !== auth()->id()) {
            abort(403);
        }

        $application->update(['status' => 'accepted']);

        // Add to team
        EcoIdeaTeam::create([
            'eco_idea_id' => $ecoIdea->id,
            'user_id' => $application->user_id,
            'role' => 'member',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Application accepted! Member added to team.');
    }

    /**
     * Reject an application
     */
    public function rejectApplication(EcoIdeaApplication $application)
    {
        $ecoIdea = $application->ecoIdea;
        
        if ($ecoIdea->creator_id !== auth()->id()) {
            abort(403);
        }

        $application->update(['status' => 'rejected']);

        return back()->with('success', 'Application rejected.');
    }

    /**
     * Remove team member
     */
    public function removeTeamMember(EcoIdeaTeam $teamMember)
    {
        $ecoIdea = $teamMember->ecoIdea;
        
        if ($ecoIdea->creator_id !== auth()->id()) {
            abort(403);
        }

        // Update their application status to 'rejected' so they can reapply
        $ecoIdea->applications()
            ->where('user_id', $teamMember->user_id)
            ->where('status', 'accepted')
            ->update(['status' => 'rejected']);

        $teamMember->delete();

        return back()->with('success', 'Team member removed successfully. They can now reapply if needed.');
    }

    /**
     * Get tasks for an eco idea
     */
    public function getTasks(EcoIdea $ecoIdea)
    {
        // Check if user is the creator OR a team member
        $isCreator = $ecoIdea->creator_id === auth()->id();
        $isTeamMember = $ecoIdea->team()->where('user_id', auth()->id())->exists();
        
        if (!$isCreator && !$isTeamMember) {
            abort(403, 'Unauthorized. You must be the creator or a team member to view tasks.');
        }

        $tasks = $ecoIdea->tasks()
            ->with('assignedUser')
            ->orderBy('status')
            ->orderBy('priority', 'desc')
            ->get();

        return response()->json($tasks);
    }

    /**
     * Create a new task
     */
    public function createTask(Request $request, EcoIdea $ecoIdea)
    {
        try {
            // Check if user is the creator OR a team member
            $isCreator = $ecoIdea->creator_id === auth()->id();
            $isTeamMember = $ecoIdea->team()->where('user_id', auth()->id())->exists();
            
            if (!$isCreator && !$isTeamMember) {
                return response()->json(['error' => 'Unauthorized. You must be the creator or a team member to create tasks.'], 403);
            }

            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'assigned_to' => 'nullable|exists:users,id',
                'priority' => 'required|in:low,medium,high',
                'due_date' => 'nullable|date',
                'status' => 'required|in:todo,in_progress,review,completed',
            ]);

            $data['eco_idea_id'] = $ecoIdea->id;

            $task = \App\Models\EcoIdeaTask::create($data);

            return response()->json($task->load('assignedUser'));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Task creation error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create task',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a task
     */
    public function updateTask(Request $request, $task)
    {
        $task = \App\Models\EcoIdeaTask::findOrFail($task);
        $ecoIdea = $task->ecoIdea;
        
        // Check if user is the creator OR a team member
        $isCreator = $ecoIdea->creator_id === auth()->id();
        $isTeamMember = $ecoIdea->team()->where('user_id', auth()->id())->exists();
        
        if (!$isCreator && !$isTeamMember) {
            abort(403, 'Unauthorized. You must be the creator or a team member to update tasks.');
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'sometimes|required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|required|in:todo,in_progress,review,completed',
        ]);

        $task->update($data);

        return response()->json($task->load('assignedUser'));
    }

    /**
     * Delete a task
     */
    public function deleteTask($task)
    {
        $task = \App\Models\EcoIdeaTask::findOrFail($task);
        $ecoIdea = $task->ecoIdea;
        
        // Check if user is the creator OR a team member
        $isCreator = $ecoIdea->creator_id === auth()->id();
        $isTeamMember = $ecoIdea->team()->where('user_id', auth()->id())->exists();
        
        if (!$isCreator && !$isTeamMember) {
            abort(403, 'Unauthorized. You must be the creator or a team member to delete tasks.');
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    /**
     * Get chat messages for an eco idea
     */
    public function getMessages(EcoIdea $ecoIdea)
    {
        // Check if user is the creator OR a team member
        $isCreator = $ecoIdea->creator_id === auth()->id();
        $isTeamMember = $ecoIdea->team()->where('user_id', auth()->id())->exists();
        
        if (!$isCreator && !$isTeamMember) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $ecoIdea->messages()
            ->with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }

    /**
     * Send a chat message
     */
    public function sendMessage(Request $request, EcoIdea $ecoIdea)
    {
        // Check if user is the creator OR a team member
        $isCreator = $ecoIdea->creator_id === auth()->id();
        $isTeamMember = $ecoIdea->team()->where('user_id', auth()->id())->exists();
        
        if (!$isCreator && !$isTeamMember) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $ecoIdea->messages()->create([
            'user_id' => auth()->id(),
            'message' => $data['message'],
        ]);

        return response()->json($message->load('user'));
    }
}
