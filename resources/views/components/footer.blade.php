<footer class="bg-[#1C1917] text-stone-400 text-xs border-t border-stone-800 mt-20 font-light">

    {{-- Trust Features Bar (Elisie Style) --}}
    <div class="border-b border-stone-800 py-10 bg-[#141211]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 bg-white/5 text-[#D4AF37] flex items-center justify-center shrink-0 border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <h5 class="text-xs font-semibold uppercase tracking-wider text-white">Frete Grátis</h5>
                    <p class="text-stone-400 text-[11px]">Nas compras acima de R$ 399 para todo o Brasil</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 bg-white/5 text-[#D4AF37] flex items-center justify-center shrink-0 border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <h5 class="text-xs font-semibold uppercase tracking-wider text-white">1ª Troca Grátis</h5>
                    <p class="text-stone-400 text-[11px]">Sem custos e sem burocracia em até 30 dias</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 bg-white/5 text-[#D4AF37] flex items-center justify-center shrink-0 border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h5 class="text-xs font-semibold uppercase tracking-wider text-white">5% OFF no Pix</h5>
                    <p class="text-stone-400 text-[11px]">Ou parcele em até 6x sem juros no cartão</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 bg-white/5 text-[#D4AF37] flex items-center justify-center shrink-0 border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div>
                    <h5 class="text-xs font-semibold uppercase tracking-wider text-white">Atendimento VIP</h5>
                    <p class="text-stone-400 text-[11px]">Consultoria e suporte humanizado via WhatsApp</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Footer Links --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">

            {{-- Column 1: Brand Info --}}
            <div class="lg:col-span-2 space-y-4">
                <div>
                    <span class="font-script text-4xl text-white block leading-none">Elisie</span>
                    <span class="text-[9px] tracking-[0.25em] text-rose-300 font-semibold uppercase block mt-1">Moderna • Elegante • Modesta</span>
                </div>
                <p class="text-xs text-stone-400 max-w-sm leading-relaxed">
                    Marca brasileira dedicada a exaltar a beleza, a virtude e a identidade da mulher contemporânea através de peças sofisticadas, vestidos midi nobres e acabamentos artesanais de alfaiataria.
                </p>

                {{-- VIP Club Newsletter --}}
                <div class="pt-2">
                    <span class="block text-xs font-semibold text-white mb-2 uppercase tracking-wider">Lista VIP Elisie (Novidades &amp; Cupons):</span>
                    <form onsubmit="event.preventDefault(); alert('Obrigada por se cadastrar na lista VIP Elisie!');" class="flex gap-2 max-w-sm">
                        <input type="email" placeholder="Seu melhor e-mail..." required class="flex-1 bg-stone-900 border border-stone-800 px-4 py-2 text-xs text-white placeholder-stone-500 focus:outline-none focus:border-brand">
                        <button type="submit" class="px-5 py-2 bg-brand hover:bg-brand-hover text-white text-xs uppercase tracking-wider font-semibold transition">
                            Cadastrar
                        </button>
                    </form>
                </div>
            </div>

            {{-- Column 2: Categorias --}}
            <div>
                <h6 class="text-xs font-semibold uppercase tracking-wider text-white mb-4">Coleção</h6>
                <ul class="space-y-2.5">
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
                <h6 class="text-xs font-semibold uppercase tracking-wider text-white mb-4" id="ajuda">Atendimento</h6>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('order.show', ['code' => 'EL-8291']) }}" class="hover:text-white transition">Acompanhar meu Pedido</a></li>
                    <li><a href="#" class="hover:text-white transition">Guia de Tamanhos &amp; Medidas</a></li>
                    <li><a href="#" class="hover:text-white transition">Política de Trocas e Devoluções</a></li>
                    <li><a href="#" class="hover:text-white transition">Cuidados com suas Peças</a></li>
                    <li><a href="#" class="hover:text-white transition">Fale com uma Consultora</a></li>
                </ul>
            </div>

            {{-- Column 4: Pagamentos & Segurança --}}
            <div>
                <h6 class="text-xs font-semibold uppercase tracking-wider text-white mb-4">Pagamento Seguro</h6>
                <div class="flex flex-wrap gap-2 text-[11px] text-stone-300">
                    <span class="px-2.5 py-1 bg-stone-900 border border-stone-800 text-[#D4AF37] font-semibold">PIX (5% OFF)</span>
                    <span class="px-2.5 py-1 bg-stone-900 border border-stone-800">Visa</span>
                    <span class="px-2.5 py-1 bg-stone-900 border border-stone-800">Mastercard</span>
                    <span class="px-2.5 py-1 bg-stone-900 border border-stone-800">Elo</span>
                    <span class="px-2.5 py-1 bg-stone-900 border border-stone-800">Boleto</span>
                </div>

                <h6 class="text-xs font-semibold uppercase tracking-wider text-white mt-6 mb-2">Certificado de Segurança</h6>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-stone-900 border border-stone-800 text-stone-300 text-[11px]">
                    <span class="w-2 h-2 bg-emerald-500"></span>
                    <span>Ambiente Criptografado SSL 256-bit</span>
                </div>
            </div>

        </div>

        {{-- Bottom Copyright --}}
        <div class="border-t border-stone-800/80 mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-stone-500 text-[11px]">
            <p>&copy; {{ date('Y') }} Elisie Moda Feminina. Todos os direitos reservados. CNPJ: 00.123.456/0001-99.</p>
            <p>Plataforma Multi-Empresa ViaEcommerce com Laravel 11, Tailwind CSS e Docker.</p>
        </div>
    </div>
</footer>
