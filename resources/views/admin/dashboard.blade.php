@extends('layouts.admin')

@section('title', 'Admin dashboard')
@section('heading', 'Merchandising cockpit')

@section('content')
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-inner">
            <p class="text-xs uppercase tracking-wide text-slate-400">Community</p>
            <p class="mt-4 text-4xl font-semibold text-white">{{ $users }}</p>
            <p class="text-xs text-slate-400">accounts onboarded</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-inner">
            <p class="text-xs uppercase tracking-wide text-slate-400">Orders</p>
            <p class="mt-4 text-4xl font-semibold text-white">{{ $orders }}</p>
            <p class="text-xs text-slate-400">lifecycle rows</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-inner">
            <p class="text-xs uppercase tracking-wide text-slate-400">Revenue</p>
            <p class="mt-4 text-4xl font-semibold text-white">₹{{ number_format($revenue, 0) }}</p>
            <p class="text-xs text-slate-400">ex-cancelled orders</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Pipeline</p>
                    <p class="text-lg font-semibold text-white">Order status distribution</p>
                </div>
            </div>
            <canvas id="statusChart" height="140" class="mt-6"></canvas>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <p class="text-xs uppercase tracking-wide text-slate-400">Inventory radar</p>
            <p class="text-lg font-semibold text-white">Low runway SKUs</p>
            <ul class="mt-4 space-y-3 text-sm text-slate-300">
                @forelse ($inventoryAlerts as $sku)
                    <li class="flex items-center justify-between rounded-2xl bg-slate-950/40 px-4 py-3">
                        <span>{{ $sku->name }}</span>
                        <span class="rounded-full bg-amber-400/20 px-3 py-1 text-xs font-semibold text-amber-200">{{ $sku->stock }} left</span>
                    </li>
                @empty
                    <li class="text-slate-500">Healthy coverage across catalog.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <div class="flex items-center justify-between">
                <p class="text-lg font-semibold text-white">Recent orders</p>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-slate-300 hover:text-white">View all</a>
            </div>
            <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
                <table class="min-w-full divide-y divide-white/5 text-sm">
                    <thead class="bg-white/5 text-left text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Order</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-200">
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td class="px-4 py-3 font-semibold">
                                    <a class="hover:text-white" href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-400">{{ $order->user?->email }}</td>
                                <td class="px-4 py-3 text-xs">₹{{ number_format($order->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <p class="text-lg font-semibold text-white">Velocity leaders</p>
            <ul class="mt-4 space-y-3 text-sm text-slate-300">
                @forelse ($bestSellers as $row)
                    <li class="flex items-center justify-between rounded-2xl bg-slate-950/40 px-4 py-3">
                        <span>{{ $row->product?->name ?? 'Archived SKU' }}</span>
                        <span class="text-xs font-semibold text-emerald-200">{{ $row->units }} units</span>
                    </li>
                @empty
                    <li class="text-slate-500">Orders will populate leaderboard automatically.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Chart) {
                return;
            }

            const ctx = document.getElementById('statusChart');
            const dataset = @json($statusMix);

            const labels = Object.keys(dataset);
            const values = Object.values(dataset).map((value) => Number(value));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Orders',
                        data: values,
                        borderRadius: 12,
                        backgroundColor: 'rgba(148, 163, 184, 0.35)',
                        borderColor: 'rgba(248, 250, 252, 0.6)',
                        borderWidth: 1,
                    }],
                },
                options: {
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        x: {
                            ticks: { color: '#cbd5f5' },
                            grid: { color: 'rgba(148,163,184,0.2)' },
                        },
                        y: {
                            ticks: { color: '#cbd5f5', precision: 0 },
                            grid: { color: 'rgba(148,163,184,0.15)' },
                            beginAtZero: true,
                        },
                    },
                },
            });
        });
    </script>
@endpush
