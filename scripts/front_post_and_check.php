<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
use Illuminate\Http\Request;

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

$post = Request::create('/admin/sign-in', 'POST', [
    '_token' => $token,
    'email' => 'ahmed6@gmail.com',
    'password' => 'password',
], [], [], ['HTTP_COOKIE' => "laravel_session=$sessionCookie"]);
$response2 = $kernel->handle($post);

echo "POST status: " . $response2->getStatusCode() . PHP_EOL;
echo "POST Location: " . $response2->headers->get('Location') . PHP_EOL;

// Now try to GET admin dashboard with same cookie
$get2 = Request::create('/admin', 'GET');
$get2->headers->set('Cookie', "laravel_session=$sessionCookie");
$response3 = $kernel->handle($get2);

echo "GET /admin status: " . $response3->getStatusCode() . PHP_EOL;
// print a small part of the body
$body = $response3->getContent();
echo "GET /admin content snippet: " . substr($body,0,200) . PHP_EOL;

$kernel->terminate($post, $response2);
$kernel->terminate($get2, $response3);
