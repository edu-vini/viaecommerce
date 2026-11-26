{{-- Mini Cart Drawer Component (Boutique Style) --}}
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
         class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"></div>

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
                    <div class="p-5 border-b border-stone-200 flex items-center justify-between bg-[#FAF8F5]">
                        <div class="flex items-center gap-2.5">
                            <span class="font-serif text-lg text-stone-900 font-normal">Sua Sacola de Compras</span>
                            <span class="text-xs text-stone-400" x-text="'(' + $store.cart.count + ')'"></span>
                        </div>
                        <button @click="$store.cart.close()" class="p-1.5 text-stone-400 hover:text-stone-800 hover:bg-stone-200/50 transition">
                            ✕
                        </button>
                    </div>

                    {{-- Free Shipping Tracker (Hadassa: R$ 399) --}}
                    <div class="p-3 bg-[#FAF8F5] border-b border-stone-200 px-5 text-xs">
                        <template x-if="$store.cart.subtotal >= 399">
                            <div class="flex items-center gap-2 text-emerald-800 font-semibold">
                                <span class="w-2 h-2 bg-emerald-500"></span>
                                Parabéns! Você ganhou Frete Grátis em todo o pedido!
                            </div>
                        </template>
                        <template x-if="$store.cart.subtotal < 399">
                            <div>
                                <div class="flex justify-between font-medium text-stone-700 mb-1.5">
                                    <span>Faltam <strong class="text-brand font-bold" x-text="'R$ ' + (399 - $store.cart.subtotal).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></strong> para Frete Grátis</span>
                                    <span class="text-stone-400" x-text="Math.round(($store.cart.subtotal / 399) * 100) + '%'"></span>
                                </div>
                                <div class="w-full h-1.5 bg-stone-200 overflow-hidden">
                                    <div class="h-full bg-brand transition-all duration-300" :style="'width: ' + Math.min(100, ($store.cart.subtotal / 399) * 100) + '%'"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Cart Items List --}}
                    <div class="flex-1 overflow-y-auto p-5 space-y-4 divide-y divide-stone-100">
                        {{-- Empty State --}}
                        <template x-if="$store.cart.items.length === 0">
                            <div class="py-16 text-center">
                                <div class="w-16 h-16 bg-[#FAF8F5] text-stone-400 mx-auto flex items-center justify-center mb-4 border border-stone-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <h4 class="font-serif text-lg text-stone-800">Sua sacola está vazia</h4>
                                <p class="text-xs text-stone-500 mt-1 max-w-xs mx-auto font-light">Nenhuma peça foi adicionada ainda. Conheça as novidades da coleção!</p>
                                <a href="{{ route('catalog.index') }}" @click="$store.cart.close()" class="mt-5 inline-flex items-center px-6 py-2.5 bg-[#1C1917] text-white text-xs uppercase tracking-wider font-semibold hover:bg-brand transition shadow-xs">
                                    Explorar Coleção
                                </a>
                            </div>
                        </template>

                        {{-- Item Loop --}}
                        <template x-for="(item, index) in $store.cart.items" :key="index">
                            <div class="pt-4 first:pt-0 flex gap-4 group">
                                <img :src="item.image" :alt="item.title" class="w-16 h-20 object-cover object-top border border-stone-100 shrink-0">
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between gap-2">
                                            <h4 class="font-serif text-xs font-semibold text-stone-800 line-clamp-2" x-text="item.title"></h4>
                                            <button @click="$store.cart.removeItem(index)" class="text-stone-400 hover:text-rose-500 transition p-1">
                                                ✕
                                            </button>
                                        </div>
                                        <template x-if="item.variation">
                                            <span class="inline-block text-[10px] text-stone-500 bg-[#FAF8F5] px-2 py-0.5 border border-stone-200 mt-1 font-light" x-text="item.variation"></span>
                                        </template>
                                    </div>

                                    <div class="flex items-center justify-between mt-3">
                                        {{-- Quantity Modifier --}}
                                        <div class="flex items-center border border-stone-200 overflow-hidden bg-white text-xs">
                                            <button @click="$store.cart.updateQty(index, item.quantity - 1)" class="px-2 py-0.5 text-stone-500 hover:bg-stone-100 transition">-</button>
                                            <span class="px-2 py-0.5 text-stone-800 font-medium min-w-[24px] text-center" x-text="item.quantity"></span>
                                            <button @click="$store.cart.updateQty(index, item.quantity + 1)" class="px-2 py-0.5 text-stone-500 hover:bg-stone-100 transition">+</button>
                                        </div>
                                        {{-- Price --}}
                                        <div class="text-right">
                                            <span class="text-xs font-bold text-stone-900" x-text="'R$ ' + (item.price * item.quantity).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Drawer Footer with Totals & Checkout --}}
                    <div x-show="$store.cart.items.length > 0" class="p-5 border-t border-stone-200 bg-[#FAF8F5] space-y-4">
                        {{-- Coupon Input --}}
                        <div x-data="{ couponInput: '' }" class="space-y-1">
                            <template x-if="!$store.cart.coupon">
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="couponInput" placeholder="CUPOM (EX: BEMVINDA10)" class="uppercase flex-1 px-3 py-1.5 text-xs border border-stone-300 focus:border-brand outline-none bg-white">
                                    <button @click="if(couponInput) { $store.cart.applyCoupon(couponInput); couponInput = ''; }" class="px-4 py-1.5 bg-[#1C1917] text-white text-[11px] uppercase tracking-wider font-semibold hover:bg-brand transition">Aplicar</button>
                                </div>
                            </template>
                            <template x-if="$store.cart.coupon">
                                <div class="flex items-center justify-between bg-emerald-50 text-emerald-800 border border-emerald-200 px-3 py-1.5 text-xs font-medium">
                                    <span>Cupom <strong x-text="$store.cart.coupon"></strong> (<span x-text="$store.cart.discountPercent + '% OFF'"></span>)</span>
                                    <button @click="$store.cart.removeCoupon()" class="text-rose-600 hover:underline text-[11px]">Remover</button>
                                </div>
                            </template>
                        </div>

                        {{-- Calculation Breakdown --}}
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between text-stone-600 font-light">
                                <span>Subtotal</span>
                                <span class="font-medium text-stone-800" x-text="'R$ ' + $store.cart.subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                            </div>
                            <template x-if="$store.cart.discountValue > 0">
                                <div class="flex justify-between text-emerald-700">
                                    <span>Desconto cupom</span>
                                    <span class="font-semibold" x-text="'- R$ ' + $store.cart.discountValue.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                                </div>
                            </template>
                            <div class="flex justify-between text-stone-600 font-light">
                                <span>Frete</span>
                                <span class="font-semibold text-emerald-700" x-text="$store.cart.subtotal >= 399 ? 'GRÁTIS' : 'Calculado no checkout'"></span>
                            </div>
                            <div class="pt-2 border-t border-stone-200 flex justify-between items-baseline">
                                <span class="font-serif text-sm text-stone-900 font-bold">Total Final</span>
                                <div class="text-right">
                                    <span class="text-lg font-bold text-stone-900" x-text="'R$ ' + $store.cart.total.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                                    <span class="block text-[10px] text-stone-500">ou até 6x de <span x-text="'R$ ' + ($store.cart.total / 6).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span> sem juros</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-2 pt-1">
                            <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center gap-2 py-3.5 px-4 bg-[#1C1917] hover:bg-brand text-white font-medium text-xs uppercase tracking-widest transition duration-300 shadow-md">
                                <span>Finalizar Pedido</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="{{ route('cart.index') }}" @click="$store.cart.close()" class="w-full block text-center py-1.5 text-[11px] uppercase tracking-wider text-stone-500 hover:text-stone-800 transition">
                                Ver Sacola Completa
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
