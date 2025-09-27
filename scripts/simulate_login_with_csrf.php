<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
use Illuminate\Http\Request;

// Step 1: GET the sign-in page to receive cookies and CSRF token
$get = Request::create('/admin/sign-in', 'GET');
$response = $kernel->handle($get);
$content = $response->getContent();
$headers = $response->headers->allPreserveCase();

// extract laravel_session cookie
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
if ($cookieHeader) {
    // cookie format: laravel_session=xxx; path=/; httponly
    if (preg_match('/laravel_session=([^;]+)/', $cookieHeader, $m)) {
        $sessionCookie = $m[1];
    }
}

// extract CSRF token from the page (hidden input named _token)
$token = null;
if (preg_match('/name="_token" value="([^"]+)"/', $content, $m)) {
    $token = $m[1];
}

echo "Got session cookie: ".($sessionCookie ? $sessionCookie : 'NONE')."\n";
echo "Got csrf token: ".($token ? $token : 'NONE')."\n";

// Step 2: POST sign-in with the token and cookie
$postData = [
    '_token' => $token,
    'email' => 'ahmed6@gmail.com',
    'password' => 'password',
    'remember' => '0',
];

$post = Request::create('/admin/sign-in', 'POST', $postData, [], [], ['HTTP_COOKIE' => "laravel_session=$sessionCookie"]);
$response2 = $kernel->handle($post);

echo "POST Status: " . $response2->getStatusCode() . "\n";
echo "POST Headers:\n";
foreach ($response2->headers->allPreserveCase() as $k => $v) {
    echo $k . ': ' . implode('; ', $v) . "\n";
}

echo "POST Body: \n" . $response2->getContent() . "\n";

$kernel->terminate($post, $response2);
