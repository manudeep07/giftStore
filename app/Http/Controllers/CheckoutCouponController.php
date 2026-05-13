<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Session-backed coupon preview prior to POST /checkout. */
class CheckoutCouponController extends Controller
{
    public function store(Request $request, CartService $carts, CouponService $coupons): RedirectResponse
    {
        $request->validate([
            'coupon_code' => ['required', 'string', 'max:64'],
        ]);

        $cart = $carts->getOrCreateCart($request);
        $subtotal = (float) $cart->subtotal();

        $applied = $coupons->apply($request->string('coupon_code'), $subtotal);

        if (! $applied['coupon']) {
            return back()->with('error', 'Invalid or expired coupon.');
        }

        session(['checkout_coupon' => $applied['coupon']->code]);

        return back()->with('success', 'Coupon applied to this checkout.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        session()->forget('checkout_coupon');

        return back()->with('success', 'Coupon removed.');
    }
}
