@php
    // Rich structured data for this app: SoftwareApplication + AggregateRating + Reviews + Breadcrumbs.
    $appSchema = [
        '@type'           => 'SoftwareApplication',
        'name'            => $app->name,
        'operatingSystem' => 'Android',
        'applicationCategory' => 'GameApplication',
        'url'             => route('app.show', $app),
        'fileSize'        => $app->app_size,
        'description'     => $app->seo_meta_description ?: $app->short_intro,
        'offers'          => [
            '@type'         => 'Offer',
            'price'         => '0',
            'priceCurrency' => 'INR',
        ],
    ];
    if ($app->logo()) {
        $appSchema['image'] = $app->logo();
    }
    if ($app->promo_code) {
        $appSchema['offers']['description'] = 'Promo code: '.$app->promo_code;
    }
    // AggregateRating from the editorial rating/votes.
    if ($votesNum = (int) preg_replace('/[^0-9]/', '', $app->votes)) {
        $appSchema['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => (string) $app->rating,
            'bestRating'  => '5',
            'ratingCount' => (string) max($votesNum, $reviews->count(), 1),
        ];
    }
    // Approved user reviews.
    if ($reviews->isNotEmpty()) {
        $appSchema['review'] = $reviews->take(10)->map(fn ($r) => [
            '@type'  => 'Review',
            'author' => ['@type' => 'Person', 'name' => $r->author],
            'datePublished' => $r->created_at->toDateString(),
            'reviewRating'  => ['@type' => 'Rating', 'ratingValue' => (string) $r->rating, 'bestRating' => '5'],
            'reviewBody'    => $r->comment,
        ])->values()->all();
    }

    $breadcrumbs = [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $app->name, 'item' => route('app.show', $app)],
        ],
    ];

    $appJsonLd = ['@context' => 'https://schema.org', '@graph' => [$appSchema, $breadcrumbs]];
@endphp

