@props([
    'snapshot' => null,
    'quantity' => null,
    'unitPrice' => null,
    'lineTotal' => null,
    'productName' => null,
    'compact' => false,
    'theme' => 'light',
])

@php
    $selections = \App\Support\CustomizationPresenter::selections($snapshot);
    $isDark = $theme === 'dark';
@endphp

<div {{ $attributes->class(['space-y-2', 'text-sm' => ! $compact]) }}>
    @if ($productName)
        <p class="{{ $isDark ? 'font-semibold text-white' : 'font-semibold text-slate-900' }}">
            {{ $productName }}
            @if ($quantity)
                <span class="{{ $isDark ? 'text-white/70' : 'text-slate-500' }}">· Qty {{ $quantity }}</span>
            @endif
        </p>
    @endif

    @if ($selections !== [])
        <ul class="{{ $compact ? 'space-y-1 text-xs' : 'grid gap-2 sm:grid-cols-2' }} {{ $isDark ? 'text-white/80' : 'text-slate-600' }}">
            @foreach (['material', 'size', 'color', 'font', 'gift_wrap', 'engraving'] as $key)
                @if (! empty($selections[$key]))
                    <li class="{{ $compact ? '' : 'rounded-lg bg-slate-50 px-3 py-2 ring-1 ring-slate-900/5 ' . ($isDark ? '!bg-white/5 !text-white/80 !ring-white/10' : '') }}">
                        <span class="{{ $isDark ? 'text-white/50' : 'text-slate-500' }}">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                        <span class="{{ $isDark ? 'text-white' : 'font-medium text-slate-900' }}">{{ $selections[$key] }}</span>
                    </li>
                @endif
            @endforeach
            @if (! empty($selections['addons']))
                <li class="{{ $compact ? '' : 'rounded-lg bg-slate-50 px-3 py-2 ring-1 ring-slate-900/5 sm:col-span-2 ' . ($isDark ? '!bg-white/5 !ring-white/10' : '') }}">
                    <span class="{{ $isDark ? 'text-white/50' : 'text-slate-500' }}">Add-ons:</span>
                    <span class="{{ $isDark ? 'text-white' : 'font-medium text-slate-900' }}">{{ implode(', ', $selections['addons']) }}</span>
                </li>
            @endif
            @if (! empty($selections['custom_text']))
                <li class="{{ $compact ? '' : 'rounded-lg bg-slate-50 px-3 py-2 ring-1 ring-slate-900/5 sm:col-span-2 ' . ($isDark ? '!bg-white/5 !ring-white/10' : '') }}">
                    <span class="{{ $isDark ? 'text-white/50' : 'text-slate-500' }}">Message:</span>
                    <span class="{{ $isDark ? 'text-white' : 'font-medium text-slate-900' }}">{{ $selections['custom_text'] }}</span>
                </li>
            @endif
            @if (! empty($snapshot['upload_path']))
                <li class="{{ $compact ? 'sm:col-span-2' : 'sm:col-span-2' }}">
                    <span class="{{ $isDark ? 'text-white/50' : 'text-slate-500' }}">Custom artwork attached</span>
                </li>
            @endif
        </ul>
    @endif

    @if ($unitPrice !== null || $lineTotal !== null)
        <p class="{{ $isDark ? 'text-white font-semibold' : 'font-semibold text-slate-900' }}">
            @if ($lineTotal !== null)
                ₹{{ number_format((float) $lineTotal, 2) }}
            @elseif ($unitPrice !== null)
                ₹{{ number_format((float) $unitPrice, 2) }} each
            @endif
        </p>
    @endif
</div>
