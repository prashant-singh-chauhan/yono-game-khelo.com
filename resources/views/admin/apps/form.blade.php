@php $editing = $app->exists; @endphp

<x-admin :title="$editing ? 'Edit Application' : 'Create Application'">

    <form method="POST"
          action="{{ $editing ? route('admin.apps.update', $app) : route('admin.apps.store') }}"
          enctype="multipart/form-data"
          x-data="appForm()"
          class="space-y-5">
        @csrf
        @if ($editing) @method('PUT') @endif

        {{-- Header --}}
        <div class="card px-5 sm:px-7 py-4 flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2.5">
                <span class="text-brand"><x-icon name="{{ $editing ? 'edit' : 'plus-circle' }}" class="w-5 h-5" /></span>
                <h2 class="font-display font-bold text-lg">{{ $editing ? 'Edit Gaming App' : 'Add New Gaming App' }}</h2>
            </div>
            <a href="{{ route('admin.apps.index') }}" class="btn btn-ghost btn-sm ml-auto">
                <x-icon name="chevron-left" class="w-4 h-4" /> Cancel and Return
            </a>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            {{-- 1. Primary Information --}}
            <div class="card p-6 lg:col-span-2">
                <p class="section-eyebrow mb-4">1. Primary Information</p>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label" for="name">App Name <span class="text-danger">*</span></label>
                        <input id="name" name="name" class="field @error('name') !border-danger @enderror" required
                               placeholder="e.g. Club INR" x-model="name" value="{{ old('name', $app->name) }}">
                        @error('name') <p class="text-danger text-sm mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="field-label" for="slug">URL Slug <span class="text-muted font-normal">(leave blank to auto-slug)</span></label>
                        <input id="slug" name="slug" class="field @error('slug') !border-danger @enderror"
                               placeholder="e.g. club-inr" x-model="slug" value="{{ old('slug', $app->slug) }}">
                        @error('slug') <p class="text-danger text-sm mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="field-label" for="download_link">Download Link (Affiliate / APK direct) <span class="text-danger">*</span></label>
                        <input id="download_link" name="download_link" type="url" class="field @error('download_link') !border-danger @enderror"
                               placeholder="https://example.com/apk-download" value="{{ old('download_link', $app->download_link) }}">
                        @error('download_link') <p class="text-danger text-sm mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="hidden" name="is_new_release" value="0">
                            <input type="checkbox" name="is_new_release" value="1" class="switch"
                                   {{ old('is_new_release', $app->is_new_release ?? true) ? 'checked' : '' }}>
                            <span class="font-medium text-text-soft">New Release Category</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- 2. Card Specifications --}}
            <div class="card p-6">
                <p class="section-eyebrow mb-4">2. Card Specifications</p>
                <div class="space-y-4">
                    <div>
                        <label class="field-label" for="sign_up_bonus">Sign Up Bonus <span class="text-danger">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-muted">₹</span>
                            <input id="sign_up_bonus" name="sign_up_bonus" type="number" min="0" class="field !pl-8" required value="{{ old('sign_up_bonus', $app->sign_up_bonus ?? 50) }}">
                        </div>
                    </div>
                    <div>
                        <label class="field-label" for="min_withdrawal">Min. Withdrawal <span class="text-danger">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-muted">₹</span>
                            <input id="min_withdrawal" name="min_withdrawal" type="number" min="0" class="field !pl-8" required value="{{ old('min_withdrawal', $app->min_withdrawal ?? 100) }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="field-label" for="rating">Rating <span class="text-danger">*</span></label>
                            <input id="rating" name="rating" type="number" step="0.1" min="0" max="5" class="field !px-2.5" required value="{{ old('rating', $app->rating ?? '4.5') }}">
                        </div>
                        <div>
                            <label class="field-label" for="votes">Votes <span class="text-danger">*</span></label>
                            <input id="votes" name="votes" class="field !px-2.5" required placeholder="10K" value="{{ old('votes', $app->votes ?? '10K') }}">
                        </div>
                        <div>
                            <label class="field-label" for="app_size">Size <span class="text-danger">*</span></label>
                            <input id="app_size" name="app_size" class="field !px-2.5" required placeholder="45MB" value="{{ old('app_size', $app->app_size ?? '45MB') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Branding --}}
        <div class="card p-6">
            <p class="section-eyebrow mb-4">3. Branding Visual Assets</p>
            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <div class="shrink-0">
                    <template x-if="preview">
                        <img :src="preview" alt="Logo preview" class="w-24 h-24 rounded-2xl object-cover ring-1 ring-[var(--line)]">
                    </template>
                    <template x-if="!preview">
                        <div class="w-24 h-24 rounded-2xl grid place-items-center text-white font-display font-bold text-xl"
                             style="background:linear-gradient(135deg,var(--brand),var(--brand-2));"
                             x-text="(name || 'APP').replace(/[^A-Za-z0-9]/g,'').slice(0,3).toUpperCase() || 'APP'"></div>
                    </template>
                </div>
                <div class="flex-1 w-full">
                    <label class="field-label" for="logo_url">App Logo/Icon Image URL <span class="text-muted font-normal">(leave blank for elegant dynamic fallback)</span></label>
                    <input id="logo_url" name="logo_url" type="url" class="field" placeholder="https://example.com/logo.webp"
                           x-model="logoUrl" @input.debounce.400ms="syncUrlPreview()" value="{{ old('logo_url', $app->logo_url) }}">
                    <div class="flex items-center gap-3 mt-3 flex-wrap">
                        <label class="btn btn-ghost btn-sm cursor-pointer">
                            <x-icon name="upload" class="w-4 h-4" /> Upload Image
                            <input type="file" name="logo" accept="image/*" class="hidden" @change="onFile($event)">
                        </label>
                        <span class="text-muted text-sm" x-text="fileName || 'Or provide an image URL above. Uploaded files take precedence.'"></span>
                    </div>
                    @error('logo') <p class="text-danger text-sm mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- 4. Explanations --}}
        <div class="card p-6">
            <p class="section-eyebrow mb-4">4. Explanations &amp; Content Guides</p>
            <div class="space-y-4">
                <div>
                    <label class="field-label" for="short_intro">Short Introduction / Hinglish Review Summary</label>
                    <textarea id="short_intro" name="short_intro" class="field" rows="2"
                              placeholder="Hinglish intro text detailing registration rewards, withdraw minimums, etc.">{{ old('short_intro', $app->short_intro) }}</textarea>
                </div>
                <div>
                    <label class="field-label" for="about_paragraph">Detailed About Paragraph (SEO Guide)</label>
                    <textarea id="about_paragraph" name="about_paragraph" class="field" rows="4"
                              placeholder="A comprehensive paragraph about the app's history, reliability, and security…">{{ old('about_paragraph', $app->about_paragraph) }}</textarea>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label" for="features">Key Features &amp; Benefits <span class="text-muted font-normal">(one per line)</span></label>
                        <textarea id="features" name="features" class="field" rows="5"
                                  placeholder="Welcome Bonus: Claim up to ₹500 instantly&#10;Low withdrawal: Minimum is ₹100&#10;Multi-language Support">{{ old('features', $app->features) }}</textarea>
                    </div>
                    <div>
                        <label class="field-label" for="download_steps">How to Download Steps <span class="text-muted font-normal">(one per line)</span></label>
                        <textarea id="download_steps" name="download_steps" class="field" rows="5"
                                  placeholder="Click Download to get the APK file&#10;Go to Settings > Enable Unknown Sources&#10;Open APK and tap Install">{{ old('download_steps', $app->download_steps) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. SEO --}}
        <div class="card p-6">
            <p class="section-eyebrow mb-4">5. Search Engine Optimization (SEO) Fields</p>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="field-label" for="seo_title">SEO Override Title Tag</label>
                    <input id="seo_title" name="seo_title" class="field" placeholder="Club INR App Yono – Free Welcome Bonus ₹505" value="{{ old('seo_title', $app->seo_title) }}">
                </div>
                <div>
                    <label class="field-label" for="seo_keywords">SEO Keywords <span class="text-muted font-normal">(comma separated)</span></label>
                    <input id="seo_keywords" name="seo_keywords" class="field" placeholder="Club INR, Club INR download, Club INR APK" value="{{ old('seo_keywords', $app->seo_keywords) }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="field-label" for="seo_meta_description">SEO Meta Description Override</label>
                    <textarea id="seo_meta_description" name="seo_meta_description" class="field" rows="2"
                              placeholder="Enter a highly compelling, keyword-rich SEO description summarizing page details…">{{ old('seo_meta_description', $app->seo_meta_description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- 6. Promo --}}
        <div class="card p-6">
            <p class="section-eyebrow mb-4 flex items-center gap-2"><x-icon name="tag" class="w-4 h-4" />6. Exclusive Promo Code (Optional)</p>
            <label class="field-label" for="promo_code">Promo / Referral Code <span class="text-muted font-normal">(leave blank if none)</span></label>
            <input id="promo_code" name="promo_code" class="field uppercase max-w-md" placeholder="E.G. YONO500, RUMMY100, CLUBINR" value="{{ old('promo_code', $app->promo_code) }}">
            <p class="text-muted text-sm mt-2 flex items-center gap-1.5"><x-icon name="sparkles" class="w-4 h-4 text-brand" />Users will see a "Copy Code" button on the homepage &amp; game detail page.</p>
        </div>

        {{-- Sticky action bar --}}
        <div class="card px-5 sm:px-6 py-4 flex items-center gap-3 sticky bottom-4 z-20">
            <button type="submit" class="btn btn-primary" x-ref="submit">
                <x-icon name="save" class="w-4.5 h-4.5" /> {{ $editing ? 'Update Game Details' : 'Save Game Details' }}
            </button>
            <a href="{{ route('admin.apps.index') }}" class="btn btn-ghost">Discard Changes</a>
            @if ($editing)
                <span class="ml-auto text-muted text-sm hidden sm:block">Last updated {{ $app->updated_at->diffForHumans() }}</span>
            @endif
        </div>
    </form>

    <script>
        function appForm() {
            return {
                name: @js(old('name', $app->name)),
                slug: @js(old('slug', $app->slug)),
                logoUrl: @js(old('logo_url', $app->logo_url)),
                preview: @js($app->logo()),
                fileName: '',
                syncUrlPreview() {
                    if (!this.fileName) this.preview = this.logoUrl || @js($app->logo());
                },
                onFile(e) {
                    const f = e.target.files[0];
                    if (!f) return;
                    this.fileName = f.name;
                    this.preview = URL.createObjectURL(f);
                },
            };
        }
    </script>

</x-admin>
