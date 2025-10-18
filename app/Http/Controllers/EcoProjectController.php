<?php

namespace App\Http\Controllers;

use App\Models\EcoProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EcoProjectController extends Controller
{
    // ===== API (stateless JSON) =====
    public function index()
    {
        return response()->json(EcoProject::with(['ecoIdea:id,title', 'user:id,name'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'eco_idea_id' => 'required|exists:eco_ideas,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'nullable|in:prototype,testing,completed,failed',
            'ai_impact_prediction' => 'nullable|array',
            'image_path' => 'nullable|string',
            'completion_date' => 'nullable|date',
        ]);

        $ecoProject = EcoProject::create($data);
        return response()->json($ecoProject, 201);
    }

    public function show(EcoProject $ecoProject)
    {
        return response()->json($ecoProject->load(['ecoIdea', 'user']));
    }

    public function update(Request $request, EcoProject $ecoProject)
    {
        $data = $request->validate([
            'eco_idea_id' => 'sometimes|required|exists:eco_ideas,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|in:prototype,testing,completed,failed',
            'ai_impact_prediction' => 'sometimes|nullable|array',
            'image_path' => 'sometimes|nullable|string',
            'completion_date' => 'sometimes|nullable|date',
        ]);

        $ecoProject->update($data);
        return response()->json($ecoProject);
    }

    public function destroy(EcoProject $ecoProject)
    {
        $ecoProject->delete();
        return response()->json(['message' => 'Deleted']);
    }

    // ===== Admin Blade (pages) =====
    public function adminIndex(Request $request)
    {
        $query = EcoProject::with(['ecoIdea','user']);
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('eco_idea_id') && $request->eco_idea_id !== 'all') {
            $query->where('eco_idea_id', $request->eco_idea_id);
        }
        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        $projects = $query->orderByDesc('created_at')->paginate(10);
        $projects->appends($request->query());
        return view('back.pages.eco-projects', compact('projects'));
    }

    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'eco_idea_id' => 'required|exists:eco_ideas,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:prototype,testing,completed,failed',
            'ai_impact_prediction' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'completion_date' => 'nullable|date',
        ]);

        $project = new EcoProject();
        $project->eco_idea_id = $data['eco_idea_id'];
        $project->user_id = $data['user_id'];
        $project->name = $data['name'];
        $project->description = $data['description'];
        $project->status = $data['status'];
        $project->ai_impact_prediction = $data['ai_impact_prediction'] ?? null;
        $project->completion_date = $data['completion_date'] ?? null;

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('eco_projects', 'public');
            $project->image_path = $imagePath;
            \Log::info('Eco project image stored at: ' . $imagePath);
        }

        $project->save();
        return redirect()->route('admin.eco-projects')->with('success', 'Eco project created successfully!');
    }

    public function adminGetData($id)
    {
        $project = EcoProject::with(['ecoIdea','user'])->findOrFail($id);
        return response()->json($project);
    }

    public function adminUpdate(Request $request, $id)
    {
        $project = EcoProject::findOrFail($id);
        $data = $request->validate([
            'eco_idea_id' => 'required|exists:eco_ideas,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:prototype,testing,completed,failed',
            'ai_impact_prediction' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'completion_date' => 'nullable|date',
        ]);

        // Update basic fields
        $project->eco_idea_id = $data['eco_idea_id'];
        $project->user_id = $data['user_id'];
        $project->name = $data['name'];
        $project->description = $data['description'];
        $project->status = $data['status'];
        $project->ai_impact_prediction = $data['ai_impact_prediction'] ?? null;
        $project->completion_date = $data['completion_date'] ?? null;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($project->image_path) {
                Storage::disk('public')->delete($project->image_path);
            }
            
            $imagePath = $request->file('image')->store('eco_projects', 'public');
            $project->image_path = $imagePath;
        }

        $project->save();
        return redirect()->route('admin.eco-projects')->with('success', 'Eco project updated successfully!');
    }

    public function adminDestroy($id)
    {
        $project = EcoProject::findOrFail($id);
        $project->delete();
        return redirect()->route('admin.eco-projects')->with('delete_success', 'Eco project deleted successfully!');
    }
}


