@php
    use App\Models\Setting;
    $portalTitle = Setting::get('portal_title', 'Yono Game Khelo');

    // FAQ content (reused for display + FAQPage structured data).
    $faqs = [
        ['What are Yono Gaming Apps?', 'Yono Gaming Apps are popular Indian gaming applications offering real cash earnings through games like Rummy, Slots, Casino and more with instant withdrawals directly into Bank or UPI.'],
        ['How much bonus do I get?', 'Each Yono app offers signup bonuses ranging from ₹50 to ₹5000 which varies by app. You can view each signup bonus amount on their cards above.'],
        ['Is withdrawal really fast?', 'Yes, all our listed apps support fast UPI and Bank withdrawals starting from ₹100 with instant processing, usually credited in minutes.'],
        ['Are these apps safe and legal?', 'Yes, all Yono apps use secure encryption protocols, verified payment gateways, and comply with standard skill-based gaming regulations in eligible states.'],
    ];

    // ItemList of listed apps + FAQPage for rich results.
    $listJsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type'    => 'ItemList',
                'name'     => $portalTitle.' — Gaming Apps',
                'numberOfItems' => $apps->count(),
                'itemListElement' => $apps->take(20)->values()->map(fn ($a, $i) => [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'url'      => route('app.show', $a),
                    'name'     => $a->name,
                ])->all(),
            ],
            [
                '@type' => 'FAQPage',
                'mainEntity' => collect($faqs)->map(fn ($f) => [
                    '@type' => 'Question',
                    'name'  => $f[0],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
                ])->all(),
            ],
        ],
    ];
