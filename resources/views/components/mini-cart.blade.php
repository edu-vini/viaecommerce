{{-- Mini Cart Drawer Component --}}
<div x-cloak
     x-show="$store.cart.isOpen"
     class="relative z-50"
     aria-labelledby="slide-over-title"
     role="dialog"
     aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="$store.cart.isOpen"
         x-transition:enter="ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.cart.close()"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    {{-- Sliding Drawer Container --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-auto fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="$store.cart.isOpen"
                     x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between">

                    {{-- Drawer Header --}}
                    <div class="p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/70">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-brand-light text-brand flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Meu Carrinho</h3>
                                <p class="text-xs text-slate-500" x-text="$store.cart.count + ' ' + ($store.cart.count === 1 ? 'item' : 'itens') + ' adicionados'"></p>
                            </div>
                        </div>
                        <button @click="$store.cart.close()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-200/60 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Free Shipping Tracker --}}
                    <div class="p-3 bg-blue-50/80 border-b border-blue-100 px-5 text-xs">
                        <template x-if="$store.cart.subtotal >= 199">
                            <div class="flex items-center gap-2 text-emerald-700 font-semibold">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Parabéns! Você ganhou Frete Grátis nesta compra!
                            </div>
                        </template>
                        <template x-if="$store.cart.subtotal < 199">
                            <div>
                                <div class="flex justify-between font-medium text-slate-700 mb-1.5">
                                    <span>Faltam <strong class="text-brand font-bold" x-text="'R$ ' + (199 - $store.cart.subtotal).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></strong> para Frete Grátis</span>
                                    <span class="text-slate-500" x-text="Math.round(($store.cart.subtotal / 199) * 100) + '%'"></span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand rounded-full transition-all duration-300" :style="'width: ' + Math.min(100, ($store.cart.subtotal / 199) * 100) + '%'"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Cart Items List --}}
                    <div class="flex-1 overflow-y-auto p-5 space-y-4 divide-y divide-slate-100">
                        {{-- Empty State --}}
                        <template x-if="$store.cart.items.length === 0">
                            <div class="py-16 text-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <h4 class="text-base font-bold text-slate-800">Seu carrinho está vazio</h4>
                                <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">Nenhum produto foi adicionado ainda. Explore nossas ofertas exclusivas!</p>
                                <a href="{{ route('catalog.index') }}" @click="$store.cart.close()" class="mt-5 inline-flex items-center px-5 py-2.5 rounded-xl bg-brand text-white font-semibold text-xs hover:bg-brand-hover transition shadow-sm">
                                    Começar a Comprar
                                </a>
                            </div>
                        </template>

                        {{-- Item Loop --}}
                        <template x-for="(item, index) in $store.cart.items" :key="index">
                            <div class="pt-4 first:pt-0 flex gap-4 group">
                                <img :src="item.image" :alt="item.title" class="w-20 h-20 object-cover rounded-xl border border-slate-100 shrink-0">
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between gap-2">
                                            <h4 class="text-xs font-semibold text-slate-800 line-clamp-2" x-text="item.title"></h4>
                                            <button @click="$store.cart.removeItem(index)" class="text-slate-400 hover:text-rose-500 transition p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                        <template x-if="item.variation">
                                            <span class="inline-block text-[11px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md mt-1" x-text="item.variation"></span>
                                        </template>
                                    </div>

                                    <div class="flex items-center justify-between mt-3">
                                        {{-- Quantity Modifier --}}
                                        <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50 text-xs font-semibold">
                                            <button @click="$store.cart.updateQty(index, item.quantity - 1)" class="px-2.5 py-1 text-slate-600 hover:bg-slate-200 transition">-</button>
                                            <span class="px-2.5 py-1 text-slate-900 bg-white min-w-[28px] text-center" x-text="item.quantity"></span>
                                            <button @click="$store.cart.updateQty(index, item.quantity + 1)" class="px-2.5 py-1 text-slate-600 hover:bg-slate-200 transition">+</button>
                                        </div>
                                        {{-- Price --}}
                                        <div class="text-right">
                                            <span class="text-xs font-bold text-slate-900" x-text="'R$ ' + (item.price * item.quantity).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Drawer Footer with Totals & Checkout --}}
                    <div x-show="$store.cart.items.length > 0" class="p-5 border-t border-slate-200 bg-slate-50/70 space-y-4">
                        {{-- Coupon Input --}}
                        <div x-data="{ couponInput: '' }" class="space-y-1">
                            <template x-if="!$store.cart.coupon">
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="couponInput" placeholder="Cupom (ex: BEMVINDO10)" class="uppercase flex-1 px-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-100 focus:border-brand outline-none">
                                    <button @click="if(couponInput) { $store.cart.applyCoupon(couponInput); couponInput = ''; }" class="px-3 py-1.5 bg-slate-800 text-white text-xs font-semibold rounded-lg hover:bg-slate-900 transition">Aplicar</button>
                                </div>
                            </template>
                            <template x-if="$store.cart.coupon">
                                <div class="flex items-center justify-between bg-emerald-50 text-emerald-800 border border-emerald-200 px-3 py-1.5 rounded-lg text-xs font-medium">
                                    <span>Cupom <strong x-text="$store.cart.coupon"></strong> aplicado (<span x-text="$store.cart.discountPercent + '% OFF'"></span>)</span>
                                    <button @click="$store.cart.removeCoupon()" class="text-rose-600 hover:underline text-[11px]">Remover</button>
                                </div>
                            </template>
                        </div>

                        {{-- Calculation Breakdown --}}
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between text-slate-600">
                                <span>Subtotal</span>
                                <span class="font-semibold text-slate-800" x-text="'R$ ' + $store.cart.subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                            </div>
                            <template x-if="$store.cart.discountValue > 0">
                                <div class="flex justify-between text-emerald-600">
                                    <span>Desconto aplicado</span>
                                    <span class="font-semibold" x-text="'- R$ ' + $store.cart.discountValue.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                                </div>
                            </template>
                            <div class="flex justify-between text-slate-600">
                                <span>Frete</span>
                                <span class="font-semibold text-emerald-600" x-text="$store.cart.subtotal >= 199 ? 'GRÁTIS' : 'Calculado no checkout'"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-200 flex justify-between items-baseline">
                                <span class="text-sm font-bold text-slate-900">Total Final</span>
                                <div class="text-right">
                                    <span class="text-lg font-black text-slate-900" x-text="'R$ ' + $store.cart.total.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                                    <span class="block text-[10px] text-emerald-600 font-semibold">ou até 10x de <span x-text="'R$ ' + ($store.cart.total / 10).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span> sem juros</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-2 pt-1">
                            <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-brand hover:bg-brand-hover text-white font-bold text-sm transition shadow-md shadow-blue-500/20">
                                <span>Finalizar Compra</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="{{ route('cart.index') }}" @click="$store.cart.close()" class="w-full block text-center py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                                Ver Carrinho Detalhado
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
