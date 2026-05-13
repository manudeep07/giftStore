@extends('layouts.store')

@section('title', 'Curated personalization')

@section('content')
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-white via-slate-50 to-slate-100 p-10 shadow-xl ring-1 ring-slate-900/5 md:p-14">
        <div class="relative grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div class="space-y-6">
                <p class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600 shadow-sm ring-1 ring-slate-200">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Live economics · Shopify-grade UX
                </p>
                <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold leading-tight text-slate-900 sm:text-5xl" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">
                    Gifts that price themselves as beautifully as they ship.
                </h1>
                <p class="text-lg text-slate-600">
                    Pair heirloom finishes, bespoke typography, and concierge packaging — every dial streams through Laravel services so shoppers never guess what premium costs.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
                        Browse catalog
                    </a>
                    <a href="{{ route('gift.ideas') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:border-slate-300">
                        Gift concierge preview
                    </a>
                </div>
                <dl class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm backdrop-blur">
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Turnaround</dt>
                        <dd class="text-2xl font-semibold text-slate-900">48h</dd>
                        <p class="text-xs text-slate-500">studio bench time</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm backdrop-blur">
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Pricing engine</dt>
                        <dd class="text-2xl font-semibold text-slate-900">Dynamic</dd>
                        <p class="text-xs text-slate-500">rules from MySQL</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm backdrop-blur">
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Experience</dt>
                        <dd class="text-2xl font-semibold text-slate-900">5★</dd>
                        <p class="text-xs text-slate-500">moderated reviews</p>
                    </div>
                </dl>
            </div>
            <div class="relative">
                <div class="absolute inset-0 rounded-[28px] bg-gradient-to-tr from-slate-900/10 via-transparent to-emerald-400/20 blur-3xl"></div>
                <div class="relative overflow-hidden rounded-[28px] border border-white shadow-2xl ring-1 ring-slate-900/10">
                    <img src="https://images.unsplash.com/photo-1513885535751-8b9238bd345a?auto=format&fit=crop&w=900&q=80" alt="Curated gift wrapping" class="h-full w-full object-cover" loading="lazy" />
                    <div class="absolute inset-x-6 bottom-6 rounded-2xl bg-white/95 p-4 shadow-xl backdrop-blur">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Spotlight</p>
                        <p class="text-lg font-semibold text-slate-900">Adaptive previews mirror checkout math instantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section class="mt-16 space-y-6">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Categories</p>
                    <h2 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Browse by ritual</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-sm font-semibold text-slate-900 hover:text-slate-600">View all</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($categories as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-900/5 transition hover:-translate-y-1 hover:shadow-lg">
                        <p class="text-lg font-semibold text-slate-900">{{ $category->name }}</p>
                        <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $category->description }}</p>
                        <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-slate-900">
                            Explore
                            <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mt-16 space-y-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Featured</p>
                <h2 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Editorially elevated picks</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-sm font-semibold text-slate-900 hover:text-slate-600">Shop everything</a>
        </div>
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center text-slate-600">Seed the catalog to populate this runway.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-16 space-y-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Trending</p>
                <h2 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Community-loved builds</h2>
            </div>
        </div>
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($trendingProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center text-slate-600">Reviews will lift trending SKUs automatically.</p>
            @endforelse
        </div>
    </section>

    @if ($recentProducts->isNotEmpty())
        <section class="mt-16 space-y-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Recently viewed</p>
                <h2 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Pick up where you left off</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($recentProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="mt-16 grid gap-8 rounded-3xl border border-slate-200 bg-white p-10 shadow-sm lg:grid-cols-3">
        <div class="space-y-3 lg:col-span-2">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Workflow</p>
            <h3 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Luxury-grade customization without the spreadsheet chaos.</h3>
            <ol class="mt-6 space-y-4 text-sm text-slate-600">
                <li class="flex gap-3"><span class="mt-1 h-6 w-6 flex-shrink-0 rounded-full bg-slate-900 text-center text-xs font-semibold leading-6 text-white">1</span> Upload artwork & dial materials — AJAX quotes stream while shoppers stay immersed.</li>
                <li class="flex gap-3"><span class="mt-1 h-6 w-6 flex-shrink-0 rounded-full bg-slate-900 text-center text-xs font-semibold leading-6 text-white">2</span> Cart snapshots freeze JSON selections so fulfillment sees exactly what was promised.</li>
                <li class="flex gap-3"><span class="mt-1 h-6 w-6 flex-shrink-0 rounded-full bg-slate-900 text-center text-xs font-semibold leading-6 text-white">3</span> Operations watches SLAs from the admin cockpit with inventory radar.</li>
            </ol>
        </div>
        <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-inner">
            <p class="text-sm uppercase tracking-wide text-white/70">Testimonials</p>
            <blockquote class="mt-4 text-lg font-medium leading-relaxed">“Feels closer to Stripe Checkout than a university Laravel demo — investors asked if we licensed the UI kit.”</blockquote>
            <p class="mt-4 text-sm text-white/70">— Maya Chen · Head of CX, placeholder brand</p>
        </div>
    </section>
@endsection
