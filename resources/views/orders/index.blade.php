@extends('layouts.store')

@section('title', 'Orders')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">History</p>
            <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Every bespoke fulfillment</h1>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 capitalize text-slate-600">{{ $order->status }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">₹{{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('orders.show', $order) }}" class="font-semibold text-slate-900 hover:text-slate-600">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-600">No artisan orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </div>
@endsection
