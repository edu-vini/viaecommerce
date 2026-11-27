@props([
    'variant' => 'bordeaux', // bordeaux, white, black, gold
    'tagline' => true,
    'class' => 'h-10 w-auto',
    'taglineClass' => '',
])

@php
    $storeName = (isset($storeSetting) && $storeSetting && $storeSetting->store_name)
        ? $storeSetting->store_name
        : (tenant('name') ?? 'Elisie');

    $isElisie = tenant('id') === 'elisie' || tenant('id') === null || str_contains(strtolower($storeName), 'elisie');

    $storeSlogan = (isset($storeSetting) && $storeSetting && $storeSetting->slogan)
        ? $storeSetting->slogan
        : ($isElisie ? 'Moderna • Elegante • Modesta' : 'Sua loja online');

    $customLogo = null;
    if (isset($storeSetting) && $storeSetting && $storeSetting->logo_path) {
        $path = $storeSetting->logo_path;
        $isDefaultBordeauxLogo = str_contains($path, 'elisie-logo-bordeaux.png');

        if (! $isDefaultBordeauxLogo || $variant === 'bordeaux') {
            if (str_starts_with($path, 'http')) {
                $customLogo = $path;
            } elseif (file_exists(public_path($path))) {
                $customLogo = asset($path);
            } else {
                $customLogo = asset('storage/' . ltrim($path, '/'));
            }
        }
    }

    if ($customLogo) {
        $logoSrc = $customLogo;
    } elseif ($isElisie) {
        $logoSrc = match($variant) {
            'white' => asset('images/elisie-logo-white.png'),
            'gold' => asset('images/elisie-logo-gold.png'),
            'black' => asset('images/elisie-logo-black.png'),
            default => asset('images/elisie-logo-bordeaux.png'),
        };
    } else {
        $logoSrc = asset('images/via-solutions-logo.png');
    }
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex flex-col items-center group']) }}>
    <div class="inline-flex items-center gap-2.5">
        <img src="{{ $logoSrc }}" alt="{{ $storeName }}" class="{{ $class }} object-contain transition-transform duration-300 group-hover:scale-[1.02]">
        @if (! $isElisie && (! isset($storeSetting) || ! $storeSetting->logo_path || str_contains((string) $storeSetting->logo_path, 'via-solutions-logo.png')))
            <span class="font-display text-lg sm:text-xl font-bold tracking-tight {{ $variant === 'white' ? 'text-white' : 'text-slate-900' }}">
                {{ $storeName }}
            </span>
        @endif
    </div>
    @if ($tagline)
        <span class="text-[8px] sm:text-[9px] tracking-[0.25em] font-semibold uppercase block mt-1 {{ $taglineClass ?: ($variant === 'white' ? 'text-slate-300' : 'text-stone-500') }}">
            {{ $storeSlogan }}
        </span>
    @endif
</div>
