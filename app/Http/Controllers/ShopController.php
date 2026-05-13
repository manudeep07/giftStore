<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Category-aware catalog browsing with lightweight search. */
class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['category', 'images']);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
        }

        if ($search = $request->string('q')->trim()) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('shop.index', compact('products', 'categories'));
    }
}
