@props(['product'])

@php
    $img = $product->primaryImage();
    $src = $img ? $product->imageUrl($img->path) : null;
@endphp

<article {{ $attributes->merge(['class' => 'group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm ring-1 ring-slate-900/5 transition duration-300 hover:-translate-y-1 hover:shadow-xl']) }}>
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-[4/3] overflow-hidden bg-slate-100">
        @if ($src)
            <img src="{{ $src }}" alt="{{ $img->alt ?? $product->name }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy" />
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 via-white to-slate-200 text-sm font-medium text-slate-500">
                Preview incoming
            </div>
        @endif
        @if ($product->badge_label)
            <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900 shadow-sm backdrop-blur">{{ $product->badge_label }}</span>
        @endif
    </a>

    <div class="flex flex-1 flex-col gap-3 p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ $product->category?->name }}</p>
                <h3 class="font-[family-name:var(--font-serif)] text-lg font-semibold text-slate-900" style="--font-serif: 'Fraunces', ui-serif, Georgia, serif;">
                    <a href="{{ route('products.show', $product) }}" class="hover:underline">{{ $product->name }}</a>
                </h3>
            </div>
            <p class="text-base font-semibold text-slate-900">₹{{ number_format($product->base_price, 0) }}</p>
        </div>
        <p class="line-clamp-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($product->description ?? ''), 120) }}</p>
        <div class="mt-auto flex items-center justify-between pt-2">
            <span class="text-xs font-medium text-slate-500">{{ $product->stock > 0 ? $product->stock.' ready to tailor' : 'Made-to-order' }}</span>
            <a href="{{ route('products.show', $product) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-900 hover:text-slate-600">
                Customize
                <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round"/></svg>
            </a>
        </div>
    </div>
</article>
