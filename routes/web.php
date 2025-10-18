<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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
    Route::post('/users', [\App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/users/check-email', [\App\Http\Controllers\AdminController::class, 'checkEmail'])->name('users.check-email');
    Route::get('/users/{id}/data', [\App\Http\Controllers\AdminController::class, 'getUserData'])->name('users.data');
    
    // Product Management Routes - specific routes first
    Route::get('/products/users', [\App\Http\Controllers\Admin\ProductController::class, 'getUsers'])->name('products.users');
    Route::get('/products/{product}/data', [\App\Http\Controllers\Admin\ProductController::class, 'getData'])->name('products.data');
    Route::post('products/{product}/change-status', [App\Http\Controllers\Admin\ProductController::class, 'changeStatus'])->name('products.changeStatus');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    // Orders routes (controller renders Blade index)
    Route::apiResource('orders', App\Http\Controllers\OrderController::class);
    Route::put('/users/{id}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Waste Collection & Requests routes
    Route::get('/waste-requests', [\App\Http\Controllers\WasteRequestController::class, 'index'])->name('waste-requests');
    Route::post('/waste-requests', [\App\Http\Controllers\WasteRequestController::class, 'store'])->name('waste-requests.store');
    Route::get('/waste-requests/customers', [\App\Http\Controllers\WasteRequestController::class, 'getCustomers'])->name('waste-requests.customers');
    Route::get('/waste-requests/collectors', [\App\Http\Controllers\WasteRequestController::class, 'getCollectors'])->name('waste-requests.collectors');
    Route::get('/waste-requests/{id}/data', [\App\Http\Controllers\WasteRequestController::class, 'getData'])->name('waste-requests.data');
    Route::put('/waste-requests/{id}', [\App\Http\Controllers\WasteRequestController::class, 'update'])->name('waste-requests.update');
    Route::delete('/waste-requests/{id}', [\App\Http\Controllers\WasteRequestController::class, 'destroy'])->name('waste-requests.delete');
    Route::post('/waste-requests/{id}/assign-collector', [\App\Http\Controllers\WasteRequestController::class, 'assignCollector'])->name('waste-requests.assign-collector');
    Route::post('/waste-requests/{id}/update-status', [\App\Http\Controllers\WasteRequestController::class, 'updateStatus'])->name('waste-requests.update-status');
    
    // Posts Management routes
    Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index'])->name('posts');
    Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/users', [\App\Http\Controllers\PostController::class, 'getUsers'])->name('posts.users');
    Route::get('/posts/{post}/data', [\App\Http\Controllers\PostController::class, 'getData'])->name('posts.data');
    Route::put('/posts/{post}', [\App\Http\Controllers\PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [\App\Http\Controllers\PostController::class, 'destroy'])->name('posts.delete');

    // Eco Ideas admin pages
    Route::get('/eco-ideas', [\App\Http\Controllers\EcoIdeaController::class, 'adminIndex'])->name('eco-ideas');
    Route::post('/eco-ideas', [\App\Http\Controllers\EcoIdeaController::class, 'adminStore'])->name('eco-ideas.store');
    Route::get('/eco-ideas/{id}/data', [\App\Http\Controllers\EcoIdeaController::class, 'adminGetData'])->name('eco-ideas.data');
    Route::put('/eco-ideas/{id}', [\App\Http\Controllers\EcoIdeaController::class, 'adminUpdate'])->name('eco-ideas.update');
    Route::delete('/eco-ideas/{id}', [\App\Http\Controllers\EcoIdeaController::class, 'adminDestroy'])->name('eco-ideas.delete');

    // Eco Projects admin pages
    Route::get('/eco-projects', [\App\Http\Controllers\EcoProjectController::class, 'adminIndex'])->name('eco-projects');
    Route::post('/eco-projects', [\App\Http\Controllers\EcoProjectController::class, 'adminStore'])->name('eco-projects.store');
    Route::get('/eco-projects/{id}/data', [\App\Http\Controllers\EcoProjectController::class, 'adminGetData'])->name('eco-projects.data');
    Route::put('/eco-projects/{id}', [\App\Http\Controllers\EcoProjectController::class, 'adminUpdate'])->name('eco-projects.update');
    Route::delete('/eco-projects/{id}', [\App\Http\Controllers\EcoProjectController::class, 'adminDestroy'])->name('eco-projects.delete');
    
    // Analytics Management routes
    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::post('/analytics/generate-today', [\App\Http\Controllers\AnalyticsController::class, 'generateTodayStats'])->name('analytics.generate-today');
    Route::post('/analytics/check-missing', [\App\Http\Controllers\AnalyticsController::class, 'checkMissingAnalytics'])->name('analytics.check-missing');
    Route::get('/analytics/data', [\App\Http\Controllers\AnalyticsController::class, 'getData'])->name('analytics.data');
    Route::delete('/analytics/{analytics}', [\App\Http\Controllers\AnalyticsController::class, 'destroy'])->name('analytics.destroy');
    
    // Debug route for analytics data
    Route::get('/analytics/debug', function() {
        $today = \Carbon\Carbon::today();
        
        // Get all records to see their exact created_at timestamps
        $allWasteRequests = \App\Models\WasteRequest::select('waste_type', 'quantity', 'created_at')->get();
        $allProducts = \App\Models\Product::select('name', 'category', 'created_at')->get();
        $allOrders = \App\Models\Order::select('total_price', 'status', 'created_at')->get();
        
        // Also test the date filtering
        $wasteToday = \App\Models\WasteRequest::whereDate('created_at', $today)->get();
        
        return response()->json([
            'today' => $today->format('Y-m-d H:i:s'),
            'all_waste_requests' => [
                'count' => $allWasteRequests->count(),
                'data' => $allWasteRequests->map(function($wr) {
                    return [
                        'waste_type' => $wr->waste_type,
                        'quantity' => $wr->quantity,
                        'created_at' => $wr->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ],
            'waste_today_filtered' => [
                'count' => $wasteToday->count(),
                'total_quantity' => $wasteToday->sum('quantity'),
                'individual_quantities' => $wasteToday->pluck('quantity')->toArray(),
                'data' => $wasteToday->map(function($wr) {
                    return [
                        'waste_type' => $wr->waste_type,
                        'quantity' => $wr->quantity,
                        'created_at' => $wr->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ],
            'all_products' => [
                'count' => $allProducts->count(),
                'data' => $allProducts->map(function($p) {
                    return [
                        'name' => $p->name,
                        'category' => $p->category,
                        'created_at' => $p->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ],
            'all_orders' => [
                'count' => $allOrders->count(),
                'data' => $allOrders->map(function($o) {
                    return [
                        'total_price' => $o->total_price,
                        'status' => $o->status,
                        'created_at' => $o->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ]
        ]);
    })->name('analytics.debug');
    
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
Route::get('/team', [PostController::class, 'frontendIndex'])->name('front.team');
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

// Front: My Orders list (authenticated users)
Route::get('/orders', function () {
    $orders = \App\Models\Order::with(['product:id,name,price,status'])
        ->where('user_id', auth()->id())
        ->orderByDesc('ordered_at')
        ->paginate(10);
    return view('front.pages.orders', compact('orders'));
})->middleware('auth')->name('front.orders');

// Front: Delete an order owned by the authenticated user
Route::delete('/orders/{order}', function ($orderId) {
    $order = \App\Models\Order::findOrFail($orderId);
    abort_if($order->user_id !== auth()->id(), 403);
    $order->delete();
    return back();
})->middleware('auth')->name('front.orders.destroy');

// Frontend Posts Routes (Public)
Route::get('/posts', [\App\Http\Controllers\PostController::class, 'frontendIndex'])->name('front.posts');
Route::post('/posts/{post}/like', [\App\Http\Controllers\PostController::class, 'like'])->name('front.posts.like');
Route::get('/posts/{post}/comments', [\App\Http\Controllers\PostController::class, 'getPostWithComments'])->name('front.posts.comments');
Route::post('/posts/{post}/comments', [\App\Http\Controllers\PostController::class, 'addComment'])->name('front.posts.add-comment');

// Eco entities CRUD kept here as requested, but exclude 'web' middleware to avoid CSRF in Postman
Route::prefix('api')
    ->withoutMiddleware('web')
    ->group(function () {
        Route::apiResource('eco-ideas', App\Http\Controllers\EcoIdeaController::class);
        Route::apiResource('eco-projects', App\Http\Controllers\EcoProjectController::class);
    });

// Fallback 404 for unknown routes
Route::fallback(function () {
    return response()->view('front.pages.404', [], 404);
});
