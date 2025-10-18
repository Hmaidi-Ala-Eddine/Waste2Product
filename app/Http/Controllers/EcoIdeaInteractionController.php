<?php

namespace App\Http\Controllers;

use App\Models\EcoIdeaInteraction;
use Illuminate\Http\Request;

class EcoIdeaInteractionController extends Controller
{
    public function index()
    {
        return response()->json(EcoIdeaInteraction::with(['ecoIdea', 'user'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'eco_idea_id' => 'required|exists:eco_ideas,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:comment,upvote,downvote',
            'content' => 'required_if:type,comment|nullable|string|max:1000',
        ]);

        // For votes, check if user already voted
        if (in_array($data['type'], ['upvote', 'downvote'])) {
            $existingVote = EcoIdeaInteraction::where('eco_idea_id', $data['eco_idea_id'])
                ->where('user_id', $data['user_id'])
                ->whereIn('type', ['upvote', 'downvote'])
                ->first();

            if ($existingVote) {
                // Update existing vote
                $existingVote->update(['type' => $data['type']]);
                return response()->json($existingVote->load(['ecoIdea', 'user']));
            }
        }

        $interaction = EcoIdeaInteraction::create($data);
        return response()->json($interaction->load(['ecoIdea', 'user']), 201);
    }

    public function show(EcoIdeaInteraction $ecoIdeaInteraction)
    {
        return response()->json($ecoIdeaInteraction->load(['ecoIdea', 'user']));
    }

    public function update(Request $request, EcoIdeaInteraction $ecoIdeaInteraction)
    {
        $data = $request->validate([
            'type' => 'sometimes|in:comment,upvote,downvote',
            'content' => 'required_if:type,comment|sometimes|nullable|string|max:1000',
        ]);

        $ecoIdeaInteraction->update($data);
        return response()->json($ecoIdeaInteraction->load(['ecoIdea', 'user']));
    }

    public function destroy(EcoIdeaInteraction $ecoIdeaInteraction)
    {
        $ecoIdeaInteraction->delete();
        return response()->json(['message' => 'Interaction deleted successfully']);
    }
}