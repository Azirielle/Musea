<?php

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing Monthly Sales Query...\n";
    $monthlySales = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
        ->where('status', '!=', 'cancelled')
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    echo "Monthly Sales: " . $monthlySales->count() . " rows\n";

    echo "Testing Category Sales Query...\n";
    // mimicking the controller code which likely failed
    $categorySales = \App\Models\OrderItem::select('artworks.category', DB::raw('SUM(order_items.price) as total'))
        ->join('artworks', 'order_items.artwork_id', '=', 'artworks.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', '!=', 'cancelled')
        ->groupBy('artworks.category')
        ->get();
    echo "Category Sales: " . $categorySales->count() . " rows\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
