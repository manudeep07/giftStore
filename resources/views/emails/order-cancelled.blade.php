@extends('emails.layout')

@section('body')
    <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">Hi <strong>{{ $order->shipping_name }}</strong>,</p>
    <p style="margin:0 0 24px;font-size:15px;line-height:1.6;color:#475569;">
        Your order <strong>{{ $order->order_number }}</strong> has been <strong style="color:#dc2626;">cancelled</strong>.
    </p>

    @if ($refundPending)
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;margin-bottom:24px;">
            <tr>
                <td style="padding:16px 20px;">
                    <p style="margin:0;font-size:14px;line-height:1.6;color:#9a3412;">
                        You paid ₹{{ number_format($order->total, 2) }} for this order. Our team will process your refund shortly and you will receive a separate confirmation email.
                    </p>
                </td>
            </tr>
        </table>
    @else
        <p style="margin:0 0 24px;font-size:14px;color:#475569;">No payment was captured for this order.</p>
    @endif

    <a href="{{ route('orders.show', $order) }}" style="display:inline-block;background:#0f172a;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;padding:12px 24px;border-radius:10px;">View order details</a>
@endsection
