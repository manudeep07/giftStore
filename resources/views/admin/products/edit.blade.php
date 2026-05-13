@extends('layouts.admin')

@section('title', $product->name)
@section('heading', 'Edit SKU')

@section('content')
    <form action="{{ route('admin.products.update', $product) }}" method="post" enctype="multipart/form-data" class="max-w-4xl space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8">
        @csrf
        @method('PUT')

        <label class="block text-sm font-semibold text-white">Category
            <select name="category_id" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected($product->category_id === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>

        <label class="block text-sm font-semibold text-white">Name
            <input name="name" value="{{ old('name', $product->name) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Slug
            <input name="slug" value="{{ old('slug', $product->slug) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Description
            <textarea name="description" rows="4" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white">{{ old('description', $product->description) }}</textarea>
        </label>

        <div class="grid gap-6 md:grid-cols-2">
            <label class="block text-sm font-semibold text-white">Base price (₹)
                <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
            </label>
            <label class="block text-sm font-semibold text-white">Stock
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
            </label>
        </div>

        <label class="block text-sm font-semibold text-white">Badge label
            <input name="badge_label" value="{{ old('badge_label', $product->badge_label) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <div class="flex flex-wrap gap-6 text-sm text-white">
            <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" class="rounded border-white/20 bg-transparent" @checked(old('is_featured', $product->is_featured))> Featured</label>
            <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" class="rounded border-white/20 bg-transparent" @checked(old('is_active', $product->is_active))> Active</label>
        </div>

        <label class="block text-sm font-semibold text-white">Append gallery assets
            <input type="file" name="images[]" multiple class="mt-2 w-full text-sm text-slate-300 file:mr-4 file:rounded-xl file:border-0 file:bg-white file:px-4 file:py-2 file:text-xs file:font-semibold file:text-slate-900" />
        </label>

        <button class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900">Update listing</button>
    </form>

    <section class="mt-10 space-y-4 rounded-3xl border border-white/10 bg-white/5 p-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Gallery integrity</p>
                <p class="text-lg font-semibold text-white">Hero imagery</p>
            </div>
            <a href="{{ route('admin.products.options.create', $product) }}" class="rounded-full border border-white/20 px-4 py-2 text-xs font-semibold text-white hover:bg-white/10">New pricing rule</a>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ($product->images as $image)
                <div class="rounded-2xl border border-white/10 bg-slate-950/40 p-3">
                    <div class="aspect-square overflow-hidden rounded-xl bg-black">
                        <img src="{{ $product->imageUrl($image->path) }}" alt="{{ $image->alt }}" class="h-full w-full object-cover" loading="lazy" />
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs font-semibold">
                        @if ($image->is_primary)
                            <span class="text-emerald-300">Primary</span>
                        @else
                            <form action="{{ route('admin.products.images.primary', [$product, $image]) }}" method="post">
                                @csrf
                                <button class="text-white hover:underline" type="submit">Make hero</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.products.images.destroy', [$product, $image]) }}" method="post" onsubmit="return confirm('Delete asset?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-rose-300" type="submit">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-10 rounded-3xl border border-white/10 bg-white/5 p-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Economics</p>
                <p class="text-lg font-semibold text-white">Customization matrix</p>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-white/10">
            <table class="min-w-full divide-y divide-white/5 text-xs">
                <thead class="bg-white/5 text-left uppercase tracking-wide text-slate-400">
                    <tr>
                        <th class="px-4 py-3">Group</th>
                        <th class="px-4 py-3">Key</th>
                        <th class="px-4 py-3">Label</th>
                        <th class="px-4 py-3">Δ ₹</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                    @foreach ($product->customizationOptions as $option)
                        <tr>
                            <td class="px-4 py-3">{{ $option->option_group }}</td>
                            <td class="px-4 py-3 font-mono text-[11px] text-slate-400">{{ $option->value_key }}</td>
                            <td class="px-4 py-3">{{ $option->label }}</td>
                            <td class="px-4 py-3">₹{{ number_format($option->price_adjustment, 2) }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.products.options.edit', [$product, $option]) }}" class="text-white hover:underline">Edit</a>
                                <form action="{{ route('admin.products.options.destroy', [$product, $option]) }}" method="post" class="inline" onsubmit="return confirm('Delete rule?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-rose-300" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
