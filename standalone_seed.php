<?php

use App\Models\User;
use App\Models\Artwork;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Starting...\n";
    $artist = User::first();
    if (!$artist)
        $artist = User::create(['first_name' => 'T', 'last_name' => 'A', 'email' => 't@t.com', 'password' => 'p']);

    echo "Artist: " . $artist->id . "\n";

    // Test base creation
    echo "1. Minimal Artwork...\n";
    $a = Artwork::create([
        'artist_id' => $artist->id,
        'title' => 'Minimal',
        'description' => 'Desc',
        'price' => 100,
        'stock' => 1,
        'status' => 'active',
        'category' => 'Painting', // Enum
        'image_url' => 'http://test.com/img.jpg',
        // 'subcategory' => 'Oil',
        // 'ready_to_hang' => true,
        // 'framing' => 'Framed',
        // 'orientation' => 'landscape',
    ]);
    echo "Minimal success: " . $a->id . "\n";

    echo "2. With Subcategory...\n";
    $a = Artwork::create([
        'artist_id' => $artist->id,
        'title' => 'With Sub',
        'description' => 'Desc',
        'price' => 100,
        'stock' => 1,
        'status' => 'active',
        'category' => 'Painting',
        'subcategory' => 'Oil',
    ]);
    echo "Subcategory success: " . $a->id . "\n";

    echo "3. With Ready To Hang...\n";
    $a = Artwork::create([
        'artist_id' => $artist->id,
        'title' => 'With RTH',
        'description' => 'Desc',
        'price' => 100,
        'stock' => 1,
        'status' => 'active',
        'category' => 'Painting',
        'subcategory' => 'Oil',
        'ready_to_hang' => true,
    ]);
    echo "RTH success: " . $a->id . "\n";

    echo "4. With Framing...\n";
    $a = Artwork::create([
        'artist_id' => $artist->id,
        'title' => 'With Framing',
        'description' => 'Desc',
        'price' => 100,
        'stock' => 1,
        'status' => 'active',
        'category' => 'Painting',
        'subcategory' => 'Oil',
        'ready_to_hang' => true,
        'framing' => 'Framed',
    ]);
    echo "Framing success: " . $a->id . "\n";

    echo "5. With Orientation...\n";
    $a = Artwork::create([
        'artist_id' => $artist->id,
        'title' => 'With Orientation',
        'description' => 'Desc',
        'price' => 100,
        'stock' => 1,
        'status' => 'active',
        'category' => 'Painting',
        'subcategory' => 'Oil',
        'ready_to_hang' => true,
        'framing' => 'Framed',
        'orientation' => 'landscape',
    ]);
    echo "Orientation success: " . $a->id . "\n";

    echo "Orientation success: " . $a->id . "\n";

} catch (\Exception $e) {
    file_put_contents('short_error.txt', $e->getMessage());
}


