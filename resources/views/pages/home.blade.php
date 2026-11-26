@extends('layouts.app')

@section('content')
<div class="space-y-16 pb-20">

    {{-- Editorial Hero Section (Hadassa Braz Concept Style) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="relative overflow-hidden rounded-3xl bg-[#1C1917] text-white shadow-2xl min-h-[520px] flex items-center">
            {{-- Background Editorial Image --}}
            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1800&q=80"
                 alt="Hadassa Concept - Nova Coleção"
                 class="absolute inset-0 w-full h-full object-cover object-center opacity-55">

            <div class="absolute inset-0 bg-gradient-to-r from-[#1C1917]/95 via-[#1C1917]/60 to-transparent"></div>

            <div class="relative z-20 px-6 sm:px-12 lg:px-16 py-16 sm:py-24 max-w-xl">
                <span class="inline-block text-[10px] tracking-[0.3em] font-medium uppercase text-[#D4AF37] mb-3">
                    Coleção Alto Verão &amp; Elegance
                </span>

                <h1 class="font-serif text-3xl sm:text-5xl lg:text-6xl font-normal tracking-tight leading-[1.1] text-white">
                    A Essência da Elegância Atemporal
                </h1>

                <p class="mt-4 text-xs sm:text-sm text-stone-300 font-light leading-relaxed max-w-md">
                    Vestidos midi com caimento nobre, alfaiataria impecável e conjuntos pensados para valorizar a modéstia e a sofisticação da mulher contemporânea.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalog.index', ['ordenacao' => 'mais_vendidos']) }}"
                       class="px-7 py-3.5 rounded-full bg-brand hover:bg-brand-hover text-white text-xs font-medium tracking-widest uppercase transition-all duration-300 transform hover:-translate-y-0.5 shadow-md">
                        Explorar Lançamentos
                    </a>
                    <a href="{{ route('catalog.category', ['categorySlug' => 'vestidos-midi']) }}"
                       class="px-7 py-3.5 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-medium tracking-widest uppercase backdrop-blur-xs transition border border-white/30">
                        Ver Vestidos Midi
                    </a>
                </div>

                {{-- Fast perks --}}
                <div class="mt-10 pt-6 border-t border-white/15 flex items-center gap-6 text-[11px] text-stone-300">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <span>1ª Troca Grátis</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <span>Em até 6x Sem Juros</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
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
                   class="group bg-white rounded-2xl p-3 border border-stone-200/80 hover:border-brand shadow-xs hover:shadow-lg transition-all text-center flex flex-col items-center">
                    <div class="w-20 h-28 sm:w-24 sm:h-32 rounded-xl overflow-hidden mb-3 bg-[#FAF8F5] group-hover:scale-105 transition-transform duration-500">
                        <img src="{{ $cat['featured_image'] }}" alt="{{ $cat['name'] }}" class="w-full h-full object-cover">
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
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-[#FAF8F5] rounded-3xl p-8 sm:p-12 border border-stone-200">
            <div class="lg:col-span-7 space-y-4">
                <span class="text-[10px] tracking-[0.3em] font-semibold uppercase text-brand">O Conceito Hadassa</span>
                <h2 class="font-serif text-3xl sm:text-4xl text-stone-900 leading-tight font-normal">
                    Modéstia &amp; Elegância: a harmonia perfeita em cada costura.
                </h2>
                <p class="text-xs sm:text-sm text-stone-600 font-light leading-relaxed">
                    Nossas criações nascem do desejo de vestir mulheres reais com decência, graça e autoridade. Cada peça passa por uma curadoria minuciosa de modelagem para garantir conforto absoluto, tecidos de caimento nobre que não marcam e acabamentos de alfaiataria fina.
                </p>
                <div class="pt-4 flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalog.category', ['categorySlug' => 'alfaiataria']) }}" class="px-6 py-3 rounded-full bg-[#1C1917] hover:bg-brand text-white text-xs font-medium tracking-widest uppercase transition">
                        Conhecer Alfaiataria &rarr;
                    </a>
                    <span class="text-xs text-stone-400 italic">"Vestir-se com virtude é expressar sua melhor essência."</span>
                </div>
            </div>
            <div class="lg:col-span-5 relative aspect-[4/3] rounded-2xl overflow-hidden shadow-lg">
                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1000&q=80"
                     alt="Conceito Hadassa"
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
            <span class="text-[10px] tracking-[0.3em] font-semibold uppercase text-brand block mb-1">#HadassaConcept</span>
            <h2 class="font-serif text-2xl sm:text-3xl text-stone-900 font-normal">Inspirações no Lookbook</h2>
            <p class="text-xs text-stone-500 mt-2 font-light">Siga nosso perfil e compartilhe seu look usando nossa hashtag oficial</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="aspect-[3/4] rounded-2xl overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=600&q=80" alt="Look" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium">Ver Peça</div>
            </div>
            <div class="aspect-[3/4] rounded-2xl overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=600&q=80" alt="Look" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium">Ver Peça</div>
            </div>
            <div class="aspect-[3/4] rounded-2xl overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=600&q=80" alt="Look" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium">Ver Peça</div>
            </div>
            <div class="aspect-[3/4] rounded-2xl overflow-hidden group relative">
                <img src="https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=600&q=80" alt="Look" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium">Ver Peça</div>
            </div>
        </div>
    </section>

</div>
@endsection
