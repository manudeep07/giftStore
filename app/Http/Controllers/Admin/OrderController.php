<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProcessRefundRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Mail\OrderStatusChangedMail;
use App\Models\Order;
use App\Services\OrderRefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()
            ->with(['user:id,name,email', 'payment'])
            ->latest()
            ->paginate(25);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'payment', 'user', 'refund.processor']);

        return view('admin.orders.show', compact('order'));
    }

    /** Records refund in DB and emails customer (manual Razorpay refund on localhost). */
    public function refund(ProcessRefundRequest $request, Order $order, OrderRefundService $refunds): RedirectResponse
    {
        try {
            $refunds->processRefund($order, $request->user(), $request->validated('reason'));
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Refund recorded and confirmation email sent to the customer.');
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        $previousStatus = $order->status;

        $order->update([
            'status' => $request->validated('status'),
        ]);

        if ($previousStatus !== $order->status && filled($order->shipping_email)) {
            Mail::to($order->shipping_email)->send(
                new OrderStatusChangedMail($order->fresh(), $previousStatus),
            );
        }

        return back()->with('success', 'Fulfillment stage synced.');
    }
}
