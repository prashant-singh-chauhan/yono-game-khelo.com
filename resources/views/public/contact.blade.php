@php use App\Models\Setting; @endphp
<x-site title="Contact Us" description="Get in touch with the team behind {{ Setting::get('portal_title') }}.">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-12">
        <div class="text-center mb-10 rise">
            <span class="badge badge-brand mb-3"><x-icon name="mail" class="w-3.5 h-3.5" /> We're here to help</span>
            <h1 class="font-display font-bold text-4xl">Contact Us</h1>
            <p class="text-muted mt-2 max-w-lg mx-auto">Have a question, suggestion, or want to list your app? Send us a message and we'll get back to you.</p>
        </div>

        <div class="grid gap-5 lg:grid-cols-5">
            {{-- Info --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl grid place-items-center text-white shrink-0" style="background:linear-gradient(135deg,#2AABEE,#229ED9);"><x-icon name="send" class="w-5 h-5" /></div>
                    <div><p class="font-semibold">Telegram</p><a href="{{ Setting::get('telegram_join_url','#') }}" target="_blank" class="text-brand text-sm hover:underline">Join our channel</a></div>
                </div>
                {{-- @if ($wa = Setting::get('whatsapp_number'))
                    <div class="card p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl grid place-items-center text-white shrink-0" style="background:linear-gradient(135deg,#25D366,#128C7E);"><x-icon name="mail" class="w-5 h-5" /></div>
                        <div><p class="font-semibold">WhatsApp</p><a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$wa) }}" class="text-brand text-sm hover:underline">{{ $wa }}</a></div>
                    </div>
                @endif --}}
                <div class="card p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl grid place-items-center text-white shrink-0" style="background:linear-gradient(135deg,var(--brand),var(--brand-2));"><x-icon name="globe" class="w-5 h-5" /></div>
                    <div><p class="font-semibold">Response time</p><p class="text-muted text-sm">Usually within 24 hours</p></div>
                </div>
            </div>

            {{-- Form --}}
            <div class="lg:col-span-3">
                <form method="POST" action="{{ route('contact.store') }}" enctype="multipart/form-data" class="card p-6 sm:p-8 space-y-4">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="field-label" for="sender_name">Your Name</label>
                            <input id="sender_name" name="sender_name" class="field" required value="{{ old('sender_name') }}" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="field-label" for="email">Email Address</label>
                            <input id="email" name="email" type="email" class="field" required value="{{ old('email') }}" placeholder="you@example.com">
                        </div>
                    </div>
                    <div>
                        <label class="field-label" for="subject">Subject</label>
                        <input id="subject" name="subject" class="field" value="{{ old('subject') }}" placeholder="How can we help?">
                    </div>
                    <div>
                        <label class="field-label" for="message">Message</label>
                        <textarea id="message" name="message" class="field" rows="5" required placeholder="Write your message…">{{ old('message') }}</textarea>
                    </div>
                    <div x-data="{ name: '' }">
                        <label class="field-label" for="attachment">Attachment <span class="text-muted font-normal">(optional)</span></label>
                        <label for="attachment" class="flex items-center gap-3 cursor-pointer field !py-2.5 hover:border-[var(--line-strong)]">
                            <span class="btn btn-ghost btn-sm shrink-0 pointer-events-none"><x-icon name="upload" class="w-4 h-4" /> Choose file</span>
                            <span class="text-muted text-sm truncate" x-text="name || 'JPG, PNG, PDF or DOC · max 5 MB'"></span>
                        </label>
                        <input id="attachment" name="attachment" type="file" class="hidden"
                               accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx"
                               @change="name = $event.target.files.length ? $event.target.files[0].name : ''">
                        @error('attachment') <p class="text-danger text-sm mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <button class="btn btn-primary w-full !py-3"><x-icon name="send" class="w-4.5 h-4.5" /> Send Message</button>
                </form>
            </div>
        </div>
    </div>
</x-site>
