<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\WasteRequest;
use Illuminate\Support\Facades\DB;

echo "Creating test product for checkout test:\n";

// Get a test user
$user = User::first();
if (!$user) {
    echo "No users found\n";
    exit;
}

// Get a waste request
$wasteRequest = WasteRequest::first();
if (!$wasteRequest) {
    echo "No waste requests found\n";
    exit;
}

// Create a test product
$product = Product::create([
    'waste_request_id' => $wasteRequest->id,
    'user_id' => $user->id,
    'name' => 'Test Product for Checkout',
    'description' => 'A test product to verify checkout status change',
    'category' => 'test',
    'condition' => 'new',
    'price' => 50.00,
    'status' => 'available',
    'stock' => 2,
    'image_path' => null,
]);

echo "Test product created:\n";
echo "  ID: {$product->id}\n";
echo "  Name: {$product->name}\n";
echo "  Status: {$product->status}\n";
echo "  Stock: {$product->stock}\n";
echo "  Price: {$product->price}\n";
