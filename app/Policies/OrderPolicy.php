<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

/** Customers see their own orders; admins see the entire pipeline. */
class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin() || $order->user_id === $user->id;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }

    public function invoice(User $user, Order $order): bool
    {
        return $this->view($user, $order);
    }
}
