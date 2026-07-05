<x-admin title="Administrative Panel" heading="Reviews Moderation">

    {{-- Filters --}}
    <form method="GET" class="card p-4 mb-6 flex flex-col sm:flex-row sm:items-end gap-3">
        <div class="flex-1">
            <label class="field-label" for="app">Filter by App</label>
            <select id="app" name="app" class="field" onchange="this.form.submit()">
                <option value="">All apps</option>
                @foreach ($apps as $a)
                    <option value="{{ $a->id }}" {{ $appId === $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="field-label" for="rating">Filter by Rating</label>
            <select id="rating" name="rating" class="field" onchange="this.form.submit()">
                <option value="">All ratings</option>
                @for ($s = 5; $s >= 1; $s--)
                    <option value="{{ $s }}" {{ $rating === $s ? 'selected' : '' }}>{{ $s }} Star{{ $s > 1 ? 's' : '' }} · {{ str_repeat('★', $s) }}</option>
                @endfor
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><x-icon name="search" class="w-4 h-4" /> Apply</button>
            @if ($appId || $rating)
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-ghost"><x-icon name="x" class="w-4 h-4" /> Clear</a>
            @endif
        </div>
    </form>

    {{-- Pending --}}
    <section>
        <div class="flex items-center gap-2.5 mb-4">
            <span class="text-warning"><x-icon name="clock" class="w-5 h-5" /></span>
            <h2 class="font-display font-bold text-lg">Pending Approval</h2>
            <span class="badge badge-warning">{{ $pending->count() }}</span>
        </div>

        @forelse ($pending as $r)
            <div class="card p-5 sm:p-6 mb-4">
                <div class="flex items-start gap-4 flex-wrap">
                    <div class="w-11 h-11 rounded-full grid place-items-center text-white font-bold shrink-0"
                         style="background:linear-gradient(135deg,#8b5cf6,#6366f1);">
                        {{ strtoupper(substr($r->author, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold">{{ $r->author }}</p>
                        <p class="text-muted text-sm flex items-center gap-1.5">
                            <x-icon name="gamepad" class="w-4 h-4" /> {{ $r->app?->name ?? 'General' }}
                        </p>
                    </div>
                    <div class="ml-auto text-right">
                        <div class="flex gap-0.5 justify-end text-gold">
                            @for ($i = 1; $i <= 5; $i++)
                                <x-icon name="star" class="w-4 h-4" style="{{ $i <= $r->rating ? 'fill:currentColor' : 'opacity:.3' }}" />
                            @endfor
                        </div>
                        <p class="text-muted text-xs mt-1">{{ $r->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="mt-4 p-4 rounded-xl italic text-text-soft" style="background:var(--surface-2);">"{{ $r->comment }}"</div>

                <div class="mt-4 flex gap-3">
                    <form method="POST" action="{{ route('admin.reviews.approve', $r) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-success btn-sm"><x-icon name="check" class="w-4 h-4" /> Approve</button>
                    </form>
                    <x-delete-form :action="route('admin.reviews.destroy', $r)" label="Delete" title="Delete this review?" message="The review by {{ $r->author }} will be removed." />
                </div>
            </div>
        @empty
            <div class="card p-8 text-center text-muted mb-4">
                <span class="opacity-50 inline-block mb-2"><x-icon name="check-circle" class="w-9 h-9" /></span>
                <p>All caught up — no reviews pending approval.</p>
            </div>
        @endforelse
    </section>

    <div class="my-8 border-t" style="border-color:var(--line);"></div>

    {{-- Approved --}}
    <section>
        <div class="flex items-center gap-2.5 mb-4">
            <span class="text-success"><x-icon name="check-circle" class="w-5 h-5" /></span>
            <h2 class="font-display font-bold text-lg">Approved Reviews</h2>
            <span class="badge badge-success">{{ $approved->count() }}</span>
        </div>

        @if ($approved->isEmpty())
            <div class="card p-12 text-center text-muted">
                <span class="opacity-40 inline-block mb-3"><x-icon name="no-chat" class="w-12 h-12" /></span>
                <p>No approved reviews yet.</p>
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($approved as $r)
                    <div class="card p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full grid place-items-center text-white font-bold shrink-0"
                                 style="background:linear-gradient(135deg,#10b981,#059669);">
                                {{ strtoupper(substr($r->author, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold">{{ $r->author }}</p>
                                <p class="text-muted text-xs">{{ $r->app?->name ?? 'General' }} · {{ $r->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="ml-auto flex gap-0.5 text-gold">
                                @for ($i = 1; $i <= 5; $i++)
                                    <x-icon name="star" class="w-3.5 h-3.5" style="{{ $i <= $r->rating ? 'fill:currentColor' : 'opacity:.3' }}" />
                                @endfor
                            </div>
                        </div>
                        <p class="mt-3 text-text-soft text-sm italic">"{{ $r->comment }}"</p>
                        <div class="mt-3">
                            <x-delete-form :action="route('admin.reviews.destroy', $r)" label="Remove" class="btn btn-ghost btn-sm !text-danger" title="Remove this review?" message="The review by {{ $r->author }} will be removed from the public site." />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

</x-admin>
