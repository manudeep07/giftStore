<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Full merchandise CRUD including gallery ingestion on the public disk.
 */
class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('category:id,name')
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $slug = $request->filled('slug')
            ? Str::slug($request->string('slug'))
            : Str::slug($request->string('name'));

        /** @var Product $product */
        $product = DB::transaction(function () use ($request, $slug): Product {
            /** @var Product $product */
            $product = Product::query()->create([
                'category_id' => $request->validated('category_id'),
                'name' => $request->validated('name'),
                'slug' => $slug,
                'description' => $request->validated('description'),
                'base_price' => $request->validated('base_price'),
                'stock' => $request->validated('stock'),
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active', true),
                'badge_label' => $request->validated('badge_label'),
            ]);

            $files = $request->file('images', []);
            foreach ($files as $index => $file) {
                if (! $file) {
                    continue;
                }

                $path = $file->store('products/gallery', 'public');

                ProductImage::query()->create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'alt' => $product->name,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }

            return $product;
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product drafted — layer customization economics next.');
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'customizationOptions']);
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $slug = $request->filled('slug')
            ? Str::slug($request->string('slug'))
            : Str::slug($request->string('name'));

        DB::transaction(function () use ($request, $product, $slug): void {
            $product->update([
                'category_id' => $request->validated('category_id'),
                'name' => $request->validated('name'),
                'slug' => $slug,
                'description' => $request->validated('description'),
                'base_price' => $request->validated('base_price'),
                'stock' => $request->validated('stock'),
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active', true),
                'badge_label' => $request->validated('badge_label'),
            ]);

            $existingMax = (int) $product->images()->max('sort_order');

            $files = $request->file('images', []);
            foreach ($files as $offset => $file) {
                if (! $file) {
                    continue;
                }

                $path = $file->store('products/gallery', 'public');
                $existingMax++;

                ProductImage::query()->create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'alt' => $product->name,
                    'sort_order' => $existingMax,
                    'is_primary' => false,
                ]);
            }
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product refreshed.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product archived.');
    }
}
