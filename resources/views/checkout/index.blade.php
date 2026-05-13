@extends('layouts.store')

@section('title', 'Checkout')

@section('content')
    <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
        <div class="space-y-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-lg ring-1 ring-slate-900/5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Delivery</p>
                <h1 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Where should we send the magic?</h1>
            </div>

            <form action="{{ route('checkout.store') }}" method="post" class="space-y-6">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <label class="text-sm font-semibold text-slate-800">Full name
                        <input name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                    </label>
                    <label class="text-sm font-semibold text-slate-800">Email
                        <input type="email" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email) }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                    </label>
                    <label class="text-sm font-semibold text-slate-800 md:col-span-2">Phone
                        <input name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                    </label>
                </div>

                <label class="text-sm font-semibold text-slate-800">Address line 1
                    <input name="shipping_address_line1" value="{{ old('shipping_address_line1') }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                </label>
                <label class="text-sm font-semibold text-slate-800">Address line 2
                    <input name="shipping_address_line2" value="{{ old('shipping_address_line2') }}" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                </label>

                <div class="grid gap-5 md:grid-cols-2">
                    <label class="text-sm font-semibold text-slate-800">City
                        <input name="shipping_city" value="{{ old('shipping_city') }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                    </label>
                    <label class="text-sm font-semibold text-slate-800">State / Region
                        <input name="shipping_state" value="{{ old('shipping_state') }}" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                    </label>
                    <label class="text-sm font-semibold text-slate-800">Postal code
                        <input name="shipping_postal" value="{{ old('shipping_postal') }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                    </label>
                    <label class="text-sm font-semibold text-slate-800">Country
                        <input name="shipping_country" value="{{ old('shipping_country', 'India') }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" />
                    </label>
                </div>

                <label class="text-sm font-semibold text-slate-800">Order notes
                    <textarea name="notes" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" placeholder="Gate codes, surprise hints, courier preferences…">{{ old('notes') }}</textarea>
                </label>

                <label class="text-sm font-semibold text-slate-800">Coupon
                    <input name="coupon_code" value="{{ old('coupon_code', $summary['coupon_code']) }}" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner uppercase" placeholder="WELCOME10" />
                </label>

                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-xl shadow-slate-900/15 transition hover:bg-slate-800">
                    Pay ₹{{ number_format($summary['total'], 2) }} · Secure placeholder gateway
                </button>
                <p class="text-center text-xs text-slate-500">Psst — swap the payment factory inside <code class="rounded bg-slate-100 px-2 py-1 text-[11px]">CheckoutController</code> for Razorpay/Stripe.</p>
            </form>
        </div>

        <aside class="space-y-6 rounded-3xl border border-slate-200 bg-slate-900 p-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-wide text-white/70">Order summary</p>
                <span class="rounded-full bg-white/10 px-3 py-1 text-[11px] font-semibold">Inclusive preview</span>
            </div>

            <div class="space-y-4">
                @foreach ($cart->items as $item)
                    <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
                        <div class="flex justify-between gap-4 text-sm font-semibold">
                            <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                            <span>₹{{ number_format($item->line_total, 2) }}</span>
                        </div>
                        <p class="mt-2 text-xs text-white/70 line-clamp-3">{{ json_encode($item->customization_snapshot['selections'] ?? []) }}</p>
                    </div>
                @endforeach
            </div>

            <dl class="space-y-3 border-t border-white/10 pt-6 text-sm">
                <div class="flex justify-between"><dt>Subtotal</dt><dd>₹{{ number_format($summary['subtotal'], 2) }}</dd></div>
                <div class="flex justify-between"><dt>Discount</dt><dd>- ₹{{ number_format($summary['discount'], 2) }}</dd></div>
                <div class="flex justify-between"><dt>Tax ({{ number_format(config('customgift.tax_rate') * 100, 0) }}%)</dt><dd>₹{{ number_format($summary['tax'], 2) }}</dd></div>
                <div class="flex justify-between"><dt>Shipping</dt><dd>₹{{ number_format($summary['shipping'], 2) }}</dd></div>
                <div class="flex justify-between text-lg font-semibold"><dt>Total</dt><dd>₹{{ number_format($summary['total'], 2) }}</dd></div>
            </dl>

            <div class="rounded-2xl bg-white/10 p-4 text-xs text-white/80">
                <form action="{{ route('checkout.coupon.store') }}" method="post" class="flex gap-3">
                    @csrf
                    <input name="coupon_code" class="flex-1 rounded-xl border border-white/20 bg-transparent px-3 py-2 text-sm text-white placeholder:text-white/40" placeholder="Apply coupon" />
                    <button class="rounded-xl bg-white px-3 py-2 text-xs font-semibold text-slate-900">Apply</button>
                </form>
                @if ($summary['coupon'])
                    <form action="{{ route('checkout.coupon.destroy') }}" method="post" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs font-semibold text-white underline">Remove {{ $summary['coupon']->code }}</button>
                    </form>
                @endif
            </div>
        </aside>
    </div>
@endsection
