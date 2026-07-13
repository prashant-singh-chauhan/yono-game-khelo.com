@props(['class' => 'w-10 h-10'])

{{-- Yono Game Khelo logo mark --}}
<img src="{{ asset('images/logo.png') }}" alt="Yono Game Khelo logo"
     {{ $attributes->merge(['class' => $class]) }} style="object-fit: contain;" />
