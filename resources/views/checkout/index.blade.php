@extends('layouts.store')

@section('title', 'Checkout')

@section('content')
    <div class="grid gap-10 xl:grid-cols-[minmax(0,1.65fr)_minmax(340px,1fr)] xl:items-start">
        <div class="w-full min-w-0 space-y-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-lg ring-1 ring-slate-900/5 md:p-10 lg:p-12">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Delivery</p>
                <h1 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Where should we send the magic?</h1>
            </div>

            <form action="{{ route('checkout.store') }}" method="post" class="space-y-8">
                @csrf

                <div class="grid gap-6 sm:grid-cols-2">
                    <label class="block text-sm font-semibold text-slate-800">Full name
                        <input name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-inner" />
                    </label>
                    <label class="block text-sm font-semibold text-slate-800">Email
                        <input type="email" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email) }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-inner" />
                    </label>
                    <label class="block text-sm font-semibold text-slate-800 sm:col-span-2">Phone
                        <input name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" required class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-inner" />
                    </label>
                </div>

                <fieldset class="space-y-6 rounded-2xl border border-slate-100 bg-slate-50/50 p-6 md:p-8">
                    <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-500">Delivery address</legend>

                    <label class="block text-sm font-semibold text-slate-800">Address line 1
                        <input name="shipping_address_line1" value="{{ old('shipping_address_line1') }}" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-inner" />
                    </label>
                    <label class="block text-sm font-semibold text-slate-800">Address line 2
                        <input name="shipping_address_line2" value="{{ old('shipping_address_line2') }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-inner" />
                    </label>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <label class="block text-sm font-semibold text-slate-800">City
                            <input name="shipping_city" value="{{ old('shipping_city') }}" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-inner" />
                        </label>
                        <label class="block text-sm font-semibold text-slate-800">State / Region
                            <input name="shipping_state" value="{{ old('shipping_state') }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-inner" />
                        </label>
                        <label class="block text-sm font-semibold text-slate-800">Postal code
                            <input name="shipping_postal" value="{{ old('shipping_postal') }}" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-inner" />
                        </label>
                        <label class="block text-sm font-semibold text-slate-800">Country
                            <input name="shipping_country" value="{{ old('shipping_country', 'India') }}" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-inner" />
                        </label>
                    </div>
                </fieldset>

                <label class="text-sm font-semibold text-slate-800">Order notes
                    <textarea name="notes" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-inner" placeholder="Gate codes, surprise hints, courier preferences…">{{ old('notes') }}</textarea>
                </label>

                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-xl shadow-slate-900/15 transition hover:bg-slate-800" @disabled(! ($razorpayConfigured ?? false))>
                    Continue to Razorpay · ₹{{ number_format($summary['total'], 2) }}
                </button>
                @unless ($razorpayConfigured ?? false)
                    <p class="text-center text-xs text-amber-700">Add <code class="rounded bg-amber-50 px-1">RAZORPAY_KEY_ID</code> and <code class="rounded bg-amber-50 px-1">RAZORPAY_KEY_SECRET</code> to <code class="rounded bg-amber-50 px-1">.env</code> (see HANDOFF.md).</p>
                @else
                    <p class="text-center text-xs text-slate-500">You will complete payment on the next screen. Stock is reserved after payment succeeds.</p>
                @endunless
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
                        <x-customization-summary
                            :snapshot="$item->customization_snapshot"
                            :product-name="$item->product->name"
                            :quantity="$item->quantity"
                            :line-total="$item->line_total"
                            theme="dark"
                            compact
                        />
                    </div>
                @endforeach
            </div>

            @if ($summary['coupon'] ?? null)
                <div class="rounded-2xl bg-emerald-500/15 px-4 py-3 text-sm ring-1 ring-emerald-400/30">
                    <p class="font-semibold text-emerald-100">{{ $summary['coupon']->code }} applied</p>
                    <p class="text-xs text-emerald-200/90">{{ $summary['coupon']->discountLabel() }} · saving ₹{{ number_format($summary['discount'], 2) }}</p>
                </div>
            @endif

            <x-available-coupons :available-coupons="$availableCoupons" :summary="$summary" theme="dark" />

            <dl class="space-y-3 border-t border-white/10 pt-6 text-sm">
                <div class="flex justify-between"><dt>Subtotal</dt><dd>₹{{ number_format($summary['subtotal'], 2) }}</dd></div>
                <div class="flex justify-between"><dt>Discount @if($summary['coupon'])({{ $summary['coupon']->code }})@endif</dt><dd>- ₹{{ number_format($summary['discount'], 2) }}</dd></div>
                <div class="flex justify-between"><dt>Tax ({{ number_format(config('customgift.tax_rate') * 100, 0) }}%)</dt><dd>₹{{ number_format($summary['tax'], 2) }}</dd></div>
                <div class="flex justify-between"><dt>Shipping</dt><dd>₹{{ number_format($summary['shipping'], 2) }}</dd></div>
                <div class="flex justify-between text-lg font-semibold"><dt>Total</dt><dd>₹{{ number_format($summary['total'], 2) }}</dd></div>
            </dl>

        </aside>
    </div>
@endsection
