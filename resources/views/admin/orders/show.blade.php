@extends('layouts.admin')

@section('title', $order->order_number)
@section('heading', 'Order management')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
        <section class="rounded-3xl border border-white/10 bg-white/5 p-8">
            <p class="text-xs uppercase tracking-wide text-slate-400">Customer</p>
            <p class="mt-3 text-lg font-semibold text-white">{{ $order->user?->name }}</p>
            <p class="text-sm text-slate-400">{{ $order->user?->email }}</p>

            <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-slate-400">Order status</dt>
                    <dd class="mt-1 font-semibold capitalize text-white">{{ $order->status }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">Payment status</dt>
                    <dd class="mt-1 font-semibold capitalize text-white">{{ $order->payment?->status ?? '—' }}</dd>
                </div>
            </dl>

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
                <p class="text-xs uppercase tracking-wide text-slate-400">Update fulfillment status</p>
                <form action="{{ route('admin.orders.update', $order) }}" method="post" class="mt-4 flex flex-wrap items-center gap-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="rounded-2xl border border-white/10 bg-slate-950 px-4 py-2 text-sm text-white">
                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected($order->status === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-900" type="submit">Update</button>
                </form>
            </div>

            @can('refund', $order)
                <div class="mt-6 rounded-2xl border border-amber-500/30 bg-amber-500/10 p-4">
                    <p class="text-xs uppercase tracking-wide text-amber-200">Process refund</p>
                    <p class="mt-2 text-xs text-amber-100/80">Customer cancelled this paid order. Record the refund and send a confirmation email.</p>
                    <form action="{{ route('admin.orders.refund', $order) }}" method="post" class="mt-4 space-y-3" onsubmit="return confirm('Mark as refunded and email the customer?');">
                        @csrf
                        <textarea name="reason" rows="2" class="w-full rounded-xl border border-white/10 bg-slate-950 px-3 py-2 text-sm text-white" placeholder="Optional note for the customer email">{{ old('reason') }}</textarea>
                        <button type="submit" class="rounded-full bg-amber-400 px-4 py-2 text-xs font-semibold text-slate-900">Process refund · ₹{{ number_format($order->total, 2) }}</button>
                    </form>
                </div>
            @elseif ($order->refund)
                <p class="mt-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    Refunded {{ $order->refund->processed_at?->format('M j, Y g:i A') }} · {{ $order->refund->reference }}
                </p>
            @endif
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

            @if ($order->payment)
                <div class="mt-6 rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-xs text-slate-400">
                    <p>Razorpay · {{ $order->payment->transaction_ref ?? 'pending' }}</p>
                </div>
            @endif

            <div class="mt-6 space-y-4">
                @foreach ($order->items as $item)
                    <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4 text-xs text-slate-300">
                        <div class="flex justify-between text-sm font-semibold text-white">
                            <span>{{ $item->product_name }}</span>
                            <span>× {{ $item->quantity }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
@endsection
