@extends('layouts.admin')

@section('title', 'Orders')
@section('heading', 'Order management')

@section('content')
    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
        <table class="min-w-full divide-y divide-white/5 text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Payment</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-200">
                @foreach ($orders as $order)
                    <tr>
                        <td class="px-6 py-4 font-semibold">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-xs text-slate-400">{{ $order->user?->email }}</td>
                        <td class="px-6 py-4 capitalize">{{ $order->status }}</td>
                        <td class="px-6 py-4 capitalize text-xs">{{ $order->payment?->status ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs">₹{{ number_format($order->total, 2) }}</td>
                        <td class="px-6 py-4 text-right text-xs font-semibold">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-white hover:underline">Manage</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
@endsection
