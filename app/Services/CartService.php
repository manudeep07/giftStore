<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Resolves persistent carts for guests + members and merges session carts after login.
 */
class CartService
{
    public function __construct(
        protected PricingService $pricing,
    ) {}

    public function getOrCreateCart(Request $request): Cart
    {
        if ($request->user()) {
            return Cart::firstOrCreate(
                ['user_id' => $request->user()->id],
                ['session_id' => null],
            );
        }

        return Cart::firstOrCreate(
            [
                'session_id' => $request->session()->getId(),
                'user_id' => null,
            ],
            [],
        );
    }

    /**
     * Moves anonymous rows onto the authenticated cart to avoid losing bespoke work mid-checkout.
     */
    public function mergeGuestCartFor(User $user, Request $request): void
    {
        $sessionId = $request->session()->getId();

        $guest = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
        if (! $guest || $guest->items()->doesntExist()) {
            return;
        }

        $member = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['session_id' => null],
        );

        DB::transaction(function () use ($guest, $member): void {
            CartItem::where('cart_id', $guest->id)->update(['cart_id' => $member->id]);
            $guest->delete();
        });
    }

    /**
     * Adds or increments a matching customized line (same product + same snapshot payload).
     *
     * @param  array<string, mixed>  $selections
     */
    public function addLine(Cart $cart, Product $product, array $selections, int $quantity = 1, ?string $uploadPath = null): CartItem
    {
        $snapshot = $this->pricing->snapshot($product, $selections, $uploadPath);
        $unit = (float) $snapshot['unit_price'];

        $existing = $cart->items()
            ->where('product_id', $product->id)
            ->get()
            ->first(function (CartItem $item) use ($snapshot): bool {
                return json_encode($item->customization_snapshot) === json_encode($snapshot);
            });

        if ($existing) {
            $existing->quantity += $quantity;
            $existing->line_total = number_format($unit * $existing->quantity, 2, '.', '');
            $existing->save();

            return $existing;
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'customization_snapshot' => $snapshot,
            'unit_price' => number_format($unit, 2, '.', ''),
            'line_total' => number_format($unit * $quantity, 2, '.', ''),
        ]);
    }

    public function recalculateLine(CartItem $item): void
    {
        $product = $item->product;
        $snapshot = $item->customization_snapshot;
        $selections = $snapshot['selections'] ?? [];
        $upload = $snapshot['upload_path'] ?? null;

        $fresh = $this->pricing->snapshot($product, $selections, $upload);
        $unit = (float) $fresh['unit_price'];

        $item->customization_snapshot = $fresh;
        $item->unit_price = number_format($unit, 2, '.', '');
        $item->line_total = number_format($unit * $item->quantity, 2, '.', '');
        $item->save();
    }
}
