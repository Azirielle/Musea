<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('first_name', 'like', '%Ino%')->first();
if (!$user) {
    echo "User not found\n";
    exit;
}

echo "User: {$user->id}\n";
try {
    $artworks = $user->artworks()->get();
    echo "Artworks Count: " . $artworks->count() . "\n";
    foreach ($artworks as $art) {
        // Accessing category to trigger cast
        $cat = $art->category;
        $sub = $art->subcategory;
    }
    echo "SUCCESS: Artworks loaded and casted correctly.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
