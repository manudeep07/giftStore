@extends('layouts.store')

@section('title', 'Review '.$product->name)

@section('content')
    <div class="mx-auto max-w-xl space-y-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">My orders · {{ $order->order_number }}</p>
            <h1 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Review {{ $product->name }}</h1>
            <p class="mt-2 text-sm text-slate-600">Only verified buyers can review. Your feedback is moderated before it appears on the product page.</p>
        </div>

        <form action="{{ route('orders.reviews.store', [$order, $orderItem]) }}" method="post" class="space-y-6 rounded-3xl border border-slate-200 bg-white p-8 shadow-lg ring-1 ring-slate-900/5">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="text-sm font-semibold text-slate-800">Rating
                    <select name="rating" required class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }} ★</option>
                        @endfor
                    </select>
                </label>
                <label class="text-sm font-semibold text-slate-800">Title
                    <input name="title" value="{{ old('title') }}" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="Short headline" />
                </label>
            </div>
            <label class="text-sm font-semibold text-slate-800">Your review
                <textarea name="body" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="How was the product and customization experience?">{{ old('body') }}</textarea>
            </label>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800">Submit review</button>
                <a href="{{ route('orders.show', $order) }}" class="rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-900 hover:border-slate-300">Cancel</a>
            </div>
        </form>
    </div>
@endsection
