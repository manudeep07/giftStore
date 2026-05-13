<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewModerationController extends Controller
{
    public function index(): View
    {
        $reviews = Review::query()
            ->with(['user:id,name', 'product:id,name,slug'])
            ->latest()
            ->paginate(25);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Review is live on the PDP.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review discarded.');
    }
}
