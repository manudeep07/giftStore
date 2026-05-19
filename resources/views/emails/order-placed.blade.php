@extends('emails.layout')

@section('body')
    <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">Hi <strong>{{ $order->shipping_name }}</strong>,</p>
    <p style="margin:0 0 24px;font-size:15px;line-height:1.6;color:#475569;">
        Thank you! Your payment was received and your order is now <strong style="color:#059669;">placed</strong>.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc;border-radius:12px;margin-bottom:24px;">
        <tr>
            <td style="padding:20px;">
                <p style="margin:0 0 8px;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:#64748b;">Order number</p>
                <p style="margin:0;font-size:20px;font-weight:700;color:#0f172a;">{{ $order->order_number }}</p>
                <p style="margin:12px 0 0;font-size:14px;color:#475569;">Total paid: <strong style="color:#0f172a;">₹{{ number_format($order->total, 2) }}</strong></p>
                <p style="margin:4px 0 0;font-size:13px;color:#64748b;">Payment status: {{ ucfirst($order->payment?->status ?? 'paid') }}</p>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 12px;font-size:14px;font-weight:600;color:#0f172a;">Items</p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:24px;">
        @foreach ($order->items as $item)
            <tr style="border-bottom:1px solid #e2e8f0;">
                <td style="padding:12px 16px;font-size:14px;">{{ $item->product_name }} × {{ $item->quantity }}</td>
                <td style="padding:12px 16px;font-size:14px;text-align:right;font-weight:600;">₹{{ number_format($item->line_total, 2) }}</td>
            </tr>
        @endforeach
    </table>

    <p style="margin:0 0 8px;font-size:14px;font-weight:600;">Shipping to</p>
    <p style="margin:0 0 24px;font-size:14px;line-height:1.6;color:#475569;">
        {{ $order->shipping_address_line1 }}<br>
        @if ($order->shipping_address_line2){{ $order->shipping_address_line2 }}<br>@endif
        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal }}<br>
        {{ $order->shipping_country }}
    </p>

    <a href="{{ route('orders.show', $order) }}" style="display:inline-block;background:#0f172a;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;padding:12px 24px;border-radius:10px;">View order</a>
@endsection
