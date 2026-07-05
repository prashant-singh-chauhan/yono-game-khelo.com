@props(['title' => 'Dashboard', 'heading' => null])

@php
    $nav = [
        ['route' => 'admin.dashboard',      'pattern' => 'admin.dashboard',  'icon' => 'chart',   'label' => 'Dashboard'],
        ['route' => 'admin.apps.index',     'pattern' => 'admin.apps.index', 'icon' => 'gamepad', 'label' => 'Manage Apps'],
        ['route' => 'admin.apps.create',    'pattern' => 'admin.apps.create','icon' => 'plus-circle', 'label' => 'Add New App'],
        ['route' => 'admin.queries.index',  'pattern' => 'admin.queries.*',  'icon' => 'mail',    'label' => 'User Queries', 'badge' => \App\Models\Query::where('is_read', false)->count()],
        ['route' => 'admin.reviews.index',  'pattern' => 'admin.reviews.*',  'icon' => 'star',    'label' => 'Reviews', 'badge' => \App\Models\Review::where('is_approved', false)->count()],
        ['route' => 'admin.settings.index', 'pattern' => 'admin.settings.*', 'icon' => 'sliders', 'label' => 'Portal Settings'],
    ];
    $portalTitle = \App\Models\Setting::get('portal_title', 'Yono Portal');
@endphp

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }} · Yono Game Khelo</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    {{-- Set theme before paint to avoid flash --}}
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
<body x-data="{ sidebar: false }">

<div class="min-h-dvh lg:flex">

    {{-- ═══════════════ Sidebar ═══════════════ --}}
    <div x-show="sidebar" x-cloak @click="sidebar = false"
         x-transition.opacity
         class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"></div>

    <aside
        class="fixed inset-y-0 left-0 z-50 w-[270px] flex flex-col px-4 py-6 transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto"
        :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
        style="background: var(--sidebar); backdrop-filter: blur(22px) saturate(140%); border-right: 1px solid var(--sidebar-line);">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-2 mb-8">
            <x-brand-mark class="w-11 h-11 shrink-0" />
            <div class="min-w-0">
                <p class="font-display font-bold text-lg leading-tight truncate">Yono <span class="text-gradient">Games</span></p>
                <p class="text-[0.68rem] text-muted -mt-0.5 tracking-[0.14em] uppercase">Khelo · Admin</p>
            </div>
            <button @click="sidebar = false" class="ml-auto lg:hidden text-muted hover:text-text p-1" aria-label="Close menu">
                <x-icon name="x" class="w-5 h-5" />
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex flex-col gap-1.5">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="nav-item {{ request()->routeIs($item['pattern']) ? 'active' : '' }}">
                    <x-icon :name="$item['icon']" class="w-5 h-5 shrink-0" />
                    <span>{{ $item['label'] }}</span>
                    @if (!empty($item['badge']))
                        <span class="ml-auto text-[0.7rem] font-bold px-1.5 py-0.5 rounded-full
                            {{ request()->routeIs($item['pattern']) ? 'bg-white/25 text-white' : 'bg-[color-mix(in_srgb,var(--brand)_18%,transparent)] text-brand' }}">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="my-5 border-t" style="border-color: var(--sidebar-line);"></div>

        <div class="flex flex-col gap-1.5">
            <a href="{{ url('/') }}" target="_blank" class="nav-item">
                <x-icon name="external" class="w-5 h-5 shrink-0" />
                <span>Public Website</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item w-full text-left" style="color: var(--danger);">
                    <x-icon name="logout" class="w-5 h-5 shrink-0" />
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <p class="mt-auto pt-6 text-center text-[0.7rem] text-muted">v1.0.0 · © {{ date('Y') }} Yono</p>
    </aside>

    {{-- ═══════════════ Main ═══════════════ --}}
    <div class="flex-1 min-w-0 flex flex-col">

        {{-- Topbar / page header --}}
        <header class="sticky top-0 z-30 px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6">
            <div class="card px-5 sm:px-7 py-4 flex items-center gap-4">
                <button @click="sidebar = true" class="lg:hidden btn-ghost btn btn-sm !px-2" aria-label="Open menu">
                    <x-icon name="menu" class="w-5 h-5" />
                </button>

                <div class="min-w-0">
                    <h1 class="font-display font-bold text-xl sm:text-2xl truncate">{{ $heading ?? $title }}</h1>
                </div>

                <div class="ml-auto flex items-center gap-2 sm:gap-3">
                    {{-- Theme toggle --}}
                    <button @click="$store.theme.toggle()"
                            class="btn btn-ghost btn-sm !px-2.5 !py-2.5 relative overflow-hidden"
                            :aria-label="$store.theme.dark ? 'Switch to light mode' : 'Switch to dark mode'"
                            title="Toggle theme">
                        <span x-show="!$store.theme.dark" x-cloak><x-icon name="moon" class="w-4.5 h-4.5" /></span>
                        <span x-show="$store.theme.dark" x-cloak><x-icon name="sun" class="w-4.5 h-4.5" /></span>
                    </button>

                    {{-- Admin badge --}}
                    <div class="flex items-center gap-2.5 pl-1 sm:pl-2">
                        <div class="w-9 h-9 rounded-full grid place-items-center text-white font-bold text-sm shrink-0"
                             style="background:linear-gradient(135deg,var(--brand),var(--brand-2));">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden sm:block leading-tight">
                            <p class="text-sm font-semibold">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[0.7rem] text-muted">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 sm:py-7">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- ═══════════════ Toast ═══════════════ --}}
<div x-data="toaster()"
     x-init="@if(session('status')) push({ msg: @js(session('status')), type: 'success' }) @endif
             @if($errors->any()) push({ msg: @js($errors->first()), type: 'error' }) @endif
             window.addEventListener('toast', e => push(e.detail))"
     class="fixed bottom-5 right-5 z-[100] flex flex-col gap-2.5 w-[min(92vw,22rem)]">
    <template x-for="t in items" :key="t.id">
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-3"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0 translate-x-4"
             class="card px-4 py-3 flex items-start gap-3 shadow-xl" role="alert" aria-live="polite">
            <div class="mt-0.5 shrink-0" :class="t.type === 'error' ? 'text-danger' : 'text-success'">
                <template x-if="t.type === 'error'"><x-icon name="alert" class="w-5 h-5" /></template>
                <template x-if="t.type !== 'error'"><x-icon name="check-circle" class="w-5 h-5" /></template>
            </div>
            <p class="text-sm text-text-soft flex-1" x-text="t.msg"></p>
            <button @click="dismiss(t.id)" class="text-muted hover:text-text shrink-0"><x-icon name="x" class="w-4 h-4" /></button>
        </div>
    </template>
</div>

</body>
</html>
