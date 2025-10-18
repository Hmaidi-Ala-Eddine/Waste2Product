<?php

namespace App\Http\Controllers;

use App\Models\EcoIdea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EcoIdeaController extends Controller
{
    // ===== API (stateless JSON) =====
    public function index()
    {
        return response()->json(EcoIdea::withCount('ecoProjects')->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'image_path' => 'nullable|string',
            'ai_generated_suggestion' => 'nullable|string',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'upvotes' => 'nullable|integer',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $ecoIdea = EcoIdea::create($data);
        return response()->json($ecoIdea, 201);
    }

    public function show(EcoIdea $ecoIdea)
    {
        return response()->json($ecoIdea->load('ecoProjects'));
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
        $query = EcoIdea::with('user');

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
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('difficulty_level') && $request->difficulty_level !== 'all') {
            $query->where('difficulty_level', $request->difficulty_level);
        }
        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        $ideas = $query->orderByDesc('created_at')->paginate(10);
        $ideas->appends($request->query());
        return view('back.pages.eco-ideas', compact('ideas'));
    }

    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'image' => 'nullable|image|max:2048',
            'ai_generated_suggestion' => 'nullable|string',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'upvotes' => 'nullable|integer',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $idea = new EcoIdea();
        $idea->user_id = $data['user_id'];
        $idea->title = $data['title'];
        $idea->description = $data['description'];
        $idea->waste_type = $data['waste_type'];
        $idea->ai_generated_suggestion = $data['ai_generated_suggestion'] ?? null;
        $idea->difficulty_level = $data['difficulty_level'];
        $idea->upvotes = $data['upvotes'] ?? 0;
        $idea->status = $data['status'];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('eco_ideas', 'public');
            $idea->image_path = $imagePath;
            \Log::info('Eco idea image stored at: ' . $imagePath);
        }

        $idea->save();
        return redirect()->route('admin.eco-ideas')->with('success', 'Eco idea created successfully!');
    }

    public function adminGetData($id)
    {
        $idea = EcoIdea::with('user')->findOrFail($id);
        return response()->json($idea);
    }

    public function adminUpdate(Request $request, $id)
    {
        $idea = EcoIdea::findOrFail($id);
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'image' => 'nullable|image|max:2048',
            'ai_generated_suggestion' => 'nullable|string',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'upvotes' => 'nullable|integer',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Update basic fields
        $idea->user_id = $data['user_id'];
        $idea->title = $data['title'];
        $idea->description = $data['description'];
        $idea->waste_type = $data['waste_type'];
        $idea->ai_generated_suggestion = $data['ai_generated_suggestion'] ?? null;
        $idea->difficulty_level = $data['difficulty_level'];
        $idea->upvotes = $data['upvotes'] ?? 0;
        $idea->status = $data['status'];

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
        $idea->delete();
        return redirect()->route('admin.eco-ideas')->with('delete_success', 'Eco idea deleted successfully!');
    }
}


