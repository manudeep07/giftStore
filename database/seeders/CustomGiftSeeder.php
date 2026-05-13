<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\CustomizationOption;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeds a demo-ready merchandising dataset mirroring production personas.
 */
class CustomGiftSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@customgift.com'],
            [
                'name' => 'Ops Lead',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+91-90000-00001',
                'email_verified_at' => now(),
            ],
        );

        $customer = User::query()->updateOrCreate(
            ['email' => 'customer@customgift.com'],
            [
                'name' => 'Isha Malhotra',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+91-98100-55667',
                'email_verified_at' => now(),
            ],
        );

        $keepsakes = Category::query()->updateOrCreate(
            ['slug' => 'heirloom-keepsakes'],
            [
                'name' => 'Heirloom Keepsakes',
                'description' => 'Archival woods, brass trims, and engraving-ready surfaces.',
                'sort_order' => 1,
            ],
        );

        $desk = Category::query()->updateOrCreate(
            ['slug' => 'desk-rituals'],
            [
                'name' => 'Desk Rituals',
                'description' => 'Quiet luxury objects for founders who live in Notion.',
                'sort_order' => 2,
            ],
        );

        $celebration = Category::query()->updateOrCreate(
            ['slug' => 'celebration-kits'],
            [
                'name' => 'Celebration Kits',
                'description' => 'Modular bundles for milestones worth photographing.',
                'sort_order' => 3,
            ],
        );

        $productsConfig = [
            [
                'category' => $keepsakes,
                'name' => 'Monolith Keepsake Chest',
                'slug' => 'monolith-keepsake-chest',
                'description' => 'Stackable walnut chest with floating hinge hardware and dual suede liners.',
                'base_price' => 5200,
                'stock' => 18,
                'is_featured' => true,
                'badge_label' => 'Bestseller',
                'image' => 'https://images.unsplash.com/photo-1513519245088-0e12902e35ca?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $keepsakes,
                'name' => 'Constellation Glass Keepsake',
                'slug' => 'constellation-glass-keepsake',
                'description' => 'Hand-blown borosilicate orb etched with astral coordinates.',
                'base_price' => 2800,
                'stock' => 32,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $desk,
                'name' => 'Telemetry Desk Totem',
                'slug' => 'telemetry-desk-totem',
                'description' => 'CNC aluminum desk sculpture with modular magnet glyphs.',
                'base_price' => 1850,
                'stock' => 6,
                'is_featured' => true,
                'badge_label' => 'Low stock',
                'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $desk,
                'name' => 'Analog Decision Coin',
                'slug' => 'analog-decision-coin',
                'description' => 'Weighted brass coin for irreversible yes/no moments.',
                'base_price' => 890,
                'stock' => 120,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'https://images.unsplash.com/photo-1522312346379-d1e52e2b99ab?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $celebration,
                'name' => 'Ribbonwave Celebration Crate',
                'slug' => 'ribbonwave-celebration-crate',
                'description' => 'Modular crate system with textile layers + edible inserts.',
                'base_price' => 3400,
                'stock' => 24,
                'is_featured' => true,
                'badge_label' => 'Editor’s pick',
                'image' => 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $celebration,
                'name' => 'Saffron Afterparty Capsule',
                'slug' => 'saffron-afterparty-capsule',
                'description' => 'Curated scent + sound capsule with NFC dedication playlist.',
                'base_price' => 2100,
                'stock' => 40,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'https://images.unsplash.com/photo-1467810563316-b5476525c6f9?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        foreach ($productsConfig as $payload) {
            /** @var Product $product */
            $product = Product::query()->updateOrCreate(
                ['slug' => $payload['slug']],
                [
                    'category_id' => $payload['category']->id,
                    'name' => $payload['name'],
                    'description' => $payload['description'],
                    'base_price' => $payload['base_price'],
                    'stock' => $payload['stock'],
                    'is_featured' => $payload['is_featured'],
                    'is_active' => true,
                    'badge_label' => $payload['badge_label'],
                ],
            );

            ProductImage::query()->where('product_id', $product->id)->delete();

            ProductImage::query()->create([
                'product_id' => $product->id,
                'path' => $payload['image'],
                'alt' => $product->name,
                'sort_order' => 0,
                'is_primary' => true,
            ]);

            CustomizationOption::query()->where('product_id', $product->id)->delete();

            $this->seedOptions($product);
        }

        Coupon::query()->updateOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percent',
                'value' => 10,
                'max_uses' => 500,
                'uses_count' => 0,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
        );

        $heroProduct = Product::query()->where('slug', 'monolith-keepsake-chest')->first();

        if ($heroProduct) {
            Review::query()->updateOrCreate(
                [
                    'user_id' => $customer->id,
                    'product_id' => $heroProduct->id,
                ],
                [
                    'rating' => 5,
                    'title' => 'Feels venture-backed',
                    'body' => 'The AJAX pricing literally matched checkout — investors assumed we licensed the UI.',
                    'is_approved' => true,
                ],
            );
        }

        $sampleProduct = Product::query()->where('slug', 'telemetry-desk-totem')->first();

        if ($sampleProduct) {
            $order = Order::query()->create([
                'order_number' => 'CG-'.strtoupper(Str::random(8)),
                'user_id' => $customer->id,
                'status' => 'delivered',
                'subtotal' => 5600,
                'tax_amount' => 1008,
                'discount_amount' => 560,
                'shipping_amount' => 49,
                'total' => 6097,
                'coupon_id' => Coupon::query()->first()?->id,
                'coupon_code' => 'WELCOME10',
                'shipping_name' => $customer->name,
                'shipping_email' => $customer->email,
                'shipping_phone' => $customer->phone ?? '+91-9810000000',
                'shipping_address_line1' => '221B Baker Street',
                'shipping_city' => 'Mumbai',
                'shipping_state' => 'MH',
                'shipping_postal' => '400001',
                'shipping_country' => 'India',
                'notes' => 'Leave with concierge — demo fulfillment row.',
            ]);

            OrderItem::query()->create([
                'order_id' => $order->id,
                'product_id' => $sampleProduct->id,
                'product_name' => $sampleProduct->name,
                'quantity' => 2,
                'customization_snapshot' => [
                    'selections' => [
                        'material' => 'machined_aluminum',
                        'size' => 'compact',
                        'gift_wrap' => 'atelier',
                        'addons' => ['nfc_chip'],
                    ],
                ],
                'unit_price' => 2800,
                'line_total' => 5600,
            ]);

            Payment::query()->create([
                'order_id' => $order->id,
                'provider' => 'placeholder',
                'status' => 'paid',
                'transaction_ref' => 'SIM-'.strtoupper(Str::random(10)),
                'amount' => $order->total,
                'meta' => ['note' => 'Auto-generated analytics seed.'],
            ]);

            Coupon::query()->where('code', 'WELCOME10')->increment('uses_count');
        }

        $this->command->info('CustomGift demo ready.');
        $this->command->warn('Admin login → admin@customgift.com / password');
        $this->command->warn('Customer login → customer@customgift.com / password');
    }

    /** @return void */
    protected function seedOptions(Product $product): void
    {
        $rows = [
            ['material', 'walnut_arch', 'Walnut arch grain', 420, null, true],
            ['material', 'obsidian_resin', 'Obsidian resin hybrid', 760, null, false],

            ['size', 'compact', 'Petite · desk footprint', 0, null, true],
            ['size', 'atelier', 'Atelier scale · statement', 340, null, false],

            ['color', 'natural_oil', 'Natural oil finish', 0, ['hex' => '#c49a6c'], true],
            ['color', 'graphite', 'Graphite vapor', 190, ['hex' => '#2f3137'], false],

            ['font', 'sans_precision', 'Neo-grotesk precision', 0, null, true],
            ['font', 'script_velvet', 'Velvet script', 120, null, false],

            ['gift_wrap', 'studio', 'Studio tissue + wax seal', 0, null, true],
            ['gift_wrap', 'atelier', 'Atelier ribbonwave crate', 390, null, false],

            ['engraving', 'none', 'No engraving', 0, null, true],
            ['engraving', 'standard', 'Standard fiber etch', 180, null, false],
            ['engraving', 'deep', 'Deep relief engraving', 420, null, false],

            ['addon', 'ribbon_dupioni', 'Dupioni ribbon sash', 240, null, false],
            ['addon', 'nfc_chip', 'Programmable NFC medallion', 320, null, false],

            ['image_upload', 'yes', 'Archival photo conservation', 180, null, false],
        ];

        foreach ($rows as $index => $row) {
            [$group, $key, $label, $price, $meta, $default] = $row;

            CustomizationOption::query()->create([
                'product_id' => $product->id,
                'option_group' => $group,
                'value_key' => $key,
                'label' => $label,
                'price_adjustment' => $price,
                'meta' => $meta,
                'is_default' => $default,
                'sort_order' => $index,
            ]);
        }
    }
}
