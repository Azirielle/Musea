<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'test.legacy@musea.art';
$password = 'secret123';

// 1. Create or update test user
\DB::table('users')->updateOrInsert(
    ['email' => $email],
    [
        'first_name' => 'Test',
        'last_name' => 'User',
        'password' => str_replace('$2y$', '$2a$', password_hash($password, PASSWORD_BCRYPT)),
        'updated_at' => now(),
        'created_at' => now(),
    ]
);

$user = \App\Models\User::where('email', $email)->first();
$legacyHash = $user->password;

echo "User setup with hash: " . $legacyHash . "\n";
echo "Attempting login...\n";

if (auth()->attempt(['email' => $email, 'password' => $password])) {
    echo "Login SUCCESS!\n";
    $user->refresh();
    echo "Current Hash: " . $user->password . "\n";
    if (str_starts_with($user->password, '$2y$')) {
        echo "Hash successfully migrated to $2y$!\n";
    } else {
        echo "Hash NOT migrated (unexpected).\n";
    }
} else {
    echo "Login FAILED.\n";
}
