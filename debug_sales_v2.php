<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Artwork;

// Check Sales by Category
$sales = OrderItem::select('artworks.category', \Illuminate\Support\Facades\DB::raw('SUM(order_items.price_each) as total'))
    ->join('artworks', 'order_items.artwork_id', '=', 'artworks.id')
    ->join('orders', 'order_items.order_id', '=', 'orders.id')
    ->where('orders.status', '!=', 'cancelled')
    ->groupBy('artworks.category')
    ->get();

echo "--- Current Sales by Category (DB) ---\n";
foreach ($sales as $sale) {
    echo "Category: {$sale->category} | Total: {$sale->total}\n";
}
