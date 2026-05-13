<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;

/** Lightweight gallery hygiene endpoints isolated from core SKU edits. */
class ProductImageController extends Controller
{
    public function destroy(Product $product, ProductImage $image): RedirectResponse
    {
        abort_if($image->product_id !== $product->id, 404);

        if ($image->is_primary) {
            $next = $product->images()->whereKeyNot($image->id)->orderBy('sort_order')->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        $image->delete();

        return back()->with('success', 'Image removed.');
    }

    public function primary(Product $product, ProductImage $image): RedirectResponse
    {
        abort_if($image->product_id !== $product->id, 404);

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Hero creative updated.');
    }
}
