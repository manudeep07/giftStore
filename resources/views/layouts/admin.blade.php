<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin · '.config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" defer></script>
    @stack('head')
</head>
<body class="min-h-screen bg-slate-950 text-slate-50 antialiased" style="font-family:'DM Sans',system-ui,sans-serif;">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 flex-shrink-0 flex-col border-r border-white/10 bg-slate-950/90 p-6 lg:flex">
            <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold text-white">CustomGift Ops</a>
            <nav class="mt-10 space-y-2 text-sm font-medium text-slate-300">
                <a class="block rounded-xl px-3 py-2 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="block rounded-xl px-3 py-2 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.categories.*') ? 'bg-white/10 text-white' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a>
                <a class="block rounded-xl px-3 py-2 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.products.*') ? 'bg-white/10 text-white' : '' }}" href="{{ route('admin.products.index') }}">Products</a>
                <a class="block rounded-xl px-3 py-2 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.orders.*') ? 'bg-white/10 text-white' : '' }}" href="{{ route('admin.orders.index') }}">Orders</a>
                <a class="block rounded-xl px-3 py-2 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.coupons.*') ? 'bg-white/10 text-white' : '' }}" href="{{ route('admin.coupons.index') }}">Coupons</a>
                <a class="block rounded-xl px-3 py-2 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.reviews.*') ? 'bg-white/10 text-white' : '' }}" href="{{ route('admin.reviews.index') }}">Reviews</a>
                <a class="block rounded-xl px-3 py-2 hover:bg-white/5 hover:text-white" href="{{ route('home') }}" target="_blank">View storefront</a>
            </nav>
            <div class="mt-auto pt-10 text-xs text-slate-500">
                Signed in as<br><span class="text-slate-200">{{ auth()->user()->email }}</span>
            </div>
        </aside>

        <main class="flex-1 bg-slate-900/60">
            <header class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 px-6 py-5 lg:px-10">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Control plane</p>
                    <h1 class="text-2xl font-semibold text-white">@yield('heading', 'Overview')</h1>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-full border border-white/10 px-4 py-2 text-xs font-semibold text-white hover:bg-white/5" type="submit">Logout</button>
                </form>
            </header>

            <div class="space-y-6 px-6 py-8 lg:px-10">
                @include('components.flash-admin')
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
