<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'graham.wells@musea.art')->first();
$hash = $user->password;

echo "Hex: " . bin2hex($hash) . "\n";
