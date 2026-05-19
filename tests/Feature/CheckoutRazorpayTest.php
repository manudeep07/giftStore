<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutRazorpayTest extends TestCase
{
    use RefreshDatabase;

    private function seedCartFor(User $user): void
    {
        $category = Category::query()->create([
            'name' => 'Gifts',
            'slug' => 'gifts',
            'sort_order' => 1,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Bracelet',
            'slug' => 'bracelet',
            'description' => 'Test',
            'base_price' => 1000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $cart = Cart::query()->create(['user_id' => $user->id]);

        CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'customization_snapshot' => ['selections' => [], 'unit_price' => '1000.00'],
            'unit_price' => '1000.00',
            'line_total' => '1000.00',
        ]);
    }

    public function test_checkout_index_shows_warning_when_razorpay_not_configured(): void
    {
        config([
            'services.razorpay.key_id' => null,
            'services.razorpay.key_secret' => null,
        ]);

        $user = User::factory()->create();
        $this->seedCartFor($user);

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response->assertOk();
        $response->assertSee('RAZORPAY_KEY_ID', false);
    }

    public function test_checkout_store_rejects_without_razorpay_keys(): void
    {
        config([
            'services.razorpay.key_id' => null,
            'services.razorpay.key_secret' => null,
        ]);

        $user = User::factory()->create();
        $this->seedCartFor($user);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'shipping_name' => $user->name,
            'shipping_email' => $user->email,
            'shipping_phone' => '9999999999',
            'shipping_address_line1' => '1 St',
            'shipping_city' => 'City',
            'shipping_postal' => '400001',
            'shipping_country' => 'India',
        ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('error');
    }
}
