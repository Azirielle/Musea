<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Artwork;
use Illuminate\Support\Facades\Storage;

echo "--- Debugging Images ---\n";

// 1. Check Symlink
$link = public_path('storage');
$target = storage_path('app/public');
echo "Symlink Path: $link\n";
echo "Target Path: $target\n";
if (file_exists($link)) {
    echo "Symlink Exists: Yes\n";
    if (is_link($link)) {
        echo "Is Link: Yes\n";
        echo "Link Target: " . readlink($link) . "\n";
    } else {
        echo "Is Link: No (It's a directory?)\n";
    }
} else {
    echo "Symlink Exists: No\n";
}

// 2. Check Directory
echo "\nChecking storage/app/public/artworks:\n";
if (file_exists($target . '/artworks')) {
    echo "Directory Exists.\n";
    $files = scandir($target . '/artworks');
    echo "Files: " . count($files) . "\n";
} else {
    echo "Directory MISSING.\n";
}

// 3. Check DB Records
echo "\nChecking DB Artworks (Limits 5):\n";
$artworks = Artwork::take(5)->get();
foreach ($artworks as $art) {
    echo "ID: {$art->id}\n";
    echo "Title: {$art->title}\n";
    echo "Raw DB Image URL: {$art->image_url}\n"; // Access raw attribute? No, accessor might interfere.
    echo "Accessor URL: {$art->image_url}\n";

    // We need raw attribute
    $raw = $art->getAttributes()['image_url'] ?? 'N/A';
    echo "Raw Attribute: $raw\n";

    if ($raw) {
        // Assume relative to public disk
        $clean = str_replace('/storage/', '', $raw); // reverse accessor roughly
        $clean = ltrim($clean, '/');

        $exists = Storage::disk('public')->exists($clean);
        echo "File Exists (public disk): " . ($exists ? 'YES' : 'NO') . "\n";
    }
    echo "----------------\n";
}
