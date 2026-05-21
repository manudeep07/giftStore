<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_inactive_coupon(): void
    {
        Coupon::query()->create([
            'code' => 'OFF',
            'type' => 'fixed',
            'value' => 50,
            'minimum_order_amount' => 0,
            'is_active' => false,
        ]);

        $result = app(CouponService::class)->validate('OFF', 1000);

        $this->assertFalse($result['valid']);
        $this->assertSame('This coupon is inactive.', $result['message']);
    }

    public function test_rejects_below_minimum_order(): void
    {
        Coupon::query()->create([
            'code' => 'BIG',
            'type' => 'percent',
            'value' => 10,
            'minimum_order_amount' => 2000,
            'is_active' => true,
        ]);

        $result = app(CouponService::class)->validate('BIG', 500);

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('Minimum order', $result['message']);
    }

    public function test_applies_percent_discount(): void
    {
        Coupon::query()->create([
            'code' => 'SAVE10',
            'type' => 'percent',
            'value' => 10,
            'minimum_order_amount' => 0,
            'is_active' => true,
        ]);

        $applied = app(CouponService::class)->apply('SAVE10', 1000);

        $this->assertSame(100.0, $applied['discount']);
        $this->assertNotNull($applied['coupon']);
    }
}
