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
echo "Hash (length " . strlen($hash) . "): " . addcslashes($hash, "\0..\37!@\177..\377") . "\n";

$info = password_get_info($hash);
echo "Algo: " . ($info['algo'] ?? 'NULL') . "\n";
echo "Algo Name: " . ($info['algoName'] ?? 'NULL') . "\n";
echo "Options: " . json_encode($info['options']) . "\n";

// Check confirm logic from Laravel
$verifyAlgorithm = true; // Default in Laravel
if ($verifyAlgorithm && ($info['algoName'] ?? 'unknown') !== 'bcrypt') {
    echo "Laravel check would FAIL: AlgoName is '" . ($info['algoName'] ?? 'unknown') . "' vs 'bcrypt'\n";
} else {
    echo "Laravel check would PASS\n";
}
