<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Review;
use App\Models\User;

class PurchasedProductService
{
    public function hasPurchased(User $user, int $productId): bool
    {
        return OrderItem::query()
            ->where('product_id', $productId)
            ->whereHas('order', function ($query) use ($user): void {
                $query->where('user_id', $user->id)
                    ->where('status', '!=', 'cancelled')
                    ->whereHas('payment', fn ($payment) => $payment->where('status', 'paid'));
            })
            ->exists();
    }

    public function canReview(User $user, int $productId): bool
    {
        if (! $this->hasPurchased($user, $productId)) {
            return false;
        }

        return ! Review::query()
            ->where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();
    }
}
