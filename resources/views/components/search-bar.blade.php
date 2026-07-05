@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'relative '.$class]) }}
     x-data="navSearch()" @keydown.escape="close()" @click.outside="close()">

    <form method="GET" action="{{ route('home') }}" role="search" @submit="onSubmit($event)">
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-muted pointer-events-none">
                <x-icon name="search" class="w-4.5 h-4.5" />
            </span>
            <input type="search" name="q" x-ref="input" x-model="q"
                   @input.debounce.180ms="fetchSuggestions()"
                   @focus="q.length && (open = true)"
                   @keydown.arrow-down.prevent="move(1)"
                   @keydown.arrow-up.prevent="move(-1)"
                   @keydown.enter="onEnter($event)"
                   autocomplete="off" role="combobox" aria-autocomplete="list"
                   :aria-expanded="open" aria-controls="nav-search-list"
                   class="field !pl-10 !pr-10 !py-2.5"
                   placeholder="Search apps…">
            <kbd x-show="!q" class="absolute right-3 top-1/2 -translate-y-1/2 text-[0.7rem] font-mono px-1.5 py-0.5 rounded border pointer-events-none text-muted"
                 style="border-color:var(--line);background:var(--surface-2);">/</kbd>
            <button type="button" x-show="q" x-cloak @click="clearInput()"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-muted hover:text-text" aria-label="Clear search">
                <x-icon name="x" class="w-4 h-4" />
            </button>
        </div>

        {{-- Suggestions dropdown --}}
        <div x-show="open" x-cloak x-transition.opacity.duration.150ms
             id="nav-search-list" role="listbox"
             class="card absolute left-0 right-0 mt-2 p-1.5 z-50 max-h-[70vh] overflow-y-auto shadow-2xl">

            <template x-if="loading">
                <div class="px-3 py-3 text-sm text-muted flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full border-2 border-brand border-t-transparent animate-spin"></span> Searching…
                </div>
            </template>

            <template x-for="(item, i) in items" :key="item.slug">
                <a :href="item.url" role="option" :aria-selected="i === active"
                   @mouseenter="active = i"
                   class="flex items-center gap-3 px-2.5 py-2 rounded-xl transition-colors"
                   :class="i === active ? 'bg-surface-2' : ''">
                    <template x-if="item.logo">
                        <img :src="item.logo" :alt="item.name" class="w-9 h-9 rounded-lg object-cover ring-1 ring-[var(--line)] shrink-0">
                    </template>
                    <template x-if="!item.logo">
                        <span class="w-9 h-9 rounded-lg grid place-items-center text-white text-xs font-bold shrink-0"
                              style="background:linear-gradient(135deg,var(--brand),var(--brand-2));" x-text="item.init"></span>
                    </template>
                    <span class="min-w-0 flex-1">
                        <span class="flex items-center gap-1.5">
                            <span class="font-semibold text-sm truncate" x-text="item.name"></span>
                            <span x-show="item.new" class="badge badge-success">New</span>
                        </span>
                        <span class="text-muted text-xs flex items-center gap-1">
                            <span class="text-gold">★</span><span x-text="item.rating"></span>
                            <span>·</span><span class="text-success font-semibold" x-text="item.bonus + ' bonus'"></span>
                        </span>
                    </span>
                    <span class="text-muted shrink-0"><x-icon name="chevron-right" class="w-4 h-4" /></span>
                </a>
            </template>

            <template x-if="!loading && q.length >= 1 && items.length === 0">
                <div class="px-3 py-3 text-sm text-muted">No apps found for "<span x-text="q"></span>".</div>
            </template>

            <a x-show="q.length >= 1" :href="'{{ route('home') }}?q=' + encodeURIComponent(q)"
               class="flex items-center gap-2 px-2.5 py-2 mt-1 rounded-xl text-sm font-medium text-brand border-t"
               style="border-color:var(--line);">
                <x-icon name="search" class="w-4 h-4" /> See all results for "<span x-text="q"></span>"
            </a>
        </div>
    </form>
</div>

@once
    <script>
        function navSearch() {
            return {
                q: new URLSearchParams(location.search).get('q') || '',
                items: [],
                open: false,
                loading: false,
                active: -1,
                _ctrl: null,
                clearInput() { this.q = ''; this.items = []; this.open = false; this.$refs.input.focus(); },
                close() { this.open = false; this.active = -1; },
                async fetchSuggestions() {
                    const term = this.q.trim();
                    if (!term) { this.items = []; this.open = false; return; }
                    this.open = true; this.loading = true; this.active = -1;
                    if (this._ctrl) this._ctrl.abort();
                    this._ctrl = new AbortController();
                    try {
                        const res = await fetch('{{ route('search.suggest') }}?q=' + encodeURIComponent(term), {
                            headers: { 'Accept': 'application/json' }, signal: this._ctrl.signal,
                        });
                        this.items = await res.json();
                    } catch (e) { if (e.name !== 'AbortError') this.items = []; }
                    finally { this.loading = false; }
                },
                move(dir) {
                    if (!this.open || !this.items.length) return;
                    this.active = (this.active + dir + this.items.length) % this.items.length;
                },
                onEnter(e) {
                    if (this.active >= 0 && this.items[this.active]) {
                        e.preventDefault();
                        window.location = this.items[this.active].url;
                    }
                    // otherwise let the form submit to /?q= (no-JS fallback path)
                },
                onSubmit(e) { /* native GET submit to home search */ },
            };
        }

        // Global "/" shortcut focuses the first visible search input.
        document.addEventListener('keydown', (e) => {
            if (e.key === '/' && !/^(INPUT|TEXTAREA|SELECT)$/.test(document.activeElement.tagName)) {
                const box = [...document.querySelectorAll('input[name="q"]')].find(el => el.offsetParent !== null);
                if (box) { e.preventDefault(); box.focus(); }
            }
        });
    </script>
@endonce
