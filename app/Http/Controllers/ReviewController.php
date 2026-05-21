<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /** @deprecated Product page is read-only; use OrderReviewController from My Orders. */
    public function store(StoreReviewRequest $request, Product $product): RedirectResponse
    {
        return redirect()
            ->route('orders.index')
            ->with('info', 'Please submit reviews from My Orders after your purchase is paid.');
    }
}
