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
        <div class="w-14 h-14 rounded-full bg-[#FAF8F5] text-[#8B5E3C] border border-stone-200 mx-auto flex items-center justify-center shadow-xs">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="font-serif text-3xl sm:text-4xl font-normal text-stone-900 tracking-tight">Pedido Recebido com Sucesso!</h1>
        <p class="text-xs sm:text-sm text-stone-600 max-w-md mx-auto font-light leading-relaxed">
            Agradecemos a sua preferência! O seu pedido <strong>#{{ $order['code'] }}</strong> já está registrado em nosso atelier e estamos preparando tudo com muito carinho.
        </p>
    </div>

    {{-- Visual Status Timeline (Boutique Style) --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-xs mb-8">
        <h3 class="text-[11px] font-bold uppercase tracking-wider text-stone-400 mb-6 text-center sm:text-left">
            Acompanhamento do seu Pedido
        </h3>

        <div class="grid grid-cols-5 gap-2 relative">
            {{-- Horizontal line --}}
            <div class="hidden sm:block absolute top-5 left-10 right-10 h-0.5 bg-stone-200 -z-0">
                <div class="h-full bg-brand w-1/5"></div>
            </div>

            {{-- 1. Aguardando Pagamento --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 rounded-full bg-brand text-white flex items-center justify-center text-xs font-serif font-bold ring-4 ring-stone-200 shadow-sm animate-pulse">
                    ✓
                </div>
                <span class="text-xs font-bold text-stone-900 mt-2">Aguardando Pix</span>
                <span class="text-[10px] text-brand font-semibold mt-0.5">Fase Atual</span>
            </div>

            {{-- 2. Pago --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center text-xs font-serif font-bold border border-stone-300">
                    2
                </div>
                <span class="text-xs font-medium text-stone-500 mt-2">Aprovado</span>
            </div>

            {{-- 3. Em Separação --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center text-xs font-serif font-bold border border-stone-300">
                    3
                </div>
                <span class="text-xs font-medium text-stone-500 mt-2">Embalando Peça</span>
            </div>

            {{-- 4. Enviado --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center text-xs font-serif font-bold border border-stone-300">
                    4
                </div>
                <span class="text-xs font-medium text-stone-500 mt-2">Enviado / Rastreio</span>
            </div>

            {{-- 5. Entregue --}}
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-9 h-9 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center text-xs font-serif font-bold border border-stone-300">
                    5
                </div>
                <span class="text-xs font-medium text-stone-500 mt-2">Entregue</span>
            </div>
        </div>
    </div>

    {{-- Pix Payment Box (if Pix selected) --}}
    @if ($order['payment_method'] === 'pix')
        <div class="bg-[#FAF8F5] rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-md mb-8">
            <div class="flex flex-col sm:flex-row items-center gap-6">

                {{-- QR Code Image Mock --}}
                <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-xs shrink-0 flex flex-col items-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($order['pix_code']) }}"
                         alt="QR Code Pix"
                         class="w-40 h-40 object-contain">
                    <span class="text-[9px] text-stone-400 font-bold uppercase tracking-widest mt-2">Abra o App do seu Banco</span>
                </div>

                {{-- Pix Instructions & Copy Key --}}
                <div class="flex-1 space-y-4 text-center sm:text-left">
                    <div>
                        <span class="inline-block text-[10px] tracking-wider uppercase font-semibold text-brand bg-white px-3 py-1 rounded-full border border-stone-200 mb-2">
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
                            <input type="text" readonly value="{{ $order['pix_code'] }}" class="text-xs bg-white border border-stone-300 rounded-full px-4 py-2.5 flex-1 font-mono text-stone-700 focus:outline-none select-all truncate">
                            <button @click="copyPixCode()" type="button" class="px-6 py-2.5 bg-[#1C1917] hover:bg-brand text-white text-xs uppercase tracking-wider font-medium rounded-full transition flex items-center gap-1.5 shadow-xs shrink-0">
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
    <div class="bg-white rounded-3xl border border-stone-200/80 p-6 sm:p-8 shadow-xs space-y-6">
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
                            <img src="{{ $it['image'] }}" class="w-12 h-16 object-cover object-top rounded-xl border border-stone-100">
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
            <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-stone-200">
                <span class="text-[10px] uppercase font-bold text-stone-400 block mb-1">Endereço de Destino</span>
                <p class="font-semibold text-stone-900">{{ $order['customer_name'] }}</p>
                <p class="text-stone-600 font-light">{{ $order['shipping_address'] }}</p>
                <p class="text-stone-600 font-light">CEP: {{ $order['shipping_cep'] }}</p>
                <p class="text-stone-500 mt-1">Envio: <strong class="text-stone-800 font-medium">{{ $order['shipping_method'] }}</strong></p>
            </div>

            <div class="p-4 rounded-2xl bg-[#FAF8F5] border border-stone-200 space-y-1.5 font-light">
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
            <button onclick="window.print()" class="px-5 py-2.5 rounded-full border border-stone-300 text-xs uppercase tracking-wider font-medium text-stone-700 hover:bg-stone-50 transition">
                Imprimir Pedido
            </button>
            <a href="{{ route('home') }}" class="px-7 py-2.5 rounded-full bg-[#1C1917] hover:bg-brand text-white text-xs uppercase tracking-wider font-medium transition shadow-xs">
                Voltar à Loja
            </a>
        </div>
    </div>

</div>
@endsection
