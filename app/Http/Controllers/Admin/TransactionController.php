<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $commissionRate = Setting::where('key', 'commission_rate')->value('value') ?? 10;

        $query = Order::with(['user:id,first_name,last_name', 'items.artwork'])
            ->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $orders = $query->paginate(10)
            ->through(function ($order) use ($commissionRate) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'buyer' => $order->user->first_name . ' ' . $order->user->last_name,
                    'shipping_address' => $order->shipping_address,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('M d, Y'),
                    'commission' => number_format($order->total_amount * ($commissionRate / 100), 2),
                    'tracking_number' => $order->tracking_number,
                    'courier' => $order->courier,
                    'shipped_at' => $order->shipped_at ? $order->shipped_at->format('M d, Y H:i') : null,
                ];
            });

        return Inertia::render('Admin/Sales/Index', [
            'orders' => $orders,
        ]);
    }

    public function ship(Request $request, Order $order)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:255',
            'courier' => 'required|string|max:255',
        ]);

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $validated['tracking_number'],
            'courier' => $validated['courier'],
            'shipped_at' => now(),
        ]);

        $order->user->notify(new \App\Notifications\OrderShippedNotification($order));

        return back()->with('success', 'Order marked as shipped.');
    }
}
