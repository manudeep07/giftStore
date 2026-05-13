@extends('layouts.admin')

@section('title', 'Coupons')
@section('heading', 'Incentives')

@section('content')
    <div class="flex justify-between gap-4">
        <p class="text-sm text-slate-400">Checkout references these rows for auditing + limits.</p>
        <a href="{{ route('admin.coupons.create') }}" class="rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-900">Mint coupon</a>
    </div>

    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
        <table class="min-w-full divide-y divide-white/5 text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                    <th class="px-6 py-4">Code</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Value</th>
                    <th class="px-6 py-4">Usage</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-200">
                @foreach ($coupons as $coupon)
                    <tr>
                        <td class="px-6 py-4 font-semibold">{{ $coupon->code }}</td>
                        <td class="px-6 py-4 text-xs capitalize">{{ $coupon->type }}</td>
                        <td class="px-6 py-4 text-xs">{{ $coupon->type === 'percent' ? $coupon->value.'%' : '₹'.$coupon->value }}</td>
                        <td class="px-6 py-4 text-xs">{{ $coupon->uses_count }} / {{ $coupon->max_uses ?? '∞' }}</td>
                        <td class="px-6 py-4 text-right text-xs font-semibold space-x-3">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-white hover:underline">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="post" class="inline" onsubmit="return confirm('Retire coupon?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-rose-300" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $coupons->links() }}
@endsection
