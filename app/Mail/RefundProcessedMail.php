<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent when an admin marks a cancelled order as refunded. */
class RefundProcessedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public Refund $refund,
    ) {
        $this->order->loadMissing(['items', 'payment']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Refund processed · '.$this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.refund-processed',
            with: [
                'headline' => 'Refund processed',
            ],
        );
    }
}
