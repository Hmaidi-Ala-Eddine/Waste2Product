<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

$email = 'ahmed6@gmail.com';
$plain = 'password';

echo "Looking up user by email: $email\n";
$user = User::where('email', $email)->first();
if (! $user) {
    echo "User not found\n";
    exit(1);
}

echo "Found user id={$user->id} email={$user->email}\n";
echo "Stored hash: {$user->password}\n";

$ok = Auth::attempt(['email' => $email, 'password' => $plain]);

echo "Auth::attempt returned: ".($ok ? 'true' : 'false')."\n";

if (! $ok) {
    echo "Resetting password to known value 'password' and trying again...\n";
    $user->password = Hash::make($plain);
    $user->save();

    $ok2 = Auth::attempt(['email' => $email, 'password' => $plain]);
    echo "Auth::attempt after reset: ".($ok2 ? 'true' : 'false')."\n";
}

echo "Done\n";
