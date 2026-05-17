@extends('layouts.store')

@section('title', 'Curated Gifts & Custom Creations')

@section('content')
<div class="space-y-16">
    <!-- Hero Section -->
    <section class="group relative overflow-hidden rounded-[3rem] bg-slate-950 px-6 py-24 text-center shadow-2xl transition-all duration-700 hover:shadow-[0_0_80px_rgba(79,70,229,0.15)] sm:px-12 sm:py-32">
        
        <!-- Animated Background Orbs -->
        <div class="absolute inset-0 z-0 overflow-hidden opacity-60 transition-opacity duration-700 group-hover:opacity-80">
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-[150%] w-[150%] animate-[spin_40s_linear_infinite] bg-[conic-gradient(from_0deg,transparent,theme(colors.indigo.500/10),theme(colors.rose.500/10),transparent)] mix-blend-screen"></div>
            
            <div class="absolute -left-[10%] -top-[10%] h-[60%] w-[60%] rounded-full bg-indigo-600/30 blur-[100px] animate-[pulse_8s_ease-in-out_infinite]"></div>
            <div class="absolute -right-[10%] -bottom-[10%] h-[50%] w-[50%] rounded-full bg-rose-600/20 blur-[100px] animate-[pulse_10s_ease-in-out_infinite_reverse]"></div>
            <div class="absolute left-[20%] bottom-[20%] h-[30%] w-[30%] rounded-full bg-purple-600/20 blur-[80px] animate-float"></div>
        </div>

        <!-- Glassmorphism Content Container -->
        <div class="relative z-10 mx-auto max-w-4xl animate-fade-in-up overflow-hidden rounded-[2.5rem] border border-white/10 bg-white/5 px-8 py-16 shadow-2xl backdrop-blur-xl transition-transform duration-700 group-hover:scale-[1.02] sm:px-16 sm:py-20">
            
            <!-- Subtle noise overlay for premium feel -->
            <div class="absolute inset-0 opacity-[0.03] mix-blend-overlay" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>

            <div class="relative z-20 space-y-8">
                <div class="mx-auto inline-flex items-center gap-2 rounded-full border border-indigo-500/30 bg-indigo-500/10 px-4 py-1.5 text-sm font-medium text-indigo-200">
                    <span class="relative flex h-2 w-2">
                      <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-indigo-400 opacity-75"></span>
                      <span class="relative inline-flex h-2 w-2 rounded-full bg-indigo-500"></span>
                    </span>
                    Now with Live Preview
                </div>

                <h1 class="font-[family-name:var(--font-serif)] text-5xl font-medium tracking-tight text-white sm:text-7xl lg:text-8xl" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">
                    Gifts that speak <br/>
                    <span class="animate-glow inline-block bg-gradient-to-r from-indigo-400 via-rose-300 to-purple-400 bg-clip-text pb-2 text-transparent">volumes.</span>
                </h1>
                
                <p class="mx-auto max-w-2xl text-lg font-light leading-relaxed text-slate-300 sm:text-xl">
                    Explore our curated collection of customizable keepsakes. Select a category below to find the perfect canvas for your personal touch.
                </p>
                
                <!-- Animated Arrow down -->
                <div class="animate-bounce pt-8">
                    <svg class="mx-auto h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Discovery (Glassmorphism) -->
    <section class="sticky top-20 z-30 -mx-4 px-4 py-4 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="mx-auto max-w-5xl rounded-full border border-white/40 bg-white/60 p-2 shadow-lg shadow-slate-200/50 backdrop-blur-xl">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 hide-scrollbar">
                <a href="{{ route('shop.index') }}" 
                   class="whitespace-nowrap rounded-full px-6 py-2.5 text-sm font-semibold transition-all duration-300 
                   {{ !request('category') ? 'bg-slate-900 text-white shadow-md scale-105' : 'text-slate-600 hover:bg-white/80 hover:text-slate-900' }}">
                    All Gifts
                </a>
                
                @foreach ($categories as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" 
                       class="whitespace-nowrap rounded-full px-6 py-2.5 text-sm font-semibold transition-all duration-300 
                       {{ request('category') === $category->slug ? 'bg-slate-900 text-white shadow-md scale-105' : 'text-slate-600 hover:bg-white/80 hover:text-slate-900' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Product Grid -->
    <section>
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <h2 class="font-[family-name:var(--font-serif)] text-3xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">
                    {{ request('category') ? $categories->firstWhere('slug', request('category'))?->name ?? 'Collection' : 'Latest Arrivals' }}
                </h2>
                <p class="mt-2 text-slate-600">{{ $products->total() }} pieces ready to tailor</p>
            </div>
            
            <form method="get" class="hidden sm:block">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                <div class="relative">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search catalog..." class="w-64 rounded-full border border-slate-200 bg-white/80 px-4 py-2.5 pl-10 text-sm shadow-sm backdrop-blur transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                    <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke-width="2" stroke-linecap="round"/></svg>
                </div>
            </form>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-[2rem] border border-dashed border-slate-300 bg-slate-50/50 py-32 text-center backdrop-blur-sm">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-sm">
                        <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h3 class="font-[family-name:var(--font-serif)] text-xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">No matches found</h3>
                    <p class="mt-2 max-w-sm text-slate-500">We couldn't find any gifts matching your criteria. Try widening your search or selecting a different category.</p>
                    <a href="{{ route('shop.index') }}" class="mt-6 rounded-full bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 hover:-translate-y-0.5">Clear filters</a>
                </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $products->links() }}
        </div>
    </section>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes glow {
        0%, 100% { filter: drop-shadow(0 0 15px rgba(225, 29, 72, 0.4)); }
        50% { filter: drop-shadow(0 0 30px rgba(79, 70, 229, 0.6)); }
    }
    .animate-glow {
        animation: glow 4s ease-in-out infinite;
    }
</style>
@endsection
