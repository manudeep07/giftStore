@extends('layouts.store')

@section('title', 'Wishlist')

@section('content')
    <div class="space-y-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Saved</p>
            <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Pieces worth returning to</h1>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($items as $wishlist)
                <div class="space-y-3">
                    <x-product-card :product="$wishlist->product" />
                    <form action="{{ route('wishlist.toggle', $wishlist->product) }}" method="post">
                        @csrf
                        <button class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-slate-300" type="submit">Remove</button>
                    </form>
                </div>
            @empty
                <p class="col-span-full rounded-3xl border border-dashed border-slate-200 bg-white p-12 text-center text-slate-600">Wishlist is resting — discover something luminous in the shop.</p>
            @endforelse
        </div>

        {{ $items->links() }}
    </div>
@endsection
