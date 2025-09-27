<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
use Illuminate\Http\Request;

// GET /login to get CSRF
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

$token = null;
if (preg_match('/name="_token" value="([^"]+)"/', $content, $m)) {
    $token = $m[1];
}

$post = Request::create('/signup', 'POST', [
    '_token' => $token,
    'from' => 'front',
    'name' => 'Front Test User',
    'email' => 'fronttest+' . rand(1000,9999) . '@example.test',
    'password' => 'password',
    'password_confirmation' => 'password'
], [], [], ['HTTP_COOKIE' => "laravel_session=$sessionCookie"]);

$response2 = $kernel->handle($post);

echo "POST sign-up status: " . $response2->getStatusCode() . PHP_EOL;
echo "Location: " . $response2->headers->get('Location') . PHP_EOL;

$kernel->terminate($post, $response2);
