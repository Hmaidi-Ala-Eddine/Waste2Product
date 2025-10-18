<?php

namespace App\Http\Controllers;

use App\Models\EcoIdeaCertificate;
use Illuminate\Http\Request;

class EcoIdeaCertificateController extends Controller
{
    public function index()
    {
        return response()->json(EcoIdeaCertificate::with(['ecoIdea', 'user'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'eco_idea_id' => 'required|exists:eco_ideas,id',
            'user_id' => 'required|exists:users,id',
            'certificate_id' => 'required|string|unique:eco_idea_certificates,certificate_id',
            'role_in_project' => 'required|string|max:255',
            'contribution_summary' => 'required|string',
            'skills_demonstrated' => 'required|array',
            'skills_demonstrated.*' => 'string',
            'certificate_file_path' => 'required|string',
            'verification_url' => 'required|string|url',
        ]);

        $certificate = EcoIdeaCertificate::create($data);
        return response()->json($certificate->load(['ecoIdea', 'user']), 201);
    }

    public function show(EcoIdeaCertificate $ecoIdeaCertificate)
    {
        return response()->json($ecoIdeaCertificate->load(['ecoIdea', 'user']));
    }

    public function update(Request $request, EcoIdeaCertificate $ecoIdeaCertificate)
    {
        $data = $request->validate([
            'role_in_project' => 'sometimes|required|string|max:255',
            'contribution_summary' => 'sometimes|required|string',
            'skills_demonstrated' => 'sometimes|required|array',
            'skills_demonstrated.*' => 'string',
            'certificate_file_path' => 'sometimes|required|string',
            'verification_url' => 'sometimes|required|string|url',
        ]);

        $ecoIdeaCertificate->update($data);
        return response()->json($ecoIdeaCertificate->load(['ecoIdea', 'user']));
    }

    public function destroy(EcoIdeaCertificate $ecoIdeaCertificate)
    {
        $ecoIdeaCertificate->delete();
        return response()->json(['message' => 'Certificate deleted successfully']);
    }
}