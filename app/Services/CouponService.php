<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function validate(string $code, float $cartTotal): Coupon
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            throw new \Exception('Invalid coupon code.');
        }

        if (!$coupon->is_active) {
            throw new \Exception('This coupon is no longer active.');
        }

        if ($coupon->isExpired()) {
            throw new \Exception('This coupon has expired.');
        }

        if (!$coupon->canApplyTo($cartTotal)) {
            $minPurchase = '$' . number_format($coupon->min_purchase, 2);
            throw new \Exception("Minimum purchase of {$minPurchase} required for this coupon.");
        }

        return $coupon;
    }

    public function calculateDiscount(Coupon $coupon, float $cartTotal): float
    {
        $discount = 0;

        if ($coupon->type === 'percentage') {
            $discount = ($cartTotal * $coupon->value) / 100;
            
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } else {
            $discount = $coupon->value;
            
            if ($discount > $cartTotal) {
                $discount = $cartTotal;
            }
        }

        return round($discount, 2);
    }

    public function apply(string $code, float $cartTotal): array
    {
        $coupon = $this->validate($code, $cartTotal);
        $discount = $this->calculateDiscount($coupon, $cartTotal);

        return [
            'coupon' => $coupon,
            'discount' => $discount,
            'final_total' => $cartTotal - $discount,
        ];
    }
}
