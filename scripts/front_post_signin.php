<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
use Illuminate\Http\Request;

// Step 1: GET the front login page to capture session & CSRF
$get = Request::create('/login', 'GET');
$response = $kernel->handle($get);
$content = $response->getContent();
$headers = $response->headers->allPreserveCase();

$cookieHeader = '';
if (isset($headers['Set-Cookie'])) {
    foreach ($headers['Set-Cookie'] as $c) {
        if (strpos($c, 'laravel_session') !== false) {
            $cookieHeader = $c;
            break;
        }
    }
}

$sessionCookie = null;
if ($cookieHeader && preg_match('/laravel_session=([^;]+)/', $cookieHeader, $m)) {
    $sessionCookie = $m[1];
}

// extract CSRF token
$token = null;
if (preg_match('/name="_token" value="([^"]+)"/', $content, $m)) {
    $token = $m[1];
}

echo "front login CSRF: " . ($token ?? 'NONE') . PHP_EOL;
echo "sessionCookie: " . ($sessionCookie ?? 'NONE') . PHP_EOL;

// Step 2: POST to front sign-in
$post = Request::create('/login', 'POST', [
    '_token' => $token,
    'from' => 'front',
    'email' => 'ahmed6@gmail.com',
    'password' => 'password',
], [], [], ['HTTP_COOKIE' => "laravel_session=$sessionCookie"]);
$response2 = $kernel->handle($post);

echo "POST status: " . $response2->getStatusCode() . PHP_EOL;
echo "Redirect location: " . $response2->headers->get('Location') . PHP_EOL;
$kernel->terminate($post, $response2);
