<?php

namespace App\Http\Controllers;

use App\Models\EcoIdeaSimilarity;
use Illuminate\Http\Request;

class EcoIdeaSimilarityController extends Controller
{
    public function index()
    {
        return response()->json(EcoIdeaSimilarity::with(['originalIdea', 'similarIdea'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'original_idea_id' => 'required|exists:eco_ideas,id',
            'similar_idea_id' => 'required|exists:eco_ideas,id|different:original_idea_id',
            'similarity_percentage' => 'required|numeric|min:0|max:100',
            'similarity_details' => 'required|array',
            'warning_sent' => 'nullable|boolean',
        ]);

        // Check if similarity already exists
        $existingSimilarity = EcoIdeaSimilarity::where('original_idea_id', $data['original_idea_id'])
            ->where('similar_idea_id', $data['similar_idea_id'])
            ->first();

        if ($existingSimilarity) {
            return response()->json(['error' => 'Similarity record already exists'], 422);
        }

        $similarity = EcoIdeaSimilarity::create($data);
        return response()->json($similarity->load(['originalIdea', 'similarIdea']), 201);
    }

    public function show(EcoIdeaSimilarity $ecoIdeaSimilarity)
    {
        return response()->json($ecoIdeaSimilarity->load(['originalIdea', 'similarIdea']));
    }

    public function update(Request $request, EcoIdeaSimilarity $ecoIdeaSimilarity)
    {
        $data = $request->validate([
            'similarity_percentage' => 'sometimes|numeric|min:0|max:100',
            'similarity_details' => 'sometimes|array',
            'warning_sent' => 'sometimes|boolean',
        ]);

        $ecoIdeaSimilarity->update($data);
        return response()->json($ecoIdeaSimilarity->load(['originalIdea', 'similarIdea']));
    }

    public function destroy(EcoIdeaSimilarity $ecoIdeaSimilarity)
    {
        $ecoIdeaSimilarity->delete();
        return response()->json(['message' => 'Similarity record deleted successfully']);
    }
}