<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomizationOptionRequest;
use App\Models\CustomizationOption;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomizationOptionController extends Controller
{
    public function create(Product $product): View
    {
        return view('admin.options.create', compact('product'));
    }

    public function store(StoreCustomizationOptionRequest $request, Product $product): RedirectResponse
    {
        CustomizationOption::query()->create([
            'product_id' => $product->id,
            'option_group' => $request->validated('option_group'),
            'value_key' => $request->validated('value_key'),
            'label' => $request->validated('label'),
            'price_adjustment' => $request->validated('price_adjustment'),
            'meta' => $request->validated('meta'),
            'is_default' => $request->boolean('is_default'),
            'sort_order' => $request->validated('sort_order') ?? 0,
        ]);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Pricing rule attached.');
    }

    public function edit(Product $product, CustomizationOption $option): View
    {
        abort_if($option->product_id !== $product->id, 404);

        return view('admin.options.edit', compact('product', 'option'));
    }

    public function update(Request $request, Product $product, CustomizationOption $option): RedirectResponse
    {
        abort_if($option->product_id !== $product->id, 404);

        $data = $request->validate([
            'option_group' => ['required', 'string', 'max:64'],
            'value_key' => ['required', 'string', 'max:128'],
            'label' => ['required', 'string', 'max:255'],
            'price_adjustment' => ['required', 'numeric'],
            'meta' => ['nullable', 'array'],
            'is_default' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $option->update([
            ...$data,
            'is_default' => $request->boolean('is_default'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Pricing rule saved.');
    }

    public function destroy(Product $product, CustomizationOption $option): RedirectResponse
    {
        abort_if($option->product_id !== $product->id, 404);

        $option->delete();

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Pricing rule removed.');
    }
}
