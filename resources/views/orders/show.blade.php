@extends('layouts.store')

@section('title', $order->order_number)

@section('content')
    <div class="space-y-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Order</p>
                <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">{{ $order->order_number }}</h1>
                <p class="mt-2 text-sm text-slate-600 capitalize">Order status · <span class="font-semibold">{{ $order->status }}</span></p>
                <p class="text-sm text-slate-600 capitalize">Payment · <span class="font-semibold">{{ $order->payment?->status ?? 'n/a' }}</span></p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if ($order->payment && $order->payment->status === 'pending')
                    <a href="{{ route('checkout.pay', $order) }}" class="rounded-2xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">Pay now</a>
                @endif
                @can('cancel', $order)
                    <form action="{{ route('orders.cancel', $order) }}" method="post" onsubmit="return confirm('Cancel this order?');">
                        @csrf
                        <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100">Cancel order</button>
                    </form>
                @endcan
                <a href="{{ route('orders.invoice', $order) }}" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-900 hover:border-slate-300">Invoice</a>
                <a href="{{ route('orders.index') }}" class="rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">All orders</a>
            </div>
        </div>

        @if ($order->status === 'cancelled' && $order->payment?->status === 'paid' && ! $order->refund)
            <p class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">Cancellation received. Refund is pending — our team will process it shortly.</p>
        @endif

        @if ($order->refund)
            <p class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                Refund processed · ₹{{ number_format($order->refund->amount, 2) }} ({{ $order->refund->reference }})
            </p>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Shipping</h2>
                <p class="mt-4 text-sm leading-relaxed text-slate-600">
                    {{ $order->shipping_name }}<br>
                    {{ $order->shipping_email }} · {{ $order->shipping_phone }}<br>
                    {{ $order->shipping_address_line1 }}<br>
                    @if ($order->shipping_address_line2) {{ $order->shipping_address_line2 }}<br> @endif
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal }}<br>
                    {{ $order->shipping_country }}
                </p>
                @if ($order->notes)
                    <p class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">{{ $order->notes }}</p>
                @endif
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Payment</h2>
                <p class="mt-4 text-sm text-slate-600">Provider · {{ $order->payment?->provider ?? '—' }}</p>
                <p class="text-sm text-slate-600 capitalize">Status · {{ $order->payment?->status ?? 'n/a' }}</p>
                <p class="text-sm text-slate-600">Reference · {{ $order->payment?->transaction_ref ?? 'Awaiting payment' }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">₹{{ number_format($order->total, 2) }}</p>
            </section>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Line items</h2>
            <div class="mt-6 space-y-4">
                @foreach ($order->items as $item)
                    <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <x-customization-summary
                                    :snapshot="$item->customization_snapshot"
                                    :product-name="$item->product_name"
                                    :quantity="$item->quantity"
                                    :line-total="$item->line_total"
                                />
                            </div>
                            @if ($item->product_id && $order->payment?->status === 'paid')
                                <div class="shrink-0 text-right">
                                    @if ($purchased->canReview(auth()->user(), $item->product_id))
                                        <a href="{{ route('orders.reviews.create', [$order, $item]) }}" class="inline-flex rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">Write review</a>
                                    @elseif ($userReviews->has($item->product_id))
                                        <span class="text-xs font-semibold text-slate-600">
                                            {{ $userReviews[$item->product_id]->is_approved ? 'Review published' : 'Review pending approval' }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
@endsection
