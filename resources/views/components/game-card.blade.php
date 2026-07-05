@props(['app', 'number' => null])

@php
    $shareUrl  = route('app.show', $app);
    $shareText = $app->name.' — ₹'.number_format($app->sign_up_bonus).' sign-up bonus. Download now!';
@endphp

<div class="card card-hover p-5 flex flex-col group relative">
    {{-- Stretched link: whole card navigates to the app detail page --}}
    <a href="{{ $shareUrl }}" class="absolute inset-0 z-0 rounded-[var(--radius-xl2)]" aria-label="{{ $app->name }} details"></a>

    {{-- Rank / count bubble (top-left corner of the card) --}}
    @isset($number)
        <span class="absolute -top-3 -left-3 z-20 pointer-events-none min-w-[1.6rem] h-[1.6rem] px-1.5 rounded-full grid place-items-center text-white font-bold text-xs tabular-nums shadow-md ring-2 ring-[var(--surface-solid)]"
              style="background:linear-gradient(135deg,var(--brand),var(--brand-2));" aria-hidden="true">{{ $number }}</span>
    @endisset

    {{-- Share menu (top-right corner) --}}
    <div class="absolute top-3.5 right-3.5 z-20" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
        <button type="button" @click.prevent.stop="open = !open"
                class="w-8 h-8 rounded-full grid place-items-center text-muted hover:text-brand transition-colors"
                style="background:var(--surface-2);"
                :aria-expanded="open" aria-label="Share {{ $app->name }}">
            <x-icon name="share" class="w-4 h-4" />
        </button>

        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="card absolute right-0 mt-2 w-44 p-1.5 z-30 shadow-2xl" role="menu">
            <p class="px-2.5 pt-1 pb-1.5 text-[0.68rem] uppercase tracking-wide text-muted">Share via</p>

            {{-- Facebook --}}
            <button type="button" role="menuitem"
                    @click.prevent.stop="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent('{{ $shareUrl }}'), '_blank', 'noopener,width=640,height=560'); open = false"
                    class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm font-medium hover:bg-surface-2 transition-colors">
                <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 shrink-0"><path fill="#1877F2" d="M24 12a12 12 0 1 0-13.9 11.9v-8.4H7.1V12h3V9.4c0-3 1.8-4.6 4.5-4.6 1.3 0 2.6.2 2.6.2v2.9h-1.5c-1.5 0-1.9.9-1.9 1.8V12h3.3l-.5 3.5h-2.8v8.4A12 12 0 0 0 24 12z"/></svg>
                Facebook
            </button>

            {{-- LinkedIn --}}
            <button type="button" role="menuitem"
                    @click.prevent.stop="window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent('{{ $shareUrl }}'), '_blank', 'noopener,width=640,height=560'); open = false"
                    class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm font-medium hover:bg-surface-2 transition-colors">
                <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 shrink-0"><path fill="#0A66C2" d="M20.4 3H3.6A1.6 1.6 0 0 0 2 4.6v14.8A1.6 1.6 0 0 0 3.6 21h16.8a1.6 1.6 0 0 0 1.6-1.6V4.6A1.6 1.6 0 0 0 20.4 3zM8 18H5.3V9.5H8V18zM6.6 8.3a1.6 1.6 0 1 1 0-3.1 1.6 1.6 0 0 1 0 3.1zM18.7 18H16v-4.2c0-1-.4-1.7-1.3-1.7-.7 0-1.1.5-1.3 1-.1.2-.1.4-.1.7V18h-2.7V9.5h2.7v1.2c.4-.6 1-1.4 2.5-1.4 1.8 0 3.2 1.2 3.2 3.8V18z"/></svg>
                LinkedIn
            </button>

            {{-- Email --}}
            <button type="button" role="menuitem"
                    @click.prevent.stop="window.location.href = 'mailto:?subject=' + encodeURIComponent('{{ addslashes($app->name) }}') + '&body=' + encodeURIComponent('{{ addslashes($shareText) }}' + ' ' + '{{ $shareUrl }}'); open = false"
                    class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm font-medium hover:bg-surface-2 transition-colors">
                <span class="w-4.5 h-4.5 shrink-0 grid place-items-center text-brand"><x-icon name="mail" class="w-4.5 h-4.5" /></span>
                Email
            </button>
        </div>
    </div>

    {{-- Header --}}
    <div class="flex items-center gap-3.5 relative z-10 pointer-events-none pr-8">
        <x-app-logo :app="$app" size="w-14 h-14" />
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <h3 class="font-display font-bold text-lg truncate">{{ $app->name }}</h3>
                @if ($app->is_new_release)<span class="badge badge-success">New</span>@endif
            </div>
            <div class="flex items-center gap-1.5 text-sm">
                <span class="text-gold inline-flex"><x-icon name="star" class="w-4 h-4" style="fill:currentColor" /></span>
                <span class="font-semibold">{{ $app->rating }}</span>
                <span class="text-muted">· {{ $app->votes }} votes</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2 mt-4 relative z-10 pointer-events-none">
        <div class="rounded-xl px-3 py-2.5" style="background:var(--surface-2);">
            <p class="text-[0.68rem] uppercase tracking-wide text-muted">Bonus</p>
            <p class="font-display font-bold text-success">₹{{ number_format($app->sign_up_bonus) }}</p>
        </div>
        <div class="rounded-xl px-3 py-2.5" style="background:var(--surface-2);">
            <p class="text-[0.68rem] uppercase tracking-wide text-muted">Min Withdraw</p>
            <p class="font-display font-bold">₹{{ number_format($app->min_withdrawal) }}</p>
        </div>
    </div>

    <p class="text-muted text-sm mt-3 line-clamp-2 flex-1 relative z-10 pointer-events-none">{{ $app->short_intro }}</p>

    <span class="btn btn-primary w-full mt-4 group-hover:brightness-105 relative z-10 pointer-events-none">
        <x-icon name="download" class="w-4 h-4" /> Download · {{ $app->app_size }}
    </span>
</div>
