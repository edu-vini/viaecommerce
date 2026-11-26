@props(['product'])

@php
    $discountPercent = 0;
    if ($product['original_price'] > $product['price']) {
        $discountPercent = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
    }
    $pixPrice = $product['price'] * (1 - ($product['pix_discount_percent'] / 100));
@endphp

<div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:border-slate-300 transition-all duration-300 flex flex-col justify-between overflow-hidden">

    {{-- Product Image & Badges --}}
    <div class="relative aspect-square overflow-hidden bg-slate-100">
        <a href="{{ route('product.show', ['slug' => $product['slug']]) }}" class="block w-full h-full">
            <img
                src="{{ $product['images'][0] }}"
                alt="{{ $product['title'] }}"
                loading="lazy"
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
            />
        </a>

        {{-- Badges --}}
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 z-10">
            @if (!empty($product['badge']))
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-900 text-white shadow-xs">
                    {{ $product['badge'] }}
                </span>
            @endif
            @if ($discountPercent > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-rose-600 text-white shadow-xs">
                    -{{ $discountPercent }}% OFF
                </span>
            @endif
        </div>

        {{-- Quick Wishlist Heart --}}
        <button type="button" class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-slate-400 hover:text-rose-500 hover:scale-110 transition shadow-xs z-10" title="Salvar nos Favoritos">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </button>
    </div>

    {{-- Card Content --}}
    <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
            {{-- Category & Brand --}}
            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1">
                <span>{{ $product['category_name'] }}</span>
                <span class="font-medium text-slate-500">{{ $product['brand'] }}</span>
            </div>

            {{-- Title --}}
            <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand transition line-clamp-2 leading-snug">
                <a href="{{ route('product.show', ['slug' => $product['slug']]) }}">
                    {{ $product['title'] }}
                </a>
            </h3>

            {{-- Ratings --}}
            <div class="flex items-center gap-1.5 mt-2">
                <div class="flex text-amber-400">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-3.5 h-3.5 {{ $i <= floor($product['rating']) ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-[11px] font-semibold text-slate-600">{{ number_format($product['rating'], 1) }}</span>
                <span class="text-[10px] text-slate-400">({{ $product['reviews_count'] }})</span>
            </div>
        </div>

        {{-- Pricing Block --}}
        <div class="mt-4 pt-3 border-t border-slate-100">
            @if ($product['original_price'] > $product['price'])
                <span class="text-[11px] line-through text-slate-400 block">
                    De: R$ {{ number_format($product['original_price'], 2, ',', '.') }}
                </span>
            @endif

            <div class="flex items-baseline gap-2">
                <span class="text-lg font-black text-slate-900">
                    R$ {{ number_format($product['price'], 2, ',', '.') }}
                </span>
            </div>

            {{-- Installments & Pix --}}
            <div class="text-[11px] text-slate-600 space-y-0.5 mt-0.5">
                <p>
                    em até <strong class="font-bold text-slate-800">{{ $product['installments'] }}x</strong> de <strong class="font-bold text-slate-800">R$ {{ number_format($product['installment_value'], 2, ',', '.') }}</strong> sem juros
                </p>
                <p class="text-emerald-600 font-semibold flex items-center gap-1">
                    <span>R$ {{ number_format($pixPrice, 2, ',', '.') }} no Pix</span>
                    <span class="text-[9px] bg-emerald-100 text-emerald-800 px-1 rounded font-bold">5% OFF</span>
                </p>
            </div>

            {{-- Quick Add To Cart Button --}}
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
                class="mt-3 w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-slate-900 hover:bg-brand text-white font-semibold text-xs transition duration-200 cursor-pointer shadow-xs group-hover:bg-brand">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Adicionar ao Carrinho</span>
            </button>
        </div>
    </div>
</div>
