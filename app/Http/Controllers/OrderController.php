<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelOrderRequest;
use App\Models\Order;
use App\Models\Review;
use App\Services\OrderCancellationService;
use App\Services\PurchasedProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function index(Request $request, PurchasedProductService $purchased): View
    {
        $orders = $request->user()
            ->orders()
            ->with(['payment', 'items.product'])
            ->latest()
            ->paginate(10);

        $userReviews = Review::query()
            ->where('user_id', $request->user()->id)
            ->get()
            ->keyBy('product_id');

        return view('orders.index', compact('orders', 'userReviews', 'purchased'));
    }

    public function show(Order $order, PurchasedProductService $purchased): View
    {
        $this->authorize('view', $order);

        $order->load(['items.product', 'payment', 'refund']);

        $userReviews = Review::query()
            ->where('user_id', $order->user_id)
            ->whereIn('product_id', $order->items->pluck('product_id')->filter())
            ->get()
            ->keyBy('product_id');

        return view('orders.show', compact('order', 'userReviews', 'purchased'));
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
