@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{
        mobileFilterOpen: false,
        priceMin: '{{ $priceMin ?? '' }}',
        priceMax: '{{ $priceMax ?? '' }}'
     }">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand transition">Início</a>
        <span>/</span>
        <a href="{{ route('catalog.index') }}" class="hover:text-brand transition">Catálogo</a>
        @if ($currentCategoryObj)
            <span>/</span>
            <span class="font-bold text-slate-800">{{ $currentCategoryObj['name'] }}</span>
        @elseif ($search)
            <span>/</span>
            <span class="font-bold text-slate-800">Busca por "{{ $search }}"</span>
        @endif
    </nav>

    {{-- Title and Sorting Header Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                {{ $currentCategoryObj ? $currentCategoryObj['name'] : ($search ? 'Resultados para "' . $search . '"' : 'Todos os Produtos') }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Exibindo <strong class="text-slate-800">{{ $totalCount }}</strong> produtos encontrados
            </p>
        </div>

        {{-- Mobile Filter Trigger & Sort Dropdown --}}
        <div class="flex items-center gap-3">
            <button @click="mobileFilterOpen = !mobileFilterOpen"
                    class="lg:hidden flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-xs">
                <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                <span>Filtros</span>
            </button>

            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
                @if ($selectedCategory) <input type="hidden" name="categoria" value="{{ $selectedCategory }}"> @endif
                @if ($search) <input type="hidden" name="busca" value="{{ $search }}"> @endif
                @if ($priceMin) <input type="hidden" name="preco_min" value="{{ $priceMin }}"> @endif
                @if ($priceMax) <input type="hidden" name="preco_max" value="{{ $priceMax }}"> @endif

                <label for="ordenacao" class="text-xs text-slate-500 whitespace-nowrap font-medium hidden sm:inline">Ordenar por:</label>
                <select name="ordenacao"
                        id="ordenacao"
                        onchange="this.form.submit()"
                        class="text-xs font-semibold bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-brand shadow-xs">
                    <option value="relevancia" {{ $sort === 'relevancia' ? 'selected' : '' }}>Mais Relevantes</option>
                    <option value="menor_preco" {{ $sort === 'menor_preco' ? 'selected' : '' }}>Menor Preço</option>
                    <option value="maior_preco" {{ $sort === 'maior_preco' ? 'selected' : '' }}>Maior Preço</option>
                    <option value="mais_vendidos" {{ $sort === 'mais_vendidos' ? 'selected' : '' }}>Mais Vendidos</option>
                    <option value="desconto" {{ $sort === 'desconto' ? 'selected' : '' }}>Maior Desconto</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Main Catalog Layout: Sidebar Filters + Products Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 pt-6">

        {{-- Filters Sidebar (Desktop) --}}
        <aside class="hidden lg:block space-y-6">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-6">

                {{-- Filter Header --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filtrar Resultados</span>
                    </h3>
                    @if ($selectedCategory || $search || $priceMin || $priceMax || $sort !== 'relevancia')
                        <a href="{{ route('catalog.index') }}" class="text-[11px] font-semibold text-rose-600 hover:underline">
                            Limpar Tudo
                        </a>
                    @endif
                </div>

                {{-- Category Filter --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Categorias</h4>
                    <ul class="space-y-1.5 text-xs">
                        <li>
                            <a href="{{ route('catalog.index', array_filter(['busca' => $search, 'preco_min' => $priceMin, 'preco_max' => $priceMax, 'ordenacao' => $sort])) }}"
                               class="flex items-center justify-between p-2 rounded-lg transition {{ !$selectedCategory ? 'bg-brand text-white font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                                <span>Todas as Categorias</span>
                                <span class="text-[10px] {{ !$selectedCategory ? 'text-white/80' : 'text-slate-400' }}">Todos</span>
                            </a>
                        </li>
                        @foreach ($categories as $cat)
                            <li>
                                <a href="{{ route('catalog.category', ['categorySlug' => $cat['slug'], 'busca' => $search, 'preco_min' => $priceMin, 'preco_max' => $priceMax, 'ordenacao' => $sort]) }}"
                                   class="flex items-center justify-between p-2 rounded-lg transition {{ $selectedCategory === $cat['slug'] ? 'bg-brand text-white font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                                    <span>{{ $cat['name'] }}</span>
                                    <span class="text-[10px] {{ $selectedCategory === $cat['slug'] ? 'text-white/80' : 'text-slate-400' }}">({{ $cat['count'] }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Price Filter Form --}}
                <div class="pt-4 border-t border-slate-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Faixa de Preço (R$)</h4>
                    <form method="GET" action="{{ url()->current() }}" class="space-y-3">
                        @if ($selectedCategory) <input type="hidden" name="categoria" value="{{ $selectedCategory }}"> @endif
                        @if ($search) <input type="hidden" name="busca" value="{{ $search }}"> @endif
                        @if ($sort) <input type="hidden" name="ordenacao" value="{{ $sort }}"> @endif

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-1">Mínimo</label>
                                <input type="number" name="preco_min" value="{{ $priceMin }}" placeholder="0" class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-brand focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-1">Máximo</label>
                                <input type="number" name="preco_max" value="{{ $priceMax }}" placeholder="10000" class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 focus:border-brand focus:outline-none">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-brand text-white text-xs font-bold rounded-xl transition shadow-xs">
                            Aplicar Preço
                        </button>
                    </form>
                </div>

                {{-- Benefits Highlights --}}
                <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-600">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked disabled class="rounded text-brand focus:ring-brand">
                        <span>Frete Grátis acima de R$ 199</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked disabled class="rounded text-brand focus:ring-brand">
                        <span>5% OFF no Pix à vista</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked disabled class="rounded text-brand focus:ring-brand">
                        <span>Produtos com Garantia Nacional</span>
                    </label>
                </div>

            </div>
        </aside>

        {{-- Mobile Filter Drawer (overlay on mobile) --}}
        <div x-show="mobileFilterOpen" class="fixed inset-0 z-50 lg:hidden" style="display: none;">
            <div @click="mobileFilterOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-xs"></div>
            <div class="fixed inset-y-0 right-0 w-80 bg-white shadow-2xl p-6 overflow-y-auto space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <h3 class="text-sm font-bold text-slate-900">Filtros de Busca</h3>
                    <button @click="mobileFilterOpen = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Categories List --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Categorias</h4>
                    <ul class="space-y-1 text-xs">
                        @foreach ($categories as $cat)
                            <li>
                                <a href="{{ route('catalog.category', ['categorySlug' => $cat['slug']]) }}"
                                   class="block py-1.5 px-2 rounded-lg {{ $selectedCategory === $cat['slug'] ? 'bg-brand text-white font-bold' : 'text-slate-700' }}">
                                    {{ $cat['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <button @click="mobileFilterOpen = false" class="w-full py-2.5 bg-brand text-white rounded-xl text-xs font-bold">
                    Ver Resultados
                </button>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="lg:col-span-3">

            @if (count($products) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            @else
                {{-- Empty Search Results --}}
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Nenhum produto encontrado</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                        Não encontramos itens com os filtros ou termo de busca selecionados. Tente ajustar os termos ou remover filtros de preço.
                    </p>
                    <a href="{{ route('catalog.index') }}" class="mt-6 inline-flex items-center px-5 py-2.5 rounded-xl bg-brand text-white text-xs font-bold hover:bg-brand-hover transition shadow-sm">
                        Ver Todos os Produtos
                    </a>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
