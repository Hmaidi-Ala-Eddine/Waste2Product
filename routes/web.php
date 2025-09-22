<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front.home');
})->name('front.home');

Route::get('/onepage', function () {
    return view('front.home-onepage');
})->name('front.onepage');

// Demo home variants
Route::view('/home-3', 'front.home-3')->name('front.home3');
Route::view('/home-3-op', 'front.home-3-onepage')->name('front.home3.onepage');
Route::view('/home-4', 'front.home-4')->name('front.home4');
Route::view('/home-4-op', 'front.home-4-onepage')->name('front.home4.onepage');
Route::view('/home-5', 'front.home-5')->name('front.home5');
Route::view('/home-5-op', 'front.home-5-onepage')->name('front.home5.onepage');
Route::view('/home-6', 'front.home-6')->name('front.home6');
Route::view('/home-6-op', 'front.home-6-onepage')->name('front.home6.onepage');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('back.dashboard');
    })->name('dashboard');
});

// Front pages
Route::view('/about-us', 'front.pages.about')->name('front.about');
Route::view('/about-us-2', 'front.pages.about-2')->name('front.about2');
Route::view('/services', 'front.pages.services')->name('front.services');
Route::view('/services-2', 'front.pages.services-2')->name('front.services2');
Route::view('/services-3', 'front.pages.services-3')->name('front.services3');
Route::view('/services-details', 'front.pages.services-details')->name('front.services.details');
Route::view('/services-details-2', 'front.pages.services-details-2')->name('front.services.details2');
Route::view('/team', 'front.pages.team')->name('front.team');
Route::view('/team-2', 'front.pages.team-2')->name('front.team2');
Route::view('/team-details', 'front.pages.team-details')->name('front.team.details');
Route::view('/project', 'front.pages.project')->name('front.project');
Route::view('/project-2', 'front.pages.project-2')->name('front.project2');
Route::view('/project-3', 'front.pages.project-3')->name('front.project3');
Route::view('/project-details', 'front.pages.project-details')->name('front.project.details');
Route::view('/blog-standard', 'front.pages.blog-standard')->name('front.blog.standard');
Route::view('/blog-with-sidebar', 'front.pages.blog-with-sidebar')->name('front.blog.with_sidebar');
Route::view('/blog-2-colum', 'front.pages.blog-2-colum')->name('front.blog.2col');
Route::view('/blog-3-colum', 'front.pages.blog-3-colum')->name('front.blog.3col');
Route::view('/blog-single', 'front.pages.blog-single')->name('front.blog.single');
Route::view('/blog-single-with-sidebar', 'front.pages.blog-single-with-sidebar')->name('front.blog.single_with_sidebar');
Route::view('/contact-us', 'front.pages.contact-us')->name('front.contact');
Route::view('/faq', 'front.pages.faq')->name('front.faq');
Route::view('/pricing', 'front.pages.pricing')->name('front.pricing');
Route::view('/404', 'front.pages.404')->name('front.404');

// Fallback 404 for unknown routes
Route::fallback(function () {
    return response()->view('front.pages.404', [], 404);
});
