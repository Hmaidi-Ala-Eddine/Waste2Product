<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $skills = [
            [
                'name' => 'engineering',
                'description' => 'Technical design and construction skills for building physical solutions',
                'keywords' => ['build', 'construct', 'engineer', 'technical', 'mechanical', 'electrical', 'design', 'prototype'],
                'related_waste_types' => ['plastic', 'metal', 'e-waste', 'mixed'],
                'difficulty_levels' => ['medium', 'hard'],
                'priority_score' => 5
            ],
            [
                'name' => 'design',
                'description' => 'Creative design and aesthetic skills for visual and user experience',
                'keywords' => ['design', 'creative', 'aesthetic', 'visual', 'artistic', 'styling', 'ui', 'ux'],
                'related_waste_types' => ['plastic', 'textile', 'paper', 'glass'],
                'difficulty_levels' => ['easy', 'medium'],
                'priority_score' => 4
            ],
            [
                'name' => 'ai_specialist',
                'description' => 'Artificial Intelligence and machine learning expertise',
                'keywords' => ['ai', 'machine learning', 'algorithm', 'data', 'intelligent', 'smart', 'automation'],
                'related_waste_types' => ['e-waste', 'mixed'],
                'difficulty_levels' => ['hard'],
                'priority_score' => 5
            ],
            [
                'name' => 'gardening',
                'description' => 'Plant care and gardening expertise for organic waste solutions',
                'keywords' => ['garden', 'plant', 'soil', 'grow', 'organic', 'compost', 'agriculture', 'horticulture'],
                'related_waste_types' => ['organic', 'plastic'],
                'difficulty_levels' => ['easy', 'medium'],
                'priority_score' => 3
            ],
            [
                'name' => 'marketing',
                'description' => 'Promotion and communication skills for project outreach',
                'keywords' => ['promote', 'market', 'social media', 'communication', 'outreach', 'branding'],
                'related_waste_types' => ['mixed'],
                'difficulty_levels' => ['easy', 'medium'],
                'priority_score' => 2
            ],
            [
                'name' => 'programming',
                'description' => 'Software development and coding skills for digital solutions',
                'keywords' => ['code', 'programming', 'software', 'development', 'web', 'app', 'database'],
                'related_waste_types' => ['e-waste', 'mixed'],
                'difficulty_levels' => ['medium', 'hard'],
                'priority_score' => 4
            ],
            [
                'name' => 'research',
                'description' => 'Research and analysis skills for data collection and validation',
                'keywords' => ['research', 'analysis', 'data', 'study', 'investigation', 'validation'],
                'related_waste_types' => ['mixed'],
                'difficulty_levels' => ['easy', 'medium'],
                'priority_score' => 3
            ],
            [
                'name' => 'project_management',
                'description' => 'Project coordination and team management skills',
                'keywords' => ['manage', 'coordinate', 'lead', 'organize', 'plan', 'schedule', 'team'],
                'related_waste_types' => ['mixed'],
                'difficulty_levels' => ['medium', 'hard'],
                'priority_score' => 4
            ]
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}