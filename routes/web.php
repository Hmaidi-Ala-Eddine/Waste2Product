<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('front.home');
})->name('front.home');

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
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
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
    
    // Collectors Management routes
    Route::get('/collectors', [\App\Http\Controllers\CollectorController::class, 'index'])->name('collectors');
    Route::post('/collectors', [\App\Http\Controllers\CollectorController::class, 'store'])->name('collectors.store');
    Route::get('/collectors/users', [\App\Http\Controllers\CollectorController::class, 'getUsers'])->name('collectors.users');
    Route::get('/collectors/{id}/data', [\App\Http\Controllers\CollectorController::class, 'getData'])->name('collectors.data');
    Route::put('/collectors/{id}', [\App\Http\Controllers\CollectorController::class, 'update'])->name('collectors.update');
    Route::delete('/collectors/{id}', [\App\Http\Controllers\CollectorController::class, 'destroy'])->name('collectors.delete');
    Route::post('/collectors/{id}/update-status', [\App\Http\Controllers\CollectorController::class, 'updateStatus'])->name('collectors.update-status');
    
    // Events Management routes
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events');
    Route::post('/events', [\App\Http\Controllers\EventController::class, 'store'])->name('events.store');
    Route::get('/events/authors', [\App\Http\Controllers\EventController::class, 'getAuthors'])->name('events.authors');
    Route::get('/events/{id}/data', [\App\Http\Controllers\EventController::class, 'getData'])->name('events.data');
    Route::put('/events/{id}', [\App\Http\Controllers\EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [\App\Http\Controllers\EventController::class, 'destroy'])->name('events.delete');
    
    // Eco Ideas Management routes
    Route::get('/eco-ideas', [\App\Http\Controllers\EcoIdeaController::class, 'index'])->name('eco-ideas');
    Route::post('/eco-ideas', [\App\Http\Controllers\EcoIdeaController::class, 'store'])->name('eco-ideas.store');
    Route::get('/eco-ideas/creators', [\App\Http\Controllers\EcoIdeaController::class, 'getCreators'])->name('eco-ideas.creators');
    Route::get('/eco-ideas/{id}/data', [\App\Http\Controllers\EcoIdeaController::class, 'getData'])->name('eco-ideas.data');
    Route::put('/eco-ideas/{id}', [\App\Http\Controllers\EcoIdeaController::class, 'update'])->name('eco-ideas.update');
    Route::delete('/eco-ideas/{id}', [\App\Http\Controllers\EcoIdeaController::class, 'destroy'])->name('eco-ideas.delete');
    
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
    Route::get('/eco-ideas/{id}/team', [\App\Http\Controllers\EcoIdeaController::class, 'getTeamData'])->name('eco-ideas.team');
    Route::put('/eco-ideas/{id}', [\App\Http\Controllers\EcoIdeaController::class, 'adminUpdate'])->name('eco-ideas.update');
    Route::put('/eco-ideas/{id}/verify', [\App\Http\Controllers\EcoIdeaController::class, 'verifyProject'])->name('eco-ideas.verify');
    Route::delete('/eco-ideas/{id}', [\App\Http\Controllers\EcoIdeaController::class, 'adminDestroy'])->name('eco-ideas.delete');
    
    // Team Management routes
    Route::delete('/eco-idea-teams/{id}', [\App\Http\Controllers\EcoIdeaTeamController::class, 'destroy'])->name('eco-idea-teams.delete');
    Route::post('/eco-idea-applications/{id}/accept', [\App\Http\Controllers\EcoIdeaApplicationController::class, 'accept'])->name('eco-idea-applications.accept');
    Route::post('/eco-idea-applications/{id}/reject', [\App\Http\Controllers\EcoIdeaApplicationController::class, 'reject'])->name('eco-idea-applications.reject');
    
    // Events Management routes
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events');
    Route::post('/events', [\App\Http\Controllers\EventController::class, 'store'])->name('events.store');
    Route::get('/events/users', [\App\Http\Controllers\EventController::class, 'getUsers'])->name('events.users');
    Route::get('/events/{event}/data', [\App\Http\Controllers\EventController::class, 'getData'])->name('events.data');
    Route::put('/events/{event}', [\App\Http\Controllers\EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [\App\Http\Controllers\EventController::class, 'destroy'])->name('events.delete');
    
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
    
    Route::get('/profile', function () {
        $user = auth()->user();
        return view('back.pages.profile', compact('user'));
    })->name('profile');
    
    Route::post('/profile/upload-picture', [\App\Http\Controllers\AdminController::class, 'uploadProfilePicture'])->name('profile.upload-picture');
    
    // Map Routes
    Route::get('/map', [\App\Http\Controllers\MapController::class, 'index'])->name('map');
    Route::get('/map/waste-requests', [\App\Http\Controllers\MapController::class, 'getWasteRequests'])->name('map.waste-requests');
    Route::get('/map/collectors', [\App\Http\Controllers\MapController::class, 'getCollectors'])->name('map.collectors');
    Route::get('/map/statistics', [\App\Http\Controllers\MapController::class, 'getStatistics'])->name('map.statistics');
    
    // Logout route
    Route::post('/logout', [\App\controller\UserController::class, 'logout'])->name('logout');
});

