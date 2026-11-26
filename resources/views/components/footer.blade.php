<footer class="bg-slate-900 text-slate-400 text-xs border-t border-slate-800 mt-20">

    {{-- Trust Features Bar --}}
    <div class="border-b border-slate-800 py-8 bg-slate-950/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-500/10 text-brand flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-white">Frete Rápido & Seguro</h5>
                    <p class="text-slate-400 text-xs">Entregas monitoradas para todo o Brasil</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-white">Pix com 5% de Desconto</h5>
                    <p class="text-slate-400 text-xs">Aprovação imediata do seu pedido</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-white">Devolução Grátis em 7 Dias</h5>
                    <p class="text-slate-400 text-xs">Satisfação garantida ou seu dinheiro de volta</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-white">Suporte Humanizado</h5>
                    <p class="text-slate-400 text-xs">Atendimento de Seg. a Sáb. das 8h às 20h</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Footer Links --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">

            {{-- Column 1: Brand Info --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-brand text-white flex items-center justify-center font-black text-lg">
                        V
                    </div>
                    <span class="text-lg font-bold text-white">Via<span class="text-brand">Ecommerce</span></span>
                </div>
                <p class="text-xs text-slate-400 max-w-sm leading-relaxed">
                    A sua plataforma de e-commerce de ponta a ponta com catálogo selecionado, preços competitivos, logística ágil e máxima segurança transacional.
                </p>

                {{-- Newsletter --}}
                <div class="pt-2">
                    <span class="block text-xs font-semibold text-white mb-2">Receba cupons e lançamentos exclusivos:</span>
                    <form onsubmit="event.preventDefault(); alert('Obrigado por se inscrever!');" class="flex gap-2 max-w-sm">
                        <input type="email" placeholder="Seu melhor e-mail..." required class="flex-1 bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-brand">
                        <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition">
                            Cadastrar
                        </button>
                    </form>
                </div>
            </div>

            {{-- Column 2: Categorias --}}
            <div>
                <h6 class="text-xs font-bold uppercase tracking-wider text-white mb-3">Departamentos</h6>
                <ul class="space-y-2">
                    @foreach (\App\Services\EcommerceDataService::getCategories() as $cat)
                        <li>
                            <a href="{{ route('catalog.category', ['categorySlug' => $cat['slug']]) }}" class="hover:text-white transition">
                                {{ $cat['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Column 3: Atendimento & Ajuda --}}
            <div>
                <h6 class="text-xs font-bold uppercase tracking-wider text-white mb-3" id="central-ajuda">Ajuda & Suporte</h6>
                <ul class="space-y-2">
                    <li><a href="{{ route('order.show', ['code' => 'DEMO123']) }}" class="hover:text-white transition">Acompanhar meu Pedido</a></li>
                    <li><a href="#" class="hover:text-white transition">Política de Trocas e Devoluções</a></li>
                    <li><a href="#" class="hover:text-white transition">Prazos e Formas de Entrega</a></li>
                    <li><a href="#" class="hover:text-white transition">Segurança e Privacidade</a></li>
                    <li><a href="#" class="hover:text-white transition">Fale Conosco / SAC</a></li>
                </ul>
            </div>

            {{-- Column 4: Pagamentos & Segurança --}}
            <div>
                <h6 class="text-xs font-bold uppercase tracking-wider text-white mb-3">Formas de Pagamento</h6>
                <div class="flex flex-wrap gap-2 text-[11px] text-slate-300">
                    <span class="px-2.5 py-1 rounded bg-slate-800 border border-slate-700 font-bold text-emerald-400">PIX (5% OFF)</span>
                    <span class="px-2.5 py-1 rounded bg-slate-800 border border-slate-700">Cartão Visa</span>
                    <span class="px-2.5 py-1 rounded bg-slate-800 border border-slate-700">Mastercard</span>
                    <span class="px-2.5 py-1 rounded bg-slate-800 border border-slate-700">Elo</span>
                    <span class="px-2.5 py-1 rounded bg-slate-800 border border-slate-700">Boleto</span>
                </div>

                <h6 class="text-xs font-bold uppercase tracking-wider text-white mt-6 mb-2">Certificados de Segurança</h6>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-[11px]">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Criptografia SSL 256-bit</span>
                </div>
            </div>

        </div>

        {{-- Bottom Copyright --}}
        <div class="border-t border-slate-800/80 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-slate-500 text-[11px]">
            <p>&copy; {{ date('Y') }} ViaEcommerce Brasil S.A. Todos os direitos reservados. CNPJ: 00.123.456/0001-99.</p>
            <p>Desenvolvido com arquitetura Laravel 11, Tailwind CSS e Docker.</p>
        </div>
    </div>
</footer>
