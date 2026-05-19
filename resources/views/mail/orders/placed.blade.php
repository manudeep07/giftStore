<x-mail::message>
# Thanks for your order, {{ $order->shipping_name }}!

Your payment was received and our team is preparing your bespoke gifts.

**Order number:** {{ $order->order_number }}

**Total paid:** ₹{{ number_format($order->total, 2) }}

@component('mail::table')
| Item | Qty | Line total |
|:-----|:---:|-----------:|
@foreach ($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | ₹{{ number_format($item->line_total, 2) }} |
@endforeach
@endcomponent

**Ship to:**  
{{ $order->shipping_address_line1 }}  
@if ($order->shipping_address_line2){{ $order->shipping_address_line2 }}  
@endif
{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal }}  
{{ $order->shipping_country }}

<x-mail::button :url="route('orders.show', $order)">
View order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
