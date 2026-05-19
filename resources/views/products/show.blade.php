@extends('layouts.store')

@section('title', $product->name)

@section('content')
    @php
        /** @var \Illuminate\Support\Collection<string,\Illuminate\Support\Collection<int,\App\Models\CustomizationOption>> $optionsByGroup */
        $primary = $product->primaryImage();
        $hero = $primary ? $product->imageUrl($primary->path) : null;
    @endphp

    @push('head')
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @endpush

    <div class="grid gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:items-start" x-data="customGiftConfigurator(@js($customDefaults), @js($initialQuote), @js($optionMeta), @js(route('products.quote', $product)))">
        <div class="space-y-6 lg:sticky lg:top-28">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl ring-1 ring-slate-900/5">
                <div class="relative w-full aspect-[4/5] overflow-hidden bg-slate-100 flex items-center justify-center">
                    @if ($hero)
                        <img src="{{ $hero }}" alt="{{ $primary->alt ?? $product->name }}" 
                             class="absolute inset-0 h-full w-full object-cover transition-all duration-500" 
                             :style="materialFilterStyle"
                             id="hero-preview" />
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-slate-100 via-white to-slate-200"></div>
                    @endif

                    <!-- Visual Configurator Overlay -->
                    <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center p-8 transition-all duration-300 text-center z-10">
                        <template x-if="uploadPreview && has_image_upload">
                            <img :src="uploadPreview" class="w-3/4 max-h-[50%] object-contain rounded shadow-lg mix-blend-multiply opacity-90 mb-6 transition-all duration-300" />
                        </template>

                        <p x-show="custom_text" 
                           class="whitespace-pre-wrap break-words w-full transition-all duration-300"
                           :style="textOverlayStyle"
                           x-text="custom_text"></p>
                    </div>
                </div>

                <div class="border-t border-slate-100 bg-slate-50 p-6">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Live preview</p>
                    <div class="mt-4 rounded-2xl border border-dashed border-slate-200 bg-white p-6 shadow-inner">
                        <p class="text-sm font-semibold text-slate-900">Etching preview</p>
                        <p class="mt-3 max-h-32 overflow-auto whitespace-pre-wrap text-lg leading-relaxed text-slate-700" x-text="custom_text || 'Your engraving appears here in real time.'" data-custom-preview></p>
                        <div class="mt-4 grid gap-3 text-xs text-slate-500 sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-900/5">
                                <p class="font-semibold text-slate-700">Material</p>
                                <p class="mt-1" x-text="material || '—'"></p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-900/5">
                                <p class="font-semibold text-slate-700">Finish color</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="h-6 w-6 rounded-full border border-slate-200 shadow-inner" :style="selectedColorHex ? `background:${selectedColorHex}` : 'background:#e2e8f0'"></span>
                                    <p x-text="color || 'Default'"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4" x-show="uploadPreview">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Uploaded artwork</p>
                            <img :src="uploadPreview" class="mt-2 max-h-48 rounded-xl border border-slate-200 object-contain" alt="Upload preview" />
                        </div>
                    </div>
                </div>
            </div>

            @if ($product->images->count() > 1)
                <div class="flex gap-3 overflow-x-auto pb-2">
                    @foreach ($product->images as $image)
                        <button type="button" class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-slate-200 ring-slate-900/10 transition hover:-translate-y-0.5 hover:ring-2"
                            onclick="document.getElementById('hero-preview').src='{{ $product->imageUrl($image->path) }}'">
                            <img src="{{ $product->imageUrl($image->path) }}" alt="{{ $image->alt }}" class="h-full w-full object-cover" loading="lazy" />
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="space-y-8">
            <div class="space-y-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $product->category?->name }}</p>
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <h1 class="font-[family-name:var(--font-serif)] text-4xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">{{ $product->name }}</h1>
                        @if($product->stock <= 0)
                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold uppercase tracking-widest text-rose-600">Out of Stock</span>
                        @endif
                    </div>
                    @auth
                        <form action="{{ route('wishlist.toggle', $product) }}" method="post">
                            @csrf
                            <button type="submit" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:border-slate-300">
                                ♥ Wishlist
                            </button>
                        </form>
                    @endauth
                </div>
                <p class="text-base leading-relaxed text-slate-600">{{ $product->description }}</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg ring-1 ring-slate-900/5">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Instant quote</p>
                        <p class="mt-2 flex items-baseline gap-2">
                            <span class="text-4xl font-semibold text-slate-900">₹<span x-text="quote.unit_price"></span></span>
                            <span class="text-sm text-slate-500">per unit · GST calculated at checkout</span>
                        </p>
                    </div>
                    <div class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow-md" x-show="loading" x-transition>Updating…</div>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    @foreach (['material' => 'Material', 'size' => 'Size', 'color' => 'Color', 'font' => 'Typography', 'gift_wrap' => 'Packaging', 'engraving' => 'Engraving depth'] as $group => $label)
                        @if ($optionsByGroup->has($group))
                            <label class="space-y-2 text-sm font-medium text-slate-700">
                                <span>{{ $label }}</span>
                                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                                    x-model="{{ $group }}" @change="refreshQuote()">
                                    @foreach ($optionsByGroup[$group] as $option)
                                        <option value="{{ $option->value_key }}">{{ $option->label }} @if ($option->price_adjustment > 0) (+₹{{ number_format($option->price_adjustment, 0) }}) @endif</option>
                                    @endforeach
                                </select>
                            </label>
                        @endif
                    @endforeach
                </div>

                @if ($optionsByGroup->has('addon'))
                    <div class="mt-6 space-y-3">
                        <p class="text-sm font-semibold text-slate-800">Add-ons</p>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach ($optionsByGroup['addon'] as $option)
                                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm shadow-sm transition hover:border-slate-300">
                                    <input type="checkbox" value="{{ $option->value_key }}" class="mt-1 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                                        :checked="addons.includes('{{ $option->value_key }}')" @change="toggleAddon('{{ $option->value_key }}')">
                                    <span>
                                        <span class="font-semibold text-slate-900">{{ $option->label }}</span>
                                        <span class="mt-1 block text-xs text-slate-500">+₹{{ number_format($option->price_adjustment, 0) }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($optionsByGroup->has('image_upload'))
                    @php $photoFee = optional($optionsByGroup['image_upload']->firstWhere('value_key', 'yes')); @endphp
                    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-inner">
                        <label class="flex items-start gap-3 text-sm font-medium text-slate-800">
                            <input type="checkbox" class="mt-1 rounded border-slate-300 text-slate-900 focus:ring-slate-900" x-model="has_image_upload" @change="refreshQuote()" />
                            <span>
                                Include archival photo upload
                                @if ($photoFee)
                                    <span class="block text-xs font-normal text-slate-500">Adds ₹{{ number_format($photoFee->price_adjustment, 0) }} for conservation-grade printing.</span>
                                @endif
                            </span>
                        </label>
                    </div>
                @endif

                <div class="mt-6 space-y-2">
                    <label class="text-sm font-semibold text-slate-800" for="custom_text">Personal message</label>
                    <textarea id="custom_text" rows="4" maxlength="{{ config('customgift.custom_text_max') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm shadow-inner focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                        placeholder="Short vow, coordinates, or insider joke…"
                        x-model="custom_text"
                        @input.debounce.400ms="refreshQuote()"></textarea>
                    <p class="text-xs text-slate-500">Server validates length · XSS escaped by Blade whenever printed.</p>
                </div>

                <div class="mt-8 rounded-2xl bg-slate-900 p-5 text-white shadow-xl">
                    <p class="text-xs uppercase tracking-wide text-white/70">Breakdown</p>
                    <ul class="mt-3 space-y-2 text-sm">
                        <template x-for="line in quote.breakdown" :key="line.label">
                            <li class="flex justify-between gap-4 border-b border-white/10 pb-2 last:border-0 last:pb-0">
                                <span x-text="line.label"></span>
                                <span>₹<span x-text="line.amount"></span></span>
                            </li>
                        </template>
                    </ul>
                </div>

                <form action="{{ route('cart.store', $product) }}" method="post" enctype="multipart/form-data" class="mt-8 space-y-4">
                    @csrf
                    <input type="hidden" name="quantity" value="1" />
                    <template x-for="addon in addons" :key="addon">
                        <input type="hidden" name="addons[]" :value="addon" />
                    </template>

                    {{-- Mirror Alpine state into classical form fields --}}
                    @if ($optionsByGroup->has('material'))
                        <input type="hidden" name="material" :value="material" />
                    @endif
                    @if ($optionsByGroup->has('size'))
                        <input type="hidden" name="size" :value="size" />
                    @endif
                    @if ($optionsByGroup->has('color'))
                        <input type="hidden" name="color" :value="color" />
                    @endif
                    @if ($optionsByGroup->has('font'))
                        <input type="hidden" name="font" :value="font" />
                    @endif
                    @if ($optionsByGroup->has('gift_wrap'))
                        <input type="hidden" name="gift_wrap" :value="gift_wrap" />
                    @endif
                    @if ($optionsByGroup->has('engraving'))
                        <input type="hidden" name="engraving" :value="engraving" />
                    @endif
                    <input type="hidden" name="custom_text" :value="custom_text" />
                    <input type="hidden" name="has_image_upload" :value="has_image_upload ? 1 : 0" />

                    <div class="space-y-2 rounded-2xl border border-white/10 bg-white/5 p-4">
                        <label class="text-sm font-semibold text-white" for="upload">Upload bespoke artwork</label>
                        <input id="upload" name="upload" type="file" accept="image/*" class="block w-full text-sm text-white file:mr-4 file:rounded-xl file:border-0 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-900"
                            @change="handleUploadPreview($event)" />
                    </div>

                    <button type="submit" @if($product->stock <= 0) disabled @endif class="w-full rounded-2xl {{ $product->stock <= 0 ? 'bg-slate-200 text-slate-500 cursor-not-allowed shadow-none' : 'bg-white text-slate-900 shadow-lg shadow-black/10 hover:-translate-y-0.5' }} px-6 py-3 text-sm font-semibold ring-1 ring-white/40 transition">
                        @if($product->stock <= 0)
                            Out of Stock
                        @else
                            Checkout · ₹<span x-text="quote.unit_price"></span>
                        @endif
                    </button>
                    @guest
                        <p class="text-center text-xs text-white/70">Create an account at checkout to unlock wishlists & tracking.</p>
                    @endguest
                </form>
            </div>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-[family-name:var(--font-serif)] text-2xl font-semibold text-slate-900" style="--font-serif:'Fraunces',ui-serif,Georgia,serif;">Reviews</h2>
                    @auth
                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Share feedback</span>
                    @endauth
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($reviews as $review)
                        <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-slate-900">{{ $review->user?->name }}</p>
                                <p class="text-sm text-amber-600">{{ str_repeat('★', $review->rating) }}</p>
                            </div>
                            @if ($review->title)
                                <p class="mt-2 font-medium text-slate-800">{{ $review->title }}</p>
                            @endif
                            <p class="mt-2 text-sm text-slate-600">{{ $review->body }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-slate-600">No public reviews yet — be the artisan’s first advocate.</p>
                    @endforelse
                </div>

                @auth
                    <form action="{{ route('products.reviews.store', $product) }}" method="post" class="mt-6 space-y-4 rounded-2xl border border-dashed border-slate-200 p-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="text-sm font-semibold text-slate-800">Rating
                                <select name="rating" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} ★</option>
                                    @endfor
                                </select>
                            </label>
                            <label class="text-sm font-semibold text-slate-800">Title
                                <input name="title" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="Headline" />
                            </label>
                        </div>
                        <label class="text-sm font-semibold text-slate-800">Notes
                            <textarea name="body" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="How did customization feel?"></textarea>
                        </label>
                        <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm" type="submit">Submit for moderation</button>
                    </form>
                @else
                    <p class="mt-6 text-sm text-slate-600"><a href="{{ route('login') }}" class="font-semibold text-slate-900 underline">Login</a> to leave a review.</p>
                @endauth
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function customGiftConfigurator(defaults, initialQuote, groupedMeta, quoteUrl) {
            return {
                ...defaults,
                quote: {
                    unit_price: initialQuote.unit_price,
                    breakdown: initialQuote.breakdown,
                },
                loading: false,
                uploadPreview: null,
                optionMeta: groupedMeta,
                get selectedColorHex() {
                    const group = this.optionMeta.color ?? [];
                    const found = group.find((opt) => opt.value_key === this.color);
                    return found?.meta?.hex ?? null;
                },
                get materialFilterStyle() {
                    let filter = '';
                    
                    // Leather tinting
                    if (this.color === 'black') {
                        filter += 'grayscale(1) brightness(0.8) ';
                    } else if (this.color === 'brown') {
                        filter += 'sepia(0.2) saturate(1.2) ';
                    }
                    
                    // Wood tinting
                    if (this.material === 'walnut') {
                        filter += 'sepia(0.3) contrast(1.1) brightness(0.9) ';
                    } else if (this.material === 'oak') {
                        filter += 'sepia(0.1) saturate(0.9) ';
                    }

                    return filter ? `filter: ${filter.trim()}; transition: filter 0.3s ease;` : 'filter: none; transition: filter 0.3s ease;';
                },
                get textOverlayStyle() {
                    let style = '';
                    
                    if (this.font === 'script_velvet') {
                        style += "font-family: 'Dancing Script', cursive; font-size: 3.5rem; line-height: 1.2; ";
                    } else {
                        style += "font-family: 'Inter', sans-serif; font-size: 1.5rem; letter-spacing: 0.15em; font-weight: 500; text-transform: uppercase; ";
                    }

                    const hex = this.selectedColorHex || '#1e293b';
                    style += `color: ${hex}; `;
                    
                    if (hex.toLowerCase() === '#c49a6c' || hex.toLowerCase() === '#d4af37') {
                        style += `text-shadow: 1px 1px 2px rgba(0,0,0,0.4), -1px -1px 1px rgba(255,255,255,0.3); opacity: 0.95; `;
                    } else if (this.engraving === 'deep') {
                        style += `mix-blend-mode: multiply; opacity: 0.85; text-shadow: inset 1px 1px 2px rgba(0,0,0,0.5); `;
                    } else {
                        style += `mix-blend-mode: multiply; opacity: 0.75; text-shadow: 0px 1px 1px rgba(255,255,255,0.3); `;
                    }

                    return style;
                },
                toggleAddon(key) {
                    if (this.addons.includes(key)) {
                        this.addons = this.addons.filter((k) => k !== key);
                    } else {
                        this.addons = [...this.addons, key];
                    }
                    this.refreshQuote();
                },
                handleUploadPreview(event) {
                    const file = event.target.files?.[0];
                    if (!file) {
                        this.uploadPreview = null;
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.uploadPreview = e.target?.result ?? null;
                    };
                    reader.readAsDataURL(file);
                },
                async refreshQuote() {
                    this.loading = true;
                    try {
                        const { data } = await axios.post(quoteUrl, {
                            material: this.material,
                            size: this.size,
                            color: this.color,
                            font: this.font,
                            gift_wrap: this.gift_wrap,
                            engraving: this.engraving,
                            addons: this.addons,
                            has_image_upload: this.has_image_upload,
                            custom_text: this.custom_text,
                        });
                        this.quote = {
                            unit_price: data.unit_price,
                            breakdown: data.breakdown,
                        };
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
@endpush
