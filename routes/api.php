<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('eco-ideas', App\Http\Controllers\EcoIdeaController::class);
Route::apiResource('eco-projects', App\Http\Controllers\EcoProjectController::class);






