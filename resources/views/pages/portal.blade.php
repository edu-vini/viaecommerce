<!DOCTYPE html>
<html lang="pt-BR" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ViaSolutions — Portal de E-commerces</title>
    <meta name="description" content="Central de e-commerces ViaSolutions. Visualize e acesse todas as lojas disponíveis na plataforma.">
    <link rel="icon" type="image/png" href="{{ asset('images/via-solutions-logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-stone-50 text-stone-900 font-sans selection:bg-stone-900 selection:text-white"
      x-data="{
          search: '',
          matches(name, domain, slogan) {
              const q = this.search.trim().toLowerCase();
              if (!q) return true;
              return name.toLowerCase().includes(q)
                  || domain.toLowerCase().includes(q)
                  || slogan.toLowerCase().includes(q);
          }
      }">

    {{-- Top Navigation Bar --}}
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                @if (file_exists(public_path('images/via-solutions-logo.png')))
                    <img src="{{ asset('images/via-solutions-logo.png') }}" alt="ViaSolutions" class="h-8 w-auto object-contain shrink-0">
                @endif
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-base sm:text-lg tracking-tight text-stone-900 truncate">ViaSolutions</span>
                        <span class="hidden sm:inline-flex items-center px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider bg-stone-100 text-stone-700 border border-stone-200">
                            Portal E-commerce
                        </span>
                    </div>
                    <p class="text-[11px] text-stone-500 hidden sm:block">Selecione uma loja para visualizar e acessar</p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ $superAdminUrl }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white text-xs sm:text-sm font-semibold tracking-wide transition shadow-sm">
                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                    <span>Acessar SuperAdmin</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-8">

        {{-- Hero / Filter Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-stone-200 pb-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-stone-500">
                    <span class="w-2 h-2 bg-emerald-500 inline-block"></span>
                    <span>{{ count($stores) }} {{ count($stores) === 1 ? 'e-commerce disponível' : 'e-commerces disponíveis' }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">
                    Lojas Disponíveis na Plataforma
                </h1>
                <p class="text-sm text-stone-600">
                    Confira a pré-visualização de cada e-commerce ativo e clique em qualquer loja para abrir a vitrine completa.
                </p>
            </div>

            {{-- Search Input --}}
            @if (count($stores) > 0)
                <div class="w-full md:w-80">
                    <label for="portal-search" class="sr-only">Buscar e-commerce</label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-stone-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                        <input id="portal-search"
                               type="search"
                               x-model="search"
                               placeholder="Buscar por nome ou domínio..."
                               class="w-full pl-9 pr-4 py-2.5 bg-white border border-stone-300 text-sm text-stone-900 placeholder:text-stone-400 focus:outline-none focus:border-stone-900 transition">
                    </div>
                </div>
            @endif
        </div>

        {{-- Stores Grid --}}
        @if (count($stores) === 0)
            <div class="bg-white border border-stone-200 p-12 text-center space-y-4">
                <p class="text-lg font-semibold text-stone-900">Nenhum e-commerce ativo encontrado</p>
                <p class="text-sm text-stone-500 max-w-md mx-auto">
                    Acesse o painel SuperAdmin para criar ou ativar uma nova loja na plataforma.
                </p>
                <div>
                    <a href="{{ $superAdminUrl }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-stone-900 hover:bg-stone-800 text-white text-sm font-semibold transition">
                        Acessar SuperAdmin
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($stores as $store)
                    <a href="{{ $store['url'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       x-show="matches(@js($store['store_name']), @js($store['domain']), @js($store['slogan']))"
                       class="group bg-white border border-stone-200 hover:border-stone-900 transition-all duration-200 flex flex-col overflow-hidden shadow-sm hover:shadow-xl">

                        {{-- Mini Browser Chrome Bar --}}
                        <div class="bg-stone-100 border-b border-stone-200 px-3.5 py-2 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="w-2 h-2 bg-stone-300 inline-block"></span>
                                <span class="w-2 h-2 bg-stone-300 inline-block"></span>
                                <span class="w-2 h-2 bg-stone-300 inline-block"></span>
                            </div>
                            <div class="flex-1 min-w-0 bg-white border border-stone-200 px-2.5 py-0.5 text-[11px] text-stone-600 font-mono truncate text-center">
                                {{ $store['domain'] }}
                            </div>
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 shrink-0">
                                <span class="w-1.5 h-1.5 bg-emerald-500 inline-block"></span>
                                Online
                            </span>
                        </div>

                        {{-- Live Storefront Miniature Preview --}}
                        <div class="relative overflow-hidden border-b border-stone-200" style="background-color: {{ $store['surface_color'] }};">
                            {{-- Mini Store Header --}}
                            <div class="px-4 py-2.5 bg-white/95 border-b border-stone-200/80 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    @if ($store['logo_url'])
                                        <img src="{{ $store['logo_url'] }}" alt="{{ $store['store_name'] }}" class="h-6 w-auto max-w-[110px] object-contain">
                                    @else
                                        <span class="w-5 h-5 flex items-center justify-center text-[10px] font-bold text-white shrink-0"
                                              style="background-color: {{ $store['primary_color'] }};">
                                            {{ mb_strtoupper(mb_substr($store['store_name'], 0, 1)) }}
                                        </span>
                                        <span class="text-xs font-bold text-stone-900 truncate">{{ $store['store_name'] }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <span class="w-2.5 h-2.5 border border-stone-200 inline-block" style="background-color: {{ $store['primary_color'] }};" title="Cor primária"></span>
                                    <span class="w-2.5 h-2.5 border border-stone-200 inline-block" style="background-color: {{ $store['accent_color'] }};" title="Cor de destaque"></span>
                                </div>
                            </div>

                            {{-- Mini Hero Banner --}}
                            <div class="relative h-36 flex items-end p-4 overflow-hidden" style="background-color: {{ $store['primary_color'] }};">
                                @if ($store['hero_image'])
                                    <img src="{{ $store['hero_image'] }}"
                                         alt="{{ $store['store_name'] }}"
                                         loading="lazy"
                                         class="absolute inset-0 w-full h-full object-cover object-center opacity-50 group-hover:scale-105 transition-transform duration-500">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/30 to-transparent"></div>
                                <div class="relative z-10 text-white space-y-0.5 min-w-0">
                                    <p class="text-[10px] uppercase tracking-widest font-semibold opacity-90 truncate">
                                        {{ $store['slogan'] }}
                                    </p>
                                    <p class="text-base font-bold leading-tight truncate">
                                        {{ $store['store_name'] }}
                                    </p>
                                </div>
                            </div>

                            {{-- Mini Product Strip --}}
                            <div class="p-3">
                                @if (count($store['sample_products']) > 0)
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach ($store['sample_products'] as $product)
                                            <div class="bg-white border border-stone-200/90 p-1.5 flex flex-col">
                                                <div class="aspect-square bg-stone-100 overflow-hidden mb-1.5">
                                                    @if ($product['image'])
                                                        <img src="{{ $product['image'] }}"
                                                             alt="{{ $product['title'] }}"
                                                             loading="lazy"
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-[10px] text-stone-400">
                                                            Produto
                                                        </div>
                                                    @endif
                                                </div>
                                                <p class="text-[10px] font-medium text-stone-800 truncate">{{ $product['title'] }}</p>
                                                <p class="text-[10px] font-bold" style="color: {{ $store['primary_color'] }};">
                                                    R$ {{ number_format($product['price'], 2, ',', '.') }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-white/80 border border-dashed border-stone-300 py-5 px-3 text-center text-[11px] text-stone-500">
                                        Vitrine pronta para cadastro de produtos
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="p-4 flex items-center justify-between gap-3 mt-auto bg-white">
                            <div class="flex items-center gap-3 text-xs text-stone-600">
                                <span><strong class="text-stone-900">{{ $store['products_count'] }}</strong> produtos</span>
                                <span class="text-stone-300">•</span>
                                <span><strong class="text-stone-900">{{ $store['categories_count'] }}</strong> categorias</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-stone-900 group-hover:translate-x-0.5 transition-transform">
                                Abrir Loja
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </main>

    {{-- Footer --}}
    <footer class="border-t border-stone-200 bg-white py-5 px-4 sm:px-6 lg:px-8 text-xs text-stone-500">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <span>© {{ date('Y') }} ViaSolutions E-commerce Platform</span>
            <a href="{{ $superAdminUrl }}" class="font-semibold text-stone-700 hover:text-stone-900 transition">
                Administração da Plataforma (SuperAdmin) →
            </a>
        </div>
    </footer>

</body>
</html>