// Main pages routes
Route::view('/about', 'front.pages.about')->name('front.about');
Route::view('/contact', 'front.pages.contact-us')->name('front.contact');
Route::view('/404', 'front.pages.404')->name('front.404');

// Main project page
Route::view('/projects', 'front.pages.project')->name('front.project');

// Main services page
Route::view('/services', 'front.pages.services')->name('front.services');

// Main blog page
Route::view('/blog', 'front.pages.blog-standard')->name('front.blog.standard');

// Login page
Route::get('/login', function () {
    return view('front.login');
})->name('front.login');

// Frontend Posts Routes - Public viewing, Auth required for interactions
Route::get('/posts', [\App\Http\Controllers\PostController::class, 'frontendIndex'])->name('front.posts');

// Posts interaction routes (Authenticated Users Only)
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/like', [\App\Http\Controllers\PostController::class, 'like'])->name('front.posts.like');
    Route::get('/posts/{post}/comments', [\App\Http\Controllers\PostController::class, 'getPostWithComments'])->name('front.posts.comments');
    Route::post('/posts/{post}/comments', [\App\Http\Controllers\PostController::class, 'addComment'])->name('front.posts.add-comment');
});

// Frontend Events Routes - Public viewing, Auth required for interactions
Route::get('/events', [\App\Http\Controllers\EventController::class, 'frontendIndex'])->name('front.events');

// Events interaction routes (Authenticated Users Only)
Route::middleware('auth')->group(function () {
    Route::post('/events/{id}/participate', [\App\Http\Controllers\EventController::class, 'participate'])->name('front.events.participate');
    Route::post('/events', [\App\Http\Controllers\EventController::class, 'frontendStore'])->name('front.events.store'); // Admin only, will check in controller
});

// Frontend Shop Routes - Public viewing
Route::get('/shop', [\App\Http\Controllers\ShopController::class, 'index'])->name('front.shop');
Route::get('/shop/{id}', [\App\Http\Controllers\ShopController::class, 'show'])->name('front.shop.show');

// Product Reserve & My Orders (Authenticated)
Route::middleware('auth')->group(function () {
    Route::post('/products/{id}/reserve', [\App\Http\Controllers\ShopController::class, 'reserve'])->name('front.products.reserve');
    Route::get('/my-orders', [\App\Http\Controllers\ShopController::class, 'myOrders'])->name('front.my-orders');
});

    // Product Status Management Routes
    Route::post('/products/{product}/mark-sold', [\App\Http\Controllers\ProductStatusController::class, 'markAsSold'])->name('front.products.mark-sold');
    Route::post('/products/{product}/mark-available', [\App\Http\Controllers\ProductStatusController::class, 'markAsAvailable'])->name('front.products.mark-available');
    Route::get('/products/{product}/status-history', [\App\Http\Controllers\ProductStatusController::class, 'getStatusHistory'])->name('front.products.status-history');

// Product-Order-Cart Jointures Routes
Route::get('/api/products-orders-cart', [\App\Http\Controllers\ProductOrderCartController::class, 'productsWithOrdersAndCart'])->name('api.products-orders-cart');
Route::get('/api/products-in-cart-not-ordered', [\App\Http\Controllers\ProductOrderCartController::class, 'productsInCartNotOrdered'])->name('api.products-in-cart-not-ordered');
Route::get('/api/products-ordered-but-in-cart', [\App\Http\Controllers\ProductOrderCartController::class, 'productsOrderedButInCart'])->name('api.products-ordered-but-in-cart');
Route::get('/api/cart-items-to-orders', [\App\Http\Controllers\ProductOrderCartController::class, 'cartItemsToOrders'])->name('api.cart-items-to-orders');
Route::get('/api/analytics', [\App\Http\Controllers\ProductOrderCartController::class, 'analytics'])->name('api.analytics');
Route::get('/api/products-pending-actions', [\App\Http\Controllers\ProductOrderCartController::class, 'productsWithPendingActions'])->name('api.products-pending-actions');
Route::get('/api/user-cart-orders', [\App\Http\Controllers\ProductOrderCartController::class, 'userCartAndOrders'])->name('api.user-cart-orders');


// Payment Routes
Route::get('/payment/{order}', [\App\Http\Controllers\PaymentController::class, 'show'])->name('front.payment.show');
Route::post('/payment/{order}/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('front.payment.process');
Route::get('/payment/{order}/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('front.payment.success');
Route::get('/payment/{order}/failure', [\App\Http\Controllers\PaymentController::class, 'failure'])->name('front.payment.failure');

// Frontend Products Routes (Public)
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'frontendIndex'])->name('front.products');
Route::get('/products/{product}/data', [\App\Http\Controllers\ProductController::class, 'getData'])->name('front.products.data');
Route::post('/products/{product}/contact', [\App\Http\Controllers\ProductController::class, 'contact'])->name('front.products.contact');
Route::post('/products/{product}/reserve', [\App\Http\Controllers\ProductController::class, 'reserve'])->name('front.products.reserve');
Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('front.products.show');

