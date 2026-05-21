<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCouponRequest;
use App\Http\Requests\Admin\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::query()->latest()->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        return view('admin.coupons.create');
    }

    public function store(StoreCouponRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Coupon::query()->create([
            'code' => strtoupper($data['code']),
            'type' => $data['type'],
            'value' => $data['value'],
            'minimum_order_amount' => $data['minimum_order_amount'] ?? 0,
            'max_uses' => $data['max_uses'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $data = $request->validated();

        $coupon->update([
            'code' => strtoupper($data['code']),
            'type' => $data['type'],
            'value' => $data['value'],
            'minimum_order_amount' => $data['minimum_order_amount'] ?? 0,
            'max_uses' => $data['max_uses'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated.');
    }

    public function toggle(Coupon $coupon): RedirectResponse
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);

        $status = $coupon->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Coupon {$status}.");
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted.');
    }
}
