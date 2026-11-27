@php
    $isElisieHeader = tenant('id') === 'elisie' || tenant('id') === null;
@endphp
<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-stone-200/80 shadow-xs"
        x-data="{
            searchQuery: '',
            searchResults: { products: [], categories: [] },
            searchOpen: false,
            searchLoading: false,
            categoryMenuOpen: false,

            async fetchSearch() {
                if (this.searchQuery.trim().length < 2) {
                    this.searchResults = { products: [], categories: [] };
                    this.searchOpen = false;
                    return;
                }
                this.searchLoading = true;
                this.searchOpen = true;
                try {
                    const res = await fetch(`/api/busca-preditiva?q=${encodeURIComponent(this.searchQuery.trim())}`);
                    const data = await res.json();
                    this.searchResults = data;
                } catch (e) {
                    console.error('Erro na busca preditiva', e);
                } finally {
                    this.searchLoading = false;
                }
            }
        }"
        @click.away="searchOpen = false; categoryMenuOpen = false">

    {{-- Main Bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4">
        <div class="flex items-center justify-between gap-4">

            {{-- Brand / Logo --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="group flex flex-col items-start">
                    <x-logo class="h-9 sm:h-11 w-auto" />
                </a>
            </div>

            {{-- Predictive Search Bar (Boutique Style - Sharp Edges) --}}
            <div class="flex-1 max-w-xl relative hidden md:block">
                <form action="{{ route('catalog.index') }}" method="GET" class="relative">
                    <div class="relative flex items-center">
                        <input
                            type="text"
                            name="busca"
                            x-model="searchQuery"
                            @input.debounce.250ms="fetchSearch()"
                            @focus="if(searchQuery.length >= 2) searchOpen = true"
                            placeholder="{{ $isElisieHeader ? 'Buscar vestidos midi, alfaiataria, chemises, conjuntos...' : 'Buscar produtos, marcas, categorias...' }}"
                            autocomplete="off"
                            class="w-full pl-10 pr-20 py-2.5 text-xs bg-[#FAF8F5] hover:bg-white focus:bg-white border border-stone-300/80 focus:border-brand focus:ring-1 focus:ring-stone-300 transition-all outline-none text-stone-800 placeholder-stone-400"
                        />
                        <div class="absolute left-3.5 text-stone-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <button type="submit" class="absolute right-1 px-4 py-1.5 text-[11px] font-medium tracking-wider uppercase text-white bg-brand hover:bg-brand-hover transition">
                            Buscar
                        </button>
                    </div>
                </form>

                {{-- Predictive Live Search Dropdown Panel --}}
                <div x-show="searchOpen && (searchResults.products.length > 0 || searchResults.categories.length > 0 || searchLoading)"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute top-full left-0 right-0 mt-1 bg-white shadow-xl border border-stone-200 overflow-hidden z-50 divide-y divide-stone-100">

                    {{-- Search Loading Indicator --}}
                    <div x-show="searchLoading" class="p-3 text-center text-xs text-stone-500 flex items-center justify-center gap-2">
                        <svg class="animate-spin h-3.5 w-3.5 text-brand" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        Buscando produtos em {{ $storeSetting->store_name ?? tenant('name') ?? 'nossa loja' }}...
                    </div>

                    {{-- Matching Categories --}}
                    <template x-if="searchResults.categories && searchResults.categories.length > 0">
                        <div class="p-3 bg-[#FAF8F5]">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 px-2 block mb-1">Categorias Sugeridas</span>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="cat in searchResults.categories" :key="cat.slug">
                                    <a :href="'/categoria/' + cat.slug" class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-stone-200 text-xs text-stone-700 hover:text-brand hover:border-brand transition">
                                        <span x-text="cat.name"></span>
                                        <span class="text-[10px] text-stone-400" x-text="'(' + cat.count + ')'"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Matching Products --}}
                    <template x-if="searchResults.products && searchResults.products.length > 0">
                        <div class="p-2 divide-y divide-stone-100">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 px-3 py-1 block">Modelos Disponíveis</span>
                            <template x-for="item in searchResults.products" :key="item.id">
                                <a :href="'/produto/' + item.slug" class="flex items-center gap-3 p-2.5 hover:bg-[#FAF8F5] transition group">
                                    <img :src="item.images[0]" :alt="item.title" class="w-12 h-16 object-cover object-top border border-stone-100">
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[10px] text-stone-400 uppercase tracking-wider block truncate" x-text="item.category_name"></span>
                                        <h4 class="text-xs font-serif font-medium text-stone-800 group-hover:text-brand transition truncate" x-text="item.title"></h4>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs font-bold text-stone-900" x-text="'R$ ' + Number(item.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                                            <span class="text-[10px] text-brand font-semibold">5% OFF no Pix</span>
                                        </div>
                                    </div>
                                    <div class="text-stone-300 group-hover:text-brand transition pr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>

                    {{-- Bottom Link --}}
                    <div class="p-2.5 bg-[#FAF8F5] text-center">
                        <a :href="'/catalogo?busca=' + encodeURIComponent(searchQuery)" class="text-xs font-semibold text-brand hover:underline inline-flex items-center gap-1">
                            Ver todos os resultados para "<span x-text="searchQuery"></span>" &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-4 sm:gap-6">

                {{-- Wishlist Heart --}}
                <a href="#favoritos" class="hidden sm:flex text-stone-500 hover:text-brand transition p-1" title="Favoritos">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </a>

                {{-- User Account Shortcut --}}
                <a href="#minha-conta" class="hidden sm:flex items-center gap-2 text-stone-600 hover:text-brand transition text-xs font-medium tracking-wider uppercase">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="hidden md:inline">Entrar</span>
                </a>

                {{-- Cart Trigger Button (Sacola de Compras - Sharp Edges) --}}
                <button
                    @click="$store.cart.open()"
                    type="button"
                    class="relative flex items-center gap-2 px-4 py-2.5 bg-brand hover:bg-brand-hover text-white transition duration-200 cursor-pointer group"
                    title="Minha Sacola">
                    <div class="relative">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span
                            x-show="$store.cart.count > 0"
                            x-text="$store.cart.count"
                            class="absolute -top-2 -right-2 bg-white text-brand text-[9px] font-black h-4 w-4 flex items-center justify-center border border-brand">
                        </span>
                    </div>
                    <span class="hidden md:inline text-[11px] font-medium tracking-wider uppercase">Sacola</span>
                    <span class="hidden lg:inline text-[11px] font-semibold text-rose-100" x-text="'• R$ ' + $store.cart.total.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Fashion Categories Menu Bar --}}
    <div class="border-t border-stone-100 bg-white hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center gap-8 py-3 text-xs tracking-wider uppercase font-medium text-stone-600">

            <a href="{{ route('catalog.index', ['ordenacao' => 'mais_vendidos']) }}"
               class="hover:text-brand transition text-brand font-bold flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-brand animate-pulse"></span>
                {{ $isElisieHeader ? 'Nova Coleção' : 'Novidades' }}
            </a>

            @foreach (\App\Services\EcommerceDataService::getCategories() as $cat)
                <a href="{{ route('catalog.category', ['categorySlug' => $cat['slug']]) }}"
                   class="hover:text-brand transition {{ request()->is('categoria/'.$cat['slug']) ? 'text-brand font-bold border-b-2 border-brand pb-0.5' : '' }}">
                    {{ $cat['name'] }}
                </a>
            @endforeach

            <a href="{{ route('catalog.index', ['ordenacao' => 'desconto']) }}"
               class="text-rose-700 font-bold hover:opacity-80 transition tracking-widest">
                Special Sale
            </a>
        </div>
    </div>
</header>