// Product Reservation Routes
Route::post('/products/{product}/reservation', [\App\Http\Controllers\ProductReservationController::class, 'store'])->name('front.products.reservation');
Route::post('/products/{product}/make-available', [\App\Http\Controllers\ProductReservationController::class, 'makeAvailable'])->name('front.products.make-available');

// Product-Order Jointure Routes (API)
Route::prefix('api')->group(function () {
    Route::get('/products-with-orders', [\App\Http\Controllers\ProductOrderController::class, 'productsWithOrderStats'])->name('api.products.with-orders');
    Route::get('/best-selling-products', [\App\Http\Controllers\ProductOrderController::class, 'bestSellingProducts'])->name('api.products.best-selling');
    Route::get('/highest-revenue-products', [\App\Http\Controllers\ProductOrderController::class, 'highestRevenueProducts'])->name('api.products.highest-revenue');
    Route::get('/products-pending-orders', [\App\Http\Controllers\ProductOrderController::class, 'productsWithPendingOrders'])->name('api.products.pending-orders');
    Route::get('/products-without-orders', [\App\Http\Controllers\ProductOrderController::class, 'productsWithoutOrders'])->name('api.products.without-orders');
    Route::get('/products/{product}/orders', [\App\Http\Controllers\ProductOrderController::class, 'productWithOrders'])->name('api.products.orders');
    Route::get('/sales-analytics', [\App\Http\Controllers\ProductOrderController::class, 'salesAnalytics'])->name('api.sales.analytics');
    Route::get('/complex-join-example', [\App\Http\Controllers\ProductOrderController::class, 'complexJoinExample'])->name('api.join.example');
    Route::get('/admin-dashboard-data', [\App\Http\Controllers\ProductOrderController::class, 'adminDashboardData'])->name('api.admin.dashboard');
});

// Eco entities CRUD kept here as requested, but exclude 'web' middleware to avoid CSRF in Postman
Route::prefix('api')
    ->withoutMiddleware('web')
    ->group(function () {
        Route::apiResource('eco-ideas', App\Http\Controllers\EcoIdeaController::class);
    });
// Frontend Waste Requests Routes (Authenticated Users Only)
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        $user = auth()->user();
        return view('front.pages.profile', compact('user'));
    })->name('front.profile');
    
    Route::put('/profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('front.profile.update');
    
    Route::get('/waste-requests', [\App\Http\Controllers\WasteRequestController::class, 'frontendIndex'])->name('front.waste-requests');
    Route::post('/waste-requests', [\App\Http\Controllers\WasteRequestController::class, 'frontendStore'])->name('front.waste-requests.store');
    Route::post('/waste-requests/{id}/rate', [\App\Http\Controllers\WasteRequestController::class, 'submitRating'])->name('front.waste-requests.rate');
    
    // Frontend Collector Application Routes
    Route::get('/collector-application', [\App\Http\Controllers\CollectorController::class, 'frontendIndex'])->name('front.collector-application');
    Route::post('/collector-application', [\App\Http\Controllers\CollectorController::class, 'frontendStore'])->name('front.collector-application.store');
    Route::put('/collector-application', [\App\Http\Controllers\CollectorController::class, 'frontendUpdate'])->name('front.collector-application.update');
    
    // Frontend Collector Dashboard Routes (for verified collectors only)
    Route::get('/collector-dashboard', [\App\Http\Controllers\CollectorController::class, 'collectorDashboard'])->name('front.collector-dashboard');
    Route::post('/collector/accept-request/{id}', [\App\Http\Controllers\CollectorController::class, 'acceptRequest'])->name('front.collector.accept-request');
    Route::post('/collector/complete-collection/{id}', [\App\Http\Controllers\CollectorController::class, 'completeCollection'])->name('front.collector.complete-collection');
    
    // Shopping Cart Routes (Authenticated Users Only)
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('front.cart');
    Route::post('/cart/add/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('front.cart.add');
    Route::put('/cart/update/{id}', [\App\Http\Controllers\CartController::class, 'update'])->name('front.cart.update');
    Route::delete('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('front.cart.remove');
    Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('front.cart.clear');
    
    // Checkout Routes (Authenticated Users Only)
    Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('front.checkout');
    Route::post('/checkout', [\App\Http\Controllers\CartController::class, 'processCheckout'])->name('front.checkout.process');
    Route::get('/order/success', [\App\Http\Controllers\CartController::class, 'orderSuccess'])->name('front.order.success');
});

// Fallback 404 for unknown routes
Route::fallback(function () {
    return response()->view('front.pages.404', [], 404);
});
