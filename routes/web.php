<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomizationOptionController as AdminOptionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Admin\ReviewModerationController as AdminReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CheckoutCouponController;
use App\Http\Controllers\RazorpayWebhookController;
use App\Http\Controllers\GiftIdeasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductQuoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product:slug}/quote', ProductQuoteController::class)
    ->middleware('throttle:120,1')
    ->name('products.quote');

Route::get('/gift-ideas', GiftIdeasController::class)->name('gift.ideas');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product:slug}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.items.destroy');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', function () {
        if (request()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('customer.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/orders/{order}/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::post('/checkout/orders/{order}/payment/callback', [CheckoutController::class, 'callback'])
        ->name('checkout.payment.callback');
    Route::post('/checkout/coupon', [CheckoutCouponController::class, 'store'])->name('checkout.coupon.store');
    Route::delete('/checkout/coupon', [CheckoutCouponController::class, 'destroy'])->name('checkout.coupon.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

    Route::get('/orders/{order}/items/{orderItem}/review', [OrderReviewController::class, 'create'])
        ->name('orders.reviews.create');
    Route::post('/orders/{order}/items/{orderItem}/review', [OrderReviewController::class, 'store'])
        ->name('orders.reviews.store');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product:slug}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::post('/products/{product:slug}/reviews', [ReviewController::class, 'store'])
        ->name('products.reviews.store');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::delete('products/{product}/images/{image}', [AdminProductImageController::class, 'destroy'])
        ->name('products.images.destroy');
    Route::post('products/{product}/images/{image}/primary', [AdminProductImageController::class, 'primary'])
        ->name('products.images.primary');

    Route::get('products/{product}/options/create', [AdminOptionController::class, 'create'])
        ->name('products.options.create');
    Route::post('products/{product}/options', [AdminOptionController::class, 'store'])
        ->name('products.options.store');
    Route::get('products/{product}/options/{option}/edit', [AdminOptionController::class, 'edit'])
        ->name('products.options.edit');
    Route::put('products/{product}/options/{option}', [AdminOptionController::class, 'update'])
        ->name('products.options.update');
    Route::delete('products/{product}/options/{option}', [AdminOptionController::class, 'destroy'])
        ->name('products.options.destroy');

    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::post('orders/{order}/refund', [AdminOrderController::class, 'refund'])->name('orders.refund');

    Route::resource('coupons', AdminCouponController::class)->except(['show']);
    Route::post('coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');

    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])
        ->name('reviews.approve');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])
        ->name('reviews.destroy');
});

Route::post('/razorpay/webhook', RazorpayWebhookController::class)->name('razorpay.webhook');

require __DIR__.'/auth.php';
