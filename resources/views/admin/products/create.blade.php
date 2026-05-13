@extends('layouts.admin')

@section('title', 'Create product')
@section('heading', 'New SKU')

@section('content')
    <form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data" class="max-w-4xl space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8">
        @csrf

        <label class="block text-sm font-semibold text-white">Category
            <select name="category_id" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </label>

        <label class="block text-sm font-semibold text-white">Name
            <input name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Slug (optional)
            <input name="slug" value="{{ old('slug') }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Description
            <textarea name="description" rows="4" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white">{{ old('description') }}</textarea>
        </label>

        <div class="grid gap-6 md:grid-cols-2">
            <label class="block text-sm font-semibold text-white">Base price (₹)
                <input type="number" step="0.01" name="base_price" value="{{ old('base_price') }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
            </label>
            <label class="block text-sm font-semibold text-white">Stock
                <input type="number" name="stock" value="{{ old('stock', 25) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
            </label>
        </div>

        <label class="block text-sm font-semibold text-white">Badge label (optional)
            <input name="badge_label" value="{{ old('badge_label') }}" placeholder="Bestseller" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <div class="flex flex-wrap gap-6 text-sm text-white">
            <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" class="rounded border-white/20 bg-transparent" @checked(old('is_featured'))> Featured</label>
            <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" class="rounded border-white/20 bg-transparent" @checked(old('is_active', true))> Active</label>
        </div>

        <label class="block text-sm font-semibold text-white">Gallery (max 8)
            <input type="file" name="images[]" multiple class="mt-2 w-full text-sm text-slate-300 file:mr-4 file:rounded-xl file:border-0 file:bg-white file:px-4 file:py-2 file:text-xs file:font-semibold file:text-slate-900" />
        </label>

        <button class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900">Save draft</button>
    </form>
@endsection
