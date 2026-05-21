<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Session-backed coupon preview for cart and checkout. */
class CheckoutCouponController extends Controller
{
    public function store(Request $request, CartService $carts, CouponService $coupons): RedirectResponse
    {
        $request->validate([
            'coupon_code' => ['required', 'string', 'max:64'],
        ]);

        $cart = $carts->getOrCreateCart($request);
        $subtotal = (float) $cart->subtotal();
        $code = $request->string('coupon_code')->toString();

        $result = $coupons->validate($code, $subtotal);

        if (! $result['valid']) {
            return back()->with('error', $result['message']);
        }

        session(['checkout_coupon' => $result['coupon']->code]);

        return back()->with('success', 'Coupon '.$result['coupon']->code.' applied. You save ₹'.number_format($result['discount'], 2).'.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        session()->forget('checkout_coupon');

        return back()->with('success', 'Coupon removed.');
    }
}
