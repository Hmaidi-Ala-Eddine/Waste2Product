<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        return response()->json(Skill::latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
            'description' => 'required|string',
            'keywords' => 'required|array',
            'keywords.*' => 'string',
            'related_waste_types' => 'required|array',
            'related_waste_types.*' => 'in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'difficulty_levels' => 'required|array',
            'difficulty_levels.*' => 'in:easy,medium,hard',
            'priority_score' => 'required|integer|min:1|max:5',
        ]);

        $skill = Skill::create($data);
        return response()->json($skill, 201);
    }

    public function show(Skill $skill)
    {
        return response()->json($skill);
    }

    public function update(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:skills,name,' . $skill->id,
            'description' => 'sometimes|required|string',
            'keywords' => 'sometimes|required|array',
            'keywords.*' => 'string',
            'related_waste_types' => 'sometimes|required|array',
            'related_waste_types.*' => 'in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'difficulty_levels' => 'sometimes|required|array',
            'difficulty_levels.*' => 'in:easy,medium,hard',
            'priority_score' => 'sometimes|required|integer|min:1|max:5',
        ]);

        $skill->update($data);
        return response()->json($skill);
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();
        return response()->json(['message' => 'Skill deleted successfully']);
    }
}