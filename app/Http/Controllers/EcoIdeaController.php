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
            'impact_metrics' => 'sometimes|nullable|string',
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
        $idea->team_requirements = $data['team_requirements'] ? json_encode([$data['team_requirements']]) : null;
        $idea->application_description = $data['application_description'] ?? null;
        $idea->project_status = 'idea';
        $idea->team_size_current = 1; // Creator is automatically part of the team
        $idea->is_recruiting = $data['team_size_needed'] ? true : false;

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('eco_ideas', 'public');
            $idea->image_path = $imagePath;
        }

        $idea->save();

        // Add creator as team leader
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
            'impact_metrics' => 'nullable|string',
        ]);

        // Update basic fields
        $idea->creator_id = $data['creator_id'];
        $idea->title = $data['title'];
        $idea->description = $data['description'];
        $idea->waste_type = $data['waste_type'];
        $idea->ai_generated_suggestion = $data['ai_generated_suggestion'] ?? null;
        $idea->difficulty_level = $data['difficulty_level'];
        $idea->team_size_needed = $data['team_size_needed'] ?? null;
        $idea->team_requirements = $data['team_requirements'] ? json_encode([$data['team_requirements']]) : null;
        $idea->application_description = $data['application_description'] ?? null;
        $idea->project_status = $data['project_status'] ?? $idea->project_status;
        $idea->final_description = $data['final_description'] ?? null;
        $idea->impact_metrics = $data['impact_metrics'] ?? null;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
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
        
        // Delete associated image
        if ($idea->image_path) {
            Storage::disk('public')->delete($idea->image_path);
        }
        
        $idea->delete();
        return redirect()->route('admin.eco-ideas')->with('delete_success', 'Eco idea deleted successfully!');
    }

    // ===== Team Management =====
    public function getTeamData($id)
    {
        $idea = EcoIdea::with(['team.user', 'applications.user'])->findOrFail($id);
        
        return response()->json([
            'team' => $idea->team,
            'applications' => $idea->applications->where('status', 'pending')
        ]);
    }

    public function verifyProject(Request $request, $id)
    {
        $idea = EcoIdea::findOrFail($id);
        
        $data = $request->validate([
            'final_description' => 'required|string',
            'impact_metrics' => 'nullable|string',
            'donated_to_ngo' => 'nullable|boolean',
            'ngo_name' => 'nullable|string',
        ]);

        $idea->update([
            'project_status' => 'verified',
            'final_description' => $data['final_description'],
            'impact_metrics' => $data['impact_metrics'],
            'donated_to_ngo' => $data['donated_to_ngo'] ?? false,
            'ngo_name' => $data['ngo_name'] ?? null,
            'verification_date' => now(),
        ]);

        return redirect()->route('admin.eco-ideas')->with('success', 'Project verified successfully!');
    }
}