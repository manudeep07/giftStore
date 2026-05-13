<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Executive overview with KPIs similar to modern SaaS operational consoles.
 */
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $users = User::query()->count();
        $orders = Order::query()->count();
        $revenue = (float) Order::query()
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $recentOrders = Order::query()
            ->with('user:id,name,email')
            ->latest()
            ->take(8)
            ->get();

        $inventoryAlerts = Product::query()
            ->where('is_active', true)
            ->where('stock', '<', 8)
            ->orderBy('stock')
            ->take(6)
            ->get();

        $bestSellers = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as units'))
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('units')
            ->take(6)
            ->with('product:id,name,slug')
            ->get();

        $statusMix = Order::query()
            ->select('status', DB::raw('count(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('admin.dashboard', compact(
            'users',
            'orders',
            'revenue',
            'recentOrders',
            'inventoryAlerts',
            'bestSellers',
            'statusMix',
        ));
    }
}
