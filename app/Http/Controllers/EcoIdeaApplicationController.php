<?php

namespace App\Http\Controllers;

use App\Models\EcoIdeaApplication;
use Illuminate\Http\Request;

class EcoIdeaApplicationController extends Controller
{
    public function index()
    {
        return response()->json(EcoIdeaApplication::with(['ecoIdea', 'user'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'eco_idea_id' => 'required|exists:eco_ideas,id',
            'user_id' => 'required|exists:users,id',
            'application_message' => 'required|string|min:10|max:1000',
            'resume_path' => 'required|string',
            'status' => 'nullable|in:pending,accepted,rejected',
        ]);

        // Check if user already applied to this idea
        $existingApplication = EcoIdeaApplication::where('eco_idea_id', $data['eco_idea_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existingApplication) {
            return response()->json(['error' => 'You have already applied to this idea'], 422);
        }

        $application = EcoIdeaApplication::create($data);
        return response()->json($application->load(['ecoIdea', 'user']), 201);
    }

    public function show(EcoIdeaApplication $ecoIdeaApplication)
    {
        return response()->json($ecoIdeaApplication->load(['ecoIdea', 'user']));
    }

    public function update(Request $request, EcoIdeaApplication $ecoIdeaApplication)
    {
        $data = $request->validate([
            'application_message' => 'sometimes|required|string|min:10|max:1000',
            'resume_path' => 'sometimes|required|string',
            'status' => 'sometimes|in:pending,accepted,rejected',
        ]);

        $ecoIdeaApplication->update($data);
        return response()->json($ecoIdeaApplication->load(['ecoIdea', 'user']));
    }

    public function destroy(EcoIdeaApplication $ecoIdeaApplication)
    {
        $ecoIdeaApplication->delete();
        return response()->json(['message' => 'Application deleted successfully']);
    }
}