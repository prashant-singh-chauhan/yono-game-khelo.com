@php use App\Models\Setting; $portalTitle = Setting::get('portal_title', 'Yono Game Khelo'); @endphp
<x-site title="About Us" description="About {{ $portalTitle }} — your trusted discovery portal for rummy, slots & teen patti gaming apps in India.">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-12 space-y-6">

        {{-- Header --}}
        <div class="text-center mb-2 rise">
            <span class="badge badge-brand mb-3"><x-icon name="sparkles" class="w-3.5 h-3.5" /> Est. 2026</span>
            <h1 class="font-display font-bold text-4xl">About Us</h1>
        </div>

        {{-- Welcome --}}
        <div class="card p-6 sm:p-8">
            <h2 class="font-display font-bold text-2xl mb-3">Welcome to <span class="text-gradient">{{ $portalTitle }}</span></h2>
            <div class="space-y-3 text-text-soft leading-relaxed">
                <p>{{ $portalTitle }} was born with a simple yet ambitious vision: to create the ultimate discovery portal for real-money mobile gaming enthusiasts in India. We noticed how challenging it can be for players to browse, compare, and safely download mobile gaming apps.</p>
                <p>Our dedicated team aggregates the finest Rummy, Slots, and Teen Patti games from trusted Yono developers under one fast, responsive catalog. We are constantly reviewing applications to provide you with verified stats, signup bonuses, and withdrawal-speed records.</p>
            </div>
        </div>

        {{-- Mission (gradient) --}}
        <div class="rounded-[var(--radius-xl2)] p-8 sm:p-10 text-center text-white relative overflow-hidden"
             style="background:linear-gradient(135deg,var(--brand),var(--brand-2) 55%,var(--accent));box-shadow:0 20px 45px -20px var(--glow);">
            <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/15 blur-2xl"></div>
            <div class="absolute -left-12 -bottom-12 w-44 h-44 rounded-full bg-black/10 blur-2xl"></div>
            <div class="relative flex flex-col items-center gap-3">
                <div class="w-14 h-14 rounded-2xl grid place-items-center bg-white/20 backdrop-blur ring-1 ring-white/30">
                    <x-icon name="star" class="w-7 h-7" style="fill:currentColor" />
                </div>
                <h2 class="font-display font-bold text-2xl">Our Mission</h2>
                <p class="max-w-lg text-white/90 leading-relaxed">To provide the best gaming and app experience for Indian users with secure downloads, verified information and reliable service.</p>
            </div>
        </div>

        {{-- Feature grid --}}
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 stagger">
            @foreach ([
                ['shield', 'Secure Downloads', 'All apps are verified and safe', '#10b981', '#059669'],
                ['phone', 'Mobile Optimized', 'Perfect experience on all devices', '#3b82f6', '#6366f1'],
                ['users', 'Community Focused', 'Built for Indian users', '#8b5cf6', '#a855f7'],
                ['clock', '24/7 Support', 'Always here to help you', '#f59e0b', '#f97316'],
            ] as [$ic, $t, $d, $c1, $c2])
                <div class="card card-hover p-6 text-center flex flex-col items-center gap-2">
                    <div class="w-12 h-12 rounded-2xl grid place-items-center text-white mb-1" style="background:linear-gradient(135deg,{{ $c1 }},{{ $c2 }});box-shadow:0 10px 22px -12px {{ $c1 }};">
                        <x-icon :name="$ic" class="w-6 h-6" />
                    </div>
                    <h3 class="font-display font-bold text-base">{{ $t }}</h3>
                    <p class="text-muted text-sm leading-relaxed">{{ $d }}</p>
                </div>
            @endforeach
        </div>

        {{-- Core values --}}
        <div class="card p-6 sm:p-8">
            <h2 class="font-display font-bold text-2xl mb-4">Our Core Values</h2>
            <ul class="space-y-3">
                @foreach ([
                    ['Transparency', 'We present precise signup-bonus amounts and withdrawal specs without fluff.'],
                    ['Security First', 'Every link on our portal points directly to safe, official channels.'],
                    ['User Focus', 'Our mobile-first template lets you search and download in just two taps!'],
                ] as [$t, $d])
                    <li class="flex items-start gap-3">
                        <span class="text-success mt-0.5 shrink-0"><x-icon name="check-circle" class="w-5 h-5" /></span>
                        <span class="text-text-soft leading-relaxed"><b class="text-text">{{ $t }}:</b> {{ $d }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Responsible gaming --}}
        <div class="rounded-[var(--radius-xl2)] p-6 sm:p-7 border" style="border-color:color-mix(in srgb,var(--warning) 40%,transparent);background:color-mix(in srgb,var(--warning) 9%,transparent);">
            <h2 class="flex items-center gap-2 font-display font-bold text-xl text-warning mb-2"><x-icon name="shield" class="w-5 h-5" /> Responsible Gaming</h2>
            <p class="text-text-soft leading-relaxed">While real-money card and board gaming is engaging and strategic, it carries inherent financial risks. We strongly advocate for self-control and responsible bankroll budgeting. Set strict gaming limits and pace yourself during play. This platform is strictly for users aged 18 and above.</p>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-3 justify-center pt-2">
            <a href="{{ route('home') }}" class="btn btn-primary"><x-icon name="chevron-left" class="w-4.5 h-4.5" /> Back to Home</a>
            <a href="{{ route('contact') }}" class="btn btn-ghost"><x-icon name="mail" class="w-4.5 h-4.5" /> Contact Us</a>
        </div>
    </div>
</x-site>
