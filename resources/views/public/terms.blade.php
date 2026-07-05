@php use App\Models\Setting; $portalTitle = Setting::get('portal_title', 'Yono Game Khelo'); @endphp
<x-site title="Terms & Conditions" description="Terms & Conditions for using {{ $portalTitle }}.">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
        <div class="text-center mb-10 rise">
            <span class="badge badge-brand mb-3"><x-icon name="shield" class="w-3.5 h-3.5" /> Legal</span>
            <h1 class="font-display font-bold text-4xl">Terms &amp; Conditions</h1>
            <p class="text-muted mt-2">Last updated {{ now()->format('F Y') }}</p>
        </div>

        <div class="card p-6 sm:p-8 space-y-5 text-text-soft leading-relaxed">
            <p>Welcome to {{ $portalTitle }}. By accessing or using this website, you agree to be bound by these Terms &amp; Conditions. If you do not agree, please do not use the site.</p>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">1. Eligibility</h2>
                <p>You must be at least <b class="text-text">18 years of age</b> to use this website or download any application listed here. By using the site you confirm that you meet this requirement and that online gaming for real money is legal in your state or jurisdiction.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">2. Nature of the Service</h2>
                <p>{{ $portalTitle }} is an independent discovery and information platform. We <b class="text-text">do not own, develop, operate or control</b> any of the applications listed. We simply aggregate publicly available information such as bonuses, ratings and download links to help you compare options.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">3. Third-Party Applications &amp; Links</h2>
                <p>Download links may direct you to third-party websites or affiliate partners. Any transaction, deposit, withdrawal or dispute is strictly between you and the respective application provider. We are not responsible for their content, security, payouts or practices.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">4. No Warranties</h2>
                <p>All information is provided "as is" without warranties of any kind. Bonus amounts, withdrawal terms and availability are set by the app operators and may change at any time without notice. We do not guarantee the accuracy, completeness or reliability of any listing.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">5. Responsible Gaming</h2>
                <p>Real-money gaming can be addictive and carries financial risk. You are solely responsible for your spending. Set limits, play responsibly, and never wager more than you can afford to lose. See our <a href="{{ route('disclaimer') }}" class="text-brand font-semibold">Disclaimer</a> for details, including states where such apps are banned.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">6. Limitation of Liability</h2>
                <p>To the maximum extent permitted by law, {{ $portalTitle }} and its operators shall not be liable for any direct, indirect, incidental or consequential losses arising from your use of this website or any third-party application discovered through it.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">7. Intellectual Property</h2>
                <p>All trademarks, logos and brand names shown are the property of their respective owners and are used for identification purposes only. This does not imply any affiliation or endorsement.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">8. Changes to These Terms</h2>
                <p>We may update these Terms &amp; Conditions at any time. Continued use of the website after changes constitutes acceptance of the revised terms.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">9. Contact</h2>
                <p>Questions about these terms? Please reach out via our <a href="{{ route('contact') }}" class="text-brand font-semibold">Contact page</a>.</p>
            </div>
        </div>

        <div class="text-center mt-8 flex flex-wrap gap-3 justify-center">
            <a href="{{ route('privacy') }}" class="btn btn-ghost"><x-icon name="lock" class="w-4.5 h-4.5" /> Privacy Policy</a>
            <a href="{{ route('disclaimer') }}" class="btn btn-ghost"><x-icon name="alert" class="w-4.5 h-4.5" /> Disclaimer</a>
        </div>
    </div>
</x-site>
