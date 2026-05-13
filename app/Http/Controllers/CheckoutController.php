<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Authenticated checkout converting bespoke cart lines into immutable orders.
 */
class CheckoutController extends Controller
{
    public function create(Request $request, CartService $carts, CouponService $coupons): View|RedirectResponse
    {
        $cart = $carts->getOrCreateCart($request)->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = (float) $cart->subtotal();
        $applied = $coupons->apply(session('checkout_coupon'), $subtotal);

        $taxRate = (float) config('customgift.tax_rate', 0);
        $shipping = (float) config('customgift.shipping_flat', 0);

        $discount = $applied['discount'];
        $taxable = max($subtotal - $discount, 0);
        $tax = round($taxable * $taxRate, 2);
        $total = max($taxable + $tax + $shipping, 0);

        return view('checkout.index', [
            'cart' => $cart,
            'summary' => [
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'coupon' => $applied['coupon'],
                'coupon_code' => session('checkout_coupon'),
            ],
        ]);
    }

    public function store(CheckoutRequest $request, CartService $carts, CouponService $coupons): RedirectResponse
    {
        $cart = $carts->getOrCreateCart($request)->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = $request->user();
        $subtotal = (float) $cart->subtotal();

        $couponCode = $request->validated('coupon_code') ?: session('checkout_coupon');
        $applied = $coupons->apply($couponCode, $subtotal);
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
                'provider' => 'placeholder',
                'status' => 'paid',
                'transaction_ref' => 'SIM-'.strtoupper(Str::random(12)),
                'amount' => $order->total,
                'meta' => ['note' => 'Swap this factory row for Razorpay/Stripe webhooks.'],
            ]);

            if ($coupon) {
                $coupon->increment('uses_count');
            }

            $cart->items()->delete();

            return $order;
        });

        session()->forget('checkout_coupon');

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order placed. Our artisans are on it.');
    }
}
