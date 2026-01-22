<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        try {
            DB::beginTransaction();

            $order->update([
                'status' => 'shipped',
                'tracking_number' => $validated['tracking_number'],
                'courier' => $validated['courier'],
                'shipped_at' => now(),
            ]);

            // Release funds to artists immediately when marked as shipped
            $commissionRate = Setting::where('key', 'commission_rate')->value('value') ?? 10;

            foreach ($order->items as $item) {
                if ($item->artwork && $item->artwork->artist) {
                    $artist = $item->artwork->artist;

                    // Calculate artist share: (Price * Quantity) - Commission
                    $totalItemPrice = $item->price_each * $item->quantity;
                    $commission = $totalItemPrice * ($commissionRate / 100);
                    $artistShare = $totalItemPrice - $commission;

                    // Update Artist Balance
                    $artist->increment('balance', $artistShare);

                    // Notify Artist
                    $artist->notify(new \App\Notifications\FundsReleasedNotification($order));
                }
            }

            // Notify buyer that order is shipped
            $order->user->notify(new \App\Notifications\OrderShippedNotification($order));

            DB::commit();

            return back()->with('success', 'Order marked as shipped and funds released to artists.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error marking order as shipped: ' . $e->getMessage());
        }
    }
}
