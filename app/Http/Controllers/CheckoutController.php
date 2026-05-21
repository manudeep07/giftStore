<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutPaymentCallbackRequest;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutOrderService;
use App\Services\CouponService;
use App\Services\PaymentFulfillmentService;
use App\Services\RazorpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Authenticated checkout: pending order first, Razorpay capture second.
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
        $summary = $coupons->checkoutSummary($subtotal, session('checkout_coupon'));
        $availableCoupons = $coupons->availableForSubtotal($subtotal);

        return view('checkout.index', [
            'cart' => $cart,
            'summary' => $summary,
            'availableCoupons' => $availableCoupons,
            'razorpayConfigured' => app(RazorpayService::class)->isConfigured(),
        ]);
    }

    public function store(
        CheckoutRequest $request,
        CartService $carts,
        CheckoutOrderService $checkoutOrders,
        RazorpayService $razorpay,
        PaymentFulfillmentService $fulfillment,
    ): RedirectResponse {
        $cart = $carts->getOrCreateCart($request)->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if (! $razorpay->isConfigured()) {
            return redirect()
                ->route('checkout.index')
                ->with('error', 'Payment gateway is not configured. Add Razorpay keys to .env (see HANDOFF.md).');
        }

        ['order' => $order] = $checkoutOrders->createPendingOrder($request, $cart, $request->user());

        session()->forget('checkout_coupon');

        try {
            $razorpayOrder = $razorpay->createOrder($order);
            $payment = $order->payment()->firstOrFail();
            $fulfillment->attachRazorpayOrderId($payment, $razorpayOrder['id'], $razorpayOrder['amount']);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'Order saved but Razorpay could not be started. Retry payment from your order page.');
        }

        return redirect()
            ->route('checkout.pay', $order)
            ->with('info', 'Complete payment to confirm your order.');
    }

    public function pay(Order $order, RazorpayService $razorpay, PaymentFulfillmentService $fulfillment): View|RedirectResponse
    {
        $this->authorize('view', $order);

        $order->load('payment');

        $payment = $order->payment;

        if (! $payment || $payment->status === 'paid') {
            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'This order is already paid.');
        }

        if (! $razorpay->isConfigured()) {
            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'Razorpay is not configured. Contact support or check HANDOFF.md.');
        }

        $meta = $payment->meta ?? [];
        $razorpayOrderId = $meta['razorpay_order_id'] ?? null;
        $amountPaise = (int) ($meta['razorpay_amount_paise'] ?? round((float) $order->total * 100));

        if (! $razorpayOrderId) {
            try {
                $razorpayOrder = $razorpay->createOrder($order);
                $fulfillment->attachRazorpayOrderId($payment, $razorpayOrder['id'], $razorpayOrder['amount']);
                $razorpayOrderId = $razorpayOrder['id'];
                $amountPaise = $razorpayOrder['amount'];
            } catch (\Throwable $e) {
                report($e);

                return redirect()
                    ->route('orders.show', $order)
                    ->with('error', 'Unable to start Razorpay checkout. Please try again shortly.');
            }
        }

        return view('checkout.pay', [
            'order' => $order,
            'razorpayKeyId' => config('services.razorpay.key_id'),
            'razorpayOrderId' => $razorpayOrderId,
            'amountPaise' => $amountPaise,
            'callbackUrl' => route('checkout.payment.callback', $order),
        ]);
    }

    public function callback(
        CheckoutPaymentCallbackRequest $request,
        Order $order,
        RazorpayService $razorpay,
        PaymentFulfillmentService $fulfillment,
    ): RedirectResponse {
        $order->load('payment');
        $payment = $order->payment;

        if ($payment?->status === 'paid') {
            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Payment already confirmed.');
        }

        $razorpayOrderId = $request->validated('razorpay_order_id');
        $razorpayPaymentId = $request->validated('razorpay_payment_id');
        $razorpaySignature = $request->validated('razorpay_signature');

        $storedOrderId = $payment?->meta['razorpay_order_id'] ?? null;
        if ($storedOrderId && $storedOrderId !== $razorpayOrderId) {
            return redirect()
                ->route('checkout.pay', $order)
                ->with('error', 'Payment does not match this order.');
        }

        try {
            $razorpay->verifyPaymentSignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature);
        } catch (\RuntimeException $e) {
            report($e);

            return redirect()
                ->route('checkout.pay', $order)
                ->with('error', 'Payment verification failed. You were not charged, or contact support with your receipt.');
        }

        $fulfillment->fulfill($order, $razorpayPaymentId, $razorpayOrderId);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Payment received. Your order is confirmed!');
    }
}
