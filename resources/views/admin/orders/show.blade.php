@extends('layouts.admin')

@section('title', $order->order_number)
@section('heading', 'Fulfillment')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
        <section class="rounded-3xl border border-white/10 bg-white/5 p-8">
            <p class="text-xs uppercase tracking-wide text-slate-400">Customer</p>
            <p class="mt-3 text-lg font-semibold text-white">{{ $order->user?->name }}</p>
            <p class="text-sm text-slate-400">{{ $order->user?->email }}</p>

            <div class="mt-6 space-y-2 text-sm text-slate-300">
                <p>{{ $order->shipping_name }}</p>
                <p>{{ $order->shipping_phone }}</p>
                <p>{{ $order->shipping_address_line1 }}</p>
                @if ($order->shipping_address_line2)
                    <p>{{ $order->shipping_address_line2 }}</p>
                @endif
                <p>{{ $order->shipping_city }}, {{ $order->shipping_postal }}</p>
                <p>{{ $order->shipping_country }}</p>
            </div>

            <div class="mt-8 rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-sm text-slate-300">
                <p class="text-xs uppercase tracking-wide text-slate-400">Adjust lifecycle</p>
                <form action="{{ route('admin.orders.update', $order) }}" method="post" class="mt-4 flex flex-wrap items-center gap-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="rounded-2xl border border-white/10 bg-slate-950 px-4 py-2 text-sm text-white">
                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected($order->status === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-900" type="submit">Sync</button>
                </form>
            </div>
        </section>

        <section class="rounded-3xl border border-white/10 bg-white/5 p-8">
            <p class="text-xs uppercase tracking-wide text-slate-400">Economics</p>
            <dl class="mt-4 space-y-2 text-sm text-slate-300">
                <div class="flex justify-between"><dt>Subtotal</dt><dd>₹{{ number_format($order->subtotal, 2) }}</dd></div>
                <div class="flex justify-between"><dt>Discount</dt><dd>- ₹{{ number_format($order->discount_amount, 2) }}</dd></div>
                <div class="flex justify-between"><dt>Tax</dt><dd>₹{{ number_format($order->tax_amount, 2) }}</dd></div>
                <div class="flex justify-between"><dt>Shipping</dt><dd>₹{{ number_format($order->shipping_amount, 2) }}</dd></div>
                <div class="flex justify-between text-lg font-semibold text-white"><dt>Total</dt><dd>₹{{ number_format($order->total, 2) }}</dd></div>
            </dl>

            @if ($order->coupon_code)
                <p class="mt-4 text-xs text-emerald-300">Coupon {{ $order->coupon_code }} applied.</p>
            @endif

            <div class="mt-6 space-y-4">
                @foreach ($order->items as $item)
                    <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-xs text-slate-300">
                        <div class="flex justify-between text-sm font-semibold text-white">
                            <span>{{ $item->product_name }}</span>
                            <span>× {{ $item->quantity }}</span>
                        </div>
                        <pre class="mt-3 overflow-x-auto text-[11px] leading-relaxed">{{ json_encode($item->customization_snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
@endsection
