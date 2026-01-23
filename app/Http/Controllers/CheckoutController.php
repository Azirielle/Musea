<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Checkout/Index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:artworks,id',
            'items.*.quantity' => 'required|integer|min:1',
            'contact' => 'required|array',
            'contact.name' => 'required|string',
            'contact.email' => 'required|email',
            'contact.phone' => 'nullable|string|max:20',
            'payment_method' => 'required|string',
        ]);


        try {
            DB::beginTransaction();

            $total = 0;
            $itemsToCreate = [];

            // Verify prices and stock
            foreach ($validated['items'] as $item) {
                $artwork = Artwork::lockForUpdate()->find($item['id']);

                if (!$artwork) {
                    throw new \Exception("Artwork not found: {$item['id']}");
                }

                if ($artwork->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for: {$artwork->title}");
                }

                $total += $artwork->price * $item['quantity'];

                $itemsToCreate[] = [
                    'artwork' => $artwork,
                    'quantity' => $item['quantity'],
                    'price' => $artwork->price,
                ];
            }

            // Coupon Logic
            $discountAmount = 0;
            $couponCode = null;
            $appliedCoupon = null;

            if ($request->has('coupon_code') && $request->coupon_code) {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
                // Basic validation again to be safe
                if ($coupon && $coupon->is_active && (!$coupon->expires_at || $coupon->expires_at->isFuture()) && (!$coupon->usage_limit || $coupon->used_count < $coupon->usage_limit)) {

                    if (!$coupon->isValidForUser(auth()->id())) {
                        throw new \Exception("You have already redeemed this coupon code.");
                    }

                    if ($coupon->type === 'fixed') {
                        $discountAmount = min($coupon->value, $total);
                    } else {
                        $discountAmount = $total * ($coupon->value / 100);
                    }
                    $couponCode = $coupon->code;
                    $appliedCoupon = $coupon;

                    // Increment usage
                    $coupon->increment('used_count');
                }
            }

            // Shipping flat rate
            $shipping = 150;
            $grandTotal = max(0, ($total - $discountAmount) + $shipping);

            // Create Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'total_amount' => $grandTotal,
                'discount_amount' => $discountAmount,
                'coupon_code' => $couponCode,
                'shipping_address' => $request->user()->address ?? 'Address on file',
            ]);

            // Create Order Items and decrease stock
            foreach ($itemsToCreate as $data) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'artwork_id' => $data['artwork']->id,
                    'quantity' => $data['quantity'],
                    'price_each' => $data['price'],
                ]);

                $data['artwork']->decrement('stock', $data['quantity']);
            }

            // Record Coupon Usage
            if ($appliedCoupon) {
                $appliedCoupon->users()->attach(auth()->id(), [
                    'order_id' => $order->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            // Notify User
            $order->user->notify(new \App\Notifications\OrderPlacedNotification($order));

            // Notify Artists (Item Sold)
            foreach ($order->items as $orderItem) {
                $artist = $orderItem->artwork->artist; // assuming relation exists
                if ($artist && $artist->id !== $order->user_id) {
                    $artist->notify(new \App\Notifications\ItemSoldNotification($orderItem));
                }
            }

            return response()->json([
                'message' => 'Order placed successfully',
                'order_id' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
