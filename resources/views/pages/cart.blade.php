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
                    if ($store.cart.subtotal >= 199) {
                        this.shippingCost = 0;
                        this.shippingName = 'Frete Grátis Promocional';
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
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand transition">Início</a>
        <span>/</span>
        <span class="font-bold text-slate-800">Meu Carrinho de Compras</span>
    </nav>

    <div class="flex items-center justify-between pb-6 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Carrinho de Compras</h1>
            <p class="text-xs text-slate-500 mt-1" x-text="'Você possui ' + $store.cart.count + ' ' + ($store.cart.count === 1 ? 'produto selecionado' : 'produtos selecionados')"></p>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-brand hover:underline flex items-center gap-1">
            &larr; Continuar Comprando
        </a>
    </div>

    {{-- Empty State --}}
    <div x-show="$store.cart.items.length === 0" class="py-20 text-center bg-white rounded-3xl border border-slate-200 mt-8 shadow-xs">
        <div class="w-20 h-20 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900">Seu carrinho está vazio no momento</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-2">
            Adicione produtos incríveis para aproveitar nossas condições especiais de frete e parcelamento!
        </p>
        <a href="{{ route('catalog.index') }}" class="mt-6 inline-flex items-center px-6 py-3 rounded-xl bg-brand text-white font-bold text-xs hover:bg-brand-hover transition shadow-md">
            Explorar Produtos
        </a>
    </div>

    {{-- Cart with Items --}}
    <div x-show="$store.cart.items.length > 0" class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8">

        {{-- Left: Items Table / List (8 cols) --}}
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="hidden sm:grid grid-cols-12 p-4 text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-50 border-b border-slate-200">
                    <div class="col-span-6">Produto</div>
                    <div class="col-span-2 text-center">Quantidade</div>
                    <div class="col-span-2 text-right">Preço Unitário</div>
                    <div class="col-span-2 text-right">Subtotal</div>
                </div>

                <div class="divide-y divide-slate-100 p-4 sm:p-6 space-y-4 sm:space-y-0">
                    <template x-for="(item, index) in $store.cart.items" :key="index">
                        <div class="flex flex-col sm:grid sm:grid-cols-12 sm:items-center gap-4 py-4 first:pt-0 last:pb-0">
                            {{-- Product info --}}
                            <div class="sm:col-span-6 flex items-center gap-4">
                                <img :src="item.image" :alt="item.title" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-xl border border-slate-100 shrink-0">
                                <div class="min-w-0">
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold" x-text="item.category"></span>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate" x-text="item.title"></h4>
                                    <template x-if="item.variation">
                                        <span class="inline-block text-[11px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded mt-1" x-text="item.variation"></span>
                                    </template>
                                    <button @click="$store.cart.removeItem(index)" class="text-[11px] text-rose-500 hover:underline block mt-1">
                                        Remover item
                                    </button>
                                </div>
                            </div>

                            {{-- Quantity modifier --}}
                            <div class="sm:col-span-2 flex justify-start sm:justify-center">
                                <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50 text-xs font-bold">
                                    <button @click="$store.cart.updateQty(index, item.quantity - 1)" class="px-2.5 py-1 text-slate-600 hover:bg-slate-200 transition">-</button>
                                    <span class="px-3 py-1 bg-white min-w-[28px] text-center" x-text="item.quantity"></span>
                                    <button @click="$store.cart.updateQty(index, item.quantity + 1)" class="px-2.5 py-1 text-slate-600 hover:bg-slate-200 transition">+</button>
                                </div>
                            </div>

                            {{-- Unit Price --}}
                            <div class="sm:col-span-2 text-left sm:text-right text-xs text-slate-600 font-medium">
                                <span class="sm:hidden text-slate-400">Unitário: </span>
                                <span x-text="'R$ ' + Number(item.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                            </div>

                            {{-- Subtotal --}}
                            <div class="sm:col-span-2 text-left sm:text-right text-sm font-black text-slate-900">
                                <span class="sm:hidden text-slate-400 font-normal text-xs">Total: </span>
                                <span x-text="'R$ ' + (item.price * item.quantity).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Shipping & Coupon Tools Box --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Coupon Tool --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Cupom Promocional</span>
                    </h4>
                    <template x-if="!$store.cart.coupon">
                        <div class="flex gap-2">
                            <input type="text" x-model="couponInput" placeholder="Ex: BEMVINDO10" class="uppercase flex-1 px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                            <button @click="if(couponInput) { $store.cart.applyCoupon(couponInput); couponInput = ''; }" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                                Aplicar
                            </button>
                        </div>
                    </template>
                    <template x-if="$store.cart.coupon">
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs">
                            <span class="text-emerald-800 font-medium">Cupom <strong x-text="$store.cart.coupon"></strong> (<span x-text="$store.cart.discountPercent + '% OFF'"></span>)</span>
                            <button @click="$store.cart.removeCoupon()" class="text-rose-600 font-bold hover:underline">Remover</button>
                        </div>
                    </template>
                    <span class="text-[10px] text-slate-400 mt-1 block">Dica: Teste os cupons <strong>BEMVINDO10</strong> ou <strong>VIAECOMMERCE</strong>.</span>
                </div>

                {{-- Shipping Estimation Tool --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        <span>Calcular Frete</span>
                    </h4>
                    <div class="flex gap-2">
                        <input type="text" x-model="cep" placeholder="00000-000" maxlength="9" class="flex-1 px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                        <button @click="calcShipping()" :disabled="shippingLoading" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition">
                            <span x-show="!shippingLoading">Calcular</span>
                            <span x-show="shippingLoading">...</span>
                        </button>
                    </div>
                    <template x-if="shippingName">
                        <div class="mt-2 text-xs flex justify-between font-medium text-slate-700 bg-slate-50 p-2 rounded-lg">
                            <span x-text="shippingName"></span>
                            <span class="font-bold text-emerald-600" x-text="shippingCost === 0 ? 'GRÁTIS' : 'R$ ' + shippingCost.toFixed(2)"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Right: Order Summary Box (4 cols) --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-md space-y-5 sticky top-24">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">Resumo do Pedido</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal dos itens</span>
                        <span class="font-semibold text-slate-900" x-text="'R$ ' + $store.cart.subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                    </div>

                    <template x-if="$store.cart.discountValue > 0">
                        <div class="flex justify-between text-emerald-600 font-medium">
                            <span>Desconto cupom (<span x-text="$store.cart.discountPercent + '%'"></span>)</span>
                            <span class="font-bold" x-text="'- R$ ' + $store.cart.discountValue.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </template>

                    <div class="flex justify-between text-slate-600">
                        <span>Frete estimado</span>
                        <span class="font-semibold text-emerald-600" x-text="$store.cart.subtotal >= 199 ? 'GRÁTIS' : (shippingCost > 0 ? 'R$ ' + shippingCost.toFixed(2) : 'A calcular')"></span>
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                        <div>
                            <span class="text-base font-black text-slate-900">Total</span>
                            <span class="block text-[10px] text-slate-400">Em até 10x sem juros</span>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black text-slate-900" x-text="'R$ ' + ($store.cart.total + shippingCost).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                        <div class="flex items-center justify-between font-bold">
                            <span>No Pix com 5% OFF:</span>
                            <span class="text-sm" x-text="'R$ ' + (($store.cart.total + shippingCost) * 0.95).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-2xl bg-brand hover:bg-brand-hover text-white font-black text-sm transition shadow-lg shadow-blue-500/25">
                    <span>Ir para o Checkout</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>

                {{-- Security badges --}}
                <div class="pt-4 border-t border-slate-100 flex items-center justify-center gap-4 text-[11px] text-slate-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Compra 100% Segura
                    </span>
                    <span>•</span>
                    <span>Garantia de 7 Dias</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Recommended products below cart --}}
    @if (!empty($recommendedProducts))
        <div class="mt-16">
            <h3 class="text-xl font-black text-slate-900 mb-6">Aproveite e leve também com frete unificado:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($recommendedProducts as $prod)
                    <x-product-card :product="$prod" />
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
