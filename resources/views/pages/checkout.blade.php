@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{
        currentStep: 1,
        // Customer
        customerName: 'Cliente Elegante',
        customerEmail: 'contato@cliente.com.br',
        customerCpf: '123.456.789-00',
        customerPhone: '(11) 98765-4321',

        // Shipping
        cep: '01310-100',
        street: 'Avenida Paulista',
        number: '1500',
        complement: 'Apto 82',
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
                alert('Por favor, preencha seus dados de identificação para continuar.');
                return;
            }
            if (step === 3 && (!this.cep || !this.street || !this.number)) {
                alert('Por favor, informe seu endereço completo de entrega.');
                return;
            }
            this.currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
     }">

    {{-- Breadcrumb & Title --}}
    <div class="pb-6 border-b border-stone-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-normal text-stone-900 tracking-tight">Checkout Seguro</h1>
            <p class="text-xs text-stone-500 mt-1 font-light">Seus dados e pagamento protegidos com criptografia SSL 256-bit</p>
        </div>
        <div class="inline-flex items-center gap-2 text-stone-700 text-xs font-medium bg-white px-4 py-2 rounded-full border border-stone-200 shadow-xs self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Ambiente 100% Protegido</span>
        </div>
    </div>

    {{-- Step Tracker Bar --}}
    <div class="py-8">
        <div class="flex items-center justify-between max-w-2xl mx-auto relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 w-full bg-stone-200 -z-0">
                <div class="h-full bg-brand transition-all duration-300"
                     :style="'width: ' + ((currentStep - 1) / 3 * 100) + '%'"></div>
            </div>

            {{-- Step 1 --}}
            <button @click="goToStep(1)" class="relative z-10 flex flex-col items-center group cursor-pointer">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-serif text-xs transition shadow-sm"
                     :class="currentStep >= 1 ? 'bg-brand text-white ring-4 ring-stone-200 font-bold' : 'bg-white border border-stone-300 text-stone-400'">
                    1
                </div>
                <span class="text-[11px] font-medium uppercase tracking-wider mt-2" :class="currentStep >= 1 ? 'text-stone-900 font-bold' : 'text-stone-400'">Identificação</span>
            </button>

            {{-- Step 2 --}}
            <button @click="goToStep(2)" class="relative z-10 flex flex-col items-center group cursor-pointer">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-serif text-xs transition shadow-sm"
                     :class="currentStep >= 2 ? 'bg-brand text-white ring-4 ring-stone-200 font-bold' : 'bg-white border border-stone-300 text-stone-400'">
                    2
                </div>
                <span class="text-[11px] font-medium uppercase tracking-wider mt-2" :class="currentStep >= 2 ? 'text-stone-900 font-bold' : 'text-stone-400'">Entrega</span>
            </button>

            {{-- Step 3 --}}
            <button @click="goToStep(3)" class="relative z-10 flex flex-col items-center group cursor-pointer">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-serif text-xs transition shadow-sm"
                     :class="currentStep >= 3 ? 'bg-brand text-white ring-4 ring-stone-200 font-bold' : 'bg-white border border-stone-300 text-stone-400'">
                    3
                </div>
                <span class="text-[11px] font-medium uppercase tracking-wider mt-2" :class="currentStep >= 3 ? 'text-stone-900 font-bold' : 'text-stone-400'">Pagamento</span>
            </button>

            {{-- Step 4 --}}
            <button @click="goToStep(4)" class="relative z-10 flex flex-col items-center group cursor-pointer">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-serif text-xs transition shadow-sm"
                     :class="currentStep >= 4 ? 'bg-brand text-white ring-4 ring-stone-200 font-bold' : 'bg-white border border-stone-300 text-stone-400'">
                    4
                </div>
                <span class="text-[11px] font-medium uppercase tracking-wider mt-2" :class="currentStep >= 4 ? 'text-stone-900 font-bold' : 'text-stone-400'">Resumo</span>
            </button>
        </div>
    </div>

    {{-- Main Checkout Grid --}}
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
            <div x-show="currentStep === 1" class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-full bg-[#FAF8F5] text-brand border border-stone-200 flex items-center justify-center font-serif font-bold text-sm">1</span>
                    <div>
                        <h2 class="font-serif text-lg text-stone-900">Seus Dados Pessoais</h2>
                        <p class="text-xs text-stone-500 font-light">Para emissão da nota fiscal e envio da sua caixinha</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Nome Completo *</label>
                        <input type="text" x-model="customerName" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">E-mail para confirmação *</label>
                        <input type="email" x-model="customerEmail" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">CPF (obrigatório para envio) *</label>
                        <input type="text" x-model="customerCpf" placeholder="000.000.000-00" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">WhatsApp para rastreio *</label>
                        <input type="text" x-model="customerPhone" placeholder="(00) 00000-0000" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="button" @click="goToStep(2)" class="px-7 py-3 rounded-full bg-[#1C1917] hover:bg-brand text-white text-xs uppercase tracking-wider font-medium transition flex items-center gap-2 shadow-sm">
                        <span>Avançar para Entrega</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 2: ENTREGA --}}
            <div x-show="currentStep === 2" class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-full bg-[#FAF8F5] text-brand border border-stone-200 flex items-center justify-center font-serif font-bold text-sm">2</span>
                    <div>
                        <h2 class="font-serif text-lg text-stone-900">Endereço de Envio</h2>
                        <p class="text-xs text-stone-500 font-light">Onde deseja receber suas peças com carinho?</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-stone-700 mb-1">CEP *</label>
                        <input type="text" x-model="cep" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Rua / Logradouro *</label>
                        <input type="text" x-model="street" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Número *</label>
                        <input type="text" x-model="number" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Complemento</label>
                        <input type="text" x-model="complement" class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Bairro *</label>
                        <input type="text" x-model="neighborhood" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Cidade *</label>
                        <input type="text" x-model="city" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-semibold text-stone-700 mb-1">UF *</label>
                        <input type="text" x-model="state" maxlength="2" required class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand uppercase">
                    </div>
                </div>

                {{-- Shipping Options Selection --}}
                <div class="pt-4 border-t border-stone-100 space-y-3">
                    <span class="text-xs font-semibold text-stone-800 uppercase tracking-wider block">Escolha a Opção de Frete:</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                               :class="shippingMethod === 'pac' ? 'border-[#1C1917] bg-[#FAF8F5] ring-2 ring-stone-200' : 'border-stone-200 bg-white hover:border-stone-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="ship_option" value="pac" x-model="shippingMethod" @change="shippingCost = ($store.cart.subtotal >= 399 ? 0 : 22.90)" class="text-brand">
                                <div>
                                    <span class="text-xs font-bold text-stone-800 block">Correios PAC Especial</span>
                                    <span class="text-[11px] text-stone-500 font-light">4 a 6 dias úteis</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-emerald-800" x-text="$store.cart.subtotal >= 399 ? 'GRÁTIS' : 'R$ 22,90'"></span>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                               :class="shippingMethod === 'express' ? 'border-[#1C1917] bg-[#FAF8F5] ring-2 ring-stone-200' : 'border-stone-200 bg-white hover:border-stone-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="ship_option" value="express" x-model="shippingMethod" @change="shippingCost = 38.00" class="text-brand">
                                <div>
                                    <span class="text-xs font-bold text-stone-800 block">Sedex Express</span>
                                    <span class="text-[11px] text-stone-500 font-light">1 a 2 dias úteis</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-stone-900">R$ 38,00</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="goToStep(1)" class="px-5 py-2.5 rounded-full border border-stone-300 text-xs font-medium text-stone-600 hover:bg-stone-50 transition">
                        &larr; Voltar
                    </button>
                    <button type="button" @click="goToStep(3)" class="px-7 py-3 rounded-full bg-[#1C1917] hover:bg-brand text-white text-xs uppercase tracking-wider font-medium transition flex items-center gap-2 shadow-sm">
                        <span>Avançar para Pagamento</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 3: FORMA DE PAGAMENTO --}}
            <div x-show="currentStep === 3" class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-full bg-[#FAF8F5] text-brand border border-stone-200 flex items-center justify-center font-serif font-bold text-sm">3</span>
                    <div>
                        <h2 class="font-serif text-lg text-stone-900">Forma de Pagamento</h2>
                        <p class="text-xs text-stone-500 font-light">Escolha como prefere concluir seu pedido</p>
                    </div>
                </div>

                {{-- Payment Method Tabs --}}
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" @click="paymentMethod = 'pix'"
                            class="p-4 rounded-2xl border text-center transition flex flex-col items-center justify-center gap-1"
                            :class="paymentMethod === 'pix' ? 'border-[#1C1917] bg-[#FAF8F5] ring-2 ring-stone-200 text-stone-900 font-bold' : 'border-stone-200 bg-white hover:bg-stone-50 text-stone-600'">
                        <span class="text-xs tracking-wider uppercase font-semibold">PIX</span>
                        <span class="text-[10px] text-[#8B5E3C] font-bold">5% OFF à vista</span>
                    </button>

                    <button type="button" @click="paymentMethod = 'credit_card'"
                            class="p-4 rounded-2xl border text-center transition flex flex-col items-center justify-center gap-1"
                            :class="paymentMethod === 'credit_card' ? 'border-[#1C1917] bg-[#FAF8F5] ring-2 ring-stone-200 text-stone-900 font-bold' : 'border-stone-200 bg-white hover:bg-stone-50 text-stone-600'">
                        <span class="text-xs tracking-wider uppercase font-semibold">Cartão de Crédito</span>
                        <span class="text-[10px] text-stone-500">Até 6x sem juros</span>
                    </button>

                    <button type="button" @click="paymentMethod = 'boleto'"
                            class="p-4 rounded-2xl border text-center transition flex flex-col items-center justify-center gap-1"
                            :class="paymentMethod === 'boleto' ? 'border-[#1C1917] bg-[#FAF8F5] ring-2 ring-stone-200 text-stone-900 font-bold' : 'border-stone-200 bg-white hover:bg-stone-50 text-stone-600'">
                        <span class="text-xs tracking-wider uppercase font-semibold">Boleto</span>
                        <span class="text-[10px] text-stone-500">Vencimento em 3 dias</span>
                    </button>
                </div>

                {{-- Pix Details Tab --}}
                <div x-show="paymentMethod === 'pix'" class="p-5 rounded-2xl bg-[#FAF8F5] border border-stone-200 space-y-2 text-xs">
                    <div class="flex items-center gap-2 text-stone-800 font-semibold">
                        <span class="w-2 h-2 rounded-full bg-[#8B5E3C]"></span>
                        <span>Desconto especial de 5% aplicado no valor total das peças!</span>
                    </div>
                    <p class="text-stone-500 font-light">
                        Após clicar em confirmar, você visualizará o QR Code e o código Pix Copia e Cola para pagar pelo aplicativo do seu banco com aprovação em poucos segundos.
                    </p>
                </div>

                {{-- Credit Card Form Tab --}}
                <div x-show="paymentMethod === 'credit_card'" class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Número do Cartão *</label>
                        <input type="text" x-model="cardNumber" placeholder="0000 0000 0000 0000" class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Nome no Cartão *</label>
                        <input type="text" x-model="cardName" placeholder="NOME COMO NO CARTÃO" class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand uppercase">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Validade *</label>
                            <input type="text" x-model="cardExp" placeholder="MM/AA" class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">CVV *</label>
                            <input type="text" x-model="cardCvv" placeholder="123" maxlength="4" class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Parcelamento *</label>
                        <select x-model="cardInstallments" class="w-full text-xs px-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:border-brand">
                            <template x-for="i in 6" :key="i">
                                <option :value="i" x-text="i + 'x de R$ ' + (finalTotal / i).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + ' sem juros'"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Boleto Details Tab --}}
                <div x-show="paymentMethod === 'boleto'" class="p-5 rounded-2xl bg-[#FAF8F5] border border-stone-200 text-xs text-stone-600 space-y-1 font-light">
                    <p class="font-bold text-stone-800">Instruções para o Boleto:</p>
                    <p>O boleto bancário será gerado na próxima tela. A confirmação ocorre normalmente em até 1 dia útil após o pagamento.</p>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="goToStep(2)" class="px-5 py-2.5 rounded-full border border-stone-300 text-xs font-medium text-stone-600 hover:bg-stone-50 transition">
                        &larr; Voltar
                    </button>
                    <button type="button" @click="goToStep(4)" class="px-7 py-3 rounded-full bg-[#1C1917] hover:bg-brand text-white text-xs uppercase tracking-wider font-medium transition flex items-center gap-2 shadow-sm">
                        <span>Revisar Pedido</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 4: REVISÃO & CONFIRMAÇÃO FINAL --}}
            <div x-show="currentStep === 4" class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 rounded-full bg-[#FAF8F5] text-brand border border-stone-200 flex items-center justify-center font-serif font-bold text-sm">4</span>
                    <div>
                        <h2 class="font-serif text-lg text-stone-900">Confirmação da Compra</h2>
                        <p class="text-xs text-stone-500 font-light">Tudo pronto para finalizar seu pedido!</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-stone-200">
                        <span class="text-[10px] uppercase font-bold text-stone-400 block mb-1">Destinatário</span>
                        <p class="font-semibold text-stone-900" x-text="customerName"></p>
                        <p class="text-stone-500" x-text="customerEmail"></p>
                        <p class="text-stone-500" x-text="customerPhone"></p>
                    </div>

                    <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-stone-200">
                        <span class="text-[10px] uppercase font-bold text-stone-400 block mb-1">Endereço</span>
                        <p class="font-semibold text-stone-900" x-text="street + ', ' + number"></p>
                        <p class="text-stone-500" x-text="neighborhood + ' - ' + city + '/' + state"></p>
                        <p class="text-stone-500" x-text="'CEP: ' + cep"></p>
                    </div>

                    <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-stone-200">
                        <span class="text-[10px] uppercase font-bold text-stone-400 block mb-1">Pagamento</span>
                        <p class="font-semibold text-brand uppercase" x-text="paymentMethod === 'pix' ? 'PIX (5% OFF Aplicado)' : (paymentMethod === 'credit_card' ? 'Cartão de Crédito' : 'Boleto Bancário')"></p>
                        <p class="text-stone-700 font-bold mt-1" x-text="'Total: R$ ' + finalTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></p>
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="goToStep(3)" class="px-5 py-2.5 rounded-full border border-stone-300 text-xs font-medium text-stone-600 hover:bg-stone-50 transition">
                        &larr; Voltar
                    </button>
                    <button type="submit" class="px-8 py-3.5 rounded-full bg-[#8B5E3C] hover:bg-[#714A2D] text-white text-xs uppercase tracking-widest font-semibold transition shadow-md flex items-center gap-2">
                        <span>Confirmar e Finalizar Pedido</span>
                    </button>
                </div>
            </div>

        </div>

        {{-- Right: Live Order Summary (4 cols) --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl border border-stone-200/80 p-6 shadow-md space-y-4 sticky top-24">
                <h3 class="font-serif text-base text-stone-900 pb-3 border-b border-stone-100 flex items-center justify-between font-normal">
                    <span>Sua Sacola</span>
                    <span class="text-xs text-brand font-medium" x-text="$store.cart.count + ' peças'"></span>
                </h3>

                {{-- Mini items preview --}}
                <div class="space-y-3 max-h-64 overflow-y-auto divide-y divide-stone-100 pr-1">
                    <template x-for="(item, idx) in $store.cart.items" :key="idx">
                        <div class="pt-2.5 first:pt-0 flex items-center gap-3">
                            <img :src="item.image" class="w-12 h-16 object-cover object-top rounded-lg border border-stone-100 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h5 class="font-serif text-xs font-semibold text-stone-800 truncate" x-text="item.title"></h5>
                                <span class="text-[10px] text-stone-400" x-text="item.quantity + 'x R$ ' + Number(item.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                            </div>
                            <span class="text-xs font-bold text-stone-900 shrink-0" x-text="'R$ ' + (item.price * item.quantity).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </template>
                </div>

                {{-- Calculations --}}
                <div class="pt-3 border-t border-stone-100 space-y-2 text-xs">
                    <div class="flex justify-between text-stone-600 font-light">
                        <span>Subtotal</span>
                        <span class="font-medium text-stone-800" x-text="'R$ ' + finalSubtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                    </div>

                    <template x-if="finalDiscount > 0">
                        <div class="flex justify-between text-emerald-700 font-medium">
                            <span>Desconto aplicado</span>
                            <span class="font-bold" x-text="'- R$ ' + finalDiscount.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </template>

                    <div class="flex justify-between text-stone-600 font-light">
                        <span>Frete</span>
                        <span class="font-bold text-emerald-700" x-text="shippingCost === 0 ? 'GRÁTIS' : 'R$ ' + shippingCost.toFixed(2)"></span>
                    </div>

                    <div class="pt-3 border-t border-stone-200 flex justify-between items-baseline">
                        <span class="font-serif text-sm font-bold text-stone-900">Total a Pagar</span>
                        <span class="text-xl font-bold text-stone-900" x-text="'R$ ' + finalTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
