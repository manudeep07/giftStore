<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('view', $product);

        /** @var \App\Models\User $user */
        $user = $request->user();

        Review::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
            ],
            [
                'rating' => $request->validated('rating'),
                'title' => $request->validated('title'),
                'body' => $request->validated('body'),
                'is_approved' => false,
            ],
        );

        return back()->with('success', 'Thanks — reviews publish after a quick moderation pass.');
    }
}
