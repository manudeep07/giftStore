@extends('layouts.store')

@section('title', 'Account')

@section('content')
    <div class="grid gap-8 lg:grid-cols-[1fr_0.9fr] lg:items-start">
        <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-lg ring-1 ring-slate-900/5">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Overview</p>
            <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="mt-3 text-sm text-slate-600">Manage bespoke carts, wishlists, and fulfillment telemetry from one calm surface.</p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <a href="{{ route('orders.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm transition hover:border-slate-300">
                    <p class="text-xs uppercase tracking-wide text-slate-500">My Orders</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ auth()->user()->orders()->count() }}</p>
                    <p class="text-xs text-slate-500">Historical receipts</p>
                </a>
                <a href="{{ route('wishlist.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm transition hover:border-slate-300">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Wishlist</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ auth()->user()->wishlistItems()->count() }}</p>
                    <p class="text-xs text-slate-500">Ideas on ice</p>
                </a>
            </div>

            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ route('cart.index') }}" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 hover:bg-slate-800">Resume cart</a>
                <a href="{{ route('profile.edit') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-900 hover:border-slate-300">Profile security</a>
            </div>
        </section>

        
    </div>
@endsection
