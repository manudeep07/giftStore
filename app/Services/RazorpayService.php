<?php

namespace App\Services;

use App\Models\Order;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayService
{
    public function isConfigured(): bool
    {
        return filled(config('services.razorpay.key_id'))
            && filled(config('services.razorpay.key_secret'));
    }

    /**
     * @return array{id: string, amount: int, currency: string}
     */
    public function createOrder(Order $order): array
    {
        $amountPaise = (int) round((float) $order->total * 100);

        if ($amountPaise < 100) {
            throw new \InvalidArgumentException('Order total must be at least ₹1.00 for Razorpay.');
        }

        $razorpayOrder = $this->api()->order->create([
            'receipt' => $order->order_number,
            'amount' => $amountPaise,
            'currency' => 'INR',
            'notes' => [
                'giftstore_order_id' => (string) $order->id,
                'giftstore_order_number' => $order->order_number,
            ],
        ]);

        return [
            'id' => $razorpayOrder['id'],
            'amount' => (int) $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
        ];
    }

    public function verifyPaymentSignature(string $razorpayOrderId, string $razorpayPaymentId, string $razorpaySignature): void
    {
        try {
            $this->api()->utility->verifyPaymentSignature([
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
            ]);
        } catch (SignatureVerificationError $e) {
            throw new \RuntimeException('Payment signature verification failed.', 0, $e);
        }
    }

    public function verifyWebhookSignature(string $payload, string $signature): void
    {
        $secret = config('services.razorpay.webhook_secret');

        if (! filled($secret)) {
            throw new \RuntimeException('RAZORPAY_WEBHOOK_SECRET is not configured.');
        }

        $this->api()->utility->verifyWebhookSignature($payload, $signature, $secret);
    }

    private function api(): Api
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Razorpay keys are missing. Set RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET in .env.');
        }

        return new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret'),
        );
    }
}
