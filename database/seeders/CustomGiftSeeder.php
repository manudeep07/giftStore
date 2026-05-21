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

        $jewelry = Category::query()->updateOrCreate(
            ['slug' => 'engraved-jewelry'],
            ['name' => 'Engraved Jewelry', 'description' => 'Delicate, timeless pieces tailored with your personal touch.', 'sort_order' => 1]
        );

        $leather = Category::query()->updateOrCreate(
            ['slug' => 'leather-goods'],
            ['name' => 'Leather Goods', 'description' => 'Premium full-grain leather, monogrammed to perfection.', 'sort_order' => 2]
        );

        $drinkware = Category::query()->updateOrCreate(
            ['slug' => 'premium-drinkware'],
            ['name' => 'Premium Drinkware', 'description' => 'Aesthetic, laser-engraved flasks and mugs.', 'sort_order' => 3]
        );

        $home = Category::query()->updateOrCreate(
            ['slug' => 'home-living'],
            ['name' => 'Home & Living', 'description' => 'Bespoke decor, custom clocks, and keepsakes for your space.', 'sort_order' => 4]
        );

        $tech = Category::query()->updateOrCreate(
            ['slug' => 'tech-accessories'],
            ['name' => 'Tech Accessories', 'description' => 'Personalized cases for the tools you use every day.', 'sort_order' => 5]
        );

        $productsConfig = [
            // Engraved Jewelry
            [
                'category' => $jewelry,
                'name' => 'Signature Name Necklace',
                'slug' => 'signature-name-necklace',
                'description' => 'A delicate 18k gold-plated chain featuring a custom name or word of your choice.',
                'base_price' => 3500,
                'stock' => 50,
                'is_featured' => true,
                'badge_label' => 'Bestseller',
                'image' => 'products/necklace.png',
            ],
            [
                'category' => $jewelry,
                'name' => 'Coordinates Bar Bracelet',
                'slug' => 'coordinates-bar-bracelet',
                'description' => 'A sleek metal bar bracelet engraved with the exact coordinates of your special place.',
                'base_price' => 2800,
                'stock' => 30,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $jewelry,
                'name' => 'Custom Engraved Pocket Watch',
                'slug' => 'custom-engraved-pocket-watch',
                'description' => 'A vintage-style mechanical pocket watch, perfect for groomsmen or anniversaries. Engravable back casing.',
                'base_price' => 4500,
                'stock' => 15,
                'is_featured' => false,
                'badge_label' => 'Vintage',
                'image' => 'https://images.unsplash.com/photo-1509048191080-d2984bad6ae5?auto=format&fit=crop&w=1200&q=80',
            ],

            // Leather Goods
            [
                'category' => $leather,
                'name' => 'Monogrammed Minimalist Wallet',
                'slug' => 'monogrammed-minimalist-wallet',
                'description' => 'A premium slim full-grain leather cardholder featuring your initials in gold or silver foil.',
                'base_price' => 1800,
                'stock' => 100,
                'is_featured' => true,
                'badge_label' => 'Essential',
                'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $leather,
                'name' => 'Classic Leather Artisan Journal',
                'slug' => 'classic-leather-artisan-journal',
                'description' => 'A refillable leather notebook perfect for writers or travelers. Custom embossed cover.',
                'base_price' => 2200,
                'stock' => 40,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $leather,
                'name' => 'Personalized Leather Keychain',
                'slug' => 'personalized-leather-keychain',
                'description' => 'A sturdy leather loop with heavy metal hardware, engraved with initials or a short date.',
                'base_price' => 850,
                'stock' => 200,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'products/keychain.png',
            ],

            // Home & Living
            [
                'category' => $home,
                'name' => 'Custom Engraved Wooden Wall Clock',
                'slug' => 'custom-engraved-wooden-wall-clock',
                'description' => 'A beautifully crafted oak or walnut clock engraved with a family name and established year.',
                'base_price' => 5500,
                'stock' => 20,
                'is_featured' => true,
                'badge_label' => 'New',
                'image' => 'https://images.unsplash.com/photo-1563861826100-9cb868fdbe1c?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $home,
                'name' => 'Personalized Acrylic Photo Frame',
                'slug' => 'personalized-acrylic-photo-frame',
                'description' => 'A modern, thick acrylic block where your photo is printed directly onto the glass alongside a custom caption.',
                'base_price' => 2100,
                'stock' => 60,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'products/frame.png',
            ],
            [
                'category' => $home,
                'name' => 'Custom Star Map Print',
                'slug' => 'custom-star-map-print',
                'description' => 'A framed poster showing the exact alignment of the stars at a specific location, date, and time.',
                'base_price' => 3200,
                'stock' => 45,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $home,
                'name' => 'Personalized Oak Cutting Board',
                'slug' => 'personalized-oak-cutting-board',
                'description' => 'A heavy, premium wood cutting board engraved with a family crest or name.',
                'base_price' => 4200,
                'stock' => 25,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'products/board.png',
            ],

            // Drinkware
            [
                'category' => $drinkware,
                'name' => 'Matte Black Thermal Flask',
                'slug' => 'matte-black-thermal-flask',
                'description' => 'A sleek, vacuum-insulated water bottle featuring your laser-engraved name.',
                'base_price' => 1500,
                'stock' => 80,
                'is_featured' => true,
                'badge_label' => 'Editor’s pick',
                'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $drinkware,
                'name' => 'Frosted Glass Coffee Mug',
                'slug' => 'frosted-glass-coffee-mug',
                'description' => 'An aesthetic, heavy-bottomed frosted mug custom printed with a quote or inside joke.',
                'base_price' => 950,
                'stock' => 150,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'products/mug.png',
            ],

            // Tech Accessories
            [
                'category' => $tech,
                'name' => 'Personalized Wood Phone Case',
                'slug' => 'personalized-wood-phone-case',
                'description' => 'Real walnut or cherry wood phone cases, precision laser engraved with your design.',
                'base_price' => 2400,
                'stock' => 70,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'https://images.unsplash.com/photo-1601593346740-925612772716?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => $tech,
                'name' => 'Monogrammed Leather Airpods Case',
                'slug' => 'monogrammed-leather-airpods-case',
                'description' => 'A snug-fitting leather case for Airpods, stamped with your initials.',
                'base_price' => 1200,
                'stock' => 110,
                'is_featured' => false,
                'badge_label' => null,
                'image' => 'https://images.unsplash.com/photo-1603351154351-5e2d0600bb77?auto=format&fit=crop&w=1200&q=80',
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

            $this->seedOptions($product, $payload['category']->slug);
        }

        Coupon::query()->updateOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percent',
                'value' => 10,
                'minimum_order_amount' => 500,
                'max_uses' => 500,
                'uses_count' => 0,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
        );

        Coupon::query()->updateOrCreate(
            ['code' => 'FLAT200'],
            [
                'type' => 'fixed',
                'value' => 200,
                'minimum_order_amount' => 1500,
                'max_uses' => 100,
                'uses_count' => 0,
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
        );

        $heroProduct = Product::query()->where('slug', 'signature-name-necklace')->first();

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

        $sampleProduct = Product::query()->where('slug', 'monogrammed-minimalist-wallet')->first();

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
                        'color' => 'brown',
                        'font' => 'sans_precision',
                    ],
                ],
                'unit_price' => 2800,
                'line_total' => 5600,
            ]);

            Payment::query()->create([
                'order_id' => $order->id,
                'provider' => 'razorpay',
                'status' => 'paid',
                'transaction_ref' => 'pay_seed_'.strtoupper(Str::random(8)),
                'amount' => $order->total,
                'meta' => ['note' => 'Seeded paid order for admin analytics.'],
            ]);

            Coupon::query()->where('code', 'WELCOME10')->increment('uses_count');
        }

        $this->command->info('CustomGift demo ready.');
        $this->command->warn('Admin login → admin@customgift.com / password');
        $this->command->warn('Customer login → customer@customgift.com / password');
    }

    /** @return void */
    protected function seedOptions(Product $product, string $categorySlug): void
    {
        $rows = [];

        // Universal Font Options
        $fontOptions = [
            ['font', 'sans_precision', 'Modern Sans', 0, null, true],
            ['font', 'script_velvet', 'Elegant Cursive', 50, null, false],
        ];

        switch ($categorySlug) {
            case 'engraved-jewelry':
                $rows = array_merge($fontOptions, [
                    ['color', 'silver', 'Classic Silver', 0, ['hex' => '#e2e8f0'], true],
                    ['color', 'gold', '18k Gold Plated', 500, ['hex' => '#d4af37'], false],
                    ['color', 'rose_gold', 'Rose Gold', 500, ['hex' => '#b76e79'], false],
                ]);
                break;

            case 'leather-goods':
                $rows = array_merge($fontOptions, [
                    ['color', 'brown', 'Cognac Brown', 0, ['hex' => '#8b4513'], true],
                    ['color', 'black', 'Midnight Black', 0, ['hex' => '#1a1a1a'], false],
                ]);
                break;

            case 'home-living':
            case 'tech-accessories':
                $rows = array_merge($fontOptions, [
                    ['material', 'walnut', 'Walnut Wood', 0, null, true],
                    ['material', 'oak', 'Classic Oak', 0, null, false],
                ]);
                break;

            case 'premium-drinkware':
                $rows = array_merge($fontOptions, [
                    ['color', 'matte_black', 'Matte Black', 0, ['hex' => '#222222'], true],
                    ['color', 'frosted', 'Frosted White', 0, ['hex' => '#f8f9fa'], false],
                ]);
                break;
                
            default:
                $rows = $fontOptions;
                break;
        }

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
