<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in · Yono Game Khelo</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script>
        (function () {
            try {
                var t = localStorage.getItem('yono-theme');
                if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh grid lg:grid-cols-2">

    {{-- Brand panel --}}
    <div class="relative hidden lg:flex flex-col justify-between p-12 overflow-hidden text-white"
         style="background:linear-gradient(150deg,#4f46e5,#7c3aed 55%,#0ea5e9);">
        <div class="absolute inset-0 opacity-30"
             style="background:radial-gradient(30rem 30rem at 80% 10%,#fff3,transparent 60%),radial-gradient(26rem 26rem at 10% 90%,#22d3ee66,transparent 60%);"></div>

        <div class="relative flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl grid place-items-center bg-white/15 backdrop-blur ring-1 ring-white/25 p-1.5">
                <x-brand-mark class="w-full h-full" />
            </div>
            <span class="font-display font-bold text-2xl">Yono Game Khelo</span>
        </div>

        <div class="relative">
            <p class="section-eyebrow !text-white/70">Admin Console · 2026</p>
            <h2 class="font-display font-bold text-4xl leading-tight mt-3 max-w-md">
                Command every gaming app from one supernatural dashboard.
            </h2>
            <p class="mt-4 text-white/80 max-w-sm leading-relaxed">
                Manage apps, moderate reviews, answer queries and tune your portal — all in a lightning-fast, secure backend.
            </p>
            <div class="mt-8 flex gap-6">
                <div><p class="font-display text-2xl font-bold">{{ \App\Models\App::count() }}+</p><p class="text-white/70 text-sm">Apps managed</p></div>
                <div><p class="font-display text-2xl font-bold">Instant</p><p class="text-white/70 text-sm">Publishing</p></div>
                <div><p class="font-display text-2xl font-bold">Secure</p><p class="text-white/70 text-sm">By default</p></div>
            </div>
        </div>

        <p class="relative text-white/60 text-sm">© {{ date('Y') }} Yono Portal. All rights reserved.</p>
    </div>

    {{-- Form panel --}}
    <div class="flex items-center justify-center p-6 sm:p-10 relative"
         x-data="{ show: false }">
        <button @click="$store.theme.toggle()" class="btn btn-ghost btn-sm !px-2.5 !py-2.5 absolute top-6 right-6" title="Toggle theme">
            <span x-show="!$store.theme.dark" x-cloak><x-icon name="moon" class="w-4.5 h-4.5" /></span>
            <span x-show="$store.theme.dark" x-cloak><x-icon name="sun" class="w-4.5 h-4.5" /></span>
        </button>

        <div class="w-full max-w-sm rise">
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <x-brand-mark class="w-11 h-11" />
                <span class="font-display font-bold text-xl">Yono <span class="text-gradient">Games Khelo</span></span>
            </div>

            <h1 class="font-display font-bold text-3xl">Welcome back</h1>
            <p class="text-muted mt-1.5">Sign in to your administrator account.</p>

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
                @csrf

                <div>
                    <label for="email" class="field-label">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="username" required autofocus
                           value="{{ old('email', 'admin@yonogame.com') }}"
                           class="field @error('email') !border-danger @enderror" placeholder="admin@yonogame.com">
                    @error('email') <p class="text-danger text-sm mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="field-label">Password</label>
                    <div class="relative">
                        <input id="password" name="password" :type="show ? 'text' : 'password'"
                               autocomplete="current-password" required
                               class="field pr-11" placeholder="••••••••">
                        <button type="button" @click="show = !show" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-text"
                                :aria-label="show ? 'Hide password' : 'Show password'">
                            <x-icon name="eye" class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2.5 select-none cursor-pointer text-sm text-text-soft">
                    <input type="checkbox" name="remember" class="switch !w-10 !h-[1.35rem]">
                    <span>Keep me signed in</span>
                </label>

                <button type="submit" class="btn btn-primary w-full !py-3 mt-2"
                        x-data @click="$el.querySelector('span').textContent='Signing in…'">
                    <span>Sign in to dashboard</span>
                </button>
            </form>

            <div class="mt-6 p-3.5 rounded-xl text-sm" style="background:var(--surface-2);border:1px solid var(--line);">
                <p class="text-muted"><span class="font-semibold text-text-soft">Demo credentials</span> — admin@yonogame.com / password</p>
            </div>
        </div>
    </div>

</body>
</html>
