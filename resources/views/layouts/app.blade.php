<!DOCTYPE html>
<html lang="pt-BR" data-theme="default" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'ViaEcommerce - O Seu Próximo E-commerce Favorito' }}</title>
    <meta name="description" content="E-commerce moderno com alta performance, frete rápido, Pix com desconto e experiência de compra premium.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-900 font-sans selection:bg-blue-500 selection:text-white"
      x-data="{ mobileMenuOpen: false }">

    {{-- Top Promotional Ticker --}}
    <div class="bg-slate-900 text-white text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2 overflow-hidden whitespace-nowrap">
                <span class="inline-flex items-center gap-1.5 font-medium text-emerald-400">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Frete Grátis
                </span>
                <span class="text-slate-300">para compras acima de R$ 199,00 para todo o Brasil</span>
                <span class="hidden md:inline text-slate-500">|</span>
                <span class="hidden md:inline text-amber-300 font-medium">Use o cupom BEMVINDO10 para 10% OFF</span>
            </div>
            <div class="hidden lg:flex items-center gap-6 text-slate-300">
                <a href="#central-ajuda" class="hover:text-white transition">Central de Ajuda</a>
                <a href="{{ route('order.show', ['code' => 'DEMO123']) }}" class="hover:text-white transition">Rastrear Pedido</a>
                <div class="flex items-center gap-2 pl-4 border-l border-slate-700" x-data>
                    <span class="text-[11px] text-slate-400">Tema:</span>
                    <button @click="$store.theme.setTheme('default')" class="w-3.5 h-3.5 rounded-full bg-blue-600 border border-white/50 hover:scale-125 transition" title="Azul Royal"></button>
                    <button @click="$store.theme.setTheme('emerald')" class="w-3.5 h-3.5 rounded-full bg-emerald-600 border border-white/50 hover:scale-125 transition" title="Verde Esmeralda"></button>
                    <button @click="$store.theme.setTheme('purple')" class="w-3.5 h-3.5 rounded-full bg-purple-600 border border-white/50 hover:scale-125 transition" title="Roxo Elétrico"></button>
                    <button @click="$store.theme.setTheme('sunset')" class="w-3.5 h-3.5 rounded-full bg-orange-600 border border-white/50 hover:scale-125 transition" title="Laranja Sunset"></button>
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
    <nav class="md:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200 z-40 py-2 px-4 shadow-lg">
        <div class="flex items-center justify-around text-slate-600">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-xs hover:text-brand transition {{ request()->routeIs('home') ? 'text-brand font-semibold' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Início</span>
            </a>
            <a href="{{ route('catalog.index') }}" class="flex flex-col items-center gap-1 text-xs hover:text-brand transition {{ request()->routeIs('catalog.*') ? 'text-brand font-semibold' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span>Catálogo</span>
            </a>
            <button @click="$store.cart.toggle()" class="flex flex-col items-center gap-1 text-xs hover:text-brand transition relative">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span x-show="$store.cart.count > 0" x-text="$store.cart.count" class="absolute -top-1.5 -right-2 bg-brand text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center"></span>
                </div>
                <span>Carrinho</span>
            </button>
            <a href="{{ route('checkout.index') }}" class="flex flex-col items-center gap-1 text-xs hover:text-brand transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Checkout</span>
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
