@extends('layouts.admin')

@section('title', 'Create coupon')
@section('heading', 'Create coupon')

@section('content')
    <form action="{{ route('admin.coupons.store') }}" method="post" class="max-w-3xl space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8">
        @csrf

        @include('admin.coupons._form')

        <button class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900">Create coupon</button>
    </form>
@endsection
