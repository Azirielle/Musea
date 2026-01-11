<?php

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Creating Artist...\n";
    $artist = User::firstOrCreate(
        ['email' => 'debug_artist@musea.com'],
        [
            'first_name' => 'Debug',
            'last_name' => 'Artist',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'is_onboarded' => true,
        ]
    );
    echo "Artist ID: " . $artist->id . "\n";

    $cols = DB::select('PRAGMA table_info(artworks)');
    foreach ($cols as $c) {
        echo "Col: " . $c->name . "\n";
    }

    DB::enableQueryLog();

    echo "Creating Artwork (Painting, Oil)...\n";
    Artwork::create([
        'artist_id' => $artist->id,
        'title' => 'Debug Painting',
        'description' => 'Desc',
        'category' => 'Painting',
        'subcategory' => 'Oil',
        'price' => 100,
        'stock' => 1,
        'status' => 'active',
        'ready_to_hang' => true,
        'framing' => 'Framed',
        'orientation' => 'landscape',
        'image_url' => 'http://example.com/img.jpg'
    ]);
    echo "Success Painting.\n";

    echo "Creating Artwork (Photography, Null)...\n";
    Artwork::create([
        'artist_id' => $artist->id,
        'title' => 'Debug Photo',
        'description' => 'Desc',
        'category' => 'Photography',
        'subcategory' => null,
        'price' => 100,
        'stock' => 1,
        'status' => 'active',
        'ready_to_hang' => false,
        'framing' => 'Unframed',
        'orientation' => 'landscape',
        'image_url' => 'http://example.com/img.jpg'
    ]);
    echo "Success Photography.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    print_r(DB::getQueryLog());
}
