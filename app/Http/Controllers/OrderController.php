<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.artwork'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10)
            ->through(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('M d, Y'),
                    'tracking_number' => $order->tracking_number,
                    'courier' => $order->courier,
                    'shipped_at' => $order->shipped_at ? $order->shipped_at->format('M d, Y') : null,
                    'delivered_at' => $order->delivered_at ? $order->delivered_at->format('M d, Y') : null,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'title' => $item->artwork ? $item->artwork->title : 'Deleted Artwork',
                            'quantity' => $item->quantity,
                            'price' => number_format($item->price_each, 2),
                        ];
                    }),
                ];
            });

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function received(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status === 'completed') {
            return back()->with('error', 'Order is already completed.');
        }

        if ($order->status !== 'shipped') {
            return back()->with('error', 'Order must be shipped before confirming receipt.');
        }

        try {
            DB::beginTransaction();

            // Note: Funds are released to artists when admin marks order as shipped
            // (in Admin\TransactionController@ship), so we don't pay them again here.
            // We only update the order status to completed.

            // Update Order Status
            $order->update([
                'status' => 'completed',
                'delivered_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Order marked as completed! Thank you for confirming.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
