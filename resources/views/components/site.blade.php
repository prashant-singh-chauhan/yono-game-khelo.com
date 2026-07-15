@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'ogImage' => null,
    'ogType' => 'website',
    'jsonLd' => null,
])

@php
    use App\Models\Setting;
    $portalTitle = Setting::get('portal_title', 'Yono Game Khelo');
    $tagline = Setting::get('portal_tagline', 'All Yono Games, Rummy Apps & Slots Games');
    $telegram = Setting::get('telegram_join_url', '#');
    $whatsapp = Setting::get('whatsapp_number');
    $logoUrl = Setting::get('brand_logo_url');
    $gStart = Setting::get('header_gradient_start', '#6366f1') ?: '#6366f1';
    $gEnd = Setting::get('header_gradient_end', '#8b5cf6') ?: '#8b5cf6';
    $accent = Setting::get('theme_accent', '#6366f1') ?: '#6366f1';
    $metaDesc = \Illuminate\Support\Str::limit(
        strip_tags($description ?? Setting::get('seo_description', $tagline)),
        160,
    );
    $keywords = Setting::get('seo_keywords');
    $fullTitle = $title ? $title . ' · ' . $portalTitle : $portalTitle . ' — ' . $tagline;
    $canonicalUrl = $canonical ?? url()->current();
    $socialImage = $ogImage ?? Setting::get('social_card_url') ?: ($logoUrl ?: asset('images/logo.png'));
    // Ensure OG/Twitter image is an absolute URL.
    if ($socialImage && !\Illuminate\Support\Str::startsWith($socialImage, ['http://', 'https://'])) {
        $socialImage = url($socialImage);
    }

    // Site-wide structured data: Organization + WebSite with SearchAction.
    $siteJsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/') . '#organization',
                'name' => $portalTitle,
                'url' => url('/'),
                'slogan' => $tagline,
            ] + ($socialImage ? ['logo' => $socialImage] : []),
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '#website',
                'url' => url('/'),
                'name' => $portalTitle,
                'description' => $tagline,
                'publisher' => ['@id' => url('/') . '#organization'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => url('/') . '?q={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ];

    $nav = [
        ['route' => 'home', 'label' => 'Home'],
        ['route' => 'about', 'label' => 'About'],
        ['route' => 'contact', 'label' => 'Contact'],
        ['route' => 'disclaimer', 'label' => 'Disclaimer'],
    ];
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/ico" href="/favicon.ico" sizes="any" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />

    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <link rel="manifest" href="/site.webmanifest" />

    {{-- Primary SEO --}}
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    @if ($keywords)
        <meta name="keywords" content="{{ $keywords }}">
    @endif
    <meta name="author" content="{{ $portalTitle }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="language" content="English">
    <meta name="revisit-after" content="1 days">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <link rel="canonical" href="{{ $canonicalUrl }}">
    {{-- hreflang: single English (India) version — self-reference + x-default --}}
    <link rel="alternate" hreflang="en-in" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">
    <meta name="theme-color" content="{{ $gStart }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $title ?? $portalTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
     @if ($socialImage)
        <meta property="og:image" content="{{ $socialImage }}">
    @endif
    <meta property="og:image:width" content="720">
    <meta property="og:image:height" content="192">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="{{ $portalTitle }}">
    <meta property="og:locale" content="en_IN">
    <meta property="article:author" content="{{ $portalTitle }}">
    <meta property="article:publisher" content="{{ $portalTitle }}">


    {{-- Twitter --}}
    <meta name="twitter:card" content="{{ $socialImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $title ?? $portalTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    @if ($socialImage)
        <meta name="twitter:image" content="{{ $socialImage }}">
    @endif

     <meta name="theme-color" content="#fb3737">
    <meta name="msapplication-TileColor" content="#fb3737">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $title ?? $portalTitle }}">
    <meta name="application-name" content="{{ $title ?? $portalTitle }}">
    <meta name="msapplication-tooltip" content="{{ $metaDesc }}">
    <meta name="msapplication-starturl" content="/">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="true">
    <meta name="MobileOptimized" content="320">
    <meta name="geo.region" content="IN">
    <meta name="geo.placename" content="India">
    <meta name="target" content="all">

    {{-- Structured data --}}
    <script type="application/ld+json">{!! json_encode($siteJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @if ($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    <script>
        (function() {
            try {
                var t = localStorage.getItem('yono-theme');
                if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Portal Settings → live color customizer (drives the whole accent theme) --}}
    <style>
        :root,
        :root.dark {
            --brand: {{ $accent }};
            --brand-strong: {{ $accent }};
            --brand-2: {{ $gEnd }};
            --glow: color-mix(in srgb, {{ $accent }} 32%, transparent);
            --ring: color-mix(in srgb, {{ $accent }} 45%, transparent);
        }
    </style>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-26X0VCJ7X0"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-26X0VCJ7X0');
    </script>
</head>

<body class="min-h-dvh flex flex-col" x-data="{ menu: false }">

    {{-- ═══════════ Header ═══════════ --}}
    <header class="sticky top-0 z-40"
        style="background:var(--sidebar);backdrop-filter:blur(20px) saturate(140%);border-bottom:1px solid var(--line);">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-[68px] flex items-center gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0"
                aria-label="{{ $portalTitle }} home">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $portalTitle }}"
                        class="h-9 sm:h-10 w-auto max-w-[200px] object-contain"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
                    <span class="items-center gap-2.5" style="display:none;">
                        <x-brand-mark class="w-10 h-10" />
                        <span class="font-display font-bold text-lg">{{ $portalTitle }}</span>
                    </span>
                @else
                    <x-brand-mark class="w-11 h-11 drop-shadow-sm" />
                    <span class="font-display font-bold text-lg">{{ $portalTitle }}</span>
                @endif
            </a>

            <nav class="hidden lg:flex items-center gap-1 ml-3">
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs($item['route']) ? 'text-brand' : 'text-text-soft hover:text-text' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- ── Instant search (desktop) ── --}}
            <x-search-bar class="hidden md:block flex-1 max-w-xs ml-auto" />

            <div class="flex items-center gap-2 ml-auto md:ml-2">
                <a href="{{ $telegram }}" target="_blank" rel="noopener"
                    class="btn btn-sm text-white hidden xl:inline-flex !py-2.5"
                    style="background:linear-gradient(120deg,#2AABEE,#229ED9);box-shadow:0 8px 20px -10px #229ED9;">
                    <x-icon name="send" class="w-4 h-4" /> Join Telegram
                </a>

                <button @click="$store.theme.toggle()" class="btn btn-ghost btn-sm !px-2.5 !py-2.5"
                    title="Toggle theme">
                    <span x-show="!$store.theme.dark" x-cloak><x-icon name="moon" class="w-4.5 h-4.5" /></span>
                    <span x-show="$store.theme.dark" x-cloak><x-icon name="sun" class="w-4.5 h-4.5" /></span>
                </button>

                <button @click="menu = !menu" class="btn btn-ghost btn-sm !px-2.5 !py-2.5 md:hidden"
                    aria-label="Open menu" :aria-expanded="menu">
                    <x-icon name="menu" class="w-5 h-5" />
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="menu" x-cloak x-collapse class="md:hidden border-t" style="border-color:var(--line);">
            <div class="max-w-6xl mx-auto px-4 py-3 flex flex-col gap-1">
                <x-search-bar class="mb-2" />
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                        class="px-3 py-2.5 rounded-lg font-medium {{ request()->routeIs($item['route']) ? 'text-brand bg-surface-2' : 'text-text-soft' }}">{{ $item['label'] }}</a>
                @endforeach
                <a href="{{ $telegram }}" target="_blank" class="btn btn-sm text-white mt-2"
                    style="background:linear-gradient(120deg,#2AABEE,#229ED9);"><x-icon name="send"
                        class="w-4 h-4" /> Join Telegram</a>
            </div>
        </div>
    </header>

    {{-- ═══════════ Running-text banner ═══════════ --}}
    @php
        $ticker = [
            ['bolt', 'Sign-up bonus up to ₹850'],
            ['money', 'Instant UPI withdrawals from ₹100'],
            ['shield', '100% secure & verified apps'],
            ['sparkles', '53+ trusted rummy, slots & teen patti apps'],
            ['send', 'Join our Telegram for daily new-app alerts'],
            ['star', 'New releases added every week'],
            ['alert', '18+ only · Play responsibly'],
        ];
    @endphp
    <div class="marquee text-white text-sm font-medium py-2"
        style="background:linear-gradient(90deg,{{ $gStart }},{{ $gEnd }});">
        <div class="marquee__track" aria-hidden="true">
            @for ($rep = 0; $rep < 2; $rep++)
                @foreach ($ticker as [$ic, $txt])
                    <span class="inline-flex items-center gap-2 px-6">
                        <x-icon :name="$ic" class="w-4 h-4 opacity-90" />
                        {{ $txt }}
                    </span>
                    <span class="opacity-40">◆</span>
                @endforeach
            @endfor
        </div>
    </div>

    {{-- ═══════════ Content ═══════════ --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- ═══════════ Footer ═══════════ --}}
    <footer class="mt-16" style="border-top:1px solid var(--line);background:var(--surface-2);">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 grid gap-10 md:grid-cols-4">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-3">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $portalTitle }}"
                            class="h-10 w-auto max-w-[200px] object-contain"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
                        <span class="items-center gap-3" style="display:none;">
                            <x-brand-mark class="w-11 h-11" />
                            <span class="font-display font-bold text-lg">{{ $portalTitle }}</span>
                        </span>
                    @else
                        <x-brand-mark class="w-11 h-11" />
                        <span class="font-display font-bold text-lg">{{ $portalTitle }}</span>
                    @endif
                </div>
                <p class="text-muted text-sm leading-relaxed max-w-md">{{ $tagline }}. Discover trusted rummy,
                    slots & teen patti apps with instant withdrawals and generous sign-up bonuses.</p>
                <a href="{{ $telegram }}" target="_blank" class="btn btn-sm text-white mt-4 inline-flex"
                    style="background:linear-gradient(120deg,#2AABEE,#229ED9);"><x-icon name="send"
                        class="w-4 h-4" /> Join our Telegram</a>
            </div>
            <div>
                <p class="font-semibold mb-3">Quick Links</p>
                <ul class="space-y-2 text-sm text-muted">
                    @foreach ($nav as $item)
                        <li><a href="{{ route($item['route']) }}" class="hover:text-brand">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="font-semibold mb-3">Support</p>
                <ul class="space-y-2 text-sm text-muted">
                    <li><a href="{{ $telegram }}" target="_blank" rel="noopener" class="hover:text-brand">Join
                            Telegram</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-brand">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-brand">Privacy Policy</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-brand">Admin Login</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t py-5 text-center text-muted text-sm" style="border-color:var(--line);">
            <p>⚠️ 18+ only. Play responsibly. © {{ date('Y') }} {{ $portalTitle }}. All rights reserved.</p>
        </div>
    </footer>

    {{-- ═══════════ Floating action buttons ═══════════ --}}
    <div class="fixed bottom-5 right-5 z-[95] flex flex-col items-center gap-3" x-data="{ up: false }"
        x-init="up = window.scrollY > 400;
        window.addEventListener('scroll', () => up = window.scrollY > 400, { passive: true })">

        {{-- Scroll to top (appears after scrolling) --}}
        <button type="button" x-show="up" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-end="opacity-0 translate-y-2 scale-90"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="w-12 h-12 rounded-full grid place-items-center card card-hover !rounded-full text-text-soft hover:text-brand"
            aria-label="Scroll to top" title="Back to top">
            <x-icon name="arrow-up" class="w-5 h-5" />
        </button>

        {{-- Telegram --}}
        <a href="{{ $telegram }}" target="_blank" rel="noopener"
            class="group relative w-12 h-12 rounded-full grid place-items-center text-white shadow-lg transition-transform hover:scale-105 active:scale-95"
            style="background:linear-gradient(135deg,#2AABEE,#229ED9);box-shadow:0 12px 28px -8px #229ED9;"
            aria-label="Join our Telegram channel" title="Join Telegram">
            <span class="absolute inset-0 rounded-full animate-ping opacity-30"
                style="background:#2AABEE;animation-duration:2.5s;"></span>
            <x-icon name="send" class="w-5 h-5 relative" />
        </a>
    </div>

    {{-- Toast --}}
    <div x-data="toaster()" x-init="@if (session('status')) push({ msg: @js(session('status')), type: 'success' }) @endif
    @if ($errors->any()) push({ msg: @js($errors->first()), type: 'error' }) @endif"
        class="fixed bottom-5 left-5 z-[100] flex flex-col gap-2.5 w-[min(92vw,22rem)]">
        <template x-for="t in items" :key="t.id">
            <div x-transition class="card px-4 py-3 flex items-start gap-3 shadow-xl" role="alert">
                <div class="mt-0.5 shrink-0" :class="t.type === 'error' ? 'text-danger' : 'text-success'">
                    <template x-if="t.type === 'error'"><x-icon name="alert" class="w-5 h-5" /></template>
                    <template x-if="t.type !== 'error'"><x-icon name="check-circle" class="w-5 h-5" /></template>
                </div>
                <p class="text-sm text-text-soft flex-1" x-text="t.msg"></p>
                <button @click="dismiss(t.id)" class="text-muted hover:text-text shrink-0"><x-icon name="x"
                        class="w-4 h-4" /></button>
            </div>
        </template>
    </div>

</body>

</html>
