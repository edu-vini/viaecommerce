@extends('layouts.app')

@section('content')
<div class="space-y-12 pb-16">

    {{-- Hero Promotional Banner Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-2xl border border-slate-800">
            {{-- Background Gradient & Pattern --}}
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-transparent z-10"></div>
            <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1600&q=80"
                 alt="ViaEcommerce Ofertas Especiais"
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-40 mix-blend-overlay">

            <div class="relative z-20 px-6 sm:px-12 lg:px-16 py-14 sm:py-20 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/20 border border-brand/40 text-blue-300 text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-brand animate-ping"></span>
                    Semana de Tecnologia & Estilo
                </div>

                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight sm:leading-none text-white">
                    Tudo o que você ama com <span class="text-brand">até 40% OFF</span>
                </h1>

                <p class="mt-4 text-sm sm:text-base text-slate-300 leading-relaxed max-w-lg">
                    Aproveite as maiores ofertas em Smart TVs 4K, smartphones de última geração, notebooks gamers e calçados de alta performance. Parcele em até 12x sem juros ou ganhe 5% extra no Pix!
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalog.index', ['ordenacao' => 'desconto']) }}"
                       class="px-6 py-3.5 rounded-xl bg-brand hover:bg-brand-hover text-white font-bold text-sm transition-all transform hover:-translate-y-0.5 shadow-lg shadow-blue-500/25 flex items-center gap-2">
                        <span>Aproveitar Ofertas Agora</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('catalog.index') }}"
                       class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm backdrop-blur-xs transition border border-white/20">
                        Ver Catálogo Completo
                    </a>
                </div>

                {{-- Fast perks --}}
                <div class="mt-8 pt-6 border-t border-slate-800 flex items-center gap-6 text-xs text-slate-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Frete Grátis > R$ 199</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Até 12x Sem Juros</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Category Visual Grid / Cards --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Compre por Categoria</h2>
                <p class="text-xs sm:text-sm text-slate-500">Explore os principais departamentos selecionados</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-brand hover:underline flex items-center gap-1">
                Ver Todas &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach ($categories as $cat)
                <a href="{{ route('catalog.category', ['categorySlug' => $cat['slug']]) }}"
                   class="group bg-white rounded-2xl p-3 border border-slate-200/80 hover:border-brand shadow-xs hover:shadow-lg transition-all text-center flex flex-col items-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden mb-3 bg-slate-100 group-hover:scale-105 transition-transform duration-300">
                        <img src="{{ $cat['featured_image'] }}" alt="{{ $cat['name'] }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 group-hover:text-brand transition line-clamp-1">
                        {{ $cat['name'] }}
                    </h3>
                    <span class="text-[10px] text-slate-400 mt-0.5">{{ $cat['count'] }} produtos</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Lightning Deals Section (Ofertas Relâmpago com Contagem Regressiva) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-rose-900 via-rose-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/30 text-rose-300 font-bold text-xs uppercase mb-2">
                    <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                    Ofertas Relâmpago do Dia
                </div>
                <h2 class="text-2xl sm:text-3xl font-black">Preços imperdíveis por tempo limitado</h2>
                <p class="text-xs sm:text-sm text-rose-200/80 mt-1">Descontos que expiram ao fim da contagem. Não deixe para depois!</p>
            </div>

            {{-- Countdown Timer --}}
            <div class="flex items-center gap-2 sm:gap-3 bg-black/40 backdrop-blur-md p-3 rounded-2xl border border-white/10"
                 x-data="{
                    hours: 7,
                    minutes: 42,
                    seconds: 18,
                    init() {
                        setInterval(() => {
                            if (this.seconds > 0) {
                                this.seconds--;
                            } else {
                                this.seconds = 59;
                                if (this.minutes > 0) {
                                    this.minutes--;
                                } else {
                                    this.minutes = 59;
                                    if (this.hours > 0) this.hours--;
                                }
                            }
                        }, 1000);
                    }
                 }">
                <div class="text-center px-2">
                    <span class="block text-xl sm:text-2xl font-black text-rose-400 font-mono" x-text="String(hours).padStart(2, '0')"></span>
                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold">Horas</span>
                </div>
                <span class="text-xl font-bold text-rose-400">:</span>
                <div class="text-center px-2">
                    <span class="block text-xl sm:text-2xl font-black text-rose-400 font-mono" x-text="String(minutes).padStart(2, '0')"></span>
                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold">Min</span>
                </div>
                <span class="text-xl font-bold text-rose-400">:</span>
                <div class="text-center px-2">
                    <span class="block text-xl sm:text-2xl font-black text-rose-400 font-mono" x-text="String(seconds).padStart(2, '0')"></span>
                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold">Seg</span>
                </div>
            </div>
        </div>

        {{-- Deals Carousel / Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($deals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    {{-- Best Sellers Carousel / Section (Mais Vendidos) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Mais Vendidos da Semana</h2>
                <p class="text-xs sm:text-sm text-slate-500">Os produtos mais bem avaliados pelos nossos clientes</p>
            </div>
            <a href="{{ route('catalog.index', ['ordenacao' => 'mais_vendidos']) }}" class="text-xs font-bold text-brand hover:underline flex items-center gap-1">
                Ver Todos &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($bestSellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    {{-- Interactive Promotional Split Banner --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-900 to-indigo-950 text-white p-8 sm:p-10 flex flex-col justify-between shadow-lg">
                <div class="relative z-10 max-w-sm">
                    <span class="inline-block px-3 py-1 rounded-full bg-blue-500/30 text-blue-300 text-[11px] font-bold uppercase tracking-wider mb-2">Linha Gamer Pro</span>
                    <h3 class="text-2xl sm:text-3xl font-black leading-tight">Notebooks & Monitores UltraWide</h3>
                    <p class="text-xs sm:text-sm text-blue-200 mt-2">Taxas de 165Hz e placas de vídeo RTX para quem não aceita perder nenhum frame.</p>
                </div>
                <div class="relative z-10 mt-6">
                    <a href="{{ route('catalog.category', ['categorySlug' => 'informatica']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-slate-900 font-bold text-xs hover:bg-blue-50 transition shadow-sm">
                        Conferir Linha Gamer &rarr;
                    </a>
                </div>
                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=600&q=80" alt="Gamer" class="absolute -right-10 -bottom-10 w-64 h-64 object-cover rounded-full opacity-30 pointer-events-none">
            </div>

            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-900 to-teal-950 text-white p-8 sm:p-10 flex flex-col justify-between shadow-lg">
                <div class="relative z-10 max-w-sm">
                    <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/30 text-emerald-300 text-[11px] font-bold uppercase tracking-wider mb-2">Casa & Conforto</span>
                    <h3 class="text-2xl sm:text-3xl font-black leading-tight">Cafeteiras Italianas & Smart Home</h3>
                    <p class="text-xs sm:text-sm text-emerald-200 mt-2">Transforme seu dia a dia com aroma de café fresco em grãos moídos na hora.</p>
                </div>
                <div class="relative z-10 mt-6">
                    <a href="{{ route('catalog.category', ['categorySlug' => 'casa-inteligente']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-slate-900 font-bold text-xs hover:bg-emerald-50 transition shadow-sm">
                        Conferir Eletrodomésticos &rarr;
                    </a>
                </div>
                <img src="https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?auto=format&fit=crop&w=600&q=80" alt="Cafeteira" class="absolute -right-10 -bottom-10 w-64 h-64 object-cover rounded-full opacity-30 pointer-events-none">
            </div>
        </div>
    </section>

    {{-- New Arrivals Carousel / Section (Lançamentos) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Lançamentos Recentes</h2>
                <p class="text-xs sm:text-sm text-slate-500">As últimas novidades do mercado adicionadas ao catálogo</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-brand hover:underline flex items-center gap-1">
                Ver Todos &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($newArrivals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

</div>
@endsection
