<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Services\PurchasedProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderReviewController extends Controller
{
    public function create(
        Order $order,
        OrderItem $orderItem,
        PurchasedProductService $purchased,
    ): View|RedirectResponse {
        $this->authorize('view', $order);
        abort_if($orderItem->order_id !== $order->id, 404);
        abort_if(! $orderItem->product_id, 404);

        $product = Product::query()->findOrFail($orderItem->product_id);
        $user = request()->user();

        if (! $purchased->hasPurchased($user, $product->id)) {
            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'You can only review products you have purchased and paid for.');
        }

        $existing = Review::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('orders.show', $order)
                ->with('info', 'You already submitted a review for this product.');
        }

        return view('orders.review', [
            'order' => $order,
            'orderItem' => $orderItem,
            'product' => $product,
        ]);
    }

    public function store(
        StoreReviewRequest $request,
        Order $order,
        OrderItem $orderItem,
        PurchasedProductService $purchased,
    ): RedirectResponse {
        $this->authorize('view', $order);
        abort_if($orderItem->order_id !== $order->id, 404);
        abort_if(! $orderItem->product_id, 404);

        $product = Product::query()->findOrFail($orderItem->product_id);
        $user = $request->user();

        if (! $purchased->canReview($user, $product->id)) {
            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'This product is not eligible for a new review.');
        }

        Review::query()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $request->validated('rating'),
            'title' => $request->validated('title'),
            'body' => $request->validated('body'),
            'is_approved' => false,
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Thanks — your review will appear after moderation.');
    }
}
