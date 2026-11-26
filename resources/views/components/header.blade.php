<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs"
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <div class="flex items-center justify-between gap-4 md:gap-8">

            {{-- Brand / Logo --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center font-black text-xl shadow-md group-hover:scale-105 transition-transform duration-300">
                        V
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-900 flex items-center">
                            Via<span class="text-brand">Ecommerce</span>
                        </span>
                        <span class="block text-[10px] tracking-widest text-slate-400 font-semibold uppercase">Loja Oficial</span>
                    </div>
                </a>
            </div>

            {{-- Predictive Search Bar --}}
            <div class="flex-1 max-w-2xl relative">
                <form action="{{ route('catalog.index') }}" method="GET" class="relative">
                    <div class="relative flex items-center">
                        <input
                            type="text"
                            name="busca"
                            x-model="searchQuery"
                            @input.debounce.250ms="fetchSearch()"
                            @focus="if(searchQuery.length >= 2) searchOpen = true"
                            placeholder="Buscar por Smart TV, fones, tênis, notebook gamer..."
                            autocomplete="off"
                            class="w-full pl-11 pr-24 py-2.5 text-sm rounded-full bg-slate-100 hover:bg-slate-50 focus:bg-white border border-slate-300/80 focus:border-brand focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                        />
                        <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <button type="submit" class="absolute right-1.5 px-4 py-1.5 text-xs font-semibold text-white bg-brand hover:bg-brand-hover rounded-full transition shadow-xs">
                            Buscar
                        </button>
                    </div>
                </form>

                {{-- Predictive Live Search Dropdown Panel --}}
                <div x-show="searchOpen && (searchResults.products.length > 0 || searchResults.categories.length > 0 || searchLoading)"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 divide-y divide-slate-100">

                    {{-- Search Loading Indicator --}}
                    <div x-show="searchLoading" class="p-3 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        Buscando produtos em tempo real...
                    </div>

                    {{-- Matching Categories --}}
                    <template x-if="searchResults.categories && searchResults.categories.length > 0">
                        <div class="p-3 bg-slate-50/70">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-2 block mb-1.5">Categorias Sugeridas</span>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="cat in searchResults.categories" :key="cat.slug">
                                    <a :href="'/categoria/' + cat.slug" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white border border-slate-200 text-xs font-medium text-slate-700 hover:text-brand hover:border-brand transition">
                                        <span x-text="cat.name"></span>
                                        <span class="text-[10px] text-slate-400" x-text="'(' + cat.count + ')'"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Matching Products --}}
                    <template x-if="searchResults.products && searchResults.products.length > 0">
                        <div class="p-2 divide-y divide-slate-100">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 py-1 block">Produtos Encontrados</span>
                            <template x-for="item in searchResults.products" :key="item.id">
                                <a :href="'/produto/' + item.slug" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition group">
                                    <img :src="item.images[0]" :alt="item.title" class="w-12 h-12 object-cover rounded-lg border border-slate-100">
                                    <div class="flex-1 min-w-0">
                                        <span class="text-xs text-slate-500 block truncate" x-text="item.category_name"></span>
                                        <h4 class="text-sm font-semibold text-slate-800 group-hover:text-brand transition truncate" x-text="item.title"></h4>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs font-bold text-slate-900" x-text="'R$ ' + Number(item.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                                            <span class="text-[10px] text-emerald-600 font-medium bg-emerald-50 px-1.5 rounded">Em estoque</span>
                                        </div>
                                    </div>
                                    <div class="text-slate-300 group-hover:text-brand transition pr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>

                    {{-- Bottom Link --}}
                    <div class="p-2.5 bg-slate-50 text-center">
                        <a :href="'/catalogo?busca=' + encodeURIComponent(searchQuery)" class="text-xs font-semibold text-brand hover:underline inline-flex items-center gap-1">
                            Ver todos os resultados para "<span x-text="searchQuery"></span>" &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-3 sm:gap-5">

                {{-- User Account Shortcut --}}
                <div class="hidden sm:flex items-center gap-2.5 text-left cursor-pointer group">
                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 group-hover:bg-brand-light group-hover:text-brand transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="hidden md:block">
                        <span class="text-[11px] text-slate-400 block leading-tight">Olá, Visitante</span>
                        <span class="text-xs font-bold text-slate-700 group-hover:text-brand transition">Minha Conta</span>
                    </div>
                </div>

                {{-- Cart Trigger Button --}}
                <button
                    @click="$store.cart.open()"
                    type="button"
                    class="relative flex items-center gap-2.5 px-3 sm:px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white transition shadow-sm cursor-pointer group"
                    title="Abrir Carrinho">
                    <div class="relative">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span
                            x-show="$store.cart.count > 0"
                            x-text="$store.cart.count"
                            class="absolute -top-2 -right-2 bg-brand text-white text-[10px] font-black rounded-full h-4 w-4 flex items-center justify-center border-2 border-white ring-1 ring-black/10">
                        </span>
                    </div>
                    <div class="hidden lg:block text-left text-xs leading-tight">
                        <span class="text-slate-400 text-[10px] block">Meu Carrinho</span>
                        <span class="font-bold text-white" x-text="'R$ ' + $store.cart.total.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    {{-- Categories & Sub-Navigation Bar --}}
    <div class="border-t border-slate-100 bg-white shadow-xs hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between text-xs font-semibold text-slate-600">

            {{-- Mega Menu Trigger --}}
            <div class="relative py-2.5" @mouseenter="categoryMenuOpen = true" @mouseleave="categoryMenuOpen = false">
                <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 transition">
                    <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span>Todas as Categorias</span>
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>

                {{-- Mega Menu Dropdown --}}
                <div x-show="categoryMenuOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full left-0 mt-1 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 p-3 z-50 divide-y divide-slate-100">
                    <div class="py-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 py-1 block">Departamentos Principais</span>
                        @foreach (\App\Services\EcommerceDataService::getCategories() as $cat)
                            <a href="{{ route('catalog.category', ['categorySlug' => $cat['slug']]) }}"
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl hover:bg-slate-50 transition group">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-2 h-2 rounded-full bg-brand/60 group-hover:scale-150 group-hover:bg-brand transition-all"></span>
                                    <span class="text-xs font-medium text-slate-700 group-hover:text-brand transition">{{ $cat['name'] }}</span>
                                </div>
                                <span class="text-[10px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ $cat['count'] }}</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="pt-2 px-2">
                        <a href="{{ route('catalog.index') }}" class="block text-center py-2 text-xs font-bold text-brand bg-brand-light rounded-xl hover:opacity-90 transition">
                            Explorar Todo o Catálogo &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Category Links --}}
            <div class="flex items-center gap-6 overflow-x-auto py-2.5 no-scrollbar">
                @foreach (array_slice(\App\Services\EcommerceDataService::getCategories(), 0, 5) as $cat)
                    <a href="{{ route('catalog.category', ['categorySlug' => $cat['slug']]) }}"
                       class="hover:text-brand transition whitespace-nowrap {{ request()->is('categoria/'.$cat['slug']) ? 'text-brand font-bold' : '' }}">
                        {{ $cat['name'] }}
                    </a>
                @endforeach
                <a href="{{ route('catalog.index', ['ordenacao' => 'desconto']) }}" class="text-rose-600 font-bold hover:text-rose-700 transition flex items-center gap-1 whitespace-nowrap">
                    <span class="inline-block w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    Ofertas Relâmpago
                </a>
            </div>

            {{-- Guaranteed Delivery Badge --}}
            <div class="hidden xl:flex items-center gap-2 text-slate-500 font-normal">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Compra 100% Protegida</span>
            </div>
        </div>
    </div>
</header>
