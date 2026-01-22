<?php

namespace App\Services;

class ShippingService
{
    /**
     * Calculate shipping cost and return breakdown.
     *
     * @param float $subtotal
     * @return array
     */
    public function calculate(float $subtotal): array
    {
        $config = config('musea.shipping');
        $threshold = $config['threshold'] ?? 5000;
        $baseRate = $config['rate'] ?? 150;

        $isFree = $subtotal >= $threshold;
        $cost = $isFree ? 0 : $baseRate;
        $amountLeft = $isFree ? 0 : max(0, $threshold - $subtotal);

        return [
            'cost' => (float) $cost,
            'is_free' => $isFree,
            'amount_left_for_free' => (float) $amountLeft,
            'threshold' => (float) $threshold,
        ];
    }
}
