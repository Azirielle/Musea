<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric',
        ]);

        $code = strtoupper($request->code);
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['message' => 'Invalid coupon code.'], 404);
        }

        if (!$coupon->is_active) {
            return response()->json(['message' => 'This coupon is inactive.'], 400);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return response()->json(['message' => 'This coupon has expired.'], 400);
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['message' => 'This coupon has reached its usage limit.'], 400);
        }

        if (auth()->check() && !$coupon->isValidForUser(auth()->id())) {
            return response()->json(['message' => 'You have already redeemed this code.'], 400);
        }

        // Calculate Discount
        $discountAmount = 0;
        if ($coupon->type === 'fixed') {
            $discountAmount = min($coupon->value, $request->subtotal);
        } else {
            $discountAmount = $request->subtotal * ($coupon->value / 100);
        }

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied!',
            'discount' => round($discountAmount, 2),
            'type' => $coupon->type,
            'code' => $coupon->code
        ]);
    }
}
