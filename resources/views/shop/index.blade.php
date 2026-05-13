@extends('layouts.store')

@section('title', 'Shop customizable gifts')

@section('content')
    <div class="flex flex-col gap-8 lg:flex-row lg:items-start">
        <aside class="w-full rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:max-w-xs lg:sticky lg:top-24">
            <h1 class="font-[family-name:var(--font-serif)] text-2xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Shop</h1>
            <p class="mt-2 text-sm text-slate-600">Filter collections tuned from the admin taxonomy.</p>

            <form method="get" class="mt-6 space-y-4">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
                    <input type="search" name="q" value="{{ request('q') }}" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10" placeholder="Search keyword…" />
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Category</label>
                    <select name="category" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                        <option value="">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Apply</button>
            </form>
        </aside>

        <div class="flex-1 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Catalog</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $products->total() }} heirloom-ready SKUs</p>
                </div>
                <p class="text-sm text-slate-600">Sorted newest → oldest</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-200 bg-white p-16 text-center text-slate-600">
                        Nothing matches yet — widen filters or seed merchandising data.
                    </div>
                @endforelse
            </div>

            <div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
