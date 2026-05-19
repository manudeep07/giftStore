<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelOrderRequest;
use App\Models\Order;
use App\Services\OrderCancellationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with('payment')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['items.product', 'payment', 'refund']);

        return view('orders.show', compact('order'));
    }

    public function invoice(Order $order): View
    {
        $this->authorize('invoice', $order);

        $order->load(['items', 'user', 'payment']);

        return view('orders.invoice', compact('order'));
    }

    /** Customer cancels their own order (unpaid or paid-before-shipment). */
    public function cancel(CancelOrderRequest $request, Order $order, OrderCancellationService $cancellations): RedirectResponse
    {
        try {
            $cancellations->cancelByCustomer($order);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order cancelled. You will receive a confirmation email shortly.');
    }
}
