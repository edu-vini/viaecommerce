@extends('layouts.app')

@section('content')
@php
    $discountPercent = 0;
    if ($product['original_price'] > $product['price']) {
        $discountPercent = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
    }
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{
        activeImage: '{{ $product['images'][0] }}',
        activeVariation: '{{ $product['variations']['options'][0]['label'] }}',
        selectedSku: '{{ $product['variations']['options'][0]['sku'] }}',
        priceDelta: {{ $product['variations']['options'][0]['price_delta'] }},
        currentStock: {{ $product['variations']['options'][0]['stock'] }},
        basePrice: {{ $product['price'] }},
        quantity: 1,
        activeTab: 'descricao',
        cep: '',
        shippingLoading: false,
        shippingError: '',
        shippingOptions: null,
        zoomActive: false,
        zoomX: 0,
        zoomY: 0,

        get currentPrice() {
            return this.basePrice + this.priceDelta;
        },

        get pixPrice() {
            return this.currentPrice * 0.95;
        },

        selectVariation(option) {
            this.activeVariation = option.label;
            this.selectedSku = option.sku;
            this.priceDelta = option.price_delta;
            this.currentStock = option.stock;
            if (this.quantity > this.currentStock) {
                this.quantity = this.currentStock;
            }
        },

        async calculateShipping() {
            if (!this.cep || this.cep.replace(/\D/g, '').length !== 8) {
                this.shippingError = 'Digite um CEP válido com 8 números.';
                this.shippingOptions = null;
                return;
            }
            this.shippingLoading = true;
            this.shippingError = '';
            try {
                const response = await fetch('/api/calcular-frete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ cep: this.cep })
                });
                const data = await response.json();
                if (data.error) {
                    this.shippingError = data.error;
                    this.shippingOptions = null;
                } else {
                    this.shippingOptions = data.options;
                }
            } catch (e) {
                this.shippingError = 'Erro ao consultar frete. Tente novamente.';
            } finally {
                this.shippingLoading = false;
            }
        },

        handleMouseMove(e) {
            const rect = e.currentTarget.getBoundingClientRect();
            this.zoomX = ((e.clientX - rect.left) / rect.width) * 100;
            this.zoomY = ((e.clientY - rect.top) / rect.height) * 100;
        }
     }">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand transition">Início</a>
        <span>/</span>
        <a href="{{ route('catalog.category', ['categorySlug' => $product['category_slug']]) }}" class="hover:text-brand transition">{{ $product['category_name'] }}</a>
        <span>/</span>
        <span class="font-bold text-slate-800 truncate max-w-sm">{{ $product['title'] }}</span>
    </nav>

    {{-- Product Main Presentation Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">

        {{-- Photos Gallery Column (5 cols) --}}
        <div class="lg:col-span-6 flex flex-col-reverse sm:flex-row gap-4">
            {{-- Thumbnails --}}
            <div class="flex sm:flex-col gap-3 overflow-x-auto sm:overflow-y-auto max-h-[500px] shrink-0 no-scrollbar">
                @foreach ($product['images'] as $img)
                    <button
                        type="button"
                        @click="activeImage = '{{ $img }}'"
                        :class="activeImage === '{{ $img }}' ? 'border-brand ring-2 ring-blue-100' : 'border-slate-200 hover:border-slate-300'"
                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden border bg-slate-50 transition p-1 shrink-0">
                        <img src="{{ $img }}" alt="Thumbnail" class="w-full h-full object-cover rounded-lg">
                    </button>
                @endforeach
            </div>

            {{-- Main Picture with Zoom Effect --}}
            <div class="flex-1 relative aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center cursor-crosshair group"
                 @mouseenter="zoomActive = true"
                 @mouseleave="zoomActive = false"
                 @mousemove="handleMouseMove($event)">

                <img :src="activeImage"
                     alt="{{ $product['title'] }}"
                     class="w-full h-full object-cover transition-transform duration-200"
                     :style="zoomActive ? `transform-origin: ${zoomX}% ${zoomY}%; transform: scale(1.75);` : 'transform: scale(1);'">

                <div class="absolute top-3 left-3 flex flex-col gap-1.5 pointer-events-none">
                    @if (!empty($product['badge']))
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-900 text-white shadow-xs">
                            {{ $product['badge'] }}
                        </span>
                    @endif
                    @if ($discountPercent > 0)
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black bg-rose-600 text-white shadow-xs">
                            -{{ $discountPercent }}% OFF
                        </span>
                    @endif
                </div>

                {{-- Zoom hint badge --}}
                <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-xs text-white text-[10px] px-2.5 py-1 rounded-full flex items-center gap-1.5 opacity-80 group-hover:opacity-100 transition pointer-events-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    <span>Passe o mouse para zoom</span>
                </div>
            </div>
        </div>

        {{-- Product Details and Buy Box Column (7 cols) --}}
        <div class="lg:col-span-6 flex flex-col justify-between space-y-6">

            <div>
                {{-- Category and Brand --}}
                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span class="font-semibold text-brand">{{ $product['category_name'] }}</span>
                    <span>Marca: <strong class="text-slate-800">{{ $product['brand'] }}</strong> | SKU: <strong class="text-slate-800" x-text="selectedSku"></strong></span>
                </div>

                {{-- Title --}}
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1 leading-snug">
                    {{ $product['title'] }}
                </h1>

                {{-- Rating and Review Count --}}
                <div class="flex items-center gap-2 mt-2.5">
                    <div class="flex text-amber-400">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= floor($product['rating']) ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="text-xs font-bold text-slate-700">{{ number_format($product['rating'], 2) }}</span>
                    <span class="text-slate-300">|</span>
                    <a href="#avaliacoes" @click="activeTab = 'avaliacoes'" class="text-xs text-brand hover:underline">
                        {{ $product['reviews_count'] }} avaliações de compradores verificados
                    </a>
                </div>

                {{-- Price Box --}}
                <div class="mt-5 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                    @if ($product['original_price'] > $product['price'])
                        <span class="text-xs line-through text-slate-400 block">
                            De R$ {{ number_format($product['original_price'], 2, ',', '.') }}
                        </span>
                    @endif

                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl font-black text-slate-900" x-text="'R$ ' + currentPrice.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">
                            Em estoque (<span x-text="currentStock"></span> un.)
                        </span>
                    </div>

                    {{-- Pix Price --}}
                    <div class="mt-2 text-emerald-600 font-bold text-sm flex items-center gap-2">
                        <span class="text-lg" x-text="'R$ ' + pixPrice.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + ' no Pix'"></span>
                        <span class="text-xs bg-emerald-600 text-white px-1.5 py-0.5 rounded font-black">5% de Desconto</span>
                    </div>

                    {{-- Installments text --}}
                    <div class="mt-1 text-xs text-slate-600">
                        <span>ou em até <strong class="text-slate-900">{{ $product['installments'] }}x</strong> de <strong class="text-slate-900" x-text="'R$ ' + (currentPrice / {{ $product['installments'] }}).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></strong> sem juros no cartão</span>
                    </div>
                </div>

                {{-- Variations Picker --}}
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Escolha a variação: <strong class="text-brand font-black" x-text="activeVariation"></strong>
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach ($product['variations']['options'] as $opt)
                            <button
                                type="button"
                                @click="selectVariation({{ json_encode($opt) }})"
                                :class="activeVariation === '{{ $opt['label'] }}' ? 'border-brand bg-brand-light text-brand ring-2 ring-blue-100 font-bold' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 font-medium'"
                                class="px-4 py-2 rounded-xl border text-xs transition flex items-center gap-2">
                                <span>{{ $opt['label'] }}</span>
                                @if ($opt['price_delta'] > 0)
                                    <span class="text-[10px] text-slate-400 font-normal">(+ R$ {{ number_format($opt['price_delta'], 2, ',', '.') }})</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Quantity Selector & Buy Buttons --}}
                <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    {{-- Quantity Selector --}}
                    <div class="flex items-center justify-between border border-slate-300 rounded-xl bg-white px-3 py-2 text-sm">
                        <span class="text-xs text-slate-500 mr-3">Qtd:</span>
                        <div class="flex items-center gap-3 font-bold">
                            <button type="button" @click="if (quantity > 1) quantity--" class="text-slate-500 hover:text-slate-900 transition text-base">-</button>
                            <span class="w-6 text-center text-slate-900" x-text="quantity"></span>
                            <button type="button" @click="if (quantity < currentStock) quantity++" class="text-slate-500 hover:text-slate-900 transition text-base">+</button>
                        </div>
                    </div>

                    {{-- Add to Cart Button --}}
                    <button
                        type="button"
                        @click="$store.cart.addItem({
                            id: {{ $product['id'] }},
                            title: '{{ addslashes($product['title']) }}',
                            price: currentPrice,
                            originalPrice: {{ $product['original_price'] }},
                            image: activeImage,
                            category: '{{ $product['category_name'] }}',
                            sku: selectedSku
                        }, activeVariation, quantity)"
                        class="flex-1 flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm transition shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Adicionar ao Carrinho</span>
                    </button>

                    {{-- Buy Now Button --}}
                    <button
                        type="button"
                        @click="$store.cart.addItem({
                            id: {{ $product['id'] }},
                            title: '{{ addslashes($product['title']) }}',
                            price: currentPrice,
                            originalPrice: {{ $product['original_price'] }},
                            image: activeImage,
                            category: '{{ $product['category_name'] }}',
                            sku: selectedSku
                        }, activeVariation, quantity); window.location.href = '{{ route('checkout.index') }}';"
                        class="flex-1 flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-brand hover:bg-brand-hover text-white font-bold text-sm transition shadow-lg shadow-blue-500/25">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Comprar Agora</span>
                    </button>
                </div>

                {{-- Shipping Simulator (Cálculo de Frete) --}}
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider block mb-2">Simular Frete e Prazo de Entrega:</span>
                    <form @submit.prevent="calculateShipping()" class="flex gap-2 max-w-sm">
                        <input
                            type="text"
                            x-model="cep"
                            placeholder="00000-000"
                            maxlength="9"
                            class="flex-1 px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:border-brand focus:ring-2 focus:ring-blue-100">
                        <button
                            type="submit"
                            :disabled="shippingLoading"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition">
                            <span x-show="!shippingLoading">Calcular</span>
                            <span x-show="shippingLoading">...</span>
                        </button>
                    </form>

                    {{-- Shipping Error --}}
                    <p x-show="shippingError" x-text="shippingError" class="text-xs text-rose-600 mt-2 font-medium"></p>

                    {{-- Shipping Options Result --}}
                    <div x-show="shippingOptions" class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-200 divide-y divide-slate-200/60 text-xs">
                        <template x-for="opt in shippingOptions" :key="opt.name">
                            <div class="py-2 first:pt-0 last:pb-0 flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-slate-800" x-text="opt.name"></span>
                                    <span class="text-[11px] text-slate-500 block" x-text="opt.deadline"></span>
                                </div>
                                <div class="text-right">
                                    <span class="font-black text-slate-900" x-text="opt.price_formatted"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Tabs Section: Detailed Description, Specs, Reviews, Installments --}}
    <div class="mt-12 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs" id="avaliacoes">
        {{-- Tab Headers --}}
        <div class="flex items-center gap-4 sm:gap-8 border-b border-slate-200 pb-4 overflow-x-auto no-scrollbar text-sm font-bold">
            <button
                @click="activeTab = 'descricao'"
                :class="activeTab === 'descricao' ? 'text-brand border-b-2 border-brand pb-4 -mb-[18px]' : 'text-slate-500 hover:text-slate-800'">
                Descrição Completa
            </button>
            <button
                @click="activeTab = 'especificacoes'"
                :class="activeTab === 'especificacoes' ? 'text-brand border-b-2 border-brand pb-4 -mb-[18px]' : 'text-slate-500 hover:text-slate-800'">
                Ficha Técnica & Especificações
            </button>
            <button
                @click="activeTab = 'parcelas'"
                :class="activeTab === 'parcelas' ? 'text-brand border-b-2 border-brand pb-4 -mb-[18px]' : 'text-slate-500 hover:text-slate-800'">
                Tabela de Parcelamento
            </button>
            <button
                @click="activeTab = 'avaliacoes'"
                :class="activeTab === 'avaliacoes' ? 'text-brand border-b-2 border-brand pb-4 -mb-[18px]' : 'text-slate-500 hover:text-slate-800'">
                Avaliações dos Clientes ({{ $product['reviews_count'] }})
            </button>
        </div>

        {{-- Tab Contents --}}
        <div class="pt-6">

            {{-- Tab 1: Descrição --}}
            <div x-show="activeTab === 'descricao'" class="prose max-w-none text-slate-600 text-sm leading-relaxed space-y-4">
                <p class="text-base text-slate-800 font-medium">
                    {{ $product['summary'] }}
                </p>
                <p>
                    {{ $product['description'] }}
                </p>
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 flex items-center gap-3 text-xs text-blue-900 mt-4">
                    <svg class="w-5 h-5 text-brand shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Produto original lacrado com Nota Fiscal Eletrônica e garantia oficial de fábrica de 12 meses.</span>
                </div>
            </div>

            {{-- Tab 2: Ficha Técnica --}}
            <div x-show="activeTab === 'especificacoes'" class="space-y-4">
                <div class="border border-slate-200 rounded-2xl overflow-hidden divide-y divide-slate-200 text-xs">
                    @foreach ($product['specifications'] as $key => $val)
                        <div class="grid grid-cols-1 sm:grid-cols-3 p-3.5 hover:bg-slate-50 transition">
                            <span class="font-bold text-slate-700 sm:col-span-1">{{ $key }}</span>
                            <span class="text-slate-600 sm:col-span-2">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab 3: Tabela de Parcelamento --}}
            <div x-show="activeTab === 'parcelas'" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                    @foreach ($installmentsTable as $row)
                        <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-900">{{ $row['times'] }}x de R$ {{ $row['value'] }}</span>
                                <span class="block text-[10px] text-emerald-600 font-semibold">{{ $row['interest'] }}</span>
                            </div>
                            <span class="text-[11px] text-slate-500">Total: R$ {{ $row['total'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab 4: Avaliações --}}
            <div x-show="activeTab === 'avaliacoes'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center flex flex-col items-center justify-center">
                        <span class="text-4xl font-black text-slate-900">{{ number_format($product['rating'], 1) }}</span>
                        <div class="flex text-amber-400 my-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-xs text-slate-500">Média baseada em {{ $product['reviews_count'] }} compras</span>
                    </div>

                    <div class="md:col-span-2 space-y-3">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-slate-900">Carlos Eduardo M.</span>
                                <span class="text-[10px] text-slate-400">Há 3 dias • Compra verificada</span>
                            </div>
                            <div class="flex text-amber-400 mb-2">★★★★★</div>
                            <p class="text-xs text-slate-600">
                                Entrega surpreendentemente rápida! Produto chegou muito bem embalado e superou minhas expectativas. A qualidade de construção é impecável.
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-slate-900">Mariana Silveira</span>
                                <span class="text-[10px] text-slate-400">Há 1 semana • Compra verificada</span>
                            </div>
                            <div class="flex text-amber-400 mb-2">★★★★★</div>
                            <p class="text-xs text-slate-600">
                                Amei o desconto no Pix e o atendimento da loja. Recomendo de olhos fechados!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Related Products Section --}}
    @if (!empty($relatedProducts))
        <div class="mt-16">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mb-6">
                Quem comprou este produto também se interessou por:
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($relatedProducts as $rel)
                    <x-product-card :product="$rel" />
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
