@php use App\Models\Setting; $portalTitle = Setting::get('portal_title', 'Yono Game Khelo'); @endphp
<x-site title="Privacy Policy" description="Privacy Policy for {{ $portalTitle }} — how we handle your information.">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
        <div class="text-center mb-10 rise">
            <span class="badge badge-brand mb-3"><x-icon name="lock" class="w-3.5 h-3.5" /> Your privacy</span>
            <h1 class="font-display font-bold text-4xl">Privacy Policy</h1>
            <p class="text-muted mt-2">Last updated {{ now()->format('F Y') }}</p>
        </div>

        <div class="card p-6 sm:p-8 space-y-5 text-text-soft leading-relaxed">
            <p>This Privacy Policy explains how {{ $portalTitle }} collects, uses and protects any information you provide when using our website.</p>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">1. Information We Collect</h2>
                <p>We collect only what you voluntarily provide — for example, your name, email address and message when you submit our <a href="{{ route('contact') }}" class="text-brand font-semibold">contact form</a> or post a review. We may also collect basic, non-identifying analytics such as pages visited and device type to improve the site.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">2. How We Use Your Information</h2>
                <ul class="space-y-2 mt-1">
                    <li class="flex items-start gap-2.5"><span class="text-success mt-0.5 shrink-0"><x-icon name="check-circle" class="w-5 h-5" /></span> To respond to your enquiries and support requests</li>
                    <li class="flex items-start gap-2.5"><span class="text-success mt-0.5 shrink-0"><x-icon name="check-circle" class="w-5 h-5" /></span> To display approved reviews on the site</li>
                    <li class="flex items-start gap-2.5"><span class="text-success mt-0.5 shrink-0"><x-icon name="check-circle" class="w-5 h-5" /></span> To improve our content, listings and user experience</li>
                </ul>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">3. Cookies &amp; Preferences</h2>
                <p>We use minimal local storage (for example, to remember your light/dark theme preference). We do not use it to personally identify you. You can clear this at any time from your browser settings.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">4. Sharing of Information</h2>
                <p>We do <b class="text-text">not</b> sell or rent your personal information. We do not share it with third parties except where required by law. Note that when you click a download link, you leave our site and any data you provide to that third-party app is governed by their own privacy policy.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">5. Data Security</h2>
                <p>We take reasonable technical measures to protect the limited information we hold. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">6. Children's Privacy</h2>
                <p>This website and the applications it lists are strictly for users aged <b class="text-text">18 and above</b>. We do not knowingly collect information from anyone under 18.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">7. Your Rights</h2>
                <p>You may request access to, correction of, or deletion of any personal information you have submitted by contacting us. We will respond within a reasonable time.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">8. Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time. The latest version will always be published on this page with an updated date.</p>
            </div>

            <div>
                <h2 class="font-display font-bold text-xl text-text mb-2">9. Contact</h2>
                <p>For any privacy-related questions, please use our <a href="{{ route('contact') }}" class="text-brand font-semibold">Contact page</a>.</p>
            </div>
        </div>

        <div class="text-center mt-8 flex flex-wrap gap-3 justify-center">
            <a href="{{ route('terms') }}" class="btn btn-ghost"><x-icon name="shield" class="w-4.5 h-4.5" /> Terms &amp; Conditions</a>
            <a href="{{ route('disclaimer') }}" class="btn btn-ghost"><x-icon name="alert" class="w-4.5 h-4.5" /> Disclaimer</a>
        </div>
    </div>
</x-site>
