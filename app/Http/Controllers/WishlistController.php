<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()
            ->wishlistItems()
            ->with(['product.images', 'product.category'])
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('view', $product);

        $user = $request->user();

        $existing = WishlistItem::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success', 'Removed from wishlist.');
        }

        WishlistItem::query()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Saved to wishlist.');
    }
}
