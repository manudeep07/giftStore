<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name')) — bespoke gifting</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|fraunces:500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased font-[family-name:var(--font-dm-sans)]" style="--font-dm-sans: 'DM Sans', ui-sans-serif, system-ui, sans-serif;">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-sm font-semibold text-white shadow-sm ring-1 ring-slate-900/10 transition group-hover:-translate-y-0.5">CG</span>
                    <div class="leading-tight">
                        <p class="font-semibold tracking-tight text-slate-900">CustomGift</p>
                        <p class="text-xs text-slate-500">Personalized gestures</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex">
                    <a href="{{ route('shop.index') }}" class="transition hover:text-slate-900 {{ request()->routeIs('shop.*') ? 'text-slate-900' : '' }}">Shop</a>
                    <!-- <a href="{{ route('gift.ideas') }}" class="transition hover:text-slate-900 {{ request()->routeIs('gift.ideas') ? 'text-slate-900' : '' }}">Gift ideas</a> -->
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-slate-900">Admin</a>
                        @endif
                    @endauth
                </nav>
            </div>

            <div class="flex flex-1 items-center justify-end gap-3 md:flex-none md:gap-4">
                <form action="{{ route('shop.index') }}" method="get" class="hidden min-w-[220px] flex-1 md:block">
                    <label class="sr-only" for="global-search">Search products</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke-width="2" stroke-linecap="round"/></svg>
                        </span>
                        <input id="global-search" name="q" value="{{ request('q') }}" type="search" placeholder="Search keepsakes…" class="w-full rounded-xl border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm shadow-sm outline-none ring-slate-900/5 transition placeholder:text-slate-400 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10" />
                    </div>
                </form>

                <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 6h15l-2 9H7L6 6Zm0 0L5 3H2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                    <span class="hidden sm:inline">Cart</span>
                </a>

                @auth
                    <a href="{{ route('wishlist.index') }}" class="hidden rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900 sm:inline-flex">Wishlist</a>
                    <a href="{{ route('dashboard') }}" class="hidden rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-slate-900/10 transition hover:bg-slate-800 sm:inline-flex">Account</a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline">
                        @csrf
                        <button class="rounded-xl px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900" type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">Login</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-slate-900/10 transition hover:bg-slate-800">Join</a>
                @endauth
            </div>
        </div>
    </header>

    @include('components.flash')

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-8 px-4 py-12 sm:px-6 lg:flex-row lg:justify-between lg:px-8">
            <div class="max-w-md space-y-3">
                <p class="text-lg font-semibold text-slate-900">Crafted like a Series B darling.</p>
                <p class="text-sm text-slate-600">Every basket mirrors admin-managed pricing physics — upload art, stack accessories, and preview totals without stale spreadsheets.</p>
            </div>
            <div class="grid grid-cols-2 gap-8 text-sm text-slate-600 sm:grid-cols-3">
                <div class="space-y-2">
                    <p class="font-semibold text-slate-900">Explore</p>
                    <a class="block hover:text-slate-900" href="{{ route('shop.index') }}">Catalog</a>
                    <a class="block hover:text-slate-900" href="{{ route('gift.ideas') }}">Gift ideas</a>
                </div>
                <div class="space-y-2">
                    <p class="font-semibold text-slate-900">Support</p>
                    @auth
                        <a class="block hover:text-slate-900" href="{{ route('orders.index') }}">Orders</a>
                    @else
                        <span class="block text-slate-400">Orders after login</span>
                    @endauth
                    <span class="block text-slate-400">Concierge 24/7 (soon)</span>
                </div>
                <div class="space-y-2">
                    <p class="font-semibold text-slate-900">Legal</p>
                    <span class="block text-slate-400">Privacy & terms placeholders</span>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-100 py-6 text-center text-xs text-slate-400">
            © {{ date('Y') }} CustomGift · Laravel {{ app()->version() }}
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
