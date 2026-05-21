@props([
    'availableCoupons' => collect(),
    'summary' => [],
    'theme' => 'light',
])

@php
    $isDark = $theme === 'dark';
    $appliedCoupon = $summary['coupon'] ?? null;
@endphp

<section class="{{ $isDark ? 'rounded-2xl bg-white/10 p-4 text-white' : 'rounded-2xl border border-slate-200 bg-slate-50 p-4' }}">
    <div class="flex items-center justify-between gap-2">
        <h3 class="{{ $isDark ? 'text-sm font-semibold text-white' : 'text-sm font-semibold text-slate-900' }}">Available coupons</h3>
        @if ($appliedCoupon)
            <span class="{{ $isDark ? 'rounded-full bg-emerald-500/20 px-2 py-0.5 text-[10px] font-semibold text-emerald-200' : 'rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800' }}">Applied</span>
        @endif
    </div>

    @if ($appliedCoupon)
        <div class="{{ $isDark ? 'mt-3 rounded-xl bg-emerald-500/15 p-3 ring-1 ring-emerald-400/30' : 'mt-3 rounded-xl border border-emerald-200 bg-emerald-50 p-3' }}">
            <p class="{{ $isDark ? 'text-sm font-semibold text-emerald-100' : 'text-sm font-semibold text-emerald-900' }}">{{ $appliedCoupon->code }} applied</p>
            <p class="{{ $isDark ? 'mt-1 text-xs text-emerald-200/90' : 'mt-1 text-xs text-emerald-800' }}">
                {{ $appliedCoupon->discountLabel() }} · You save ₹{{ number_format($summary['discount'] ?? 0, 2) }}
            </p>
            <form action="{{ route('checkout.coupon.destroy') }}" method="post" class="mt-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="{{ $isDark ? 'text-xs font-semibold text-white underline' : 'text-xs font-semibold text-emerald-900 underline' }}">Remove coupon</button>
            </form>
        </div>
    @endif

    <form action="{{ route('checkout.coupon.store') }}" method="post" class="mt-3 flex gap-2">
        @csrf
        <input
            name="coupon_code"
            value="{{ old('coupon_code', $summary['coupon_code'] ?? '') }}"
            placeholder="Enter code"
            class="{{ $isDark
                ? 'flex-1 rounded-xl border border-white/20 bg-transparent px-3 py-2 text-sm uppercase text-white placeholder:text-white/40'
                : 'flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm uppercase text-slate-900 placeholder:text-slate-400' }}"
        />
        <button type="submit" class="{{ $isDark ? 'rounded-xl bg-white px-3 py-2 text-xs font-semibold text-slate-900' : 'rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white' }}">
            Apply
        </button>
    </form>

    @if ($availableCoupons->isEmpty())
        <p class="{{ $isDark ? 'mt-3 text-xs text-white/60' : 'mt-3 text-xs text-slate-500' }}">No coupons available right now.</p>
    @else
        <ul class="mt-4 max-h-64 space-y-2 overflow-y-auto">
            @foreach ($availableCoupons as $entry)
                @php
                    /** @var \App\Models\Coupon $coupon */
                    $coupon = $entry['coupon'];
                    $eligible = $entry['eligible'];
                    $isApplied = $appliedCoupon && $appliedCoupon->id === $coupon->id;
                @endphp
                <li class="{{ $isDark
                    ? 'rounded-xl p-3 ring-1 ' . ($eligible ? 'bg-white/5 ring-white/15' : 'bg-white/[0.02] ring-white/5 opacity-60')
                    : 'rounded-xl p-3 ring-1 ' . ($eligible ? 'bg-white ring-slate-200' : 'bg-slate-100 ring-slate-100 opacity-70') }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="{{ $isDark ? 'font-semibold text-white' : 'font-semibold text-slate-900' }}">{{ $coupon->code }}</p>
                            <p class="{{ $isDark ? 'mt-0.5 text-xs text-white/70' : 'mt-0.5 text-xs text-slate-600' }}">{{ $coupon->discountLabel() }}</p>
                            @if ((float) $coupon->minimum_order_amount > 0)
                                <p class="{{ $isDark ? 'text-[11px] text-white/50' : 'text-[11px] text-slate-500' }}">Min. order ₹{{ number_format((float) $coupon->minimum_order_amount, 2) }}</p>
                            @endif
                            @if ($coupon->expires_at)
                                <p class="{{ $isDark ? 'text-[11px] text-white/50' : 'text-[11px] text-slate-500' }}">Expires {{ $coupon->expires_at->format('M j, Y') }}</p>
                            @endif
                            @unless ($eligible)
                                <p class="{{ $isDark ? 'mt-1 text-[11px] font-medium text-amber-200' : 'mt-1 text-[11px] font-medium text-amber-700' }}">Add more to cart to unlock</p>
                            @endunless
                        </div>
                        @if ($isApplied)
                            <span class="{{ $isDark ? 'shrink-0 text-[10px] font-semibold uppercase text-emerald-300' : 'shrink-0 text-[10px] font-semibold uppercase text-emerald-700' }}">Active</span>
                        @elseif ($eligible)
                            <form action="{{ route('checkout.coupon.store') }}" method="post" class="shrink-0">
                                @csrf
                                <input type="hidden" name="coupon_code" value="{{ $coupon->code }}" />
                                <button type="submit" class="{{ $isDark ? 'rounded-lg bg-white px-2.5 py-1.5 text-[11px] font-semibold text-slate-900' : 'rounded-lg bg-slate-900 px-2.5 py-1.5 text-[11px] font-semibold text-white' }}">
                                    Apply
                                </button>
                            </form>
                        @endif
                    </div>
                    @if ($eligible && $entry['preview_discount'] > 0)
                        <p class="{{ $isDark ? 'mt-2 text-[11px] text-emerald-200' : 'mt-2 text-[11px] text-emerald-700' }}">Saves ₹{{ number_format($entry['preview_discount'], 2) }} on this order</p>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</section>
