<?php

namespace Tests\Feature;

use App\Mail\OrderCancelledMail;
use App\Mail\RefundProcessedMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderCancellationRefundTest extends TestCase
{
    use RefreshDatabase;

    private function paidOrder(User $customer): Order
    {
        $category = Category::query()->create(['name' => 'T', 'slug' => 't', 'sort_order' => 1]);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Gift',
            'slug' => 'gift',
            'description' => 'd',
            'base_price' => 100,
            'stock' => 5,
            'is_active' => true,
        ]);

        $order = Order::query()->create([
            'order_number' => 'CG-CANCEL1',
            'user_id' => $customer->id,
            'status' => 'placed',
            'subtotal' => '100.00',
            'tax_amount' => '0.00',
            'discount_amount' => '0.00',
            'shipping_amount' => '0.00',
            'total' => '100.00',
            'shipping_name' => 'Buyer',
            'shipping_email' => 'buyer@test.com',
            'shipping_phone' => '1',
            'shipping_address_line1' => 'x',
            'shipping_city' => 'y',
            'shipping_postal' => '1',
            'shipping_country' => 'India',
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => 'Gift',
            'quantity' => 1,
            'customization_snapshot' => [],
            'unit_price' => '100.00',
            'line_total' => '100.00',
        ]);

        Payment::query()->create([
            'order_id' => $order->id,
            'provider' => 'razorpay',
            'status' => 'paid',
            'transaction_ref' => 'pay_test',
            'amount' => '100.00',
        ]);

        $product->decrement('stock', 1);

        return $order;
    }

    public function test_customer_can_cancel_placed_paid_order(): void
    {
        Mail::fake();

        $customer = User::factory()->create();
        $order = $this->paidOrder($customer);

        $this->actingAs($customer)
            ->post(route('orders.cancel', $order))
            ->assertRedirect(route('orders.show', $order));

        $order->refresh();
        $this->assertSame('cancelled', $order->status);
        $this->assertSame('paid', $order->payment->status);

        Mail::assertSent(OrderCancelledMail::class, fn ($m) => $m->hasTo('buyer@test.com') && $m->refundPending);
    }

    public function test_admin_can_process_refund_after_cancellation(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create();
        $order = $this->paidOrder($customer);
        $order->update(['status' => 'cancelled']);

        $this->actingAs($admin)
            ->post(route('admin.orders.refund', $order), ['reason' => 'Test refund'])
            ->assertRedirect();

        $order->refresh();
        $this->assertSame('refunded', $order->payment->status);
        $this->assertNotNull($order->refund);

        Mail::assertSent(RefundProcessedMail::class, fn ($m) => $m->hasTo('buyer@test.com'));
    }
}
