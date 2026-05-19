@extends('layouts.store')

@section('title', 'Order history')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Your account</p>
            <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Order history</h1>
            <p class="mt-2 text-sm text-slate-600">Track payments, cancellations, and refunds for every bespoke order.</p>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Order status</th>
                        <th class="px-6 py-4">Payment</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 capitalize text-slate-600">{{ $order->status }}</td>
                            <td class="px-6 py-4 capitalize text-slate-600">{{ $order->payment?->status ?? '—' }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">₹{{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $order->created_at->format('M j, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('orders.show', $order) }}" class="font-semibold text-slate-900 hover:text-slate-600">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-600">No orders yet. <a href="{{ route('shop.index') }}" class="font-semibold underline">Browse the shop</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </div>
@endsection
