@extends('layouts.admin')

@section('title', 'New pricing rule')
@section('heading', $product->name)

@section('content')
    <form action="{{ route('admin.products.options.store', $product) }}" method="post" class="max-w-3xl space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8">
        @csrf
        <p class="text-sm text-slate-400">Groups map directly to storefront controls (`material`, `size`, `addon`, `image_upload`, …).</p>

        <label class="block text-sm font-semibold text-white">Option group
            <input name="option_group" value="{{ old('option_group') }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Value key
            <input name="value_key" value="{{ old('value_key') }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Customer-facing label
            <input name="label" value="{{ old('label') }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Price adjustment (₹)
            <input type="number" step="0.01" name="price_adjustment" value="{{ old('price_adjustment', 0) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Sort order
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="inline-flex items-center gap-2 text-sm text-white">
            <input type="checkbox" name="is_default" value="1" class="rounded border-white/20 bg-transparent" @checked(old('is_default'))>
            Default selection for group
        </label>

        <div class="flex gap-3">
            <button class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900">Save rule</button>
            <a href="{{ route('admin.products.edit', $product) }}" class="rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10">Cancel</a>
        </div>
    </form>
@endsection
