<?php

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

$sales = OrderItem::select('artworks.category', DB::raw('SUM(order_items.price_each) as total'), DB::raw('count(*) as count'))
    ->join('artworks', 'order_items.artwork_id', '=', 'artworks.id')
    ->join('orders', 'order_items.order_id', '=', 'orders.id')
    ->where('orders.status', '!=', 'cancelled')
    ->groupBy('artworks.category')
    ->get();

foreach ($sales as $s) {
    echo "Category: {$s->category} | Count: {$s->count} | Total: {$s->total}\n";
}
