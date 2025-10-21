<?php

namespace App\Http\Controllers;

use App\Models\EcoIdeaTeam;
use Illuminate\Http\Request;

class EcoIdeaTeamController extends Controller
{
    public function index()
    {
        return response()->json(EcoIdeaTeam::with(['ecoIdea', 'user'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'eco_idea_id' => 'required|exists:eco_ideas,id',
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:leader,member',
            'specialization' => 'nullable|string|max:255',
            'responsibilities' => 'nullable|string',
            'status' => 'nullable|in:active,completed,left',
        ]);

        $existingMember = EcoIdeaTeam::where('eco_idea_id', $data['eco_idea_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existingMember) {
            return response()->json(['error' => 'User is already in this team'], 422);
        }

        $teamMember = EcoIdeaTeam::create($data);
        return response()->json($teamMember->load(['ecoIdea', 'user']), 201);
    }

    public function show(EcoIdeaTeam $ecoIdeaTeam)
    {
        return response()->json($ecoIdeaTeam->load(['ecoIdea', 'user']));
    }

    public function update(Request $request, EcoIdeaTeam $ecoIdeaTeam)
    {
        $data = $request->validate([
            'role' => 'sometimes|in:leader,member',
            'specialization' => 'sometimes|nullable|string|max:255',
            'responsibilities' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:active,completed,left',
        ]);

        $ecoIdeaTeam->update($data);
        return response()->json($ecoIdeaTeam->load(['ecoIdea', 'user']));
    }

    public function destroy(EcoIdeaTeam $ecoIdeaTeam)
    {
        // Prevent removal of creators and leaders
        $idea = $ecoIdeaTeam->ecoIdea;
        
        // Check if this is the creator
        if ($ecoIdeaTeam->user_id === $idea->creator_id) {
            return response()->json(['error' => 'Creator cannot be removed from the team'], 403);
        }
        
        // Check if this is a leader
        if ($ecoIdeaTeam->role === 'leader') {
            return response()->json(['error' => 'Team leaders cannot be removed'], 403);
        }
        
        $ecoIdeaTeam->delete();
        return response()->json(['message' => 'Team member removed successfully']);
    }
}



