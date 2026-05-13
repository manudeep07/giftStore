@extends('layouts.store')

@section('title', 'Gift ideas')

@section('content')
    <div class="mx-auto max-w-4xl space-y-10 rounded-3xl border border-slate-200 bg-white p-10 shadow-xl ring-1 ring-slate-900/5">
        <div class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Roadmap</p>
            <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Concierge-grade recommendations — AI lane upcoming.</h1>
            <p class="text-lg text-slate-600">Today we ship deterministic storytelling so merchandisers understand where embeddings will plug in later: vector memory per shopper, occasion calendars, and socially curated bundles.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-inner">
                <p class="text-xs uppercase tracking-wide text-white/70">Phase 01</p>
                <p class="mt-3 text-xl font-semibold">Signals</p>
                <p class="mt-2 text-sm text-white/80">Capture browsing entropy + cart abandon cues.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 p-6 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500">Phase 02</p>
                <p class="mt-3 text-xl font-semibold">Models</p>
                <p class="mt-2 text-sm text-slate-600">Fine-tune lightweight LLMs on SKU metaphors + rituals.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 p-6 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500">Phase 03</p>
                <p class="mt-3 text-xl font-semibold">Delivery</p>
                <p class="mt-2 text-sm text-slate-600">Blend AI prompts with inventory-aware pricing guards.</p>
            </div>
        </div>

        <div class="rounded-3xl bg-slate-50 p-8 ring-1 ring-slate-900/5">
            <p class="text-sm font-semibold text-slate-900">Placeholder endpoint ready for:</p>
            <ul class="mt-4 list-disc space-y-2 pl-6 text-sm text-slate-600">
                <li>Partner embeddings stored alongside `products.meta_suggestions` JSON.</li>
                <li>Queue workers scoring affinity vs margin constraints.</li>
                <li>SMS/email drip referencing personalized narratives.</li>
            </ul>
        </div>
    </div>
@endsection
