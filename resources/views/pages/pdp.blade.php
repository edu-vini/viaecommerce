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
        activeColor: '{{ $product['colors'][0]['name'] ?? 'Padrão' }}',
        selectedSku: '{{ $product['variations']['options'][0]['sku'] }}',
        priceDelta: {{ $product['variations']['options'][0]['price_delta'] }},
        currentStock: {{ $product['variations']['options'][0]['stock'] }},
        basePrice: {{ $product['price'] }},
        quantity: 1,
        activeTab: 'descricao',
        measureModalOpen: false,
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
    <nav class="flex items-center gap-2 text-[11px] text-stone-400 mb-6 uppercase tracking-wider">
        <a href="{{ route('home') }}" class="hover:text-brand transition">Início</a>
        <span>/</span>
        <a href="{{ route('catalog.category', ['categorySlug' => $product['category_slug']]) }}" class="hover:text-brand transition">{{ $product['category_name'] }}</a>
        <span>/</span>
        <span class="font-medium text-stone-700 truncate max-w-sm">{{ $product['title'] }}</span>
    </nav>

    {{-- Product Main Presentation Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 bg-white rounded-3xl p-6 sm:p-10 border border-stone-200/80 shadow-xs">

        {{-- Photos Gallery Column (Fashion 3:4 aspect) (6 cols) --}}
        <div class="lg:col-span-6 flex flex-col-reverse sm:flex-row gap-4">
            {{-- Thumbnails --}}
            <div class="flex sm:flex-col gap-3 overflow-x-auto sm:overflow-y-auto max-h-[580px] shrink-0 no-scrollbar">
                @foreach ($product['images'] as $img)
                    <button
                        type="button"
                        @click="activeImage = '{{ $img }}'"
                        :class="activeImage === '{{ $img }}' ? 'border-brand ring-2 ring-stone-200' : 'border-stone-200 hover:border-stone-400'"
                        class="w-16 h-20 sm:w-20 sm:h-28 rounded-xl overflow-hidden border bg-[#FAF8F5] transition p-0.5 shrink-0">
                        <img src="{{ $img }}" alt="Thumbnail" class="w-full h-full object-cover object-top rounded-lg">
                    </button>
                @endforeach
            </div>

            {{-- Main Picture with Zoom Effect --}}
            <div class="flex-1 relative aspect-[3/4] rounded-2xl overflow-hidden bg-[#FAF8F5] border border-stone-200 flex items-center justify-center cursor-crosshair group"
                 @mouseenter="zoomActive = true"
                 @mouseleave="zoomActive = false"
                 @mousemove="handleMouseMove($event)">

                <img :src="activeImage"
                     alt="{{ $product['title'] }}"
                     class="w-full h-full object-cover object-top transition-transform duration-200"
                     :style="zoomActive ? `transform-origin: ${zoomX}% ${zoomY}%; transform: scale(1.8);` : 'transform: scale(1);'">

                <div class="absolute top-3 left-3 flex flex-col gap-1.5 pointer-events-none">
                    @if (!empty($product['badge']))
                        <span class="px-2.5 py-1 rounded-sm text-[9px] font-semibold uppercase tracking-widest bg-[#1C1917]/90 text-white backdrop-blur-xs">
                            {{ $product['badge'] }}
                        </span>
                    @endif
                    @if ($discountPercent > 0)
                        <span class="px-2.5 py-1 rounded-sm text-[9px] font-semibold bg-[#9F4958] text-white">
                            -{{ $discountPercent }}% OFF
                        </span>
                    @endif
                </div>

                {{-- Zoom hint badge --}}
                <div class="absolute bottom-3 right-3 bg-white/80 backdrop-blur-xs text-stone-700 text-[10px] px-2.5 py-1 rounded-full flex items-center gap-1.5 opacity-80 group-hover:opacity-100 transition pointer-events-none">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Zoom na textura</span>
                </div>
            </div>
        </div>

        {{-- Product Details and Buy Box Column (6 cols) --}}
        <div class="lg:col-span-6 flex flex-col justify-between space-y-6">

            <div>
                {{-- Category and Reference --}}
                <div class="flex items-center justify-between text-xs text-stone-400 uppercase tracking-wider">
                    <span class="font-semibold text-brand">{{ $product['category_name'] }}</span>
                    <span>Ref: <strong class="text-stone-700 font-mono" x-text="selectedSku"></strong></span>
                </div>

                {{-- Title --}}
                <h1 class="font-serif text-2xl sm:text-3xl lg:text-4xl font-normal text-stone-900 tracking-tight mt-1.5 leading-snug">
                    {{ $product['title'] }}
                </h1>

                {{-- Rating and Review Count --}}
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex text-amber-500 text-xs">
                        ★★★★★
                    </div>
                    <span class="text-xs font-semibold text-stone-700">{{ number_format($product['rating'], 2) }}</span>
                    <span class="text-stone-300">|</span>
                    <a href="#avaliacoes" @click="activeTab = 'avaliacoes'" class="text-xs text-stone-500 hover:text-brand hover:underline">
                        {{ $product['reviews_count'] }} avaliações de clientes verificadas
                    </a>
                </div>

                {{-- Price Box --}}
                <div class="mt-5 p-5 rounded-2xl bg-[#FAF8F5] border border-stone-200/80">
                    @if ($product['original_price'] > $product['price'])
                        <span class="text-xs line-through text-stone-400 block font-light">
                            De R$ {{ number_format($product['original_price'], 2, ',', '.') }}
                        </span>
                    @endif

                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl font-bold text-stone-900" x-text="'R$ ' + currentPrice.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">
                            Disponível (<span x-text="currentStock"></span> em estoque)
                        </span>
                    </div>

                    {{-- Pix Price --}}
                    <div class="mt-2 text-[#8B5E3C] font-semibold text-sm flex items-center gap-2">
                        <span class="text-base" x-text="'R$ ' + pixPrice.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + ' no Pix'"></span>
                        <span class="text-[10px] bg-[#8B5E3C] text-white px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">5% de Desconto</span>
                    </div>

                    {{-- Installments text --}}
                    <div class="mt-1 text-xs text-stone-500">
                        <span>ou em até <strong class="text-stone-800">{{ $product['installments'] }}x</strong> de <strong class="text-stone-800" x-text="'R$ ' + (currentPrice / {{ $product['installments'] }}).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></strong> sem juros no cartão</span>
                    </div>
                </div>

                {{-- Color Selection Swatches --}}
                @if (!empty($product['colors']))
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-stone-700 uppercase tracking-wider">
                                Cor: <strong class="text-stone-900 font-bold" x-text="activeColor"></strong>
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            @foreach ($product['colors'] as $col)
                                <button
                                    type="button"
                                    @click="activeColor = '{{ $col['name'] }}'"
                                    :class="activeColor === '{{ $col['name'] }}' ? 'ring-2 ring-stone-900 ring-offset-2 scale-110' : 'hover:scale-105'"
                                    class="w-7 h-7 rounded-full border border-stone-300 transition-all shadow-xs"
                                    style="background-color: {{ $col['hex'] }}"
                                    title="{{ $col['name'] }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Size Variations Picker & Measurement Guide Link --}}
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Tamanho: <strong class="text-brand font-bold" x-text="activeVariation"></strong>
                        </span>
                        <button type="button" @click="measureModalOpen = true" class="text-xs font-semibold text-stone-600 hover:text-brand flex items-center gap-1 underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            <span>Tabela de Medidas</span>
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-2.5">
                        @foreach ($product['variations']['options'] as $opt)
                            <button
                                type="button"
                                @click="selectVariation({{ json_encode($opt) }})"
                                :class="activeVariation === '{{ $opt['label'] }}' ? 'border-[#1C1917] bg-[#1C1917] text-white font-bold' : 'border-stone-300 bg-white text-stone-700 hover:border-stone-500 font-medium'"
                                class="min-w-[56px] px-3.5 py-2 rounded-lg border text-xs transition uppercase tracking-wider text-center">
                                {{ $opt['label'] }}
                            </button>
                        @endforeach
                    </div>

                    @if (!empty($product['model_info']))
                        <p class="text-[11px] text-stone-500 italic mt-2.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $product['model_info'] }}</span>
                        </p>
                    @endif
                </div>

                {{-- Quantity & Action Buttons --}}
                <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    {{-- Quantity Selector --}}
                    <div class="flex items-center justify-between border border-stone-300 rounded-full bg-white px-3 py-2 text-xs">
                        <span class="text-stone-400 mr-2 uppercase text-[10px] font-semibold">Qtd:</span>
                        <div class="flex items-center gap-3 font-bold">
                            <button type="button" @click="if (quantity > 1) quantity--" class="text-stone-500 hover:text-stone-900 transition text-base">-</button>
                            <span class="w-5 text-center text-stone-900" x-text="quantity"></span>
                            <button type="button" @click="if (quantity < currentStock) quantity++" class="text-stone-500 hover:text-stone-900 transition text-base">+</button>
                        </div>
                    </div>

                    {{-- Add to Bag Button --}}
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
                        }, activeVariation + ' | ' + activeColor, quantity)"
                        class="flex-1 flex items-center justify-center gap-2 py-3.5 px-6 rounded-full bg-[#1C1917] hover:bg-brand text-white font-medium text-xs tracking-widest uppercase transition duration-300 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Adicionar à Sacola</span>
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
                        }, activeVariation + ' | ' + activeColor, quantity); window.location.href = '{{ route('checkout.index') }}';"
                        class="flex-1 flex items-center justify-center gap-2 py-3.5 px-6 rounded-full bg-brand hover:bg-brand-hover text-white font-medium text-xs tracking-widest uppercase transition duration-300 shadow-lg">
                        <span>Comprar Agora</span>
                    </button>
                </div>

                {{-- Shipping Simulator --}}
                <div class="mt-6 pt-6 border-t border-stone-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-stone-800 uppercase tracking-wider">Calcular Frete e Prazo:</span>
                        <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">Frete Grátis > R$ 399</span>
                    </div>
                    <form @submit.prevent="calculateShipping()" class="flex gap-2 max-w-sm">
                        <input
                            type="text"
                            x-model="cep"
                            placeholder="00000-000"
                            maxlength="9"
                            class="flex-1 px-3.5 py-2 text-xs rounded-full border border-stone-300 focus:outline-none focus:border-brand">
                        <button
                            type="submit"
                            :disabled="shippingLoading"
                            class="px-5 py-2 bg-stone-900 hover:bg-brand text-white text-xs font-medium uppercase tracking-wider rounded-full transition">
                            <span x-show="!shippingLoading">Calcular</span>
                            <span x-show="shippingLoading">...</span>
                        </button>
                    </form>

                    {{-- Shipping Error --}}
                    <p x-show="shippingError" x-text="shippingError" class="text-xs text-rose-600 mt-2 font-medium"></p>

                    {{-- Shipping Options Result --}}
                    <div x-show="shippingOptions" class="mt-3 p-3 bg-[#FAF8F5] rounded-xl border border-stone-200 divide-y divide-stone-200/60 text-xs">
                        <template x-for="opt in shippingOptions" :key="opt.name">
                            <div class="py-2 first:pt-0 last:pb-0 flex items-center justify-between">
                                <div>
                                    <span class="font-semibold text-stone-800" x-text="opt.name"></span>
                                    <span class="text-[11px] text-stone-500 block" x-text="opt.deadline"></span>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-stone-900" x-text="opt.price_formatted"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Tabs Section: Detailed Description, Specs, Reviews, Installments --}}
    <div class="mt-12 bg-white rounded-3xl p-6 sm:p-10 border border-stone-200/80 shadow-xs" id="avaliacoes">
        {{-- Tab Headers --}}
        <div class="flex items-center gap-6 sm:gap-10 border-b border-stone-200 pb-4 overflow-x-auto no-scrollbar text-xs font-semibold uppercase tracking-wider">
            <button
                @click="activeTab = 'descricao'"
                :class="activeTab === 'descricao' ? 'text-brand border-b-2 border-brand pb-4 -mb-[18px]' : 'text-stone-400 hover:text-stone-800'">
                Descrição da Peça
            </button>
            <button
                @click="activeTab = 'especificacoes'"
                :class="activeTab === 'especificacoes' ? 'text-brand border-b-2 border-brand pb-4 -mb-[18px]' : 'text-stone-400 hover:text-stone-800'">
                Ficha Técnica &amp; Cuidados
            </button>
            <button
                @click="activeTab = 'medidas'"
                :class="activeTab === 'medidas' ? 'text-brand border-b-2 border-brand pb-4 -mb-[18px]' : 'text-stone-400 hover:text-stone-800'">
                Tabela de Medidas
            </button>
            <button
                @click="activeTab = 'parcelas'"
                :class="activeTab === 'parcelas' ? 'text-brand border-b-2 border-brand pb-4 -mb-[18px]' : 'text-stone-400 hover:text-stone-800'">
                Opções de Parcelamento
            </button>
            <button
                @click="activeTab = 'avaliacoes'"
                :class="activeTab === 'avaliacoes' ? 'text-brand border-b-2 border-brand pb-4 -mb-[18px]' : 'text-stone-400 hover:text-stone-800'">
                Avaliações das Clientes ({{ $product['reviews_count'] }})
            </button>
        </div>

        {{-- Tab Contents --}}
        <div class="pt-6">

            {{-- Tab 1: Descrição --}}
            <div x-show="activeTab === 'descricao'" class="max-w-3xl text-stone-600 text-sm leading-relaxed space-y-4 font-light">
                <p class="text-base text-stone-900 font-serif font-normal italic">
                    "{{ $product['summary'] }}"
                </p>
                <p>
                    {{ $product['description'] }}
                </p>
                <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-stone-200 flex items-center gap-3 text-xs text-stone-700 mt-4">
                    <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                    <span>Peça autêntica da marca Hadassa Concept com acabamento de alta costura e primeira troca grátis em até 30 dias.</span>
                </div>
            </div>

            {{-- Tab 2: Ficha Técnica --}}
            <div x-show="activeTab === 'especificacoes'" class="space-y-4">
                <div class="border border-stone-200 rounded-2xl overflow-hidden divide-y divide-stone-200 text-xs">
                    @foreach ($product['specifications'] as $key => $val)
                        <div class="grid grid-cols-1 sm:grid-cols-3 p-3.5 hover:bg-[#FAF8F5] transition">
                            <span class="font-bold text-stone-700 sm:col-span-1">{{ $key }}</span>
                            <span class="text-stone-600 sm:col-span-2">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab 3: Tabela de Medidas --}}
            <div x-show="activeTab === 'medidas'" class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-stone-200 rounded-xl overflow-hidden">
                        <thead class="bg-[#FAF8F5] text-stone-500 uppercase tracking-wider font-semibold border-b border-stone-200">
                            <tr>
                                <th class="p-3">Tamanho</th>
                                <th class="p-3">Busto</th>
                                <th class="p-3">Cintura</th>
                                <th class="p-3">Quadril</th>
                                <th class="p-3">Comprimento</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($product['measurement_table'] ?? [] as $m)
                                <tr class="hover:bg-[#FAF8F5] transition">
                                    <td class="p-3 font-bold text-stone-800">{{ $m['size'] }}</td>
                                    <td class="p-3 text-stone-600">{{ $m['bust'] }}</td>
                                    <td class="p-3 text-stone-600">{{ $m['waist'] }}</td>
                                    <td class="p-3 text-stone-600">{{ $m['hip'] }}</td>
                                    <td class="p-3 text-stone-600">{{ $m['length'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-[11px] text-stone-400 italic">
                    * Medidas em centímetros do corpo recomendado. Em caso de dúvidas entre dois tamanhos, sugerimos optar pelo maior para maior conforto e modéstia.
                </p>
            </div>

            {{-- Tab 4: Tabela de Parcelamento --}}
            <div x-show="activeTab === 'parcelas'" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                    @foreach ($installmentsTable as $row)
                        <div class="p-3 rounded-xl border border-stone-200 bg-[#FAF8F5] flex items-center justify-between">
                            <div>
                                <span class="font-bold text-stone-900">{{ $row['times'] }}x de R$ {{ $row['value'] }}</span>
                                <span class="block text-[10px] text-emerald-700 font-semibold">{{ $row['interest'] }}</span>
                            </div>
                            <span class="text-[11px] text-stone-500">Total: R$ {{ $row['total'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab 5: Avaliações --}}
            <div x-show="activeTab === 'avaliacoes'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-stone-200 text-center flex flex-col items-center justify-center">
                        <span class="font-serif text-4xl text-stone-900">{{ number_format($product['rating'], 1) }}</span>
                        <div class="flex text-amber-500 my-2">
                            ★★★★★
                        </div>
                        <span class="text-xs text-stone-500">Média de satisfação de {{ $product['reviews_count'] }} clientes</span>
                    </div>

                    <div class="md:col-span-2 space-y-3">
                        <div class="p-4 rounded-xl bg-[#FAF8F5] border border-stone-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-stone-900">Débora L. Alencar</span>
                                <span class="text-[10px] text-stone-400">Há 4 dias • Compra verificada</span>
                            </div>
                            <div class="text-amber-500 text-xs mb-1.5">★★★★★</div>
                            <p class="text-xs text-stone-600 font-light">
                                "O caimento deste vestido superou todas as minhas expectativas! O tecido tem um toque muito macio, o forro não é transparente e o acabamento é perfeito para ir à igreja e a eventos especiais."
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-[#FAF8F5] border border-stone-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-stone-900">Priscila Medeiros</span>
                                <span class="text-[10px] text-stone-400">Há 2 semanas • Compra verificada</span>
                            </div>
                            <div class="text-amber-500 text-xs mb-1.5">★★★★★</div>
                            <p class="text-xs text-stone-600 font-light">
                                "A entrega chegou antes do prazo e a caixinha veio super cheirosa com uma cartinha linda. O cinto dá um charme maravilhoso na cintura sem apertar."
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Measurement Guide Modal --}}
    <div x-show="measureModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" style="display: none;">
        <div @click.away="measureModalOpen = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                <h3 class="font-serif text-xl text-stone-900 font-normal">Guia de Medidas Oficial</h3>
                <button @click="measureModalOpen = false" class="text-stone-400 hover:text-stone-600">✕</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border border-stone-200 rounded-xl overflow-hidden">
                    <thead class="bg-[#FAF8F5] text-stone-600 uppercase font-bold">
                        <tr>
                            <th class="p-2.5">Tamanho</th>
                            <th class="p-2.5">Busto (cm)</th>
                            <th class="p-2.5">Cintura (cm)</th>
                            <th class="p-2.5">Quadril (cm)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($product['measurement_table'] ?? [] as $m)
                            <tr>
                                <td class="p-2.5 font-bold">{{ $m['size'] }}</td>
                                <td class="p-2.5">{{ $m['bust'] }}</td>
                                <td class="p-2.5">{{ $m['waist'] }}</td>
                                <td class="p-2.5">{{ $m['hip'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-[11px] text-stone-500 space-y-1">
                <p><strong>Busto:</strong> Meça ao redor da parte mais saliente do busto mantendo a fita métrica nivelada.</p>
                <p><strong>Cintura:</strong> Meça na parte mais estreita do tronco, logo acima do umbigo.</p>
                <p><strong>Quadril:</strong> Meça ao redor da parte mais larga dos quadris e glúteos.</p>
            </div>
            <button @click="measureModalOpen = false" class="w-full py-2.5 rounded-full bg-[#1C1917] text-white text-xs uppercase font-medium">Fechar Guia</button>
        </div>
    </div>

    {{-- Related Products Section --}}
    @if (!empty($relatedProducts))
        <div class="mt-16">
            <div class="text-center max-w-md mx-auto mb-8">
                <span class="text-[10px] tracking-[0.3em] font-semibold uppercase text-brand block mb-1">Combine seu Look</span>
                <h2 class="font-serif text-2xl sm:text-3xl text-stone-900 font-normal">Você Também Pode Amar</h2>
                <div class="w-12 h-0.5 bg-brand mx-auto mt-2"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($relatedProducts as $rel)
                    <x-product-card :product="$rel" />
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
