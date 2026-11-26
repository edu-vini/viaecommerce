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
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Pedido Recebido com Sucesso!</h1>
        <p class="text-sm text-slate-600 max-w-md mx-auto">
            Obrigado por comprar na ViaEcommerce! O seu pedido <strong>#{{ $order['code'] }}</strong> foi registrado e está pronto para o processamento.
        </p>
    </div>

    {{-- Visual Status Timeline --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs mb-8">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6 text-center sm:text-left">
            Timeline de Acompanhamento do Pedido
        </h3>

        <div class="grid grid-cols-5 gap-2 relative">
            {{-- Horizontal line --}}
            <div class="hidden sm:block absolute top-5 left-10 right-10 h-1 bg-slate-200 -z-0">
                <div class="h-full bg-emerald-500 w-1/5"></div>
            </div>

            {{-- 1. Aguardando Pagamento --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold ring-4 ring-emerald-100 shadow-sm animate-pulse">
                    ✓
                </div>
                <span class="text-xs font-bold text-slate-900 mt-2">Aguardando Pagamento</span>
                <span class="text-[10px] text-emerald-600 font-semibold mt-0.5">Fase Atual</span>
            </div>

            {{-- 2. Pago --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-bold border-2 border-slate-300">
                    2
                </div>
                <span class="text-xs font-semibold text-slate-500 mt-2">Pagamento Aprovado</span>
            </div>

            {{-- 3. Em Separação --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-bold border-2 border-slate-300">
                    3
                </div>
                <span class="text-xs font-semibold text-slate-500 mt-2">Em Separação</span>
            </div>

            {{-- 4. Enviado --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-bold border-2 border-slate-300">
                    4
                </div>
                <span class="text-xs font-semibold text-slate-500 mt-2">Enviado / Rastreio</span>
            </div>

            {{-- 5. Entregue --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-bold border-2 border-slate-300">
                    5
                </div>
                <span class="text-xs font-semibold text-slate-500 mt-2">Entregue</span>
            </div>
        </div>
    </div>

    {{-- Pix Payment Box (if Pix selected) --}}
    @if ($order['payment_method'] === 'pix')
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl p-6 sm:p-8 border border-emerald-200/80 shadow-md mb-8">
            <div class="flex flex-col sm:flex-row items-center gap-6">

                {{-- QR Code Image Mock --}}
                <div class="bg-white p-4 rounded-2xl border border-emerald-200 shadow-sm shrink-0 flex flex-col items-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($order['pix_code']) }}"
                         alt="QR Code Pix"
                         class="w-40 h-40 object-contain">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-2">Pague com o App do Banco</span>
                </div>

                {{-- Pix Instructions & Copy Key --}}
                <div class="flex-1 space-y-4 text-center sm:text-left">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            Aguardando Pagamento Pix
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mt-2">Valor com 5% de desconto: R$ {{ number_format($order['total'], 2, ',', '.') }}</h3>
                        <p class="text-xs text-slate-600 mt-1">
                            Abra o aplicativo do seu banco, escolha a opção <strong>Pagar via Pix &gt; Ler QR Code</strong> ou cole o código abaixo.
                        </p>
                    </div>

                    {{-- Copy & Paste Box --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase">Chave Pix Copia e Cola:</label>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $order['pix_code'] }}" class="text-xs bg-white border border-emerald-300 rounded-xl px-3 py-2.5 flex-1 font-mono text-slate-700 focus:outline-none select-all truncate">
                            <button @click="copyPixCode()" type="button" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-sm shrink-0">
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
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400">Número do Pedido:</span>
                <h3 class="text-lg font-black text-slate-900">#{{ $order['code'] }}</h3>
            </div>
            <div class="text-right">
                <span class="text-xs text-slate-400">Data e Hora:</span>
                <span class="text-xs font-semibold text-slate-800 block">{{ $order['created_at'] }}</span>
            </div>
        </div>

        {{-- Items purchased --}}
        <div>
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Itens Adquiridos</h4>
            <div class="divide-y divide-slate-100">
                @foreach ($order['items'] as $it)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $it['image'] }}" class="w-12 h-12 object-cover rounded-xl border border-slate-100">
                            <div>
                                <h5 class="text-xs font-bold text-slate-900">{{ $it['title'] }}</h5>
                                <span class="text-[11px] text-slate-500">{{ $it['variation'] ?? '' }} • Quantidade: {{ $it['quantity'] }}</span>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-900">R$ {{ number_format($it['price'] * $it['quantity'], 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Shipping and payment info grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Endereço de Entrega</span>
                <p class="font-bold text-slate-800">{{ $order['customer_name'] }}</p>
                <p class="text-slate-600">{{ $order['shipping_address'] }}</p>
                <p class="text-slate-600">CEP: {{ $order['shipping_cep'] }}</p>
                <p class="text-slate-500 mt-1">Modalidade: <strong class="text-slate-800">{{ $order['shipping_method'] }}</strong></p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Resumo Financeiro</span>
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span>R$ {{ number_format($order['subtotal'], 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-emerald-600">
                    <span>Descontos:</span>
                    <span>- R$ {{ number_format($order['discount'], 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Frete:</span>
                    <span>{{ $order['shipping_cost'] == 0 ? 'GRÁTIS' : 'R$ ' . number_format($order['shipping_cost'], 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-900 font-black text-sm pt-2 border-t border-slate-200">
                    <span>Total Pago:</span>
                    <span>R$ {{ number_format($order['total'], 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pt-4 flex flex-wrap items-center justify-between gap-4">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                🖨️ Imprimir Comprovante
            </button>
            <a href="{{ route('home') }}" class="px-6 py-2.5 rounded-xl bg-brand hover:bg-brand-hover text-white text-xs font-bold transition shadow-sm">
                Voltar à Página Inicial
            </a>
        </div>
    </div>

</div>
@endsection
