@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     x-data="{
        copied: false,
        copyPixCode() {
            navigator.clipboard.writeText('{{ $order['pix_code'] }}');
            this.copied = true;
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message: 'Código Pix copiado com sucesso!', type: 'success' }
            }));
            setTimeout(() => this.copied = false, 3000);
        }
     }">

    {{-- Success Header Banner --}}
    <div class="text-center space-y-3 pb-8">
        <div class="w-14 h-14 bg-[#FAF8F5] text-[#8B5E3C] border border-stone-200 mx-auto flex items-center justify-center shadow-xs">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="font-serif text-3xl sm:text-4xl font-normal text-stone-900 tracking-tight">Pedido Recebido com Sucesso!</h1>
        <p class="text-xs sm:text-sm text-stone-600 max-w-md mx-auto font-light leading-relaxed">
            Agradecemos a sua preferência! O seu pedido <strong>#{{ $order['code'] }}</strong> já está registrado em nosso atelier e estamos preparando tudo com muito carinho.
        </p>
    </div>

    @php
        $statusStepMap = [
            'pending' => 1,
            'aguardando_pagamento' => 1,
            'paid' => 2,
            'pago' => 2,
            'preparing' => 3,
            'preparando' => 3,
            'shipped' => 4,
            'enviado' => 4,
            'delivered' => 5,
            'entregue' => 5,
            'cancelled' => 0,
        ];
        $currentStep = $statusStepMap[$order['status'] ?? 'pending'] ?? 1;
        $lineWidth = match($currentStep) {
            1 => '20%',
            2 => '40%',
            3 => '60%',
            4 => '80%',
            5 => '100%',
            default => '0%',
        };
    @endphp

    {{-- Visual Status Timeline (Boutique Style) --}}
    <div class="bg-white p-6 sm:p-8 border border-stone-200/80 shadow-xs mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-[11px] font-bold uppercase tracking-wider text-stone-400">
                Acompanhamento do seu Pedido
            </h3>
            <span class="text-xs font-bold px-3 py-1 uppercase tracking-wider bg-brand text-white">
                {{ $order['status_label'] ?? ucfirst($order['status']) }}
            </span>
        </div>

        <div class="grid grid-cols-5 gap-2 relative">
            {{-- Horizontal line --}}
            <div class="hidden sm:block absolute top-5 left-10 right-10 h-0.5 bg-stone-200 -z-0">
                <div class="h-full bg-brand transition-all duration-500" style="width: {{ $lineWidth }}"></div>
            </div>

            {{-- 1. Aguardando Pagamento --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 {{ $currentStep >= 1 ? 'bg-brand text-white border-brand' : 'bg-stone-100 text-stone-400 border-stone-300' }} flex items-center justify-center text-xs font-serif font-bold shadow-sm border">
                    {{ $currentStep > 1 ? '✓' : '1' }}
                </div>
                <span class="text-xs font-bold text-stone-900 mt-2">Aguardando Pix</span>
                <span class="text-[10px] {{ $currentStep === 1 ? 'text-brand font-semibold' : 'text-stone-400' }} mt-0.5">
                    {{ $currentStep === 1 ? 'Fase Atual' : 'Concluído' }}
                </span>
            </div>

            {{-- 2. Pago --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 {{ $currentStep >= 2 ? 'bg-brand text-white border-brand' : 'bg-stone-100 text-stone-400 border-stone-300' }} flex items-center justify-center text-xs font-serif font-bold border">
                    {{ $currentStep > 2 ? '✓' : '2' }}
                </div>
                <span class="text-xs {{ $currentStep >= 2 ? 'font-bold text-stone-900' : 'font-medium text-stone-500' }} mt-2">Aprovado</span>
                <span class="text-[10px] {{ $currentStep === 2 ? 'text-brand font-semibold' : 'text-stone-400' }} mt-0.5">
                    {{ $currentStep === 2 ? 'Fase Atual' : ($currentStep > 2 ? 'Concluído' : 'Pendente') }}
                </span>
            </div>

            {{-- 3. Em Separação --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 {{ $currentStep >= 3 ? 'bg-brand text-white border-brand' : 'bg-stone-100 text-stone-400 border-stone-300' }} flex items-center justify-center text-xs font-serif font-bold border">
                    {{ $currentStep > 3 ? '✓' : '3' }}
                </div>
                <span class="text-xs {{ $currentStep >= 3 ? 'font-bold text-stone-900' : 'font-medium text-stone-500' }} mt-2">Separação</span>
                <span class="text-[10px] {{ $currentStep === 3 ? 'text-brand font-semibold' : 'text-stone-400' }} mt-0.5">
                    {{ $currentStep === 3 ? 'Fase Atual' : ($currentStep > 3 ? 'Concluído' : 'Pendente') }}
                </span>
            </div>

            {{-- 4. Enviado --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 {{ $currentStep >= 4 ? 'bg-brand text-white border-brand' : 'bg-stone-100 text-stone-400 border-stone-300' }} flex items-center justify-center text-xs font-serif font-bold border">
                    {{ $currentStep > 4 ? '✓' : '4' }}
                </div>
                <span class="text-xs {{ $currentStep >= 4 ? 'font-bold text-stone-900' : 'font-medium text-stone-500' }} mt-2">Enviado</span>
                <span class="text-[10px] {{ $currentStep === 4 ? 'text-brand font-semibold' : 'text-stone-400' }} mt-0.5">
                    {{ $currentStep === 4 ? 'Fase Atual' : ($currentStep > 4 ? 'Concluído' : 'Pendente') }}
                </span>
            </div>

            {{-- 5. Entregue --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 {{ $currentStep >= 5 ? 'bg-brand text-white border-brand' : 'bg-stone-100 text-stone-400 border-stone-300' }} flex items-center justify-center text-xs font-serif font-bold border">
                    {{ $currentStep >= 5 ? '✓' : '5' }}
                </div>
                <span class="text-xs {{ $currentStep >= 5 ? 'font-bold text-stone-900' : 'font-medium text-stone-500' }} mt-2">Entregue</span>
                <span class="text-[10px] {{ $currentStep === 5 ? 'text-brand font-semibold' : 'text-stone-400' }} mt-0.5">
                    {{ $currentStep === 5 ? 'Finalizado' : 'Pendente' }}
                </span>
            </div>
        </div>

        {{-- Merchant Status Notes & Tracking Alert --}}
        @if (!empty($order['status_notes']) || !empty($order['tracking_code']))
            <div class="mt-8 p-4 bg-[#FAF8F5] border-l-4 border-brand text-xs text-stone-700">
                <div class="flex items-center gap-2 font-bold text-stone-900 uppercase tracking-wider mb-1">
                    <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Mensagem do Lojista ao Comprador {{ !empty($order['status_updated_at']) ? '• Atualizado em ' . $order['status_updated_at'] : '' }}
                </div>
                @if (!empty($order['status_notes']))
                    <p class="leading-relaxed mt-1 text-stone-600">{{ $order['status_notes'] }}</p>
                @endif
                @if (!empty($order['tracking_code']))
                    <div class="mt-2.5 flex items-center gap-2">
                        <span class="font-bold text-stone-800">Código de Rastreamento Oficial:</span>
                        <span class="font-mono bg-white px-2.5 py-1 border border-stone-300 text-brand font-bold">{{ $order['tracking_code'] }}</span>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Pix Payment Box (if Pix selected) --}}
    @if ($order['payment_method'] === 'pix')
        <div class="bg-[#FAF8F5] p-6 sm:p-8 border border-stone-200/80 shadow-md mb-8">
            <div class="flex flex-col sm:flex-row items-center gap-6">

                {{-- QR Code Image Mock --}}
                <div class="bg-white p-4 border border-stone-200 shadow-xs shrink-0 flex flex-col items-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($order['pix_code']) }}"
                         alt="QR Code Pix"
                         class="w-40 h-40 object-contain">
                    <span class="text-[9px] text-stone-400 font-bold uppercase tracking-widest mt-2">Abra o App do seu Banco</span>
                </div>

                {{-- Pix Instructions & Copy Key --}}
                <div class="flex-1 space-y-4 text-center sm:text-left">
                    <div>
                        <span class="inline-block text-[10px] tracking-wider uppercase font-semibold text-brand bg-white px-3 py-1 border border-stone-200 mb-2">
                            Pagamento Instantâneo
                        </span>
                        <h3 class="font-serif text-2xl text-stone-900 font-normal">Valor com 5% de desconto: R$ {{ number_format($order['total'], 2, ',', '.') }}</h3>
                        <p class="text-xs text-stone-600 mt-1 font-light leading-relaxed">
                            No app do seu banco, escolha <strong>Pix &gt; Ler QR Code</strong> ou use a opção <strong>Pix Copia e Cola</strong> com a chave abaixo:
                        </p>
                    </div>

                    {{-- Copy & Paste Box --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-stone-700 uppercase tracking-wider">Chave Pix Copia e Cola:</label>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $order['pix_code'] }}" class="text-xs bg-white border border-stone-300 px-4 py-2.5 flex-1 font-mono text-stone-700 focus:outline-none select-all truncate">
                            <button @click="copyPixCode()" type="button" class="px-6 py-2.5 bg-[#1C1917] hover:bg-brand text-white text-xs uppercase tracking-wider font-medium transition flex items-center gap-1.5 shadow-xs shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span x-text="copied ? 'Copiado!' : 'Copiar Código'"></span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif

    {{-- Order Breakdown Details --}}
    <div class="bg-white border border-stone-200/80 p-6 sm:p-8 shadow-xs space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-stone-100">
            <div>
                <span class="text-[11px] text-stone-400 uppercase tracking-wider">Identificador do Pedido:</span>
                <h3 class="font-serif text-xl font-normal text-stone-900">#{{ $order['code'] }}</h3>
            </div>
            <div class="text-right">
                <span class="text-[11px] text-stone-400 uppercase tracking-wider">Data da Compra:</span>
                <span class="text-xs font-medium text-stone-800 block">{{ $order['created_at'] }}</span>
            </div>
        </div>

        {{-- Items purchased --}}
        <div>
            <h4 class="text-[11px] font-bold uppercase tracking-wider text-stone-400 mb-3">Peças Selecionadas</h4>
            <div class="divide-y divide-stone-100">
                @foreach ($order['items'] as $it)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $it['image'] }}" class="w-12 h-16 object-cover object-top border border-stone-100">
                            <div>
                                <h5 class="font-serif text-xs font-semibold text-stone-900">{{ $it['title'] }}</h5>
                                <span class="text-[11px] text-stone-500 font-light">{{ $it['variation'] ?? '' }} • Quantidade: {{ $it['quantity'] }}</span>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-stone-900">R$ {{ number_format($it['price'] * $it['quantity'], 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Shipping and payment info grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-stone-100 text-xs">
            <div class="p-4 bg-[#FAF8F5] border border-stone-200">
                <span class="text-[10px] uppercase font-bold text-stone-400 block mb-1">Endereço de Destino</span>
                <p class="font-semibold text-stone-900">{{ $order['customer_name'] }}</p>
                <p class="text-stone-600 font-light">{{ $order['shipping_address'] }}</p>
                <p class="text-stone-600 font-light">CEP: {{ $order['shipping_cep'] }}</p>
                <p class="text-stone-500 mt-1">Envio: <strong class="text-stone-800 font-medium">{{ $order['shipping_method'] }}</strong></p>
            </div>

            <div class="p-4 bg-[#FAF8F5] border border-stone-200 space-y-1.5 font-light">
                <span class="text-[10px] uppercase font-bold text-stone-400 block mb-1">Resumo Financeiro</span>
                <div class="flex justify-between text-stone-600">
                    <span>Subtotal das peças:</span>
                    <span>R$ {{ number_format($order['subtotal'], 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-emerald-800">
                    <span>Desconto:</span>
                    <span>- R$ {{ number_format($order['discount'], 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-stone-600">
                    <span>Frete:</span>
                    <span>{{ $order['shipping_cost'] == 0 ? 'GRÁTIS' : 'R$ ' . number_format($order['shipping_cost'], 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-stone-900 font-bold text-sm pt-2 border-t border-stone-200 font-serif">
                    <span>Total Final:</span>
                    <span>R$ {{ number_format($order['total'], 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pt-4 flex flex-wrap items-center justify-between gap-4">
            <button onclick="window.print()" class="px-5 py-2.5 border border-stone-300 text-xs uppercase tracking-wider font-medium text-stone-700 hover:bg-stone-50 transition">
                Imprimir Pedido
            </button>
            <a href="{{ route('home') }}" class="px-7 py-2.5 bg-[#1C1917] hover:bg-brand text-white text-xs uppercase tracking-wider font-medium transition shadow-xs">
                Voltar à Loja
            </a>
        </div>
    </div>

</div>
@endsection
