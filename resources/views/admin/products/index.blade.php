@extends('layouts.admin')

@section('title', 'Products')
@section('heading', 'Catalog')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-slate-400">SKUs inherit customization economics via linked option rows.</p>
        <a href="{{ route('admin.products.create') }}" class="rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-900 shadow-lg shadow-black/30">New product</a>
    </div>

    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
        <table class="min-w-full divide-y divide-white/5 text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Base</th>
                    <th class="px-6 py-4">Stock</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-200">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-6 py-4 font-semibold">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-xs text-slate-400">{{ $product->category?->name }}</td>
                        <td class="px-6 py-4 text-xs">₹{{ number_format($product->base_price, 2) }}</td>
                        <td class="px-6 py-4 text-xs">{{ $product->stock }}</td>
                        <td class="px-6 py-4 text-right text-xs font-semibold">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-white hover:underline">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="inline pl-3" onsubmit="return confirm('Archive SKU?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-rose-300 hover:text-rose-100" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
@endsection
