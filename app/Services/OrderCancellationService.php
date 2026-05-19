<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\OrderCancelledMail;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

/**
 * Lets customers cancel unpaid or paid-but-not-shipped orders.
 * Paid cancellations notify the customer; admin must process the refund separately.
 */
class OrderCancellationService
{
    public function cancelByCustomer(Order $order): Order
    {
        if (! $order->canBeCancelledByCustomer()) {
            throw new RuntimeException('This order cannot be cancelled.');
        }

        return DB::transaction(function () use ($order): Order {
            /** @var Order $order */
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            $order->load(['items.product', 'payment']);

            $wasPaid = $order->payment?->status === PaymentStatus::Paid->value;

            $order->update(['status' => OrderStatus::Cancelled->value]);

            if ($wasPaid) {
                $this->restoreStock($order);
            }

            $fresh = $order->fresh(['items', 'payment', 'user']);

            Mail::to($fresh->shipping_email)->send(new OrderCancelledMail($fresh, $wasPaid));

            return $fresh;
        });
    }

    private function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }
    }
}
