<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request, CartService $carts): View
    {
        if ($request->user()) {
            $carts->mergeGuestCartFor($request->user(), $request);
        }

        $cart = $carts->getOrCreateCart($request)->load('items.product.images');

        return view('cart.index', compact('cart'));
    }

    public function store(StoreCartItemRequest $request, Product $product, CartService $carts): RedirectResponse
    {
        $this->authorize('view', $product);

        if ($request->user()) {
            $carts->mergeGuestCartFor($request->user(), $request);
        }

        $cart = $carts->getOrCreateCart($request);

        $selections = $request->selections();
        $selections['has_image_upload'] = $request->boolean('has_image_upload') || $request->hasFile('upload');

        $path = null;
        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('uploads/custom', 'public');
        }

        $carts->addLine($cart, $product, $selections, (int) $request->validated('quantity'), $path);

        return redirect()->route('cart.index')->with('success', 'Added to your bespoke cart.');
    }

    public function update(Request $request, CartItem $cartItem, CartService $carts): RedirectResponse
    {
        $cart = $carts->getOrCreateCart($request);
        abort_if($cartItem->cart_id !== $cart->id, 403);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $cartItem->quantity = (int) $data['quantity'];
        $unit = (float) $cartItem->unit_price;
        $cartItem->line_total = number_format($unit * $cartItem->quantity, 2, '.', '');
        $cartItem->save();

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Request $request, CartItem $cartItem, CartService $carts): RedirectResponse
    {
        $cart = $carts->getOrCreateCart($request);
        abort_if($cartItem->cart_id !== $cart->id, 403);

        $cartItem->delete();

        return back()->with('success', 'Removed from cart.');
    }
}
