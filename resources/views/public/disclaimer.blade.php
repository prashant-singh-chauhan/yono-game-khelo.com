@php use App\Models\Setting; @endphp
<x-site title="Disclaimer" description="Legal disclaimer and responsible gaming notice for {{ Setting::get('portal_title') }}.">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
        <div class="text-center mb-10 rise">
            <span class="badge badge-warning mb-3"><x-icon name="shield" class="w-3.5 h-3.5" /> Please read carefully</span>
            <h1 class="font-display font-bold text-4xl">Legal Disclaimer</h1>
            <p class="text-muted mt-2">Last updated {{ now()->format('F Y') }}</p>
        </div>

        {{-- General disclaimer --}}
        <div class="rounded-2xl p-6 border mb-5" style="border-color:color-mix(in srgb,var(--danger) 35%,transparent);background:color-mix(in srgb,var(--danger) 8%,transparent);">
            <p class="flex items-center gap-2 font-bold text-danger mb-3 text-lg"><x-icon name="alert" class="w-5 h-5" /> General Disclaimer</p>
            <p class="text-text-soft leading-relaxed whitespace-pre-line">{{ Setting::get('site_disclaimer', 'This is an independent platform. We do not own, manage, or operate any of the apps listed here.') }}</p>
        </div>

        {{-- Banned states --}}
        <div class="rounded-2xl p-6 border mb-5" style="border-color:color-mix(in srgb,var(--warning) 40%,transparent);background:color-mix(in srgb,var(--warning) 10%,transparent);">
            <p class="flex items-center gap-2 font-bold text-warning mb-3 text-lg"><x-icon name="shield" class="w-5 h-5" /> Banned States Notice</p>
            <p class="text-text-soft leading-relaxed">{{ Setting::get('banned_states', 'Rummy is not legal in all states. Please check your local laws before downloading.') }}</p>
        </div>

        {{-- Responsible gaming --}}
        <div class="card p-6 sm:p-8 space-y-4 text-text-soft leading-relaxed">
            <h2 class="font-display font-bold text-xl text-text">Responsible Gaming</h2>
            <p>These applications are intended for entertainment purposes and are strictly for users aged <b class="text-text">18 years and above</b>. Gaming can be addictive and carries financial risk. Please play responsibly: set a budget, take regular breaks, and never spend more than you can afford to lose.</p>
            <h2 class="font-display font-bold text-xl text-text pt-2">No Financial Advice</h2>
            <p>Nothing on this website constitutes financial or legal advice. Bonus amounts, withdrawal terms and app availability are subject to change by the respective app operators without notice.</p>
            <h2 class="font-display font-bold text-xl text-text pt-2">Third-Party Links</h2>
            <p>Download links may direct you to third-party websites or affiliate partners. We are not responsible for the content, security or practices of those external services.</p>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('contact') }}" class="btn btn-ghost"><x-icon name="mail" class="w-4.5 h-4.5" /> Questions? Contact us</a>
        </div>
    </div>
</x-site>
