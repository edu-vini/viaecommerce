@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{
        cep: '',
        shippingCost: 0,
        shippingName: '',
        shippingLoading: false,
        couponInput: '',

        async calcShipping() {
            if (!this.cep || this.cep.replace(/\D/g, '').length !== 8) {
                alert('Informe um CEP válido com 8 dígitos.');
                return;
            }
            this.shippingLoading = true;
            try {
                const res = await fetch('/api/calcular-frete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ cep: this.cep })
                });
                const data = await res.json();
                if (data.options && data.options.length > 0) {
                    if ($store.cart.subtotal >= 399) {
                        this.shippingCost = 0;
                        this.shippingName = 'Frete Grátis Especial';
                    } else {
                        this.shippingCost = data.options[0].price;
                        this.shippingName = data.options[0].name;
                    }
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.shippingLoading = false;
            }
        }
     }">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] text-stone-400 mb-6 uppercase tracking-wider">
        <a href="{{ route('home') }}" class="hover:text-brand transition">Início</a>
        <span>/</span>
        <span class="font-medium text-stone-700">Minha Sacola</span>
    </nav>

    <div class="flex items-center justify-between pb-6 border-b border-stone-200">
        <div>
            <h1 class="font-serif text-3xl font-normal text-stone-900 tracking-tight">Sacola de Compras</h1>
            <p class="text-xs text-stone-500 mt-1 font-light" x-text="'Você possui ' + $store.cart.count + ' ' + ($store.cart.count === 1 ? 'peça selecionada' : 'peças selecionadas')"></p>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs uppercase tracking-wider font-semibold text-brand hover:underline flex items-center gap-1">
            &larr; Continuar Comprando
        </a>
    </div>

    {{-- Empty State --}}
    <div x-show="$store.cart.items.length === 0" class="py-20 text-center bg-white rounded-3xl border border-stone-200 mt-8 shadow-xs">
        <div class="w-16 h-16 rounded-full bg-[#FAF8F5] text-stone-400 mx-auto flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <h3 class="font-serif text-xl text-stone-900">Sua sacola está vazia</h3>
        <p class="text-xs text-stone-500 max-w-sm mx-auto mt-2 font-light">
            Encontre o vestido ou look perfeito para expressar sua essência com sofisticação!
        </p>
        <a href="{{ route('catalog.index') }}" class="mt-6 inline-flex items-center px-7 py-3 rounded-full bg-[#1C1917] text-white text-xs uppercase tracking-wider font-medium hover:bg-brand transition shadow-xs">
            Ver Coleção
        </a>
    </div>

    {{-- Cart with Items --}}
    <div x-show="$store.cart.items.length > 0" class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8">

        {{-- Left: Items List (8 cols) --}}
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white rounded-3xl border border-stone-200/80 shadow-xs overflow-hidden">
                <div class="hidden sm:grid grid-cols-12 p-4 text-[11px] font-semibold text-stone-400 uppercase tracking-wider bg-[#FAF8F5] border-b border-stone-200">
                    <div class="col-span-6">Peça</div>
                    <div class="col-span-2 text-center">Quantidade</div>
                    <div class="col-span-2 text-right">Preço</div>
                    <div class="col-span-2 text-right">Subtotal</div>
                </div>

                <div class="divide-y divide-stone-100 p-4 sm:p-6 space-y-4 sm:space-y-0">
                    <template x-for="(item, index) in $store.cart.items" :key="index">
                        <div class="flex flex-col sm:grid sm:grid-cols-12 sm:items-center gap-4 py-4 first:pt-0 last:pb-0">
                            {{-- Product info --}}
                            <div class="sm:col-span-6 flex items-center gap-4">
                                <img :src="item.image" :alt="item.title" class="w-16 h-20 sm:w-20 sm:h-24 object-cover object-top rounded-xl border border-stone-100 shrink-0">
                                <div class="min-w-0">
                                    <span class="text-[10px] text-stone-400 uppercase tracking-wider" x-text="item.category"></span>
                                    <h4 class="font-serif text-xs sm:text-sm font-semibold text-stone-900 truncate" x-text="item.title"></h4>
                                    <template x-if="item.variation">
                                        <span class="inline-block text-[11px] font-medium text-stone-500 bg-[#FAF8F5] px-2 py-0.5 rounded mt-1 font-light" x-text="item.variation"></span>
                                    </template>
                                    <button @click="$store.cart.removeItem(index)" class="text-[11px] text-rose-500 hover:underline block mt-1">
                                        Remover peça
                                    </button>
                                </div>
                            </div>

                            {{-- Quantity modifier --}}
                            <div class="sm:col-span-2 flex justify-start sm:justify-center">
                                <div class="flex items-center border border-stone-200 rounded-md overflow-hidden bg-white text-xs">
                                    <button @click="$store.cart.updateQty(index, item.quantity - 1)" class="px-2.5 py-0.5 text-stone-500 hover:bg-stone-100 transition">-</button>
                                    <span class="px-3 py-0.5 bg-white min-w-[28px] text-center font-medium" x-text="item.quantity"></span>
                                    <button @click="$store.cart.updateQty(index, item.quantity + 1)" class="px-2.5 py-0.5 text-stone-500 hover:bg-stone-100 transition">+</button>
                                </div>
                            </div>

                            {{-- Unit Price --}}
                            <div class="sm:col-span-2 text-left sm:text-right text-xs text-stone-600 font-light">
                                <span class="sm:hidden text-stone-400">Valor: </span>
                                <span x-text="'R$ ' + Number(item.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                            </div>

                            {{-- Subtotal --}}
                            <div class="sm:col-span-2 text-left sm:text-right text-sm font-bold text-stone-900">
                                <span class="sm:hidden text-stone-400 font-normal text-xs">Total: </span>
                                <span x-text="'R$ ' + (item.price * item.quantity).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Shipping & Coupon Box --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Coupon Tool --}}
                <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
                    <h4 class="text-xs font-semibold text-stone-800 uppercase tracking-wider mb-2">
                        Cupom Exclusivo
                    </h4>
                    <template x-if="!$store.cart.coupon">
                        <div class="flex gap-2">
                            <input type="text" x-model="couponInput" placeholder="Ex: BEMVINDA10" class="uppercase flex-1 px-3.5 py-2 text-xs rounded-full border border-stone-300 focus:outline-none focus:border-brand">
                            <button @click="if(couponInput) { $store.cart.applyCoupon(couponInput); couponInput = ''; }" class="px-5 py-2 bg-[#1C1917] text-white text-xs uppercase tracking-wider font-semibold rounded-full hover:bg-brand transition">
                                Aplicar
                            </button>
                        </div>
                    </template>
                    <template x-if="$store.cart.coupon">
                        <div class="flex items-center justify-between p-2.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs">
                            <span class="text-emerald-800 font-medium">Cupom <strong x-text="$store.cart.coupon"></strong> (<span x-text="$store.cart.discountPercent + '% OFF'"></span>)</span>
                            <button @click="$store.cart.removeCoupon()" class="text-rose-600 font-bold hover:underline">Remover</button>
                        </div>
                    </template>
                    <span class="text-[10px] text-stone-400 mt-1.5 block">Dica: Use <strong>BEMVINDA10</strong> para 10% OFF na primeira compra.</span>
                </div>

                {{-- Shipping Estimation Tool --}}
                <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-xs">
                    <h4 class="text-xs font-semibold text-stone-800 uppercase tracking-wider mb-2">
                        Calcular Frete
                    </h4>
                    <div class="flex gap-2">
                        <input type="text" x-model="cep" placeholder="00000-000" maxlength="9" class="flex-1 px-3.5 py-2 text-xs rounded-full border border-stone-300 focus:outline-none focus:border-brand">
                        <button @click="calcShipping()" :disabled="shippingLoading" class="px-5 py-2 bg-stone-100 hover:bg-stone-200 text-stone-800 text-xs font-medium uppercase tracking-wider rounded-full transition">
                            <span x-show="!shippingLoading">Calcular</span>
                            <span x-show="shippingLoading">...</span>
                        </button>
                    </div>
                    <template x-if="shippingName">
                        <div class="mt-2 text-xs flex justify-between font-medium text-stone-700 bg-[#FAF8F5] p-2 rounded-lg">
                            <span x-text="shippingName"></span>
                            <span class="font-bold text-emerald-800" x-text="shippingCost === 0 ? 'GRÁTIS' : 'R$ ' + shippingCost.toFixed(2)"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Right: Order Summary Box (4 cols) --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl border border-stone-200/80 p-6 shadow-md space-y-5 sticky top-24">
                <h3 class="font-serif text-lg text-stone-900 pb-3 border-b border-stone-100 font-normal">Resumo da Sacola</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-stone-600 font-light">
                        <span>Subtotal das peças</span>
                        <span class="font-medium text-stone-900" x-text="'R$ ' + $store.cart.subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                    </div>

                    <template x-if="$store.cart.discountValue > 0">
                        <div class="flex justify-between text-emerald-700 font-medium">
                            <span>Desconto cupom</span>
                            <span class="font-bold" x-text="'- R$ ' + $store.cart.discountValue.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </template>

                    <div class="flex justify-between text-stone-600 font-light">
                        <span>Frete estimado</span>
                        <span class="font-semibold text-emerald-700" x-text="$store.cart.subtotal >= 399 ? 'GRÁTIS' : (shippingCost > 0 ? 'R$ ' + shippingCost.toFixed(2) : 'A calcular')"></span>
                    </div>

                    <div class="pt-3 border-t border-stone-200 flex justify-between items-baseline">
                        <div>
                            <span class="font-serif text-base font-bold text-stone-900">Total</span>
                            <span class="block text-[10px] text-stone-400">Em até 6x sem juros</span>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-stone-900" x-text="'R$ ' + ($store.cart.total + shippingCost).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>

                    <div class="p-3 rounded-2xl bg-[#FAF8F5] border border-stone-200 text-stone-800 text-xs">
                        <div class="flex items-center justify-between font-medium">
                            <span class="text-[#8B5E3C] font-semibold">No Pix com 5% OFF:</span>
                            <span class="text-sm font-bold text-stone-900" x-text="'R$ ' + (($store.cart.total + shippingCost) * 0.95).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-full bg-[#1C1917] hover:bg-brand text-white text-xs uppercase tracking-widest font-medium transition duration-300 shadow-md">
                    <span>Ir para o Checkout</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>

                {{-- Security badges --}}
                <div class="pt-4 border-t border-stone-100 flex items-center justify-center gap-4 text-[11px] text-stone-400">
                    <span>Compra 100% Segura</span>
                    <span>•</span>
                    <span>1ª Troca Grátis</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Recommended products below cart --}}
    @if (!empty($recommendedProducts))
        <div class="mt-16">
            <h3 class="font-serif text-2xl text-stone-900 mb-6 font-normal">Complete o visual com estas peças:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($recommendedProducts as $prod)
                    <x-product-card :product="$prod" />
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
