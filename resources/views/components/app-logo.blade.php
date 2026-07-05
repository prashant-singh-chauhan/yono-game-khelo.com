@props(['app', 'size' => 'w-10 h-10'])

@php
    $src = $app->logo();
    // Deterministic gradient per app so the initials fallback looks intentional.
    $grads = [
        ['#6366f1', '#8b5cf6'], ['#0ea5e9', '#22d3ee'], ['#f43f5e', '#f97316'],
        ['#10b981', '#22c55e'], ['#a855f7', '#ec4899'], ['#f59e0b', '#facc15'],
    ];
    $g = $grads[($app->id ?? crc32($app->name ?? 'x')) % count($grads)];
@endphp

@if ($src)
    <img src="{{ $src }}" alt="{{ $app->name }} logo"
         class="{{ $size }} rounded-xl object-cover ring-1 ring-[var(--line)] shrink-0"
         loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='grid';">
    <span class="{{ $size }} rounded-xl place-items-center text-white font-bold text-sm shrink-0"
          style="display:none;background:linear-gradient(135deg,{{ $g[0] }},{{ $g[1] }});">{{ $app->initials() }}</span>
@else
    <span class="{{ $size }} rounded-xl grid place-items-center text-white font-bold text-sm shrink-0"
          style="background:linear-gradient(135deg,{{ $g[0] }},{{ $g[1] }});">{{ $app->initials() }}</span>
@endif
