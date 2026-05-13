<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** PDP with grouped customization schema for Alpine-driven UX. */
class ProductController extends Controller
{
    public function show(Request $request, Product $product, PricingService $pricing): View
    {
        $this->authorize('view', $product);

        $product->load(['category', 'images', 'customizationOptions']);

        $optionsByGroup = $product->customizationOptions->groupBy('option_group');

        $defaults = [
            'material' => optional($optionsByGroup->get('material'))?->firstWhere('is_default', true)?->value_key
                ?? optional($optionsByGroup->get('material'))?->first()?->value_key,
            'size' => optional($optionsByGroup->get('size'))?->firstWhere('is_default', true)?->value_key
                ?? optional($optionsByGroup->get('size'))?->first()?->value_key,
            'color' => optional($optionsByGroup->get('color'))?->firstWhere('is_default', true)?->value_key
                ?? optional($optionsByGroup->get('color'))?->first()?->value_key,
            'font' => optional($optionsByGroup->get('font'))?->firstWhere('is_default', true)?->value_key
                ?? optional($optionsByGroup->get('font'))?->first()?->value_key,
            'gift_wrap' => optional($optionsByGroup->get('gift_wrap'))?->firstWhere('is_default', true)?->value_key
                ?? optional($optionsByGroup->get('gift_wrap'))?->first()?->value_key,
            'engraving' => optional($optionsByGroup->get('engraving'))?->firstWhere('is_default', true)?->value_key
                ?? optional($optionsByGroup->get('engraving'))?->first()?->value_key,
            'addons' => collect($optionsByGroup->get('addon'))
                ->where('is_default', true)
                ->pluck('value_key')
                ->values()
                ->all(),
            'has_image_upload' => false,
            'custom_text' => '',
        ];

        $recent = collect(session('recent_products', []))->reject(fn ($id) => (int) $id === $product->id);
        $recent->prepend($product->id);
        session(['recent_products' => $recent->take(12)->values()->all()]);

        $reviews = $product->reviews()
            ->where('is_approved', true)
            ->with('user:id,name')
            ->latest()
            ->take(12)
            ->get();

        $initialQuote = $pricing->quote($product, $defaults);

        $optionMeta = $optionsByGroup->mapWithKeys(fn ($opts, $group) => [
            $group => $opts->map(fn ($o) => [
                'value_key' => $o->value_key,
                'meta' => $o->meta,
            ])->values(),
        ])->toArray();

        return view('products.show', [
            'product' => $product,
            'optionsByGroup' => $optionsByGroup,
            'reviews' => $reviews,
            'customDefaults' => $defaults,
            'initialQuote' => $initialQuote,
            'optionMeta' => $optionMeta,
        ]);
    }
}
