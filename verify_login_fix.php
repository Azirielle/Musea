<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ensure we are using the correct guard/provider
// $auth = auth()->guard('web');

$email = 'graham.wells@musea.art';
$password = 'password'; // Assuming this is the password based on inspection, or random guess. 
// IF successful, the hash should change from $2a... to $2y...

$user = \App\Models\User::where('email', $email)->first();
echo "Old Hash Start: " . substr($user->password, 0, 7) . "\n";

if (auth()->attempt(['email' => $email, 'password' => $password])) {
    echo "Login SUCCESS!\n";

    $user->refresh();
    echo "New Hash Start: " . substr($user->password, 0, 7) . " (" . (password_get_info($user->password)['algoName']) . ")\n";
} else {
    echo "Login FAILED.\n";
}
