<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'graham.wells@musea.art')->first();

if (!$user) {
    echo "User not found.\n";
    exit;
}

$hash = $user->password;
echo "Hash: " . $hash . "\n";

$info = password_get_info($hash);
echo "Algo: " . $info['algo'] . "\n";
echo "Algo Name: " . $info['algoName'] . "\n";
echo "Options: " . json_encode($info['options']) . "\n";

if (password_verify('password', $hash)) { // blindly guessing 'password' just to check, but unlikely to work and not needed for info
    echo "Verify 'password': true\n";
} else {
    echo "Verify 'password': false\n";
}
