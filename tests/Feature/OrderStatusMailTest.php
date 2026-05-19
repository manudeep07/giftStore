<?php

namespace Tests\Feature;

use App\Mail\OrderStatusChangedMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderStatusMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_status_update_sends_mail(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create();

        $order = Order::query()->create([
            'order_number' => 'CG-ADM001',
            'user_id' => $customer->id,
            'status' => 'pending',
            'subtotal' => '100.00',
            'tax_amount' => '0.00',
            'discount_amount' => '0.00',
            'shipping_amount' => '0.00',
            'total' => '100.00',
            'shipping_name' => 'Buyer',
            'shipping_email' => 'status@example.com',
            'shipping_phone' => '1',
            'shipping_address_line1' => 'x',
            'shipping_city' => 'y',
            'shipping_postal' => '1',
            'shipping_country' => 'India',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), ['status' => 'processing'])
            ->assertRedirect();

        Mail::assertSent(OrderStatusChangedMail::class, function (OrderStatusChangedMail $mail) use ($order) {
            return $mail->hasTo('status@example.com')
                && $mail->previousStatus === 'pending'
                && $mail->order->status === 'processing';
        });
    }
}
