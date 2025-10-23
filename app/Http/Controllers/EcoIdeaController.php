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
            'project_status' => 'sometimes|in:recruiting,in_progress,completed,verified,donated',
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
            'project_status' => 'nullable|in:recruiting,in_progress,completed,verified,donated',
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

        // Check if all tasks are completed before verifying
        $totalTasks = $idea->tasks()->count();
        $completedTasks = $idea->tasks()->where('status', 'completed')->count();
        
        if ($totalTasks > 0 && $completedTasks !== $totalTasks) {
            return redirect()->route('admin.eco-ideas')
                ->with('error', 'Cannot verify project: All tasks must be completed first! (' . $completedTasks . '/' . $totalTasks . ' completed)');
        }

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
        ]);
        
        // Set status based on team size and task completion
        $this->updateProjectStatus($idea);

        return response()->json(['message' => 'Verification deleted successfully']);
    }

    public function removeVerification($id)
    {
        $idea = EcoIdea::findOrFail($id);
        
        $idea->update([
            'verification_date' => null,
        ]);
        
        // Set status based on team size and task completion
        $this->updateProjectStatus($idea);

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
        
        // Get all approved eco-ideas for main view
        $ideas = EcoIdea::with(['creator', 'team', 'applications', 'interactions'])
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->get();
        
        // Get user-specific data if authenticated
        $myProjects = collect();
        $joinedProjects = collect();
        $myApplications = collect();
        
        if (auth()->check()) {
            $myProjects = EcoIdea::where('creator_id', auth()->id())
                ->withCount(['applications' => function($query) {
                    $query->where('status', 'pending');
                }])
                ->withCount('team')
                ->withCount('tasks')
                ->latest()
                ->get();

            $joinedProjects = EcoIdea::whereHas('team', function($query) {
                    $query->where('user_id', auth()->id());
                })
                ->where('creator_id', '!=', auth()->id())
                ->with(['creator', 'team', 'tasks'])
                ->withCount('team')
                ->withCount('tasks')
                ->latest()
                ->get();
            
            $myApplications = \App\Models\EcoIdeaApplication::where('user_id', auth()->id())
                ->with(['ecoIdea.creator'])
                ->latest()
                ->get();
        }

        return view('front.pages.eco-ideas', compact('ideas', 'myProjects', 'joinedProjects', 'myApplications'));
    }

    public function frontendStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'team_size_needed' => 'nullable|integer|min:1',
            'team_requirements' => 'nullable|string',
            'application_description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('eco-ideas', 'public');
            $validated['image_path'] = $imagePath;
        }

        // Set creator and default values
        $validated['creator_id'] = auth()->id();
        $validated['status'] = 'pending'; // Admin approval required
        
        // Automatic project_status based on team size
        // Creator counts as 1 member, so if team_size_needed > 1, we need more members (recruiting)
        $teamSizeNeeded = $validated['team_size_needed'] ?? 1;
        if ($teamSizeNeeded > 1) {
            $validated['project_status'] = 'recruiting'; // Need more team members
        } else {
            $validated['project_status'] = 'in_progress'; // Solo project or team complete
        }

        // Create the eco idea
        $ecoIdea = EcoIdea::create($validated);

        return redirect()->route('front.eco-ideas')
            ->with('success', 'Eco idea created successfully! It will be reviewed by admin.');
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

        // Check if user is a team member
        $isTeamMember = false;
        if (auth()->check()) {
            $isTeamMember = $ecoIdea->team()
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('front.pages.eco-idea-details', compact('ecoIdea', 'hasApplied', 'hasLiked', 'isTeamMember'));
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

        // Prevent team members from applying (they're already in the team!)
        $isTeamMember = $ecoIdea->team()->where('user_id', auth()->id())->exists();
        if ($isTeamMember) {
            return back()->with('error', 'You are already a team member of this project!');
        }

        // Check if the project is open for recruitment (using project_status)
        if ($ecoIdea->project_status !== 'recruiting') {
            return back()->with('error', 'This eco idea is not currently accepting applications. Recruitment is closed.');
        }

        // Check if team is full (include owner in count)
        $currentTeamCount = $ecoIdea->team()->count() + 1; // +1 for owner
        $teamSizeNeeded = $ecoIdea->team_size_needed ?? 0;
        
        if ($teamSizeNeeded > 0 && $currentTeamCount >= $teamSizeNeeded) {
            return back()->with('error', "This team is full! All positions have been filled ({$currentTeamCount}/{$teamSizeNeeded} members including owner).");
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
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Please login to add a review.'], 401);
            }
            return redirect()->route('front.login')->with('error', 'Please login to add a review.');
        }

        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $interaction = $ecoIdea->interactions()->create([
            'user_id' => auth()->id(),
            'type' => 'comment',
            'content' => $data['content'],
        ]);

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            $user = auth()->user();
            return response()->json([
                'success' => true,
                'message' => 'Review posted successfully!',
                'review' => [
                    'id' => $interaction->id,
                    'user_name' => $user->name,
                    'user_avatar' => $user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=10b981&color=fff',
                    'review_text' => $interaction->content,
                    'created_at' => $interaction->created_at->diffForHumans(),
                ]
            ]);
        }

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
        // Get ALL approved eco ideas for discovery
        $allIdeas = EcoIdea::where('status', 'approved')
            ->with(['creator', 'team'])
            ->withCount('team')
            ->withCount('tasks')
            ->latest()
            ->get();
        
        // Get eco ideas created by the user (My Projects)
        $myProjects = EcoIdea::where('creator_id', auth()->id())
            ->withCount(['applications' => function($query) {
                $query->where('status', 'pending');
            }])
            ->withCount('team')
            ->withCount('tasks')
            ->latest()
            ->get();

        // Get eco ideas the user has joined as a team member (Joined Projects)
        $joinedProjects = EcoIdea::whereHas('team', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('creator_id', '!=', auth()->id())
            ->with(['creator', 'team', 'tasks'])
            ->withCount('team')
            ->withCount('tasks')
            ->latest()
            ->get();
        
        // Get user's applications
        $myApplications = \App\Models\EcoIdeaApplication::where('user_id', auth()->id())
            ->with(['ecoIdea.creator'])
            ->latest()
            ->get();

        return view('front.pages.eco-ideas-dashboard', compact('allIdeas', 'myProjects', 'joinedProjects', 'myApplications'));
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
        $data['status'] = 'approved'; // Auto-approve user-created ideas
        
        // Smart auto-status: No team needed = in_progress, Team needed = recruiting
        // team_size_needed includes the owner (e.g., if owner wants 2 more people, they enter 3)
        $teamSizeNeeded = $data['team_size_needed'] ?? 0;
        if ($teamSizeNeeded > 1) { // >1 means owner needs additional members
            $data['project_status'] = 'recruiting';
            $data['is_recruiting'] = true;
        } else {
            $data['project_status'] = 'in_progress';
            $data['is_recruiting'] = false;
        }
        
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

        // Get current team count (including owner)
        $currentTeamCount = $ecoIdea->team()->count() + 1; // +1 for owner

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'team_size_needed' => 'nullable|integer|min:1',
            'team_requirements' => 'nullable|string',
            'application_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);
        
        // Note: project_status is NOT included - it's managed automatically by the system

        // Validate team_size_needed (must include the owner)
        if (isset($data['team_size_needed'])) {
            if ($data['team_size_needed'] < $currentTeamCount) {
                return back()->withErrors([
                    'team_size_needed' => "Team size cannot be less than current team members ({$currentTeamCount}). You currently have {$currentTeamCount} member(s) including yourself as the owner."
                ])->withInput();
            }
        }

        if ($request->hasFile('image')) {
            if ($ecoIdea->image_path) {
                Storage::disk('public')->delete($ecoIdea->image_path);
            }
            $data['image_path'] = $request->file('image')->store('eco-ideas', 'public');
        }

        $ecoIdea->update($data);

        // Auto-update project status based on team size and task completion
        $this->updateProjectStatus($ecoIdea);

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

        // Auto-update project status based on team size
        $this->updateProjectStatus($ecoIdea);

        $currentTeamCount = $ecoIdea->team()->count() + 1; // +1 for owner
        $teamSizeNeeded = $ecoIdea->team_size_needed ?? 0;

        $message = 'Application accepted! Member added to team.';
        if ($currentTeamCount >= $teamSizeNeeded && $teamSizeNeeded > 0) {
            $message .= " Team is now full ({$currentTeamCount}/{$teamSizeNeeded}) - project status changed to In Progress.";
        }

        return back()->with('success', $message);
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

        // Auto-update project status based on team size and task completion
        $this->updateProjectStatus($ecoIdea);

        $currentTeamCount = $ecoIdea->team()->count() + 1; // +1 for owner
        $teamSizeNeeded = $ecoIdea->team_size_needed ?? 0;
        $spotsAvailable = max(0, $teamSizeNeeded - $currentTeamCount);
        
        $message = 'Team member removed successfully. They can now reapply if needed.';
        if ($currentTeamCount < $teamSizeNeeded) {
            $message .= " Recruitment reopened ({$currentTeamCount}/{$teamSizeNeeded}) - {$spotsAvailable} spot(s) available.";
        }

        return back()->with('success', $message);
    }

    /**
     * Mark project as completed (owner only, requires all tasks done)
     */
    public function markAsCompleted(EcoIdea $ecoIdea)
    {
        if ($ecoIdea->creator_id !== auth()->id()) {
            abort(403, 'Only the project creator can mark it as completed.');
        }

        // Check if all tasks are completed
        $totalTasks = $ecoIdea->tasks()->count();
        $completedTasks = $ecoIdea->tasks()->where('status', 'completed')->count();

        if ($totalTasks === 0) {
            return back()->with('error', 'Cannot mark as completed: No tasks have been created yet.');
        }

        if ($completedTasks !== $totalTasks) {
            return back()->with('error', "Cannot mark as completed: {$completedTasks}/{$totalTasks} tasks completed. All tasks must be completed first.");
        }

        $ecoIdea->update(['project_status' => 'completed']);

        return back()->with('success', 'Project marked as completed! Waiting for Waste2Product verification.');
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

            // Auto-update project status based on team size and task completion
            $this->updateProjectStatus($ecoIdea);

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

        // Auto-update project status based on team size and task completion
        $this->updateProjectStatus($ecoIdea);

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

        // Auto-update project status based on team size and task completion
        $this->updateProjectStatus($ecoIdea);

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

    /**
     * Automatically update project status based on team size and task completion
     * Status Logic:
     * - recruiting: Team is not full (current team < team_size_needed)
     * - in_progress: Team is full but not all tasks are completed
     * - completed: Team is full AND all tasks are completed
     */
    private function updateProjectStatus(EcoIdea $ecoIdea)
    {
        // Count current team members (including owner)
        $currentTeamCount = $ecoIdea->team()->count() + 1; // +1 for owner
        $teamSizeNeeded = $ecoIdea->team_size_needed ?? 1;
        
        // Check if team is full
        $teamIsFull = $currentTeamCount >= $teamSizeNeeded;
        
        // Count tasks
        $totalTasks = $ecoIdea->tasks()->count();
        $completedTasks = $ecoIdea->tasks()->where('status', 'completed')->count();
        $allTasksCompleted = $totalTasks > 0 && $completedTasks === $totalTasks;
        
        // Determine new status
        $newStatus = 'recruiting'; // Default
        
        if (!$teamIsFull) {
            // Team not full - recruiting
            $newStatus = 'recruiting';
        } elseif ($teamIsFull && $allTasksCompleted) {
            // Team full AND all tasks completed - completed
            $newStatus = 'completed';
        } elseif ($teamIsFull) {
            // Team full but tasks not all completed - in progress
            $newStatus = 'in_progress';
        }
        
        // Only update if status has changed (to avoid unnecessary database writes)
        if ($ecoIdea->project_status !== $newStatus) {
            $ecoIdea->update(['project_status' => $newStatus]);
        }
    }

    /**
     * Download completion certificate for verified/completed projects
     */
    public function downloadCertificate(EcoIdea $ecoIdea)
    {
        // Check if user is owner or team member
        $isOwner = auth()->id() === $ecoIdea->creator_id;
        $isTeamMember = $ecoIdea->team()->where('user_id', auth()->id())->exists();
        
        if (!$isOwner && !$isTeamMember) {
            abort(403, 'You must be a project owner or team member to download the certificate.');
        }

        // Check if project is completed or verified
        if (!in_array($ecoIdea->project_status, ['completed', 'verified'])) {
            return back()->with('error', 'Certificates are only available for completed or verified projects.');
        }

        // Return a printable HTML certificate
        $user = auth()->user();
        $isVerified = $ecoIdea->project_status === 'verified';
        
        return view('front.certificates.eco-idea', compact('ecoIdea', 'user', 'isVerified'));
    }
}

