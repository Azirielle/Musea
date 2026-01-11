<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('first_name', 'like', '%Ino%')
    ->orWhere('last_name', 'like', '%Yasha%')
    ->first();

if (!$user) {
    echo "User not found.\n";
    exit;
}

echo "User Found: {$user->first_name} {$user->last_name} (ID: {$user->id})\n";
echo "Role: {$user->role}\n";

echo "\n--- Raw DB Values ---\n";
$rawArtworks = \Illuminate\Support\Facades\DB::table('artworks')->where('artist_id', $user->id)->get();
foreach ($rawArtworks as $art) {
    echo "[{$art->id}] {$art->title} - Cat: '{$art->category}' - Sub: '{$art->subcategory}'\n";
}

echo "\n--- Artworks via Relationship (\$user->artworks) ---\n";
$relArtworks = $user->artworks()->get();
echo "Count: " . $relArtworks->count() . "\n";

echo "\n--- Artworks via ArtistController Query ---\n";
$controllerQuery = $user->artworks()->where('status', 'active')->get();
echo "Count: " . $controllerQuery->count() . "\n";
