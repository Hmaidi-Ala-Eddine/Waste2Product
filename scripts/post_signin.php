<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

$request = Request::create('/admin/sign-in', 'POST', [
    'email' => 'ahmed6@gmail.com',
    'password' => 'password',
]);
$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo $response->getContent() . "\n";

$kernel->terminate($request, $response);
