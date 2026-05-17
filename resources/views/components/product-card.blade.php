@props(['product'])

@php
    $img = $product->primaryImage();
    $src = $img ? $product->imageUrl($img->path) : null;
@endphp

<article {{ $attributes->merge(['class' => 'group relative flex flex-col overflow-hidden rounded-[1.5rem] bg-white shadow-sm ring-1 ring-slate-900/5 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl']) }}>
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-[4/5] overflow-hidden bg-slate-100">
        @if ($src)
            <img src="{{ $src }}" alt="{{ $img->alt ?? $product->name }}" class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-110" loading="lazy" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/0 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 via-white to-slate-200 text-sm font-medium text-slate-500">
                Preview incoming
            </div>
        @endif
        
        @if ($product->stock <= 0)
            <span class="absolute left-4 top-4 rounded-full bg-rose-500/90 px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-widest text-white shadow-sm backdrop-blur-md transition-transform duration-500 group-hover:scale-105">Out of Stock</span>
        @elseif ($product->badge_label)
            <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-900 shadow-sm backdrop-blur-md transition-transform duration-500 group-hover:scale-105">{{ $product->badge_label }}</span>
        @endif
        
        <div class="absolute bottom-4 left-4 right-4 translate-y-8 opacity-0 transition-all duration-500 group-hover:translate-y-0 group-hover:opacity-100">
            @if ($product->stock <= 0)
                <span class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100/95 px-4 py-2.5 text-sm font-semibold text-slate-400 shadow-lg backdrop-blur-sm">
                    Sold Out
                </span>
            @else
                <span class="flex w-full items-center justify-center gap-2 rounded-xl bg-white/95 px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-lg backdrop-blur-sm">
                    Personalize
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14m7-7H5" stroke-width="2" stroke-linecap="round"/></svg>
                </span>
            @endif
        </div>
    </a>

    <div class="flex flex-1 flex-col p-6">
        <div class="mb-3">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $product->category?->name }}</p>
        </div>
        <div class="flex items-start justify-between gap-4">
            <h3 class="font-[family-name:var(--font-serif)] text-lg font-semibold leading-tight text-slate-900 transition-colors group-hover:text-indigo-600" style="--font-serif: 'Fraunces', ui-serif, Georgia, serif;">
                <a href="{{ route('products.show', $product) }}">
                    <span class="absolute inset-0 z-10"></span>
                    {{ $product->name }}
                </a>
            </h3>
            <p class="whitespace-nowrap font-medium text-slate-900">₹{{ number_format($product->base_price, 0) }}</p>
        </div>
    </div>
</article>
