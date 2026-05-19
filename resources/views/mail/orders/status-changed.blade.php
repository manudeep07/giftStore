<x-mail::message>
# Order status updated

Hi {{ $order->shipping_name }},

Your order **{{ $order->order_number }}** moved from **{{ ucfirst($previousStatus) }}** to **{{ ucfirst($order->status) }}**.

<x-mail::button :url="route('orders.show', $order)">
Track order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
