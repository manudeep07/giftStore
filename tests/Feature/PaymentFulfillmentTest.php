<?php

namespace Tests\Feature;

use App\Mail\OrderPlacedMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\PaymentFulfillmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentFulfillmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_fulfillment_marks_payment_paid_and_sends_mail(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $category = Category::query()->create([
            'name' => 'Test',
            'slug' => 'test',
            'sort_order' => 1,
        ]);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Mug',
            'slug' => 'mug',
            'description' => 'Test',
            'base_price' => 500,
            'stock' => 10,
            'is_active' => true,
        ]);

        $order = Order::query()->create([
            'order_number' => 'CG-TEST001',
            'user_id' => $user->id,
            'status' => 'pending',
            'subtotal' => '500.00',
            'tax_amount' => '90.00',
            'discount_amount' => '0.00',
            'shipping_amount' => '49.00',
            'total' => '639.00',
            'shipping_name' => 'Test User',
            'shipping_email' => 'buyer@example.com',
            'shipping_phone' => '9999999999',
            'shipping_address_line1' => '1 Test St',
            'shipping_city' => 'Mumbai',
            'shipping_postal' => '400001',
            'shipping_country' => 'India',
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'customization_snapshot' => [],
            'unit_price' => '250.00',
            'line_total' => '500.00',
        ]);

        Payment::query()->create([
            'order_id' => $order->id,
            'provider' => 'razorpay',
            'status' => 'pending',
            'amount' => $order->total,
            'meta' => ['razorpay_order_id' => 'order_test123'],
        ]);

        $service = app(PaymentFulfillmentService::class);
        $fulfilled = $service->fulfill($order, 'pay_test456', 'order_test123');

        $this->assertTrue($fulfilled);
        $this->assertSame('paid', $order->fresh()->payment->status);
        $this->assertSame('placed', $order->fresh()->status);
        $this->assertSame(8, $product->fresh()->stock);

        Mail::assertSent(OrderPlacedMail::class, function (OrderPlacedMail $mail) use ($order) {
            return $mail->hasTo('buyer@example.com')
                && $mail->order->is($order->fresh());
        });
    }

    public function test_fulfillment_is_idempotent(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $order = Order::query()->create([
            'order_number' => 'CG-TEST002',
            'user_id' => $user->id,
            'status' => 'processing',
            'subtotal' => '100.00',
            'tax_amount' => '0.00',
            'discount_amount' => '0.00',
            'shipping_amount' => '0.00',
            'total' => '100.00',
            'shipping_name' => 'Test',
            'shipping_email' => 'a@b.com',
            'shipping_phone' => '1',
            'shipping_address_line1' => 'x',
            'shipping_city' => 'y',
            'shipping_postal' => '1',
            'shipping_country' => 'India',
        ]);

        Payment::query()->create([
            'order_id' => $order->id,
            'provider' => 'razorpay',
            'status' => 'paid',
            'transaction_ref' => 'pay_existing',
            'amount' => '100.00',
        ]);

        $service = app(PaymentFulfillmentService::class);
        $this->assertFalse($service->fulfill($order, 'pay_new'));

        Mail::assertNothingSent();
    }
}
