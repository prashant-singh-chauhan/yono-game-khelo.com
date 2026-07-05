@props(['class' => 'w-10 h-10'])

@php $gid = 'bm'.uniqid(); @endphp

{{-- Yono Game Khelo logo mark: gradient squircle + spade (card-game motif) + lucky spark --}}
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 48 48" fill="none"
     xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Yono Game Khelo logo">
    <defs>
        <linearGradient id="{{ $gid }}" x1="4" y1="4" x2="44" y2="44" gradientUnits="userSpaceOnUse">
            <stop stop-color="#6366F1"/>
            <stop offset="0.55" stop-color="#7C5CF6"/>
            <stop offset="1" stop-color="#A855F7"/>
        </linearGradient>
        <linearGradient id="{{ $gid }}s" x1="24" y1="3" x2="24" y2="24" gradientUnits="userSpaceOnUse">
            <stop stop-color="#FFFFFF" stop-opacity="0.35"/>
            <stop offset="1" stop-color="#FFFFFF" stop-opacity="0"/>
        </linearGradient>
    </defs>

    <rect x="3" y="3" width="42" height="42" rx="13" fill="url(#{{ $gid }})"/>
    <rect x="3" y="3" width="42" height="21" rx="13" fill="url(#{{ $gid }}s)"/>

    {{-- spade body --}}
    <path d="M24 12 C24 12 10 22 10 29.5 C10 34 15 36 18.5 33.5 C20.5 32 22.5 30 24 29.5 C25.5 30 27.5 32 29.5 33.5 C33 36 38 34 38 29.5 C38 22 24 12 24 12 Z" fill="#fff"/>
    {{-- spade stem --}}
    <path d="M24 28.5 C23 32.5 21 35.5 17.5 37.5 L30.5 37.5 C27 35.5 25 32.5 24 28.5 Z" fill="#fff"/>

    {{-- lucky spark accent --}}
    <path d="M34.2 11 l1.05 2.75 2.75 1.05 -2.75 1.05 -1.05 2.75 -1.05 -2.75 -2.75 -1.05 2.75 -1.05z" fill="#22D3EE"/>
</svg>
