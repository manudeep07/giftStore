<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Collection;

/** Applies stack-safe discounts for checkout previews and persisted orders. */
class CouponService
{
    /**
     * @return array{valid: bool, message: ?string, discount: float, coupon: ?Coupon}
     */
    public function validate(?string $code, float $subtotal): array
    {
        if (! $code || trim($code) === '') {
            return [
                'valid' => false,
                'message' => 'Please enter a coupon code.',
                'discount' => 0.0,
                'coupon' => null,
            ];
        }

        $coupon = Coupon::query()
            ->whereRaw('LOWER(code) = ?', [mb_strtolower(trim($code))])
            ->first();

        if (! $coupon) {
            return [
                'valid' => false,
                'message' => 'Coupon code not found.',
                'discount' => 0.0,
                'coupon' => null,
            ];
        }

        if (! $coupon->is_active) {
            return [
                'valid' => false,
                'message' => 'This coupon is inactive.',
                'discount' => 0.0,
                'coupon' => null,
            ];
        }

        if ($coupon->isExpired()) {
            return [
                'valid' => false,
                'message' => 'This coupon has expired.',
                'discount' => 0.0,
                'coupon' => null,
            ];
        }

        if (! $coupon->hasUsesRemaining()) {
            return [
                'valid' => false,
                'message' => 'This coupon has reached its usage limit.',
                'discount' => 0.0,
                'coupon' => null,
            ];
        }

        if ($subtotal < (float) $coupon->minimum_order_amount) {
            return [
                'valid' => false,
                'message' => 'Minimum order of ₹'.number_format((float) $coupon->minimum_order_amount, 2).' required for this coupon.',
                'discount' => 0.0,
                'coupon' => null,
            ];
        }

        return [
            'valid' => true,
            'message' => null,
            'discount' => $this->calculateDiscount($coupon, $subtotal),
            'coupon' => $coupon,
        ];
    }

    /**
     * @return array{discount: float, coupon: ?Coupon}
     */
    public function apply(?string $code, float $subtotal): array
    {
        $result = $this->validate($code, $subtotal);

        if (! $result['valid']) {
            return ['discount' => 0.0, 'coupon' => null];
        }

        return [
            'discount' => $result['discount'],
            'coupon' => $result['coupon'],
        ];
    }

    /**
     * @return Collection<int, array{coupon: Coupon, eligible: bool, preview_discount: float}>
     */
    public function availableForSubtotal(float $subtotal): Collection
    {
        return Coupon::query()
            ->where('is_active', true)
            ->where(function ($query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function ($query): void {
                $query->whereNull('max_uses')
                    ->orWhereColumn('uses_count', '<', 'max_uses');
            })
            ->orderBy('code')
            ->get()
            ->map(function (Coupon $coupon) use ($subtotal): array {
                $eligible = $subtotal >= (float) $coupon->minimum_order_amount;

                return [
                    'coupon' => $coupon,
                    'eligible' => $eligible,
                    'preview_discount' => $eligible ? $this->calculateDiscount($coupon, $subtotal) : 0.0,
                ];
            });
    }

    /**
     * @return array{
     *     subtotal: float,
     *     discount: float,
     *     tax: float,
     *     shipping: float,
     *     total: float,
     *     coupon: ?Coupon,
     *     coupon_code: ?string
     * }
     */
    public function checkoutSummary(float $subtotal, ?string $sessionCouponCode): array
    {
        $applied = $this->apply($sessionCouponCode, $subtotal);
        $discount = $applied['discount'];

        $taxRate = (float) config('customgift.tax_rate', 0);
        $shipping = (float) config('customgift.shipping_flat', 0);

        $taxable = max($subtotal - $discount, 0);
        $tax = round($taxable * $taxRate, 2);
        $total = max($taxable + $tax + $shipping, 0);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'coupon' => $applied['coupon'],
            'coupon_code' => $sessionCouponCode,
        ];
    }

    public function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        return match ($coupon->type) {
            'percent' => round($subtotal * ((float) $coupon->value / 100), 2),
            default => min((float) $coupon->value, $subtotal),
        };
    }
}
