@php
    $coupon = $coupon ?? null;
@endphp

<label class="block text-sm font-semibold text-white">Coupon code
    <input name="code" value="{{ old('code', $coupon?->code) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm uppercase text-white" />
    @error('code')<p class="mt-1 text-xs text-rose-300">{{ $message }}</p>@enderror
</label>

<label class="block text-sm font-semibold text-white">Discount type
    <select name="type" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white">
        <option value="percent" @selected(old('type', $coupon?->type) === 'percent')>Percentage (%)</option>
        <option value="fixed" @selected(old('type', $coupon?->type) === 'fixed')>Flat amount (₹)</option>
    </select>
    @error('type')<p class="mt-1 text-xs text-rose-300">{{ $message }}</p>@enderror
</label>

<label class="block text-sm font-semibold text-white">Discount value
    <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $coupon?->value) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
    <p class="mt-1 text-xs text-slate-400">Percent (e.g. 10) or flat rupees (e.g. 500).</p>
    @error('value')<p class="mt-1 text-xs text-rose-300">{{ $message }}</p>@enderror
</label>

<label class="block text-sm font-semibold text-white">Minimum order amount (₹)
    <input type="number" step="0.01" min="0" name="minimum_order_amount" value="{{ old('minimum_order_amount', $coupon?->minimum_order_amount ?? 0) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
    <p class="mt-1 text-xs text-slate-400">Set 0 for no minimum.</p>
    @error('minimum_order_amount')<p class="mt-1 text-xs text-rose-300">{{ $message }}</p>@enderror
</label>

<label class="block text-sm font-semibold text-white">Usage limit (optional)
    <input type="number" min="1" name="max_uses" value="{{ old('max_uses', $coupon?->max_uses) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
    <p class="mt-1 text-xs text-slate-400">Leave empty for unlimited uses.</p>
    @error('max_uses')<p class="mt-1 text-xs text-rose-300">{{ $message }}</p>@enderror
</label>

@if ($coupon)
    <p class="text-xs text-slate-400">Times used: {{ $coupon->uses_count }}</p>
@endif

<label class="block text-sm font-semibold text-white">Expiry date (optional)
    <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d\TH:i')) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
    @error('expires_at')<p class="mt-1 text-xs text-rose-300">{{ $message }}</p>@enderror
</label>

<label class="inline-flex items-center gap-2 text-sm text-white">
    <input type="checkbox" name="is_active" value="1" class="rounded border-white/20 bg-transparent" @checked(old('is_active', $coupon?->is_active ?? true))>
    Active (customers can use this coupon)
</label>
