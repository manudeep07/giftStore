@extends('emails.layout')

@section('body')
    <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">Hi <strong>{{ $order->shipping_name }}</strong>,</p>
    <p style="margin:0 0 24px;font-size:15px;line-height:1.6;color:#475569;">
        Your refund for order <strong>{{ $order->order_number }}</strong> has been processed.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;margin-bottom:24px;">
        <tr>
            <td style="padding:20px;">
                <p style="margin:0 0 8px;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:#047857;">Refund amount</p>
                <p style="margin:0;font-size:28px;font-weight:700;color:#065f46;">₹{{ number_format($refund->amount, 2) }}</p>
                <p style="margin:12px 0 0;font-size:13px;color:#047857;">Reference: {{ $refund->reference }}</p>
                <p style="margin:4px 0 0;font-size:13px;color:#047857;">Payment status: Refunded</p>
            </td>
        </tr>
    </table>

    @if ($refund->reason)
        <p style="margin:0 0 24px;font-size:14px;color:#475569;"><strong>Note:</strong> {{ $refund->reason }}</p>
    @endif

    <p style="margin:0 0 24px;font-size:13px;color:#64748b;">
        On localhost we record refunds in the database. In production you would also trigger the refund in the Razorpay dashboard or API.
    </p>

    <a href="{{ route('orders.show', $order) }}" style="display:inline-block;background:#0f172a;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;padding:12px 24px;border-radius:10px;">View order</a>
@endsection
