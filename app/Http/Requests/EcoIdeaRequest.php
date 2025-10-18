<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EcoIdeaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'creator_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255|min:5',
            'description' => 'required|string|min:20|max:2000',
            'waste_type' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'image_path' => 'nullable|string|max:500',
            'ai_generated_suggestion' => 'nullable|string|max:1000',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'upvotes' => 'nullable|integer|min:0',
            'status' => 'nullable|in:pending,approved,rejected',
            'team_requirements' => 'nullable|array',
            'team_requirements.*' => 'integer|min:0|max:10',
            'team_size_needed' => 'nullable|integer|min:1|max:20',
            'application_description' => 'nullable|string|max:1000',
            'project_status' => 'nullable|in:idea,recruiting,active,completed,verified',
        ];
    }

    public function messages()
    {
        return [
            'creator_id.required' => 'Creator ID is required',
            'creator_id.exists' => 'Creator must be a valid user',
            'title.required' => 'Title is required',
            'title.min' => 'Title must be at least 5 characters',
            'title.max' => 'Title cannot exceed 255 characters',
            'description.required' => 'Description is required',
            'description.min' => 'Description must be at least 20 characters',
            'description.max' => 'Description cannot exceed 2000 characters',
            'waste_type.required' => 'Waste type is required',
            'waste_type.in' => 'Invalid waste type selected',
            'difficulty_level.required' => 'Difficulty level is required',
            'difficulty_level.in' => 'Invalid difficulty level selected',
            'upvotes.min' => 'Upvotes cannot be negative',
            'status.in' => 'Invalid status selected',
            'team_size_needed.min' => 'Team size must be at least 1',
            'team_size_needed.max' => 'Team size cannot exceed 20',
            'project_status.in' => 'Invalid project status selected',
        ];
    }
}