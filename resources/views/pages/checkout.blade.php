@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{
        currentStep: 1,
        // Customer
        customerName: 'Comprador Exemplar',
        customerEmail: 'cliente@viaecommerce.com.br',
        customerCpf: '123.456.789-00',
        customerPhone: '(11) 98765-4321',

        // Shipping
        cep: '01310-100',
        street: 'Avenida Paulista',
        number: '1000',
        complement: 'Apto 42',
        neighborhood: 'Bela Vista',
        city: 'São Paulo',
        state: 'SP',
        shippingMethod: 'pac',
        shippingCost: 0,

        // Payment
        paymentMethod: 'pix',
        cardNumber: '',
        cardName: '',
        cardExp: '',
        cardCvv: '',
        cardInstallments: 1,

        get finalSubtotal() {
            return $store.cart.subtotal;
        },

        get finalDiscount() {
            let disc = $store.cart.discountValue;
            if (this.paymentMethod === 'pix') {
                disc += (this.finalSubtotal - disc) * 0.05;
            }
            return disc;
        },

        get finalTotal() {
            return Math.max(0, this.finalSubtotal - this.finalDiscount + this.shippingCost);
        },

        goToStep(step) {
            if (step === 2 && (!this.customerName || !this.customerEmail)) {
                alert('Preencha os dados de identificação para continuar.');
                return;
            }
            if (step === 3 && (!this.cep || !this.street || !this.number)) {
                alert('Preencha o endereço completo de entrega.');
                return;
            }
            this.currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
     }">

    {{-- Breadcrumb & Title --}}
    <div class="pb-6 border-b border-slate-200 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Checkout Seguro</h1>
            <p class="text-xs text-slate-500 mt-1">Ambiente criptografado com certificação SSL de ponta a ponta</p>
        </div>
        <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <span>Transação 100% Protegida</span>
        </div>
    </div>

    {{-- Step Tracker Bar --}}
    <div class="py-8">
        <div class="flex items-center justify-between max-w-3xl mx-auto relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 w-full bg-slate-200 -z-0">
                <div class="h-full bg-brand transition-all duration-300"
                     :style="'width: ' + ((currentStep - 1) / 3 * 100) + '%'"></div>
            </div>

            {{-- Step 1 --}}
            <button @click="goToStep(1)" class="relative z-10 flex flex-col items-center group cursor-pointer">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition shadow-sm"
                     :class="currentStep >= 1 ? 'bg-brand text-white ring-4 ring-blue-100' : 'bg-white border-2 border-slate-300 text-slate-400'">
                    1
                </div>
                <span class="text-xs font-bold mt-2" :class="currentStep >= 1 ? 'text-slate-900' : 'text-slate-400'">Identificação</span>
            </button>

            {{-- Step 2 --}}
            <button @click="goToStep(2)" class="relative z-10 flex flex-col items-center group cursor-pointer">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition shadow-sm"
                     :class="currentStep >= 2 ? 'bg-brand text-white ring-4 ring-blue-100' : 'bg-white border-2 border-slate-300 text-slate-400'">
                    2
                </div>
                <span class="text-xs font-bold mt-2" :class="currentStep >= 2 ? 'text-slate-900' : 'text-slate-400'">Entrega</span>
            </button>

            {{-- Step 3 --}}
            <button @click="goToStep(3)" class="relative z-10 flex flex-col items-center group cursor-pointer">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition shadow-sm"
                     :class="currentStep >= 3 ? 'bg-brand text-white ring-4 ring-blue-100' : 'bg-white border-2 border-slate-300 text-slate-400'">
                    3
                </div>
                <span class="text-xs font-bold mt-2" :class="currentStep >= 3 ? 'text-slate-900' : 'text-slate-400'">Pagamento</span>
            </button>

            {{-- Step 4 --}}
            <button @click="goToStep(4)" class="relative z-10 flex flex-col items-center group cursor-pointer">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition shadow-sm"
                     :class="currentStep >= 4 ? 'bg-brand text-white ring-4 ring-blue-100' : 'bg-white border-2 border-slate-300 text-slate-400'">
                    4
                </div>
                <span class="text-xs font-bold mt-2" :class="currentStep >= 4 ? 'text-slate-900' : 'text-slate-400'">Resumo</span>
            </button>
        </div>
    </div>

    {{-- Main Checkout Grid: Step Forms (8 cols) + Order Summary (4 cols) --}}
    <form method="POST" action="{{ route('checkout.process') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        @csrf
        <input type="hidden" name="cart_items" :value="JSON.stringify($store.cart.items)">
        <input type="hidden" name="subtotal" :value="finalSubtotal">
        <input type="hidden" name="discount" :value="finalDiscount">
        <input type="hidden" name="shipping_cost" :value="shippingCost">
        <input type="hidden" name="total" :value="finalTotal">
        <input type="hidden" name="payment_method" :value="paymentMethod">
        <input type="hidden" name="shipping_method" :value="shippingMethod">
        <input type="hidden" name="name" :value="customerName">
        <input type="hidden" name="email" :value="customerEmail">
        <input type="hidden" name="address" :value="street + ', ' + number + ' - ' + neighborhood + ', ' + city + ' - ' + state">
        <input type="hidden" name="cep" :value="cep">

        {{-- Left: Multi-step interactive panel (8 cols) --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- STEP 1: IDENTIFICAÇÃO --}}
            <div x-show="currentStep === 1" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <span class="w-8 h-8 rounded-xl bg-brand-light text-brand flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Identificação do Comprador</h2>
                        <p class="text-xs text-slate-500">Informe seus dados para emissão da nota fiscal e envio</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nome Completo *</label>
                        <input type="text" x-model="customerName" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">E-mail para confirmação *</label>
                        <input type="email" x-model="customerEmail" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">CPF (para Nota Fiscal) *</label>
                        <input type="text" x-model="customerCpf" placeholder="000.000.000-00" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Celular / WhatsApp *</label>
                        <input type="text" x-model="customerPhone" placeholder="(00) 00000-0000" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand focus:ring-2 focus:ring-blue-100">
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="button" @click="goToStep(2)" class="px-6 py-3 rounded-xl bg-brand hover:bg-brand-hover text-white text-xs font-bold transition flex items-center gap-2 shadow-sm">
                        <span>Continuar para Entrega</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 2: ENTREGA --}}
            <div x-show="currentStep === 2" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <span class="w-8 h-8 rounded-xl bg-brand-light text-brand flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Endereço de Entrega & Frete</h2>
                        <p class="text-xs text-slate-500">Onde você deseja receber os produtos?</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">CEP *</label>
                        <input type="text" x-model="cep" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Rua / Logradouro *</label>
                        <input type="text" x-model="street" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Número *</label>
                        <input type="text" x-model="number" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Complemento (opcional)</label>
                        <input type="text" x-model="complement" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Bairro *</label>
                        <input type="text" x-model="neighborhood" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Cidade *</label>
                        <input type="text" x-model="city" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">UF *</label>
                        <input type="text" x-model="state" maxlength="2" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand uppercase">
                    </div>
                </div>

                {{-- Shipping Options Selection --}}
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <span class="text-xs font-bold text-slate-900 block">Opção de Envio:</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center justify-between p-3.5 rounded-2xl border cursor-pointer transition"
                               :class="shippingMethod === 'pac' ? 'border-brand bg-brand-light/50 ring-2 ring-blue-100' : 'border-slate-200 bg-white hover:border-slate-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="ship_option" value="pac" x-model="shippingMethod" @change="shippingCost = ($store.cart.subtotal >= 199 ? 0 : 19.90)" class="text-brand">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Correios PAC</span>
                                    <span class="text-[11px] text-slate-500">5 a 7 dias úteis</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-emerald-600" x-text="$store.cart.subtotal >= 199 ? 'GRÁTIS' : 'R$ 19,90'"></span>
                        </label>

                        <label class="flex items-center justify-between p-3.5 rounded-2xl border cursor-pointer transition"
                               :class="shippingMethod === 'express' ? 'border-brand bg-brand-light/50 ring-2 ring-blue-100' : 'border-slate-200 bg-white hover:border-slate-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="ship_option" value="express" x-model="shippingMethod" @change="shippingCost = 35.00" class="text-brand">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">ViaExpress (Entrega Rápida)</span>
                                    <span class="text-[11px] text-slate-500">Até 24h úteis</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-900">R$ 35,00</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="goToStep(1)" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        &larr; Voltar
                    </button>
                    <button type="button" @click="goToStep(3)" class="px-6 py-3 rounded-xl bg-brand hover:bg-brand-hover text-white text-xs font-bold transition flex items-center gap-2 shadow-sm">
                        <span>Continuar para Pagamento</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 3: FORMA DE PAGAMENTO --}}
            <div x-show="currentStep === 3" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <span class="w-8 h-8 rounded-xl bg-brand-light text-brand flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Forma de Pagamento</h2>
                        <p class="text-xs text-slate-500">Escolha a melhor condição de pagamento para você</p>
                    </div>
                </div>

                {{-- Payment Method Tabs --}}
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" @click="paymentMethod = 'pix'"
                            class="p-4 rounded-2xl border text-center transition flex flex-col items-center justify-center gap-1.5"
                            :class="paymentMethod === 'pix' ? 'border-brand bg-emerald-50/50 ring-2 ring-emerald-200 text-emerald-800' : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-700'">
                        <span class="font-black text-sm">PIX</span>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">5% de Desconto</span>
                    </button>

                    <button type="button" @click="paymentMethod = 'credit_card'"
                            class="p-4 rounded-2xl border text-center transition flex flex-col items-center justify-center gap-1.5"
                            :class="paymentMethod === 'credit_card' ? 'border-brand bg-brand-light/50 ring-2 ring-blue-100 text-blue-900' : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-700'">
                        <span class="font-black text-sm">Cartão de Crédito</span>
                        <span class="text-[10px] text-slate-500">Até 12x sem juros</span>
                    </button>

                    <button type="button" @click="paymentMethod = 'boleto'"
                            class="p-4 rounded-2xl border text-center transition flex flex-col items-center justify-center gap-1.5"
                            :class="paymentMethod === 'boleto' ? 'border-brand bg-slate-100 ring-2 ring-slate-200 text-slate-900' : 'border-slate-200 bg-white hover:bg-slate-50 text-slate-700'">
                        <span class="font-black text-sm">Boleto</span>
                        <span class="text-[10px] text-slate-500">Vencimento em 3 dias</span>
                    </button>
                </div>

                {{-- Pix Details Tab --}}
                <div x-show="paymentMethod === 'pix'" class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-lg shrink-0">
                            ✓
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-emerald-950">Pagamento Instantâneo via Pix</h4>
                            <p class="text-xs text-emerald-800">Você receberá o QR Code e a chave Copia e Cola na próxima etapa para pagar pelo app do seu banco com aprovação em segundos.</p>
                        </div>
                    </div>
                </div>

                {{-- Credit Card Form Tab --}}
                <div x-show="paymentMethod === 'credit_card'" class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Número do Cartão *</label>
                        <input type="text" x-model="cardNumber" placeholder="0000 0000 0000 0000" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nome impresso no Cartão *</label>
                        <input type="text" x-model="cardName" placeholder="NOME SOBRENOME" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand uppercase">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Validade *</label>
                            <input type="text" x-model="cardExp" placeholder="MM/AA" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">CVV / Código de Segurança *</label>
                            <input type="text" x-model="cardCvv" placeholder="123" maxlength="4" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Número de Parcelas *</label>
                        <select x-model="cardInstallments" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-brand">
                            <template x-for="i in 10" :key="i">
                                <option :value="i" x-text="i + 'x de R$ ' + (finalTotal / i).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + ' sem juros'"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Boleto Details Tab --}}
                <div x-show="paymentMethod === 'boleto'" class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                    <p class="font-bold text-slate-800">Informações sobre o Boleto Bancário:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>O boleto será gerado após a conclusão do pedido.</li>
                        <li>Pode ser pago em qualquer banco ou aplicativo de banco.</li>
                        <li>A confirmação bancária ocorre em até 1 a 2 dias úteis.</li>
                    </ul>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="goToStep(2)" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        &larr; Voltar
                    </button>
                    <button type="button" @click="goToStep(4)" class="px-6 py-3 rounded-xl bg-brand hover:bg-brand-hover text-white text-xs font-bold transition flex items-center gap-2 shadow-sm">
                        <span>Revisar e Finalizar</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 4: REVISÃO & CONFIRMAÇÃO FINAL --}}
            <div x-show="currentStep === 4" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <span class="w-8 h-8 rounded-xl bg-brand-light text-brand flex items-center justify-center font-bold text-sm">4</span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Resumo Final do Pedido</h2>
                        <p class="text-xs text-slate-500">Revise suas escolhas antes de concluir a compra</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Destinatário</span>
                        <p class="font-bold text-slate-800" x-text="customerName"></p>
                        <p class="text-slate-500" x-text="customerEmail"></p>
                        <p class="text-slate-500" x-text="customerPhone"></p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Endereço de Entrega</span>
                        <p class="font-bold text-slate-800" x-text="street + ', ' + number"></p>
                        <p class="text-slate-500" x-text="neighborhood + ' - ' + city + '/' + state"></p>
                        <p class="text-slate-500" x-text="'CEP: ' + cep"></p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Pagamento Escolhido</span>
                        <p class="font-bold text-emerald-700 uppercase" x-text="paymentMethod === 'pix' ? 'PIX (5% OFF Aplicado)' : (paymentMethod === 'credit_card' ? 'Cartão de Crédito' : 'Boleto Bancário')"></p>
                        <p class="text-slate-500" x-text="'Total a pagar: R$ ' + finalTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></p>
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="goToStep(3)" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        &larr; Voltar
                    </button>
                    <button type="submit" class="px-8 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-black transition shadow-lg shadow-emerald-600/25 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Confirmar e Finalizar Pedido</span>
                    </button>
                </div>
            </div>

        </div>

        {{-- Right: Live Order Summary (4 cols) --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-md space-y-4 sticky top-24">
                <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span>Itens do Pedido</span>
                    <span class="text-xs text-brand font-semibold" x-text="$store.cart.count + ' itens'"></span>
                </h3>

                {{-- Mini items preview --}}
                <div class="space-y-3 max-h-60 overflow-y-auto divide-y divide-slate-100 pr-1">
                    <template x-for="(item, idx) in $store.cart.items" :key="idx">
                        <div class="pt-2 first:pt-0 flex items-center gap-3">
                            <img :src="item.image" class="w-12 h-12 object-cover rounded-lg border border-slate-100 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h5 class="text-xs font-semibold text-slate-800 truncate" x-text="item.title"></h5>
                                <span class="text-[10px] text-slate-400" x-text="item.quantity + 'x R$ ' + Number(item.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                            </div>
                            <span class="text-xs font-bold text-slate-900 shrink-0" x-text="'R$ ' + (item.price * item.quantity).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </template>
                </div>

                {{-- Calculations --}}
                <div class="pt-3 border-t border-slate-100 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span class="font-bold text-slate-800" x-text="'R$ ' + finalSubtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                    </div>

                    <template x-if="finalDiscount > 0">
                        <div class="flex justify-between text-emerald-600 font-medium">
                            <span>Desconto total</span>
                            <span class="font-bold" x-text="'- R$ ' + finalDiscount.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </template>

                    <div class="flex justify-between text-slate-600">
                        <span>Frete</span>
                        <span class="font-bold text-emerald-600" x-text="shippingCost === 0 ? 'GRÁTIS' : 'R$ ' + shippingCost.toFixed(2)"></span>
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                        <span class="text-sm font-black text-slate-900">Total a Pagar</span>
                        <span class="text-xl font-black text-slate-900" x-text="'R$ ' + finalTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
