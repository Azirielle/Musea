<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Artwork;
use App\Models\OrderItem;

$categories = OrderItem::join('artworks', 'order_items.artwork_id', '=', 'artworks.id')
    ->select('artworks.category', \DB::raw('count(*) as count'))
    ->groupBy('artworks.category')
    ->pluck('count', 'category');

echo "Sales Counts:\n";
foreach ($categories as $cat => $count) {
    echo "$cat: $count\n";
}
