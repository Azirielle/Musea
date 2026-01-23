<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\JournalPost;

$bi = JournalPost::latest()->get();

echo "--- Journal Posts Image URLs ---\n";
foreach ($bi as $p) {
    echo "ID: {$p->id} | Title: {$p->title} | Image URL: {$p->image_url}\n";
}
