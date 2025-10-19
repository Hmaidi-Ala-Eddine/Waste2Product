<?php

namespace App\Http\Controllers;

use App\Models\EcoIdea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EcoIdeaController extends Controller
{
    /**
     * Display a listing of eco ideas with search and filtering
     */
    public function index(Request $request)
    {
        $query = EcoIdea::with('creator');
        
        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('creator', function($creatorQuery) use ($search) {
                      $creatorQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Handle waste type filter
        if ($request->filled('waste_type') && $request->get('waste_type') !== 'all') {
            $query->where('waste_type', $request->get('waste_type'));
        }
        
        // Handle difficulty filter
        if ($request->filled('difficulty') && $request->get('difficulty') !== 'all') {
            $query->where('difficulty', $request->get('difficulty'));
        }
        
        // Handle status filter
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('status', $request->get('status'));
        }
        
        // Order and paginate results
        $ecoIdeas = $query->orderBy('created_at', 'desc')->paginate(5);
        
        // Preserve query parameters in pagination links
        $ecoIdeas->appends($request->query());
        
        return view('back.pages.eco-ideas', compact('ecoIdeas'));
    }

    /**
     * Store a newly created eco idea
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'creator_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'waste_type' => 'required|in:' . implode(',', array_keys(EcoIdea::getWasteTypes())),
            'difficulty' => 'required|in:' . implode(',', array_keys(EcoIdea::getDifficulties())),
            'description' => 'required|string|max:5000',
            'ai_suggestion' => 'nullable|string|max:5000',
            'team_size_needed' => 'nullable|integer|min:1|max:100',
            'team_requirements' => 'nullable|string|max:2000',
            'application_description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:' . implode(',', array_keys(EcoIdea::getStatuses())),
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'eco_idea_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('eco_ideas', $filename, 'public');
            $validated['image'] = $path;
        }

        // Set default status if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        EcoIdea::create($validated);

        return redirect()->route('admin.eco-ideas')->with('success', 'Eco Idea created successfully!');
    }

    /**
     * Get eco idea data for editing
     */
    public function getData($id)
    {
        $ecoIdea = EcoIdea::with('creator')->findOrFail($id);
        return response()->json($ecoIdea);
    }

    /**
     * Update the specified eco idea
     */
    public function update(Request $request, $id)
    {
        $ecoIdea = EcoIdea::findOrFail($id);
        
        $validated = $request->validate([
            'creator_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'waste_type' => 'required|in:' . implode(',', array_keys(EcoIdea::getWasteTypes())),
            'difficulty' => 'required|in:' . implode(',', array_keys(EcoIdea::getDifficulties())),
            'description' => 'required|string|max:5000',
            'ai_suggestion' => 'nullable|string|max:5000',
            'team_size_needed' => 'nullable|integer|min:1|max:100',
            'team_requirements' => 'nullable|string|max:2000',
            'application_description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:' . implode(',', array_keys(EcoIdea::getStatuses())),
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($ecoIdea->image) {
                Storage::disk('public')->delete($ecoIdea->image);
            }
            
            $image = $request->file('image');
            $filename = 'eco_idea_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('eco_ideas', $filename, 'public');
            $validated['image'] = $path;
        }

        $ecoIdea->update($validated);

        return redirect()->route('admin.eco-ideas')->with('success', 'Eco Idea updated successfully!');
    }

    /**
     * Delete the specified eco idea
     */
    public function destroy($id)
    {
        $ecoIdea = EcoIdea::findOrFail($id);
        
        // Delete image if exists
        if ($ecoIdea->image) {
            Storage::disk('public')->delete($ecoIdea->image);
        }
        
        $ecoIdea->delete();

        return redirect()->route('admin.eco-ideas')->with('delete_success', 'Eco Idea deleted successfully!');
    }

    /**
     * Get creators for dropdown
     */
    public function getCreators()
    {
        $creators = User::where('is_active', true)
                       ->select('id', 'name', 'email')
                       ->get();
        
        return response()->json($creators);
    }
}
