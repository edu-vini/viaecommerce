@extends('layouts.app')

@section('content')
<div class="space-y-16 pb-20">

    {{-- Editorial Hero Section (Elisie Style - Architectural Sharp Edges) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="relative overflow-hidden bg-[#720403] text-white shadow-2xl min-h-[520px] flex items-center">
            {{-- Background Editorial Image --}}
            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1200&q=75"
                 alt="Elisie Moda Feminina"
                 fetchpriority="high"
                 decoding="async"
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-40 mix-blend-overlay">

            <div class="absolute inset-0 bg-gradient-to-r from-[#720403]/95 via-[#720403]/75 to-transparent"></div>

            <div class="relative z-20 px-6 sm:px-12 lg:px-16 py-16 sm:py-24 max-w-xl">
                <div class="mb-4">
                    <span class="font-script text-5xl sm:text-6xl text-white block leading-none">
                        Elisie
                    </span>
                    <span class="text-[10px] tracking-[0.35em] uppercase text-rose-200/90 font-medium block mt-1">
                        Moderna • Elegante • Modesta
                    </span>
                </div>

                <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-normal tracking-tight leading-[1.15] text-white mt-4">
                    A Essência da Mulher Virtuosa e Contemporânea
                </h1>

                <p class="mt-4 text-xs sm:text-sm text-rose-100/90 font-light leading-relaxed max-w-md">
                    Vestidos midi com caimento nobre, alfaiataria fina e modelagens atemporais desenhadas para exaltar sua beleza com discrição, sofisticação e decência.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalog.index', ['ordenacao' => 'mais_vendidos']) }}"
                       class="px-8 py-3.5 bg-white hover:bg-stone-100 text-[#720403] text-xs font-semibold tracking-widest uppercase transition-all duration-300 shadow-md">
                        Explorar Lançamentos
                    </a>
                    <a href="{{ route('catalog.category', ['categorySlug' => 'vestidos-midi']) }}"
                       class="px-7 py-3.5 bg-[#720403]/80 hover:bg-[#720403] text-white text-xs font-medium tracking-widest uppercase backdrop-blur-xs transition border border-white/30">
                        Vestidos Midi
                    </a>
                </div>

                {{-- Fast perks --}}
                <div class="mt-10 pt-6 border-t border-white/20 flex items-center gap-6 text-[11px] text-rose-100">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-[#D4AF37]"></span>
                        <span>1ª Troca Grátis</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-[#D4AF37]"></span>
                        <span>Em até 6x Sem Juros</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-[#D4AF37]"></span>
                        <span>5% OFF no Pix</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Category Visual Department Grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-[10px] tracking-[0.3em] font-medium uppercase text-stone-400 block mb-1">Departamentos Selecionados</span>
            <h2 class="font-serif text-2xl sm:text-3xl text-stone-900 tracking-tight">Compre por Categoria</h2>
            <div class="w-12 h-0.5 bg-brand mx-auto mt-3"></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-5">
            @foreach ($categories as $cat)
                <a href="{{ route('catalog.category', ['categorySlug' => $cat['slug']]) }}"
                   class="group bg-white p-3 border border-stone-200/80 hover:border-brand shadow-xs hover:shadow-lg transition-all text-center flex flex-col items-center">
                    <div class="w-20 h-28 sm:w-24 sm:h-32 overflow-hidden mb-3 bg-[#FAF8F5] group-hover:scale-105 transition-transform duration-500">
                        <img src="{{ $cat['featured_image'] }}" alt="{{ $cat['name'] }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-serif text-xs font-semibold text-stone-800 group-hover:text-brand transition line-clamp-1">
                        {{ $cat['name'] }}
                    </h3>
                    <span class="text-[10px] text-stone-400 mt-0.5 tracking-wider uppercase">{{ $cat['count'] }} modelos</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Highlight / Nova Coleção Grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-baseline justify-between mb-8 pb-4 border-b border-stone-200">
            <div>
                <span class="text-[10px] tracking-[0.25em] font-medium uppercase text-brand block mb-1">Destaques da Temporada</span>
                <h2 class="font-serif text-2xl sm:text-3xl text-stone-900 font-normal">Lançamentos Exclusivos</h2>
            </div>
            <a href="{{ route('catalog.index', ['ordenacao' => 'mais_vendidos']) }}" class="text-xs tracking-wider uppercase font-semibold text-stone-600 hover:text-brand transition flex items-center gap-1 mt-2 sm:mt-0">
                Ver Toda a Coleção &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($newArrivals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    {{-- Concept Editorial Split Banner (Brand Philosophy) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-[#FAF8F5] p-8 sm:p-12 border border-stone-200">
            <div class="lg:col-span-7 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="font-script text-3xl text-brand">Elisie</span>
                    <span class="text-stone-300">|</span>
                    <span class="text-[10px] tracking-[0.3em] font-semibold uppercase text-stone-500">Conceito &amp; Propósito</span>
                </div>
                <h2 class="font-serif text-3xl sm:text-4xl text-stone-900 leading-tight font-normal">
                    Moderna. Elegante. Modesta.
                </h2>
                <p class="text-xs sm:text-sm text-stone-600 font-light leading-relaxed">
                    A Elisie foi pensada para mulheres que não abrem mão de estarem bem vestidas em qualquer ocasião, unindo recato e bom gosto. Nossas peças priorizam tecidos de altíssima qualidade, forros confortáveis, caimentos impecáveis e modelagens que valorizam a postura e a feminilidade com delicadeza.
                </p>
                <div class="pt-4 flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalog.category', ['categorySlug' => 'alfaiataria']) }}" class="px-7 py-3 bg-brand hover:bg-brand-hover text-white text-xs font-medium tracking-widest uppercase transition shadow-sm">
                        Conhecer Alfaiataria &rarr;
                    </a>
                    <span class="text-xs text-stone-400 italic">"A verdadeira elegância está na sutileza dos detalhes."</span>
                </div>
            </div>
            <div class="lg:col-span-5 relative aspect-[4/3] overflow-hidden shadow-lg border border-stone-200">
                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=75"
                     alt="Conceito Elisie"
                     loading="lazy"
                     decoding="async"
                     class="w-full h-full object-cover">
            </div>
        </div>
    </section>

    {{-- Best Sellers (Os Mais Desejados) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-baseline justify-between mb-8 pb-4 border-b border-stone-200">
            <div>
                <span class="text-[10px] tracking-[0.25em] font-medium uppercase text-brand block mb-1">Favoritos das Clientes</span>
                <h2 class="font-serif text-2xl sm:text-3xl text-stone-900 font-normal">Os Mais Desejados</h2>
            </div>
            <a href="{{ route('catalog.index', ['ordenacao' => 'mais_vendidos']) }}" class="text-xs tracking-wider uppercase font-semibold text-stone-600 hover:text-brand transition flex items-center gap-1 mt-2 sm:mt-0">
                Ver Mais Desejados &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($bestSellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    {{-- Instagram Lookbook Showcase --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-md mx-auto mb-8">
            <span class="font-script text-2xl text-brand block">#EuUsoElisie</span>
            <h2 class="font-serif text-2xl sm:text-3xl text-stone-900 font-normal">Inspirações do Lookbook</h2>
            <p class="text-xs text-stone-500 mt-2 font-light">Siga nosso perfil e compartilhe seu momento elegante com a hashtag oficial</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="aspect-[3/4] overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=500&q=75" alt="Look Elisie" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-[#720403]/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs uppercase font-medium tracking-wider">Ver Peça</div>
            </div>
            <div class="aspect-[3/4] overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=500&q=75" alt="Look Elisie" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-[#720403]/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs uppercase font-medium tracking-wider">Ver Peça</div>
            </div>
            <div class="aspect-[3/4] overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=500&q=75" alt="Look Elisie" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-[#720403]/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs uppercase font-medium tracking-wider">Ver Peça</div>
            </div>
            <div class="aspect-[3/4] overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=500&q=75" alt="Look Elisie" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-[#720403]/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs uppercase font-medium tracking-wider">Ver Peça</div>
            </div>
        </div>
    </section>

</div>
@endsection
