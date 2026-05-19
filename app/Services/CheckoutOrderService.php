<?php

namespace App\Services;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Creates a pending order + payment from the cart without capturing funds or stock.
 */
class CheckoutOrderService
{
    public function __construct(
        private readonly CouponService $coupons,
    ) {}

    /**
     * @return array{order: Order, coupon: ?Coupon}
     */
    public function createPendingOrder(CheckoutRequest $request, Cart $cart, User $user): array
    {
        $cart->load('items.product');

        $subtotal = (float) $cart->subtotal();

        $couponCode = $request->validated('coupon_code') ?: session('checkout_coupon');
        $applied = $this->coupons->apply($couponCode, $subtotal);
        $discount = $applied['discount'];
        $coupon = $applied['coupon'];

        $taxRate = (float) config('customgift.tax_rate', 0);
        $shipping = (float) config('customgift.shipping_flat', 0);

        $taxable = max($subtotal - $discount, 0);
        $tax = round($taxable * $taxRate, 2);
        $total = max($taxable + $tax + $shipping, 0);

        $order = DB::transaction(function () use ($request, $user, $cart, $subtotal, $discount, $tax, $shipping, $total, $coupon): Order {
            /** @var Order $order */
            $order = Order::query()->create([
                'order_number' => 'CG-'.strtoupper(Str::random(10)),
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => number_format($subtotal, 2, '.', ''),
                'tax_amount' => number_format($tax, 2, '.', ''),
                'discount_amount' => number_format($discount, 2, '.', ''),
                'shipping_amount' => number_format($shipping, 2, '.', ''),
                'total' => number_format($total, 2, '.', ''),
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'shipping_name' => $request->validated('shipping_name'),
                'shipping_email' => $request->validated('shipping_email'),
                'shipping_phone' => $request->validated('shipping_phone'),
                'shipping_address_line1' => $request->validated('shipping_address_line1'),
                'shipping_address_line2' => $request->validated('shipping_address_line2'),
                'shipping_city' => $request->validated('shipping_city'),
                'shipping_state' => $request->validated('shipping_state'),
                'shipping_postal' => $request->validated('shipping_postal'),
                'shipping_country' => $request->validated('shipping_country'),
                'notes' => $request->validated('notes'),
            ]);

            foreach ($cart->items as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'customization_snapshot' => $item->customization_snapshot,
                    'unit_price' => $item->unit_price,
                    'line_total' => $item->line_total,
                ]);
            }

            Payment::query()->create([
                'order_id' => $order->id,
                'provider' => 'razorpay',
                'status' => 'pending',
                'transaction_ref' => null,
                'amount' => $order->total,
                'meta' => [],
            ]);

            $cart->items()->delete();

            return $order;
        });

        return ['order' => $order, 'coupon' => $coupon];
    }
}
