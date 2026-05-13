<?php

namespace App\Services;

use App\Models\Coupon;

/** Applies stack-safe discounts for checkout previews and persisted orders. */
class CouponService
{
    /**
     * @return array{discount: float, coupon: ?Coupon}
     */
    public function apply(?string $code, float $subtotal): array
    {
        if (! $code) {
            return ['discount' => 0.0, 'coupon' => null];
        }

        $coupon = Coupon::whereRaw('LOWER(code) = ?', [mb_strtolower($code)])->first();

        if (! $coupon || ! $coupon->isUsable()) {
            return ['discount' => 0.0, 'coupon' => null];
        }

        $discount = match ($coupon->type) {
            'percent' => round($subtotal * ((float) $coupon->value / 100), 2),
            default => min((float) $coupon->value, $subtotal),
        };

        return [
            'discount' => $discount,
            'coupon' => $coupon,
        ];
    }
}
