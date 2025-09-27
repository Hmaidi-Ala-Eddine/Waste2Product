<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
use Illuminate\Http\Request;

// GET /login
$get = Request::create('/login', 'GET');
$response = $kernel->handle($get);
$headers = $response->headers->allPreserveCase();
$cookieHeader = '';
if (isset($headers['Set-Cookie'])) {
    foreach ($headers['Set-Cookie'] as $c) {
        if (strpos($c, 'laravel_session') !== false) { $cookieHeader = $c; break; }
    }
}
$sessionCookie = null;
if ($cookieHeader && preg_match('/laravel_session=([^;]+)/', $cookieHeader, $m)) { $sessionCookie = $m[1]; }
$token = null;
$content = $response->getContent();
if (preg_match('/name="_token" value="([^"]+)"/', $content, $m)) { $token = $m[1]; }

// POST sign-up
// use the public /signup endpoint
$post = Request::create('/signup', 'POST', [
    '_token' => $token,
    'from' => 'front',
    'name' => 'FrontAutoUser',
    'email' => 'frontauto' . rand(1000,9999) . '@example.test',
    'password' => 'password',
    'password_confirmation' => 'password'
], [], [], ['HTTP_COOKIE' => "laravel_session=$sessionCookie"]);
$response2 = $kernel->handle($post);
// get new session cookie from response2 (after redirect)
$headers2 = $response2->headers->allPreserveCase();
$newCookie = null;
if (isset($headers2['Set-Cookie'])) {
    foreach ($headers2['Set-Cookie'] as $c) {
        if (strpos($c, 'laravel_session') !== false) { $newCookie = $c; break; }
    }
}

$cookieToUse = $sessionCookie;
if ($newCookie && preg_match('/laravel_session=([^;]+)/', $newCookie, $m)) { $cookieToUse = $m[1]; }

// Now GET /login with cookieToUse
$get3 = Request::create('/login', 'GET');
$get3->headers->set('Cookie', "laravel_session=$cookieToUse");
$response3 = $kernel->handle($get3);
$body = $response3->getContent();

if (strpos($body, 'container sign-in') !== false) {
    echo "After sign-up, front login page shows sign-in panel (OK)\n";
} else {
    echo "After sign-up, front login page does NOT show sign-in panel\n";
    // save body for debugging
    file_put_contents(__DIR__.'/last_login_page.html', $body);
}

$kernel->terminate($post, $response2);
$kernel->terminate($get3, $response3);
