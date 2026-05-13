@extends('layouts.admin')

@section('title', $coupon->code)
@section('heading', 'Tune coupon')

@section('content')
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="post" class="max-w-3xl space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8">
        @csrf
        @method('PUT')

        <label class="block text-sm font-semibold text-white">Code
            <input name="code" value="{{ old('code', $coupon->code) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm uppercase text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Type
            <select name="type" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white">
                <option value="percent" @selected($coupon->type === 'percent')>Percent</option>
                <option value="fixed" @selected($coupon->type === 'fixed')>Fixed ₹</option>
            </select>
        </label>

        <label class="block text-sm font-semibold text-white">Value
            <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Max uses
            <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="block text-sm font-semibold text-white">Expires at
            <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>

        <label class="inline-flex items-center gap-2 text-sm text-white">
            <input type="checkbox" name="is_active" value="1" class="rounded border-white/20 bg-transparent" @checked(old('is_active', $coupon->is_active))>
            Active
        </label>

        <button class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900">Update</button>
    </form>
@endsection
