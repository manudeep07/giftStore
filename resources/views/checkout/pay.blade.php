@extends('layouts.store')

@section('title', 'Pay · '.$order->order_number)

@section('content')
    <div class="mx-auto max-w-lg space-y-8 text-center">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Secure checkout</p>
            <h1 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Pay for {{ $order->order_number }}</h1>
            <p class="mt-2 text-sm text-slate-600">Total due · <span class="font-semibold text-slate-900">₹{{ number_format($order->total, 2) }}</span></p>
        </div>

        @if (request('failed'))
            <p class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">Payment did not complete. Try again when ready.</p>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-lg ring-1 ring-slate-900/5">
            <button id="rzp-button" type="button" class="w-full rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-xl shadow-slate-900/15 transition hover:bg-slate-800">
                Pay with Razorpay
            </button>
            <p class="mt-4 text-xs text-slate-500">Test mode: use Razorpay test cards from the dashboard.</p>
            <a href="{{ route('orders.show', $order) }}" class="mt-6 inline-block text-sm font-medium text-slate-600 underline hover:text-slate-900">Back to order</a>
        </div>
    </div>

    <form id="payment-callback-form" action="{{ $callbackUrl }}" method="post" class="hidden">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" />
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" />
        <input type="hidden" name="razorpay_signature" id="razorpay_signature" />
    </form>
@endsection

@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('rzp-button').addEventListener('click', function () {
            const options = {
                key: @json($razorpayKeyId),
                amount: @json($amountPaise),
                currency: 'INR',
                name: @json(config('app.name')),
                description: @json('Order '.$order->order_number),
                order_id: @json($razorpayOrderId),
                prefill: {
                    name: @json($order->shipping_name),
                    email: @json($order->shipping_email),
                    contact: @json($order->shipping_phone),
                },
                theme: { color: '#0f172a' },
                handler: function (response) {
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                    document.getElementById('razorpay_signature').value = response.razorpay_signature;
                    document.getElementById('payment-callback-form').submit();
                },
                modal: {
                    ondismiss: function () {
                        window.location.href = @json(route('orders.show', $order));
                    },
                },
            };

            const rzp = new Razorpay(options);
            rzp.on('payment.failed', function () {
                window.location.href = @json(route('checkout.pay', $order)).concat('?failed=1');
            });
            rzp.open();
        });
    </script>
@endpush