<x-site :title="$app->seo_title ?: $app->name"
        :description="$app->seo_meta_description ?: $app->short_intro"
        :canonical="route('app.show', $app)"
        :og-image="$app->logo()"
        og-type="product"
        :json-ld="$appJsonLd">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        {{-- Visible breadcrumb (semantic, aids SEO + navigation) --}}
        <nav aria-label="Breadcrumb" class="mb-3">
            <ol class="flex items-center gap-1.5 text-sm text-muted flex-wrap">
                <li><a href="{{ route('home') }}" class="hover:text-brand">Home</a></li>
                <li aria-hidden="true">/</li>
                <li class="text-text-soft font-medium truncate max-w-[60vw]">{{ $app->name }}</li>
            </ol>
        </nav>
        <a href="{{ route('home') }}" class="btn btn-ghost btn-sm mb-5"><x-icon name="chevron-left" class="w-4 h-4" /> Back to all apps</a>

        {{-- Hero --}}
        <div class="card p-6 sm:p-8 relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full opacity-20 blur-3xl" style="background:linear-gradient(135deg,var(--brand),var(--brand-2));"></div>
            <div class="relative flex flex-col sm:flex-row gap-6 items-start">
                <x-app-logo :app="$app" size="w-24 h-24" />
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="font-display font-bold text-3xl">{{ $app->name }}</h1>
                        @if ($app->is_new_release)<span class="badge badge-success">New Release</span>@endif
                    </div>
                    <div class="flex items-center gap-4 mt-2 text-sm flex-wrap">
                        <span class="inline-flex items-center gap-1"><span class="text-gold"><x-icon name="star" class="w-4 h-4" style="fill:currentColor" /></span><b>{{ $app->rating }}</b> <span class="text-muted">({{ $app->votes }} votes)</span></span>
                        <span class="text-muted">·</span>
                        <span class="text-muted">{{ $app->app_size }}</span>
                    </div>
                    <p class="text-text-soft mt-3 leading-relaxed">{{ $app->short_intro }}</p>

                    <div class="flex flex-wrap gap-3 mt-5">
                        <a href="{{ $app->download_link ?: '#' }}" target="_blank" rel="noopener" class="btn btn-primary !py-3 !px-6">
                            <x-icon name="download" class="w-5 h-5" /> Download APK
                        </a>
                        @if ($app->promo_code)
                            <div x-data="{ copied: false }">
                                <button type="button"
                                        @click="navigator.clipboard.writeText('{{ $app->promo_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="btn btn-ghost !py-3 !px-5 font-mono">
                                    <x-icon name="tag" class="w-4 h-4" />
                                    <span x-text="copied ? 'Copied!' : 'Code: {{ $app->promo_code }}'"></span>
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Share dropdown --}}
                    @php
                        $shareUrl  = route('app.show', $app);
                        $shareText = $app->name.' — ₹'.number_format($app->sign_up_bonus).' sign-up bonus. Download now!';
                    @endphp
                    <div class="mt-5 relative inline-block" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false">
                        <button type="button" @click="open = !open" class="btn btn-ghost !py-2.5 !px-4" :aria-expanded="open" aria-label="Share {{ $app->name }}">
                            <x-icon name="share" class="w-4 h-4" /> Share
                            <x-icon name="chevron-down" class="w-4 h-4 transition-transform" ::class="open && 'rotate-180'" />
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             class="card absolute left-0 mt-2 w-48 p-1.5 z-30 shadow-2xl" role="menu">
                            <p class="px-2.5 pt-1 pb-1.5 text-[0.68rem] uppercase tracking-wide text-muted">Share via</p>

                            <button type="button" role="menuitem"
                                    @click="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent('{{ $shareUrl }}'), '_blank', 'noopener,width=640,height=560'); open = false"
                                    class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm font-medium hover:bg-surface-2 transition-colors">
                                <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 shrink-0"><path fill="#1877F2" d="M24 12a12 12 0 1 0-13.9 11.9v-8.4H7.1V12h3V9.4c0-3 1.8-4.6 4.5-4.6 1.3 0 2.6.2 2.6.2v2.9h-1.5c-1.5 0-1.9.9-1.9 1.8V12h3.3l-.5 3.5h-2.8v8.4A12 12 0 0 0 24 12z"/></svg>
                                Facebook
                            </button>

                            <button type="button" role="menuitem"
                                    @click="window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent('{{ $shareUrl }}'), '_blank', 'noopener,width=640,height=560'); open = false"
                                    class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm font-medium hover:bg-surface-2 transition-colors">
                                <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 shrink-0"><path fill="#0A66C2" d="M20.4 3H3.6A1.6 1.6 0 0 0 2 4.6v14.8A1.6 1.6 0 0 0 3.6 21h16.8a1.6 1.6 0 0 0 1.6-1.6V4.6A1.6 1.6 0 0 0 20.4 3zM8 18H5.3V9.5H8V18zM6.6 8.3a1.6 1.6 0 1 1 0-3.1 1.6 1.6 0 0 1 0 3.1zM18.7 18H16v-4.2c0-1-.4-1.7-1.3-1.7-.7 0-1.1.5-1.3 1-.1.2-.1.4-.1.7V18h-2.7V9.5h2.7v1.2c.4-.6 1-1.4 2.5-1.4 1.8 0 3.2 1.2 3.2 3.8V18z"/></svg>
                                LinkedIn
                            </button>

                            <button type="button" role="menuitem"
                                    @click="window.location.href = 'mailto:?subject=' + encodeURIComponent('{{ addslashes($app->name) }}') + '&body=' + encodeURIComponent('{{ addslashes($shareText) }}' + ' ' + '{{ $shareUrl }}'); open = false"
                                    class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm font-medium hover:bg-surface-2 transition-colors">
                                <span class="w-4.5 h-4.5 shrink-0 grid place-items-center text-brand"><x-icon name="mail" class="w-4.5 h-4.5" /></span>
                                Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Spec strip --}}
            <div class="relative grid grid-cols-2 sm:grid-cols-4 gap-3 mt-7">
                @foreach ([['Sign-up Bonus', '₹'.number_format($app->sign_up_bonus), 'text-success'], ['Min Withdraw', '₹'.number_format($app->min_withdrawal), ''], ['Rating', $app->rating.' ★', 'text-gold'], ['Downloads', $app->votes, '']] as [$k, $v, $c])
                    <div class="rounded-xl px-4 py-3 text-center" style="background:var(--surface-2);">
                        <p class="text-[0.68rem] uppercase tracking-wide text-muted">{{ $k }}</p>
                        <p class="font-display font-bold text-lg {{ $c }}">{{ $v }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-3 mt-5">
            {{-- Features + About --}}
            <div class="lg:col-span-2 space-y-5">
                @if ($app->featuresArray())
                    <div class="card p-6">
                        <h2 class="font-display font-bold text-xl mb-4">Key Features &amp; Benefits</h2>
                        <ul class="space-y-2.5">
                            @foreach ($app->featuresArray() as $f)
                                <li class="flex items-start gap-2.5"><span class="text-success mt-0.5 shrink-0"><x-icon name="check-circle" class="w-5 h-5" /></span><span class="text-text-soft">{{ $f }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($app->about_paragraph)
                    <div class="card p-6">
                        <h2 class="font-display font-bold text-xl mb-3">About {{ $app->name }}</h2>
                        <p class="text-text-soft leading-relaxed whitespace-pre-line">{{ $app->about_paragraph }}</p>
                    </div>
                @endif

                {{-- Reviews --}}
                <div class="card p-6">
                    <h2 class="font-display font-bold text-xl mb-4">Player Reviews <span class="text-muted font-sans text-base">({{ $reviews->count() }})</span></h2>

                    @forelse ($reviews as $r)
                        <div class="py-4 {{ !$loop->last ? 'border-b' : '' }}" style="border-color:var(--line);">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full grid place-items-center text-white font-bold text-sm shrink-0" style="background:linear-gradient(135deg,var(--brand),var(--brand-2));">{{ strtoupper(substr($r->author,0,1)) }}</div>
                                <div>
                                    <p class="font-semibold text-sm">{{ $r->author }}</p>
                                    <div class="flex gap-0.5 text-gold">@for ($i=1;$i<=5;$i++)<x-icon name="star" class="w-3.5 h-3.5" style="{{ $i <= $r->rating ? 'fill:currentColor' : 'opacity:.3' }}" />@endfor</div>
                                </div>
                                <span class="ml-auto text-muted text-xs">{{ $r->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-text-soft text-sm mt-2">{{ $r->comment }}</p>
                        </div>
                    @empty
                        <p class="text-muted text-sm mb-2">No reviews yet — be the first to share your experience!</p>
                    @endforelse

                    {{-- Submit review --}}
                    <form method="POST" action="{{ route('app.review', $app) }}" class="mt-5 pt-5 border-t space-y-3" style="border-color:var(--line);"
                          x-data="{ rating: 5 }">
                        @csrf
                        <p class="font-semibold">Write a review</p>
                        <div class="flex gap-1">
                            <template x-for="i in 5" :key="i">
                                <button type="button" @click="rating = i" class="text-gold" :class="i <= rating ? 'opacity-100' : 'opacity-30'">
                                    <x-icon name="star" class="w-6 h-6" style="fill:currentColor" />
                                </button>
                            </template>
                            <input type="hidden" name="rating" :value="rating">
                        </div>
                        <input name="author" class="field" placeholder="Your name" required maxlength="120">
                        <textarea name="comment" class="field" rows="3" placeholder="Share your experience…" required maxlength="1000"></textarea>
                        <button class="btn btn-primary"><x-icon name="send" class="w-4 h-4" /> Submit Review</button>
                    </form>
                </div>
            </div>

            {{-- How to download + related --}}
            <div class="space-y-5">
                @if ($app->stepsArray())
                    <div class="card p-6">
                        <h2 class="font-display font-bold text-xl mb-4">How to Download</h2>
                        <ol class="space-y-3">
                            @foreach ($app->stepsArray() as $i => $step)
                                <li class="flex items-start gap-3">
                                    <span class="w-6 h-6 rounded-full grid place-items-center text-white text-xs font-bold shrink-0" style="background:linear-gradient(135deg,var(--brand),var(--brand-2));">{{ $i + 1 }}</span>
                                    <span class="text-text-soft text-sm">{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                @if ($related->isNotEmpty())
                    <div class="card p-6">
                        <h2 class="font-display font-bold text-xl mb-4">Similar Apps</h2>
                        <div class="space-y-3">
                            @foreach ($related as $rel)
                                <a href="{{ route('app.show', $rel) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-2 transition-colors">
                                    <x-app-logo :app="$rel" size="w-11 h-11" />
                                    <div class="min-w-0">
                                        <p class="font-semibold text-sm truncate">{{ $rel->name }}</p>
                                        <p class="text-success text-xs font-bold">₹{{ number_format($rel->sign_up_bonus) }} bonus</p>
                                    </div>
                                    <span class="ml-auto text-muted"><x-icon name="chevron-right" class="w-4 h-4" /></span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-site>
