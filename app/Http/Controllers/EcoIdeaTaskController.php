<?php

namespace App\Http\Controllers;

use App\Models\EcoIdeaTask;
use Illuminate\Http\Request;

class EcoIdeaTaskController extends Controller
{
    public function index()
    {
        return response()->json(EcoIdeaTask::with(['ecoIdea', 'assignedUser'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'eco_idea_id' => 'required|exists:eco_ideas,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'nullable|in:todo,in_progress,done',
            'priority' => 'nullable|integer|min:1|max:5',
            'due_date' => 'nullable|date|after:today',
        ]);

        $task = EcoIdeaTask::create($data);
        return response()->json($task->load(['ecoIdea', 'assignedUser']), 201);
    }

    public function show(EcoIdeaTask $ecoIdeaTask)
    {
        return response()->json($ecoIdeaTask->load(['ecoIdea', 'assignedUser']));
    }

    public function update(Request $request, EcoIdeaTask $ecoIdeaTask)
    {
        $data = $request->validate([
            'assigned_to' => 'sometimes|required|exists:users,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|in:todo,in_progress,done',
            'priority' => 'sometimes|integer|min:1|max:5',
            'due_date' => 'sometimes|nullable|date',
        ]);

        $ecoIdeaTask->update($data);
        return response()->json($ecoIdeaTask->load(['ecoIdea', 'assignedUser']));
    }

    public function destroy(EcoIdeaTask $ecoIdeaTask)
    {
        $ecoIdeaTask->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}