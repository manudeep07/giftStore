<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Idempotently marks a Razorpay payment as paid and finalizes the order.
 */
class PaymentFulfillmentService
{
    public function fulfill(Order $order, string $razorpayPaymentId, ?string $razorpayOrderId = null): bool
    {
        return (bool) DB::transaction(function () use ($order, $razorpayPaymentId, $razorpayOrderId): bool {
            /** @var Order $order */
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);

            /** @var Payment|null $payment */
            $payment = Payment::query()
                ->where('order_id', $order->id)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                return false;
            }

            if ($payment->status === PaymentStatus::Paid->value) {
                return false;
            }

            $meta = $payment->meta ?? [];
            if ($razorpayOrderId) {
                $meta['razorpay_order_id'] = $razorpayOrderId;
            }

            $payment->update([
                'status' => PaymentStatus::Paid->value,
                'transaction_ref' => $razorpayPaymentId,
                'meta' => array_merge($meta, [
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'paid_at' => now()->toIso8601String(),
                ]),
            ]);

            if ($order->status === OrderStatus::Pending->value) {
                $order->update(['status' => OrderStatus::Placed->value]);
            }

            $order->load('items.product');

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            if ($order->coupon_id) {
                $order->coupon()?->increment('uses_count');
            }

            $freshOrder = $order->fresh(['items', 'payment', 'user']);

            Mail::to($freshOrder->shipping_email)->send(new OrderPlacedMail($freshOrder));

            return true;
        });
    }

    public function attachRazorpayOrderId(Payment $payment, string $razorpayOrderId, int $amountPaise): void
    {
        $payment->update([
            'meta' => array_merge($payment->meta ?? [], [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_amount_paise' => $amountPaise,
            ]),
        ]);
    }

    public function findOrderByRazorpayOrderId(string $razorpayOrderId): ?Order
    {
        $payment = Payment::query()
            ->where('provider', 'razorpay')
            ->where('meta->razorpay_order_id', $razorpayOrderId)
            ->first();

        return $payment?->order;
    }
}
