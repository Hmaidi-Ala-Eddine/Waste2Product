<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
use Illuminate\Http\Request;

$request = Request::create('/debug-send-mail', 'GET');
$response = $kernel->handle($request);
echo $response->getContent();
$kernel->terminate($request, $response);
