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

    public function accept($id)
    {
        $application = EcoIdeaApplication::findOrFail($id);
        
        // Check if team is full
        $idea = $application->ecoIdea;
        if ($idea->team_size_current >= $idea->team_size_needed) {
            return response()->json(['error' => 'Team is already full'], 422);
        }
        
        // Update application status
        $application->update(['status' => 'accepted']);
        
        // Add user to team
        \App\Models\EcoIdeaTeam::create([
            'eco_idea_id' => $application->eco_idea_id,
            'user_id' => $application->user_id,
            'role' => 'member',
            'specialization' => 'Team Member',
            'status' => 'active'
        ]);
        
        // Update team size
        $idea->increment('team_size_current');
        
        return response()->json(['message' => 'Application accepted and user added to team']);
    }

    public function reject($id)
    {
        $application = EcoIdeaApplication::findOrFail($id);
        $application->update(['status' => 'rejected']);
        
        return response()->json(['message' => 'Application rejected']);
    }
}