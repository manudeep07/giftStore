<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; padding: 40px; color: #0f172a; }
        .muted { color: #64748b; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; font-size: 13px; }
        th, td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        .total { font-size: 20px; font-weight: 700; margin-top: 24px; }
        @media print {
            body { padding: 0; }
            a { display: none; }
        }
    </style>
</head>
<body>
    <h1>CustomGift Invoice</h1>
    <p class="muted">Issued {{ now()->toFormattedDateString() }}</p>
    <p><strong>{{ $order->order_number }}</strong><br>
        Customer · {{ $order->user->name }} ({{ $order->user->email }})</p>

    <section style="margin-top:24px;">
        <p><strong>Bill to</strong><br>
            {{ $order->shipping_name }}<br>
            {{ $order->shipping_email }}<br>
            {{ $order->shipping_phone }}<br>
            {{ $order->shipping_address_line1 }}<br>
            @if ($order->shipping_address_line2) {{ $order->shipping_address_line2 }}<br> @endif
            {{ $order->shipping_city }}, {{ $order->shipping_postal }}<br>
            {{ $order->shipping_country }}
        </p>
    </section>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Line</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->unit_price, 2) }}</td>
                    <td>₹{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="muted" style="margin-top:16px;">Customization JSON archived for fulfillment QA.</p>

    <p class="total">Total · ₹{{ number_format($order->total, 2) }}</p>

    <p class="muted">Payment reference {{ $order->payment?->transaction_ref }}</p>

    <p style="margin-top:40px;"><a href="{{ url()->previous() }}">← Back</a></p>
</body>
</html>
