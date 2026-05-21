@extends('layouts.admin')

@section('title', 'Coupons')
@section('heading', 'Coupon management')

@section('content')
    <div class="flex flex-wrap justify-between gap-4">
        <p class="text-sm text-slate-400">Create, edit, activate, or retire checkout coupons.</p>
        <a href="{{ route('admin.coupons.create') }}" class="rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-900">New coupon</a>
    </div>

    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
        <table class="min-w-full divide-y divide-white/5 text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                    <th class="px-6 py-4">Code</th>
                    <th class="px-6 py-4">Discount</th>
                    <th class="px-6 py-4">Min. order</th>
                    <th class="px-6 py-4">Expiry</th>
                    <th class="px-6 py-4">Usage</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-200">
                @forelse ($coupons as $coupon)
                    <tr>
                        <td class="px-6 py-4 font-semibold">{{ $coupon->code }}</td>
                        <td class="px-6 py-4 text-xs">
                            <span class="block text-slate-400">{{ $coupon->typeLabel() }}</span>
                            {{ $coupon->discountLabel() }}
                        </td>
                        <td class="px-6 py-4 text-xs">
                            {{ (float) $coupon->minimum_order_amount > 0 ? '₹'.number_format((float) $coupon->minimum_order_amount, 2) : '—' }}
                        </td>
                        <td class="px-6 py-4 text-xs">
                            {{ $coupon->expires_at?->format('M j, Y g:i A') ?? 'No expiry' }}
                        </td>
                        <td class="px-6 py-4 text-xs">{{ $coupon->uses_count }} / {{ $coupon->max_uses ?? '∞' }}</td>
                        <td class="px-6 py-4">
                            @if ($coupon->is_active)
                                <span class="rounded-full bg-emerald-500/20 px-2 py-0.5 text-[10px] font-semibold uppercase text-emerald-300">Active</span>
                            @else
                                <span class="rounded-full bg-slate-500/30 px-2 py-0.5 text-[10px] font-semibold uppercase text-slate-300">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-xs font-semibold">
                            <div class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-white hover:underline">Edit</a>
                                <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="post" class="inline">
                                    @csrf
                                    <button type="submit" class="text-amber-200 hover:underline">
                                        {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="post" class="inline" onsubmit="return confirm('Delete this coupon permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-rose-300 hover:underline" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">No coupons yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $coupons->links() }}
@endsection
