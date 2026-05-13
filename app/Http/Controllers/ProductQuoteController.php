<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotePriceRequest;
use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;

/** JSON endpoint powering instant rupee updates without full round trips. */
class ProductQuoteController extends Controller
{
    public function __invoke(QuotePriceRequest $request, Product $product, PricingService $pricing): JsonResponse
    {
        $this->authorize('view', $product);

        $payload = $pricing->quote($product, $request->selections());

        return response()->json([
            'currency' => 'INR',
            'unit_price' => $payload['unit_price'],
            'breakdown' => $payload['breakdown'],
        ]);
    }
}
