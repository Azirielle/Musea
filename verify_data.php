<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$staffPicks = \App\Models\Artwork::where('is_staff_pick', true)->get();
echo "Staff Picks Count: " . $staffPicks->count() . "\n";
foreach ($staffPicks as $a) {
    echo "- {$a->title} (Status: {$a->status})\n";
}

$featured = \App\Models\User::where('is_featured', true)->get();
echo "Featured Users Count: " . $featured->count() . "\n";
foreach ($featured as $u) {
    echo "- {$u->first_name} {$u->last_name}\n";
}
