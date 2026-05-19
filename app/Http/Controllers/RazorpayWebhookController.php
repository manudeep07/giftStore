<?php

namespace App\Http\Controllers;

use App\Services\PaymentFulfillmentService;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RazorpayWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        RazorpayService $razorpay,
        PaymentFulfillmentService $fulfillment,
    ): Response {
        $signature = $request->header('X-Razorpay-Signature', '');
        $payload = $request->getContent();

        try {
            $razorpay->verifyWebhookSignature($payload, $signature);
        } catch (\Throwable $e) {
            Log::warning('Razorpay webhook signature failed', ['message' => $e->getMessage()]);

            return response('Invalid signature', 400);
        }

        /** @var array<string, mixed> $body */
        $body = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        $event = $body['event'] ?? '';

        if ($event === 'payment.captured') {
            $entity = $body['payload']['payment']['entity'] ?? [];
            $razorpayOrderId = $entity['order_id'] ?? null;
            $razorpayPaymentId = $entity['id'] ?? null;

            if (is_string($razorpayOrderId) && is_string($razorpayPaymentId)) {
                $order = $fulfillment->findOrderByRazorpayOrderId($razorpayOrderId);

                if ($order) {
                    $fulfillment->fulfill($order, $razorpayPaymentId, $razorpayOrderId);
                }
            }
        }

        return response('OK', 200);
    }
}
