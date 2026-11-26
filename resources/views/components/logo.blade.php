@props([
    'variant' => 'bordeaux', // bordeaux, white, black, gold
    'tagline' => true,
    'class' => 'h-10 w-auto',
    'taglineClass' => '',
])

@php
    $logoSrc = match($variant) {
        'white' => asset('images/elisie-logo-white.png'),
        'gold' => asset('images/elisie-logo-gold.png'),
        'black' => asset('images/elisie-logo-black.png'),
        default => asset('images/elisie-logo-bordeaux.png'),
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex flex-col items-center group']) }}>
    <img src="{{ $logoSrc }}" alt="Elisie" class="{{ $class }} object-contain transition-transform duration-300 group-hover:scale-[1.02]">
    @if ($tagline)
        <span class="text-[8px] sm:text-[9px] tracking-[0.25em] font-semibold uppercase block mt-1 {{ $taglineClass ?: ($variant === 'white' ? 'text-rose-200' : 'text-stone-500') }}">
            Moderna • Elegante • Modesta
        </span>
    @endif
</div>
