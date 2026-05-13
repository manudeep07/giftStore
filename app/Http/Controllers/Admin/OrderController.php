<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()
            ->with('user:id,name,email')
            ->latest()
            ->paginate(25);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'payment', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        $order->update([
            'status' => $request->validated('status'),
        ]);

        return back()->with('success', 'Fulfillment stage synced.');
    }
}
