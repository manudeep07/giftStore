@extends('layouts.store')

@section('title', 'Your cart')

@section('content')
    <div class="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
        <div class="flex-1 space-y-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Basket</p>
                <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Thoughtfully queued creations</h1>
            </div>

            @forelse ($cart->items as $item)
                @php
                    $snapshot = $item->customization_snapshot;
                    $selections = $snapshot['selections'] ?? [];
                @endphp
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
                    <div class="flex flex-col gap-6 lg:flex-row lg:justify-between">
                        <div class="flex-1 space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-xl font-semibold text-slate-900">{{ $item->product->name }}</h2>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">₹{{ number_format($item->unit_price, 2) }} each</span>
                            </div>
                            <dl class="grid gap-3 text-sm text-slate-600 sm:grid-cols-2">
                                @foreach (['material','size','color','font','gift_wrap','engraving'] as $key)
                                    @if (! empty($selections[$key]))
                                        <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-900/5">
                                            <dt class="text-xs uppercase tracking-wide text-slate-500">{{ ucfirst(str_replace('_',' ', $key)) }}</dt>
                                            <dd class="font-medium text-slate-900">{{ $selections[$key] }}</dd>
                                        </div>
                                    @endif
                                @endforeach
                                @if (! empty($selections['addons']))
                                    <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-900/5 sm:col-span-2">
                                        <dt class="text-xs uppercase tracking-wide text-slate-500">Add-ons</dt>
                                        <dd class="font-medium text-slate-900">{{ implode(', ', $selections['addons']) }}</dd>
                                    </div>
                                @endif
                                @if (! empty($selections['custom_text']))
                                    <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-900/5 sm:col-span-2">
                                        <dt class="text-xs uppercase tracking-wide text-slate-500">Message</dt>
                                        <dd class="font-medium text-slate-900 whitespace-pre-wrap">{{ $selections['custom_text'] }}</dd>
                                    </div>
                                @endif
                                @if (! empty($snapshot['upload_path']))
                                    <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-900/5 sm:col-span-2">
                                        <dt class="text-xs uppercase tracking-wide text-slate-500">Upload</dt>
                                        <dd><img src="{{ asset('storage/'.$snapshot['upload_path']) }}" alt="Upload" class="mt-2 max-h-40 rounded-xl border border-slate-200 object-contain" /></dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <div class="w-full max-w-xs space-y-4 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <form action="{{ route('cart.items.update', $item) }}" method="post" class="flex items-center gap-3">
                                @csrf
                                @method('PATCH')
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Qty</label>
                                <input type="number" min="1" name="quantity" value="{{ $item->quantity }}" class="w-20 rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                                <button class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white" type="submit">Update</button>
                            </form>
                            <form action="{{ route('cart.items.destroy', $item) }}" method="post" onsubmit="return confirm('Remove this bespoke line?');">
                                @csrf
                                @method('DELETE')
                                <button class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700" type="submit">Remove</button>
                            </form>
                            <p class="text-right text-lg font-semibold text-slate-900">₹{{ number_format($item->line_total, 2) }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-16 text-center text-slate-600">
                    Nothing bespoke yet — <a href="{{ route('shop.index') }}" class="font-semibold text-slate-900 underline">continue shopping</a>.
                </div>
            @endforelse
        </div>

        <aside class="w-full rounded-3xl border border-slate-200 bg-white p-6 shadow-lg lg:max-w-sm lg:sticky lg:top-28">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Summary</p>
            <p class="mt-4 text-4xl font-semibold text-slate-900">₹{{ number_format((float) $cart->subtotal(), 2) }}</p>
            <p class="mt-2 text-sm text-slate-600">Tax & coupons finalize during authenticated checkout.</p>

            @auth
                <a href="{{ route('checkout.index') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800 {{ $cart->items->isEmpty() ? 'pointer-events-none opacity-40' : '' }}">
                    Proceed to checkout
                </a>
            @else
                <div class="mt-6 space-y-3 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                    <p>Login to secure bespoke totals and unlock concierge tracking.</p>
                    <div class="flex gap-3">
                        <a href="{{ route('login') }}" class="flex-1 rounded-xl bg-slate-900 px-3 py-2 text-center text-xs font-semibold text-white">Login</a>
                        <a href="{{ route('register') }}" class="flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-center text-xs font-semibold text-slate-900">Register</a>
                    </div>
                </div>
            @endauth

            <a href="{{ route('shop.index') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 hover:border-slate-300">
                Continue customizing
            </a>
        </aside>
    </div>
@endsection
