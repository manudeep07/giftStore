<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent when a customer cancels their order. */
class OrderCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public bool $refundPending = false,
    ) {
        $this->order->loadMissing(['items', 'payment']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order cancelled · '.$this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-cancelled',
            with: [
                'headline' => 'Order cancelled',
                'refundPending' => $this->refundPending,
            ],
        );
    }
}
