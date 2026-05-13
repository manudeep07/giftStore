@extends('layouts.store')

@section('title', $order->order_number)

@section('content')
    <div class="space-y-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Order</p>
                <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">{{ $order->order_number }}</h1>
                <p class="mt-2 text-sm text-slate-600 capitalize">Status · {{ $order->status }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('orders.invoice', $order) }}" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-900 hover:border-slate-300">Invoice</a>
                <a href="{{ route('orders.index') }}" class="rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">All orders</a>
            </div>
        </div>

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
                <p class="mt-4 text-sm text-slate-600">Provider · {{ $order->payment?->provider }}</p>
                <p class="text-sm text-slate-600">Reference · {{ $order->payment?->transaction_ref }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">₹{{ number_format($order->total, 2) }}</p>
            </section>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Line items</h2>
            <div class="mt-6 space-y-4">
                @foreach ($order->items as $item)
                    <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <div class="flex flex-wrap justify-between gap-4">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $item->product_name }}</p>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Qty {{ $item->quantity }}</p>
                            </div>
                            <p class="text-lg font-semibold text-slate-900">₹{{ number_format($item->line_total, 2) }}</p>
                        </div>
                        <pre class="mt-3 overflow-x-auto rounded-xl bg-white p-3 text-xs text-slate-600">{{ json_encode($item->customization_snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
@endsection