@endphp
<x-site :canonical="url('/')" :json-ld="$listJsonLd">
    {{-- Hero --}}
    <section class="relative overflow-hidden">
        {{-- Falling snow --}}
        <div class="snowflakes" aria-hidden="true">
            @for ($i = 0; $i < 26; $i++)
                @php
                    $size = rand(4, 11) / 2;                 // 2px – 5.5px
                    $left = rand(0, 100);
                    $dur  = rand(60, 130) / 10;              // 6s – 13s
                    $delay = -rand(0, 130) / 10;             // negative → staggered mid-fall
                    $sway = rand(-40, 40);
                    $op   = rand(45, 95) / 100;
                @endphp
                <span class="snowflake" style="
                    left:{{ $left }}%;
                    width:{{ $size }}px;height:{{ $size }}px;
                    --sway:{{ $sway }}px;--op:{{ $op }};
                    animation-duration:{{ $dur }}s;animation-delay:{{ $delay }}s;"></span>
            @endfor
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 pt-14 pb-10 sm:pt-20 sm:pb-14 text-center rise">
            <span class="badge badge-brand mb-4"><x-icon name="sparkles" class="w-3.5 h-3.5" /> 2026 · Verified Apps</span>
            <h1 class="font-display font-bold text-4xl sm:text-6xl leading-[1.05] tracking-tight">
                {{ Setting::get('portal_title', 'Yono Game Khelo') }}
            </h1>
            <p class="text-gradient font-display font-semibold text-xl sm:text-2xl mt-3">{{ Setting::get('portal_tagline') }}</p>
            <p class="text-muted max-w-xl mx-auto mt-4 leading-relaxed">
                Download the latest rummy, slots & teen patti apps with instant withdrawals, low minimums and the biggest sign-up bonuses of 2026.
            </p>

            {{-- Search --}}
            <form method="GET" class="mt-8 max-w-lg mx-auto flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted"><x-icon name="search" class="w-5 h-5" /></span>
                    <input type="search" name="q" value="{{ $search }}" class="field !pl-12 !py-3.5" placeholder="Search rummy, slots, teen patti…">
                </div>
                <button class="btn btn-primary !px-6">Search</button>
            </form>
        </div>
    </section>

    {{-- Intro / about the platform --}}
    @unless ($search)
        <section class="max-w-6xl mx-auto px-4 sm:px-6 mb-10">
            <div class="card relative overflow-hidden p-6 sm:p-8 rise">
                {{-- ambient accent --}}
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full opacity-20 blur-3xl" style="background:linear-gradient(135deg,var(--brand),var(--brand-2));"></div>
                <div class="absolute -left-12 -bottom-12 w-40 h-40 rounded-full opacity-10 blur-3xl" style="background:linear-gradient(135deg,var(--accent),var(--brand-2));"></div>

                <div class="relative flex items-start gap-4">
                    <div class="hidden sm:grid w-12 h-12 rounded-2xl place-items-center text-white shrink-0" style="background:linear-gradient(135deg,var(--brand),var(--brand-2));box-shadow:0 12px 26px -12px var(--glow);">
                        <x-icon name="sparkles" class="w-6 h-6" />
                    </div>
                    <div>
                        <h2 class="font-display font-bold text-2xl mb-3">Welcome to <span class="text-gradient">{{ Setting::get('portal_title', 'Yono Game Khelo') }}</span></h2>
                        <p class="text-text-soft leading-relaxed">
                            <b class="text-text">{{ Setting::get('portal_title', 'Yono Game Khelo') }}</b> is a popular platform where players can explore a wide collection of real money gaming apps in one place. From rummy tables to slot spins and bingo games, users can enjoy multiple categories like Rummy, Slots, Bingo, Arcade, and Spin games with daily rewards. This collection includes many trending apps such as
                            <span class="font-semibold text-brand">Yono Bonus</span>, <span class="font-semibold text-brand">Club INR</span>, <span class="font-semibold text-brand">INR Rummy</span>, <span class="font-semibold text-brand">Rumble Rummy</span>, <span class="font-semibold text-brand">Spin101</span>, and more. Explore all latest releases with secure UPI withdrawals and quick signup bonuses!
                        </p>

                        {{-- category chips --}}
                        <div class="flex flex-wrap gap-2 mt-5">
                            @foreach (['Rummy', 'Slots', 'Bingo', 'Arcade', 'Spin'] as $cat)
                                <span class="badge badge-brand !text-[0.72rem] !py-1.5 !px-3">{{ $cat }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endunless

    {{-- Top Rated Apps — Embla slider --}}
    @if ($featured->isNotEmpty() && !$search)
        <section class="max-w-6xl mx-auto px-4 sm:px-6 mb-10" data-embla>
            <div class="flex items-center gap-3 mb-4">
                <h2 class="font-display font-bold text-2xl flex items-center gap-2 mr-auto">
                    <span class="text-gold"><x-icon name="star" class="w-5 h-5" style="fill:currentColor" /></span> Top Rated Apps
                </h2>
                <span class="text-muted text-sm hidden sm:block">Swipe to explore →</span>
                <div class="flex gap-2">
                    <button type="button" class="tr-prev tr-nav-btn w-9 h-9 rounded-full grid place-items-center card card-hover !rounded-full text-text-soft hover:text-brand" aria-label="Previous">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </button>
                    <button type="button" class="tr-next tr-nav-btn w-9 h-9 rounded-full grid place-items-center card card-hover !rounded-full text-text-soft hover:text-brand" aria-label="Next">
                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <div class="relative">
                <div class="embla__viewport">
                    <div class="embla__container">
                        @foreach ($featured as $app)
                            <div class="embla__slide">
                                <a href="{{ route('app.show', $app) }}"
                                   class="card card-hover p-5 relative overflow-hidden group block">
                                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full opacity-20 blur-2xl" style="background:linear-gradient(135deg,var(--brand),var(--brand-2));"></div>
                                    <div class="relative flex items-center gap-3.5">
                                        <x-app-logo :app="$app" size="w-16 h-16" />
                                        <div class="min-w-0">
                                            <span class="badge badge-warning mb-1"><x-icon name="star" class="w-3 h-3" style="fill:currentColor" /> {{ $app->rating }}</span>
                                            <h3 class="font-display font-bold text-lg truncate group-hover:text-brand transition-colors">{{ $app->name }}</h3>
                                            <p class="text-muted text-xs">{{ $app->votes }} votes</p>
                                        </div>
                                    </div>
                                    <div class="relative flex items-end justify-between mt-4">
                                        <div>
                                            <p class="text-[0.68rem] uppercase tracking-wide text-muted">Sign-up bonus</p>
                                            <p class="font-display font-bold text-success text-lg">₹{{ number_format($app->sign_up_bonus) }}</p>
                                        </div>
                                        <span class="btn btn-primary btn-sm"><x-icon name="download" class="w-4 h-4" /> Get</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tr-pagination"></div>
            </div>
        </section>
    @endif

    {{-- App grid --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pb-6">
        {{-- Tabs --}}
        <div class="flex items-center gap-2 mb-6 flex-wrap">
            <h2 class="font-display font-bold text-2xl mr-auto">
                {{ $search ? 'Results for "'.$search.'"' : 'All Applications' }}
            </h2>
            <a href="{{ route('home') }}" class="btn btn-sm {{ $tab !== 'new' && !$search ? 'btn-primary' : 'btn-ghost' }}">All Apps</a>
            <a href="{{ route('home', ['tab' => 'new']) }}" class="btn btn-sm {{ $tab === 'new' ? 'btn-primary' : 'btn-ghost' }}">New Releases</a>
        </div>

        @if ($apps->isEmpty())
            <div class="card p-14 text-center text-muted">
                <span class="opacity-50 inline-block mb-3"><x-icon name="search" class="w-10 h-10" /></span>
                <p>No apps found. Try a different search term.</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm mt-4">View all apps</a>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 stagger">
                @foreach ($apps as $app)
                    <x-game-card :app="$app" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- Tips for new players + FAQ (side by side) --}}
    @unless ($search)
        <section class="max-w-6xl mx-auto px-4 sm:px-6 py-10 grid gap-5 lg:grid-cols-2 items-start">
            {{-- Start Smart Tips --}}
            <article class="card p-6 sm:p-7 h-full">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-9 h-9 rounded-xl grid place-items-center text-white shrink-0" style="background:linear-gradient(135deg,var(--brand),var(--brand-2));"><x-icon name="sparkles" class="w-5 h-5" /></span>
                    <h3 class="font-display font-bold text-xl">Start Smart: Tips for New Players</h3>
                </div>
                <p class="text-text-soft leading-relaxed">Getting started with <b class="text-text">{{ $portalTitle }}</b> is simple, but a few pointers can help you feel right at home from the first click. Whether you've been playing for years or you're trying something new, these tips can make your experience smoother and more enjoyable.</p>
                <ul class="mt-4 space-y-3">
                    @foreach ([
                        ['Begin with Simpler Games', "If you're new here, start with games that are easy to pick up. Titles like Yono Rummy and other card classics have clear instructions and familiar rules."],
                        ['Check Reviews Before You Play', "Every game page has specs and rating metrics. Reading these gives you a sense of what to expect, especially if you're unsure which game to try first."],
                        ["Don't Play Continuously", 'Gaming is more fun when you pace yourself. Short breaks keep your mind fresh and help you enjoy each session without feeling tired.'],
                    ] as [$t, $d])
                        <li class="flex items-start gap-3">
                            <span class="text-success mt-0.5 shrink-0"><x-icon name="check-circle" class="w-5 h-5" /></span>
                            <span class="text-text-soft text-sm leading-relaxed"><b class="text-text">{{ $t }}:</b> {{ $d }}</span>
                        </li>
                    @endforeach
                </ul>
            </article>

            {{-- FAQ --}}
            <article class="card p-6 sm:p-7 h-full">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-9 h-9 rounded-xl grid place-items-center text-white shrink-0" style="background:linear-gradient(135deg,var(--accent),var(--brand));"><x-icon name="inbox" class="w-5 h-5" /></span>
                    <h3 class="font-display font-bold text-xl">Frequently Asked Questions</h3>
                </div>
                <div class="divide-y" style="border-color:var(--line);" x-data="{ open: 0 }">
                    @foreach ($faqs as $i => [$q, $a])
                        <div class="py-1">
                            <button type="button" @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                                    class="w-full flex items-center justify-between gap-3 py-3 text-left font-semibold hover:text-brand transition-colors"
                                    :aria-expanded="open === {{ $i }}">
                                <span>{{ $q }}</span>
                                <span class="text-muted shrink-0 transition-transform" :class="open === {{ $i }} && 'rotate-180'"><x-icon name="chevron-down" class="w-4 h-4" /></span>
                            </button>
                            <div x-show="open === {{ $i }}" x-collapse x-cloak>
                                <p class="text-text-soft text-sm leading-relaxed pb-3">{{ $a }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </section>
    @endunless

    {{-- Disclaimer + banned states --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-10 grid gap-5 md:grid-cols-2">
        @if ($d = Setting::get('site_disclaimer'))
            <div class="rounded-2xl p-5 border" style="border-color:color-mix(in srgb,var(--danger) 35%,transparent);background:color-mix(in srgb,var(--danger) 8%,transparent);">
                <p class="flex items-center gap-2 font-bold text-danger mb-2"><x-icon name="alert" class="w-5 h-5" /> Important Disclaimer</p>
                <p class="text-sm text-text-soft leading-relaxed line-clamp-4">{{ $d }}</p>
                <a href="{{ route('disclaimer') }}" class="text-danger text-sm font-semibold mt-2 inline-block">Read full disclaimer →</a>
            </div>
        @endif
        @if ($b = Setting::get('banned_states'))
            <div class="rounded-2xl p-5 border" style="border-color:color-mix(in srgb,var(--warning) 40%,transparent);background:color-mix(in srgb,var(--warning) 10%,transparent);">
                <p class="flex items-center gap-2 font-bold text-warning mb-2"><x-icon name="shield" class="w-5 h-5" /> Banned States Notice</p>
                <p class="text-sm text-text-soft leading-relaxed">{{ $b }}</p>
            </div>
        @endif
    </section>
</x-site>
