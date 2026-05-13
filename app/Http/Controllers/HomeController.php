<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Marketing homepage with merchandising rails driven by real catalog data.
 */
class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $featured = Product::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'images'])
            ->latest()
            ->take(8)
            ->get();

        $trending = Product::query()
            ->where('is_active', true)
            ->with(['category', 'images'])
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->take(8)
            ->get();

        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(12)
            ->get();

        $recentIds = collect(session('recent_products', []))->take(8)->filter()->values()->all();
        $recentProducts = Product::query()
            ->whereIn('id', $recentIds)
            ->where('is_active', true)
            ->with(['category', 'images'])
            ->get();

        $orderMap = array_flip($recentIds);
        $recentSorted = $recentProducts
            ->sortBy(fn (Product $p) => $orderMap[$p->id] ?? 999)
            ->values();

        return view('home', [
            'featuredProducts' => $featured,
            'trendingProducts' => $trending,
            'categories' => $categories,
            'recentProducts' => $recentSorted,
        ]);
    }
}
