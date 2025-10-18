<?php

use Illuminate\Support\Facades\Route;

// Main EcoIdea API routes
Route::apiResource('eco-ideas', App\Http\Controllers\EcoIdeaController::class);

// Skills API routes
Route::apiResource('skills', App\Http\Controllers\SkillController::class);

// EcoIdea Applications API routes
Route::apiResource('eco-idea-applications', App\Http\Controllers\EcoIdeaApplicationController::class);

// EcoIdea Teams API routes
Route::apiResource('eco-idea-teams', App\Http\Controllers\EcoIdeaTeamController::class);

// EcoIdea Tasks API routes
Route::apiResource('eco-idea-tasks', App\Http\Controllers\EcoIdeaTaskController::class);

// EcoIdea Certificates API routes
Route::apiResource('eco-idea-certificates', App\Http\Controllers\EcoIdeaCertificateController::class);

// EcoIdea Interactions API routes
Route::apiResource('eco-idea-interactions', App\Http\Controllers\EcoIdeaInteractionController::class);

// EcoIdea Similarities API routes
Route::apiResource('eco-idea-similarities', App\Http\Controllers\EcoIdeaSimilarityController::class);






