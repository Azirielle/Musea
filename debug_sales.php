<?php

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Artwork;

// Check Sales by Category
$sales = OrderItem::select('artworks.category', \DB::raw('SUM(order_items.price_each) as total'))
    ->join('artworks', 'order_items.artwork_id', '=', 'artworks.id')
    ->join('orders', 'order_items.order_id', '=', 'orders.id')
    ->where('orders.status', '!=', 'cancelled')
    ->groupBy('artworks.category')
    ->get();

echo "--- Current Sales by Category (DB) ---\n";
foreach ($sales as $sale) {
    echo "Category: {$sale->category} | Total: {$sale->total}\n";
}

// Check if these orders are real
echo "\n--- Sample Mixed Media Orders ---\n";
$mmOrders = OrderItem::whereHas('artwork', function ($q) {
    $q->where('category', 'Mixed Media');
})->with(['order', 'artwork'])->limit(3)->get();

if ($mmOrders->isEmpty()) {
    echo "No Mixed Media orders found (Strange, query above shouldn't have returned them).\n";
} else {
    foreach ($mmOrders as $item) {
        echo "Order #{$item->order->id} | Date: {$item->order->created_at} | Artwork: {$item->artwork->title}\n";
    }
}
