@extends('layouts.admin')

@section('title', $coupon->code)
@section('heading', 'Edit coupon')

@section('content')
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="post" class="max-w-3xl space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8">
        @csrf
        @method('PUT')

        @include('admin.coupons._form', ['coupon' => $coupon])

        <div class="flex flex-wrap gap-3">
            <button class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900">Save changes</button>
            <a href="{{ route('admin.coupons.index') }}" class="rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white">Back</a>
        </div>
    </form>
@endsection
