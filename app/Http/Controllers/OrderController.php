<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['items.product', 'payment']);

        return view('orders.show', compact('order'));
    }

    public function invoice(Order $order): View
    {
        $this->authorize('invoice', $order);

        $order->load(['items', 'user', 'payment']);

        return view('orders.invoice', compact('order'));
    }
}
