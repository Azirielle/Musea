<?php
$hash_2a = '$2a$12$0gO0qerUOlsehzaQOXjwwdsuNYqTvyda'; // The problematic hash seems to be this based on incomplete output, or I'll fetch it again properly.
// let's fetch it from DB to be sure.

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'graham.wells@musea.art')->first();
$hash = $user->password;

echo "Original Hash: " . $hash . "\n";
$info = password_get_info($hash);
echo "Original Algo: " . ($info['algoName'] ?? 'unknown') . "\n";

$fixed_hash = str_replace('$2a$', '$2y$', $hash);
echo "Fixed Hash: " . $fixed_hash . "\n";
$info_fixed = password_get_info($fixed_hash);
echo "Fixed Algo: " . ($info_fixed['algoName'] ?? 'unknown') . "\n";
