<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Mail\RefundProcessedMail;
use App\Models\Order;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Admin marks a cancelled paid order as refunded (manual / Razorpay dashboard on localhost).
 */
class OrderRefundService
{
    public function processRefund(Order $order, User $admin, ?string $reason = null): Refund
    {
        if (! $order->canBeRefundedByAdmin()) {
            throw new RuntimeException('This order is not eligible for refund.');
        }

        return DB::transaction(function () use ($order, $admin, $reason): Refund {
            /** @var Order $order */
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            $order->load('payment');

            $payment = $order->payment;
            if (! $payment) {
                throw new RuntimeException('No payment record found.');
            }

            $payment->update(['status' => PaymentStatus::Refunded->value]);

            $refund = Refund::query()->create([
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'processed_by' => $admin->id,
                'amount' => $payment->amount,
                'status' => 'completed',
                'reason' => $reason ?? 'Customer cancellation — admin refund (test/local).',
                'reference' => 'REF-'.strtoupper(Str::random(10)),
                'processed_at' => now(),
            ]);

            $freshOrder = $order->fresh(['items', 'payment', 'refund', 'user']);

            Mail::to($freshOrder->shipping_email)->send(new RefundProcessedMail($freshOrder, $refund));

            return $refund;
        });
    }
}
