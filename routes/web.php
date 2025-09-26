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

use App\Http\Middleware\EnsureUserIsAdmin;

// Admin auth routes (publicly accessible)
Route::get('admin/sign-in', function () {
    return view('back.pages.sign-in');
})->name('admin.sign-in');

// handle sign-in form submission (public)
Route::post('admin/sign-in', [\App\controller\UserController::class, 'login'])->name('admin.sign-in.post');

// front sign-in (public) - users and admins can sign in via the front page
Route::post('/login', [\App\controller\UserController::class, 'login'])->name('front.sign-in.post');

// front signup for public users (used by front login page)
Route::post('/signup', [\App\controller\UserController::class, 'store'])->name('front.signup.post');

// front logout route (for logged in users)
Route::post('/logout', [\App\controller\UserController::class, 'logout'])->name('front.logout');

// Email verification link
Route::get('/verify-email', [\App\controller\UserController::class, 'verifyEmail'])->name('front.verify-email');

// DEBUG route: send a test verification email to TEST_MAIL_TO (REMOVE in production)
Route::get('/debug-send-mail', function () {
    $to = env('TEST_MAIL_TO');
    if (! $to) {
        return response()->json(['ok' => false, 'error' => 'TEST_MAIL_TO not set in .env'], 400);
    }

    $user = App\Models\User::first();
    if (! $user) {
        return response()->json(['ok' => false, 'error' => 'No users found to use in test'], 400);
    }

    $verifyUrl = url('/verify-email?token=testtoken&email=' . urlencode($user->email));

    try {
        \Illuminate\Support\Facades\Mail::to($to)->send(new App\Mail\VerifyEmail($user, $verifyUrl));
        return response()->json(['ok' => true, 'message' => 'Email sent to ' . $to]);
    } catch (\Throwable $e) {
        return response()->json(['ok' => false, 'error' => $e->getMessage()]);
    }
});

// Protected admin pages (require authentication + admin role)
Route::prefix('admin')->name('admin.')->middleware([EnsureUserIsAdmin::class])->group(function () {
    Route::get('/', function () {
        return view('back.dashboard');
    })->name('dashboard');
    
    Route::get('/tables', function () {
        return view('back.pages.tables');
    })->name('tables');
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/data', [\App\Http\Controllers\AdminController::class, 'getUserData'])->name('users.data');
    Route::put('/users/{id}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
    
    Route::get('/billing', function () {
        return view('back.pages.billing');
    })->name('billing');
    
    Route::get('/virtual-reality', function () {
        return view('back.pages.virtual-reality');
    })->name('virtual-reality');
    
    Route::get('/rtl', function () {
        return view('back.pages.rtl');
    })->name('rtl');
    
    Route::get('/notifications', function () {
        return view('back.pages.notifications');
    })->name('notifications');
    
    Route::get('/profile', function () {
        return view('back.pages.profile');
    })->name('profile');
    
    Route::get('/icons', function () {
        return view('back.pages.icons');
    })->name('icons');
    
    Route::get('/typography', function () {
        return view('back.pages.typography');
    })->name('typography');
    
    Route::get('/map', function () {
        return view('back.pages.map');
    })->name('map');
    
    Route::get('/landing', function () {
        return redirect('/');
    })->name('landing');
    
    // Logout route
    Route::post('/logout', [\App\controller\UserController::class, 'logout'])->name('logout');
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

// Login page
Route::get('/login', function () {
    return view('front.login');
})->name('front.login');

// Fallback 404 for unknown routes
Route::fallback(function () {
    return response()->view('front.pages.404', [], 404);
});
