<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|min:2|unique:skills,name',
            'description' => 'required|string|min:10|max:500',
            'keywords' => 'required|array|min:1|max:10',
            'keywords.*' => 'required|string|max:50',
            'related_waste_types' => 'required|array|min:1',
            'related_waste_types.*' => 'required|in:organic,plastic,metal,e-waste,paper,glass,textile,mixed',
            'difficulty_levels' => 'required|array|min:1',
            'difficulty_levels.*' => 'required|in:easy,medium,hard',
            'priority_score' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Skill name is required',
            'name.min' => 'Skill name must be at least 2 characters',
            'name.max' => 'Skill name cannot exceed 255 characters',
            'name.unique' => 'This skill name already exists',
            'description.required' => 'Description is required',
            'description.min' => 'Description must be at least 10 characters',
            'description.max' => 'Description cannot exceed 500 characters',
            'keywords.required' => 'At least one keyword is required',
            'keywords.min' => 'At least one keyword is required',
            'keywords.max' => 'Maximum 10 keywords allowed',
            'keywords.*.required' => 'Each keyword is required',
            'keywords.*.max' => 'Each keyword cannot exceed 50 characters',
            'related_waste_types.required' => 'At least one waste type is required',
            'related_waste_types.min' => 'At least one waste type is required',
            'related_waste_types.*.required' => 'Each waste type is required',
            'related_waste_types.*.in' => 'Invalid waste type selected',
            'difficulty_levels.required' => 'At least one difficulty level is required',
            'difficulty_levels.min' => 'At least one difficulty level is required',
            'difficulty_levels.*.required' => 'Each difficulty level is required',
            'difficulty_levels.*.in' => 'Invalid difficulty level selected',
            'priority_score.required' => 'Priority score is required',
            'priority_score.min' => 'Priority score must be at least 1',
            'priority_score.max' => 'Priority score cannot exceed 5',
        ];
    }
}