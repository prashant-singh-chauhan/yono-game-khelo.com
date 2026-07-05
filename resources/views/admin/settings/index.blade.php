<x-admin title="Portal Settings">

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5"
          x-data="{ pwd: false, pwd2: false }">
        @csrf
        @method('PUT')

        <div class="card px-5 sm:px-7 py-4 flex items-center gap-2.5">
            <span class="text-brand"><x-icon name="sliders" class="w-5 h-5" /></span>
            <h2 class="font-display font-bold text-lg">Configure Web Portal</h2>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            {{-- Branding --}}
            <div class="card p-6 lg:col-span-2">
                <p class="section-eyebrow mb-4">1. Branding &amp; Identity</p>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label" for="portal_title">Portal Title <span class="text-danger">*</span></label>
                        <input id="portal_title" name="portal_title" class="field" required value="{{ old('portal_title', $settings['portal_title']) }}">
                    </div>
                    <div>
                        <label class="field-label" for="portal_tagline">Portal Tagline <span class="text-danger">*</span></label>
                        <input id="portal_tagline" name="portal_tagline" class="field" required value="{{ old('portal_tagline', $settings['portal_tagline']) }}">
                    </div>
                    <div>
                        <label class="field-label" for="telegram_join_url">Telegram Join URL <span class="text-danger">*</span></label>
                        <input id="telegram_join_url" name="telegram_join_url" class="field" required value="{{ old('telegram_join_url', $settings['telegram_join_url']) }}">
                    </div>
                    <div>
                        <label class="field-label" for="whatsapp_number">WhatsApp Support Number</label>
                        <input id="whatsapp_number" name="whatsapp_number" class="field" placeholder="+9198765432" value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="field-label" for="brand_logo_url">Brand Logo URL</label>
                        <input id="brand_logo_url" name="brand_logo_url" class="field" placeholder="https://allnewyonoapps.com/logo.jpg" value="{{ old('brand_logo_url', $settings['brand_logo_url']) }}">
                    </div>
                </div>
            </div>

            {{-- Color customizer --}}
            <div class="card p-6" x-data="{
                    start: @js(old('header_gradient_start', $settings['header_gradient_start'] ?: '#6366f1')),
                    end: @js(old('header_gradient_end', $settings['header_gradient_end'] ?: '#8b5cf6')),
                    accent: @js(old('theme_accent', $settings['theme_accent'] ?: '#6366f1')),
                 }">
                <p class="section-eyebrow mb-4">2. Portal Color Customizer</p>

                <div class="rounded-xl h-16 mb-5 ring-1 ring-[var(--line)]"
                     :style="`background:linear-gradient(120deg,${start},${end})`"></div>

                @foreach ([['header_gradient_start','Header Gradient Start','start'],['header_gradient_end','Header Gradient End','end'],['theme_accent','Global Theme Accent','accent']] as [$key,$label,$model])
                    <div class="mb-3">
                        <label class="field-label">{{ $label }}</label>
                        <div class="flex items-center gap-2">
                            <input type="color" x-model="{{ $model }}" class="w-11 h-11 rounded-lg cursor-pointer border-0 bg-transparent p-0 shrink-0" style="background:transparent">
                            <input type="text" name="{{ $key }}" x-model="{{ $model }}" class="field font-mono !py-2.5 uppercase">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Legal --}}
        <div class="card p-6">
            <p class="section-eyebrow mb-4">3. Legal Warnings &amp; Alert Statements</p>
            <div class="space-y-4">
                <div>
                    <label class="field-label" for="site_disclaimer">General Site Disclaimer <span class="text-muted font-normal">(appears in red alert card)</span></label>
                    <textarea id="site_disclaimer" name="site_disclaimer" class="field" rows="4">{{ old('site_disclaimer', $settings['site_disclaimer']) }}</textarea>
                </div>
                <div>
                    <label class="field-label" for="banned_states">Banned States Notification Alert <span class="text-muted font-normal">(appears in amber shield card)</span></label>
                    <textarea id="banned_states" name="banned_states" class="field" rows="3">{{ old('banned_states', $settings['banned_states']) }}</textarea>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="card p-6">
            <p class="section-eyebrow mb-4">4. Search Engine Optimization (SEO) Configurations</p>
            <div class="space-y-4">
                <div>
                    <label class="field-label" for="seo_keywords">Global Meta SEO Keywords <span class="text-muted font-normal">(comma separated)</span></label>
                    <input id="seo_keywords" name="seo_keywords" class="field" value="{{ old('seo_keywords', $settings['seo_keywords']) }}">
                </div>
                <div>
                    <label class="field-label" for="seo_description">Global Meta SEO Description</label>
                    <textarea id="seo_description" name="seo_description" class="field" rows="2">{{ old('seo_description', $settings['seo_description']) }}</textarea>
                </div>
                <div>
                    <label class="field-label" for="social_card_url">Social Card Image URL</label>
                    <input id="social_card_url" name="social_card_url" class="field" placeholder="https://example.com/social-card.jpg" value="{{ old('social_card_url', $settings['social_card_url']) }}">
                </div>
            </div>
        </div>

        {{-- Telegram bot --}}
        <div class="card p-6">
            <p class="section-eyebrow mb-1 flex items-center gap-2"><x-icon name="send" class="w-4 h-4" />5. Telegram Bot Auto-Notifications</p>
            <p class="text-muted text-sm mb-4">When configured, a message is automatically sent to your Telegram channel/group whenever a new app is added. Leave blank to disable.</p>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="field-label" for="telegram_bot_token">Telegram Bot Token</label>
                    <input id="telegram_bot_token" name="telegram_bot_token" class="field font-mono" placeholder="e.g. 1234567890:ABCDefgh…" value="{{ old('telegram_bot_token', $settings['telegram_bot_token']) }}">
                </div>
                <div>
                    <label class="field-label" for="telegram_chat_id">Channel / Group Chat ID</label>
                    <input id="telegram_chat_id" name="telegram_chat_id" class="field" placeholder="@yourchannel or -100…" value="{{ old('telegram_chat_id', $settings['telegram_chat_id']) }}">
                </div>
            </div>
            <p class="text-muted text-xs mt-3">Create a bot via <b>@BotFather</b> → get your bot token → add it to your channel as Admin → get Chat ID from <b>@userinfobot</b>.</p>
        </div>

        {{-- Credentials --}}
        <div class="card p-6">
            <p class="section-eyebrow mb-1 flex items-center gap-2"><x-icon name="key" class="w-4 h-4" />6. Admin Credentials Security Lock</p>
            <p class="text-muted text-sm mb-4">Fill out the fields below ONLY if you explicitly want to change the administrator password. Leave blank to keep current password.</p>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="field-label" for="new_password">New Password</label>
                    <div class="relative">
                        <input id="new_password" name="new_password" :type="pwd ? 'text' : 'password'" class="field pr-11 @error('new_password') !border-danger @enderror" placeholder="••••••••" autocomplete="new-password">
                        <button type="button" @click="pwd = !pwd" tabindex="-1" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-text"><x-icon name="eye" class="w-5 h-5" /></button>
                    </div>
                    @error('new_password') <p class="text-danger text-sm mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="field-label" for="new_password_confirmation">Confirm Password</label>
                    <div class="relative">
                        <input id="new_password_confirmation" name="new_password_confirmation" :type="pwd2 ? 'text' : 'password'" class="field pr-11" placeholder="Confirm password matching" autocomplete="new-password">
                        <button type="button" @click="pwd2 = !pwd2" tabindex="-1" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-text"><x-icon name="eye" class="w-5 h-5" /></button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card px-5 sm:px-6 py-4 flex flex-wrap items-center gap-3 sticky bottom-4 z-20">
            <button type="submit" class="btn btn-primary"><x-icon name="save" class="w-4.5 h-4.5" /> Save Site Configurations</button>
            <a href="{{ route('sitemap') }}" target="_blank" rel="noopener" class="btn btn-ghost"
               @click="window.dispatchEvent(new CustomEvent('toast', { detail: { msg: 'Live sitemap opened — it is auto-generated from all apps.', type: 'success' } }))">
                <x-icon name="sitemap" class="w-4.5 h-4.5" /> View &amp; Publish Sitemap
            </a>
        </div>
    </form>

</x-admin>
