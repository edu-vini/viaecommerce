<!DOCTYPE html>
<html lang="pt-BR" data-theme="default" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Elisie - Moderna. Elegante. Modesta' }}</title>
    <meta name="description" content="Elisie Moda Feminina: Moderna, elegante e modesta. Vestidos midi exclusivos, alfaiataria fina e conjuntos atemporais.">
    <link rel="icon" type="image/png" href="{{ asset('images/elisie-logo-bordeaux.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-[#FAF8F5] text-stone-900 font-sans selection:bg-[#720403] selection:text-white"
      x-data="{ mobileMenuOpen: false }">

    {{-- Top Promotional Bar --}}
    <div class="bg-[#1C1917] text-stone-300 text-[11px] py-2 px-4 tracking-wider uppercase">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2 overflow-hidden whitespace-nowrap mx-auto lg:mx-0">
                <span class="text-[#D4AF37] font-semibold">Frete Grátis</span>
                <span class="text-stone-300">acima de R$ 399 para todo o Brasil</span>
                <span class="hidden md:inline text-stone-600">|</span>
                <span class="hidden md:inline text-stone-300">5% de desconto no Pix à vista</span>
                <span class="hidden md:inline text-stone-600">|</span>
                <span class="hidden md:inline text-stone-300">1ª Troca Grátis em até 30 dias</span>
            </div>
            <div class="hidden lg:flex items-center gap-6 text-stone-300 font-medium">
                <a href="#ajuda" class="hover:text-white transition">Ajuda & FAQ</a>
                <a href="{{ route('order.show', ['code' => 'EL-8291']) }}" class="hover:text-white transition">Rastrear Pedido</a>
                <div class="flex items-center gap-2 pl-4 border-l border-stone-800" x-data>
                    <span class="text-[10px] text-stone-400">Paleta:</span>
                    <button @click="$store.theme.setTheme('default')" class="w-3.5 h-3.5 bg-[#720403] border border-white/60 hover:scale-125 transition" title="Elisie Bordeaux"></button>
                    <button @click="$store.theme.setTheme('dourado')" class="w-3.5 h-3.5 bg-[#855B28] border border-white/60 hover:scale-125 transition" title="Dourado Nobre"></button>
                    <button @click="$store.theme.setTheme('noir')" class="w-3.5 h-3.5 bg-[#18181B] border border-white/60 hover:scale-125 transition" title="Noir Elegance"></button>
                    <button @click="$store.theme.setTheme('rose')" class="w-3.5 h-3.5 bg-[#8E3646] border border-white/60 hover:scale-125 transition" title="Rosé Marsala"></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    @include('components.header')

    {{-- Main Content --}}
    <main class="flex-grow">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Mini Cart Drawer --}}
    @include('components.mini-cart')

    {{-- Global Toast Alerts --}}
    @include('components.toast')

    {{-- Footer --}}
    @include('components.footer')

    {{-- Mobile Bottom Navigation Bar --}}
    <nav class="md:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-stone-200 z-40 py-2.5 px-4 shadow-lg">
        <div class="flex items-center justify-around text-stone-600">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-[11px] hover:text-brand transition {{ request()->routeIs('home') ? 'text-brand font-bold' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Início</span>
            </a>
            <a href="{{ route('catalog.index') }}" class="flex flex-col items-center gap-1 text-[11px] hover:text-brand transition {{ request()->routeIs('catalog.*') ? 'text-brand font-bold' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span>Coleção</span>
            </a>
            <button @click="$store.cart.toggle()" class="flex flex-col items-center gap-1 text-[11px] hover:text-brand transition relative">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span x-show="$store.cart.count > 0" x-text="$store.cart.count" class="absolute -top-1.5 -right-2 bg-brand text-white text-[9px] font-bold h-4 w-4 flex items-center justify-center"></span>
                </div>
                <span>Sacola</span>
            </button>
            <a href="{{ route('checkout.index') }}" class="flex flex-col items-center gap-1 text-[11px] hover:text-brand transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Finalizar</span>
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
