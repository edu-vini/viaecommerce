@props(['product'])

@php
    $discountPercent = 0;
    if ($product['original_price'] > $product['price']) {
        $discountPercent = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
    }
    $pixPrice = $product['price'] * (1 - ($product['pix_discount_percent'] / 100));
@endphp

<div class="group relative bg-white rounded-xl border border-stone-200/70 shadow-xs hover:shadow-xl hover:border-stone-300 transition-all duration-300 flex flex-col justify-between overflow-hidden">

    {{-- Lookbook Image with Fashion 3:4 Aspect Ratio --}}
    <div class="relative aspect-[3/4] overflow-hidden bg-[#FAF8F5]">
        <a href="{{ route('product.show', ['slug' => $product['slug']]) }}" class="block w-full h-full">
            <img
                src="{{ $product['images'][0] }}"
                alt="{{ $product['title'] }}"
                loading="lazy"
                class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700 ease-out"
            />
        </a>

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1 z-10">
            @if (!empty($product['badge']))
                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[9px] font-medium uppercase tracking-widest bg-[#1C1917]/90 text-white backdrop-blur-xs">
                    {{ $product['badge'] }}
                </span>
            @endif
            @if ($discountPercent > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[9px] font-semibold bg-[#9F4958] text-white">
                    -{{ $discountPercent }}%
                </span>
            @endif
        </div>

        {{-- Wishlist Heart Button --}}
        <button type="button" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/80 backdrop-blur-xs flex items-center justify-center text-stone-400 hover:text-[#9F4958] hover:scale-110 transition shadow-xs z-10" title="Salvar na Lista de Desejos">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </button>

        {{-- Fast Size Overlay on Hover --}}
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 flex items-center justify-center gap-1.5 z-10">
            <span class="text-[10px] text-white/90 mr-1 uppercase font-medium">Tamanhos:</span>
            @foreach ($product['variations']['options'] as $opt)
                <span class="px-1.5 py-0.5 text-[9px] font-bold bg-white/90 text-stone-900 rounded-xs">
                    {{ explode(' ', $opt['label'])[0] }}
                </span>
            @endforeach
        </div>
    </div>

    {{-- Card Content --}}
    <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
            {{-- Category & Colors Swatches --}}
            <div class="flex items-center justify-between text-[10px] uppercase tracking-wider text-stone-400 mb-1.5">
                <span>{{ $product['category_name'] }}</span>
                @if (!empty($product['colors']))
                    <div class="flex items-center gap-1">
                        @foreach ($product['colors'] as $col)
                            <span class="w-2.5 h-2.5 rounded-full border border-stone-300" style="background-color: {{ $col['hex'] }}" title="{{ $col['name'] }}"></span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Title --}}
            <h3 class="font-serif text-sm font-medium text-stone-900 group-hover:text-brand transition line-clamp-2 leading-snug">
                <a href="{{ route('product.show', ['slug' => $product['slug']]) }}">
                    {{ $product['title'] }}
                </a>
            </h3>

            {{-- Ratings --}}
            <div class="flex items-center gap-1.5 mt-1.5">
                <div class="flex text-amber-500 text-xs">
                    ★★★★★
                </div>
                <span class="text-[10px] text-stone-400">({{ $product['reviews_count'] }})</span>
            </div>
        </div>

        {{-- Pricing & CTA --}}
        <div class="mt-4 pt-3 border-t border-stone-100">
            @if ($product['original_price'] > $product['price'])
                <span class="text-[11px] line-through text-stone-400 block font-light">
                    R$ {{ number_format($product['original_price'], 2, ',', '.') }}
                </span>
            @endif

            <div class="flex items-baseline gap-2">
                <span class="text-base font-bold text-stone-900">
                    R$ {{ number_format($product['price'], 2, ',', '.') }}
                </span>
            </div>

            {{-- Installments & Pix --}}
            <div class="text-[11px] text-stone-500 space-y-0.5 mt-0.5">
                <p>
                    até <strong class="font-semibold text-stone-800">{{ $product['installments'] }}x</strong> de <strong class="font-semibold text-stone-800">R$ {{ number_format($product['installment_value'], 2, ',', '.') }}</strong> s/ juros
                </p>
                <p class="text-[#8B5E3C] font-semibold text-[10px]">
                    R$ {{ number_format($pixPrice, 2, ',', '.') }} no Pix à vista (5% OFF)
                </p>
            </div>

            {{-- Quick Add To Bag --}}
            <button
                type="button"
                @click.prevent="$store.cart.addItem({
                    id: {{ $product['id'] }},
                    title: '{{ addslashes($product['title']) }}',
                    price: {{ $product['price'] }},
                    originalPrice: {{ $product['original_price'] }},
                    image: '{{ $product['images'][0] }}',
                    category: '{{ $product['category_name'] }}',
                    defaultVariation: '{{ $product['variations']['options'][0]['label'] ?? '' }}'
                })"
                class="mt-3 w-full py-2 px-3 rounded-full bg-[#FAF8F5] hover:bg-[#1C1917] text-stone-800 hover:text-white border border-stone-300/80 hover:border-transparent font-medium text-[11px] uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Adicionar à Sacola</span>
            </button>
        </div>
    </div>
</div>
