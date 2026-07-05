<x-admin title="Dashboard Overview">

    {{-- Stat cards --}}
    <div class="grid gap-4 sm:gap-5 sm:grid-cols-2 xl:grid-cols-3 stagger">
        <div class="card card-hover p-5 flex items-center gap-4">
            <div class="icon-tile" style="background:linear-gradient(135deg,#4f46e5,#6366f1);"><x-icon name="gamepad" class="w-7 h-7" /></div>
            <div>
                <p class="font-display font-bold text-3xl tabular-nums">{{ number_format($stats['total_apps']) }}</p>
                <p class="text-muted text-sm">Total Applications</p>
            </div>
        </div>
        <div class="card card-hover p-5 flex items-center gap-4">
            <div class="icon-tile" style="background:linear-gradient(135deg,#059669,#10b981);"><x-icon name="star" class="w-7 h-7" /></div>
            <div>
                <p class="font-display font-bold text-3xl tabular-nums">{{ number_format($stats['new_releases']) }}</p>
                <p class="text-muted text-sm">New Releases</p>
            </div>
        </div>
        <div class="card card-hover p-5 flex items-center gap-4">
            <div class="icon-tile" style="background:linear-gradient(135deg,#ea580c,#f59e0b);"><x-icon name="layers" class="w-7 h-7" /></div>
            <div>
                <p class="font-display font-bold text-3xl tabular-nums">{{ number_format($stats['other_apps']) }}</p>
                <p class="text-muted text-sm">Other Category Apps</p>
            </div>
        </div>
    </div>

    <div class="grid gap-5 mt-5 lg:grid-cols-3">
        {{-- Quick operations --}}
        <div class="card p-6 lg:col-span-2">
            <div class="flex items-center gap-2.5 mb-5">
                <span class="text-brand"><x-icon name="bolt" class="w-5 h-5" /></span>
                <h2 class="font-display font-bold text-lg">Quick Operations</h2>
            </div>
            <div class="space-y-3">
                @php
                    $ops = [
                        ['route' => route('admin.apps.create'), 'icon' => 'plus', 'title' => 'Add New Gaming App', 'desc' => 'Input detailed specifications and SEO tags'],
                        ['route' => route('admin.settings.index'), 'icon' => 'sliders', 'title' => 'Portal Settings', 'desc' => 'Manage Telegram join link, logos, and disclaimer text'],
                        ['route' => url('/'), 'icon' => 'eye', 'title' => 'View Public Portal', 'desc' => 'Check styling, search functionality, and tabs', 'blank' => true],
                    ];
                @endphp
                @foreach ($ops as $op)
                    <a href="{{ $op['route'] }}" @if(!empty($op['blank'])) target="_blank" @endif
                       class="group flex items-center gap-4 p-4 rounded-2xl border transition-all hover:-translate-y-0.5"
                       style="border-color:var(--line);background:var(--surface-2);">
                        <div class="w-11 h-11 rounded-xl grid place-items-center shrink-0 text-brand"
                             style="background:color-mix(in srgb,var(--brand) 14%,transparent);">
                            <x-icon :name="$op['icon']" class="w-5 h-5" />
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold">{{ $op['title'] }}</p>
                            <p class="text-muted text-sm truncate">{{ $op['desc'] }}</p>
                        </div>
                        <span class="ml-auto text-muted group-hover:text-brand transition-colors"><x-icon name="chevron-right" class="w-5 h-5" /></span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Platform stats --}}
        <div class="card p-6">
            <div class="flex items-center gap-2.5 mb-5">
                <span class="text-brand"><x-icon name="server" class="w-5 h-5" /></span>
                <h2 class="font-display font-bold text-lg">Platform Stats</h2>
            </div>
            <dl class="space-y-1">
                @php
                    $meta = [
                        ['Laravel Version', 'v'.app()->version()],
                        ['Database Engine', ucfirst(config('database.default')).' (workbench)'],
                        ['Active Environment', ucfirst(app()->environment()).' (debug='.(config('app.debug') ? 'true' : 'false').')'],
                        ['Admin User', auth()->user()->email],
                    ];
                @endphp
                @foreach ($meta as [$k, $v])
                    <div class="flex items-center justify-between gap-4 py-2.5 {{ !$loop->last ? 'border-b' : '' }}" style="border-color:var(--line);">
                        <dt class="text-muted text-sm">{{ $k }}</dt>
                        <dd class="font-semibold text-sm text-right">{{ $v }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>

    {{-- Recently modified apps --}}
    <div class="card mt-5 overflow-hidden">
        <div class="flex items-center gap-2.5 px-6 py-5">
            <span class="text-brand"><x-icon name="clock" class="w-5 h-5" /></span>
            <h2 class="font-display font-bold text-lg">Recently Modified Apps</h2>
            <a href="{{ route('admin.apps.index') }}" class="ml-auto text-brand font-semibold text-sm hover:underline">View All Apps</a>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table min-w-[680px]">
                <thead>
                    <tr>
                        <th>App Name</th><th>Slug</th><th>Bonus</th><th>Min. Withdraw</th><th>Rating</th><th>Last Updated</th><th class="text-right pr-6">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentApps as $app)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-app-logo :app="$app" size="w-9 h-9" />
                                    <span class="font-semibold">{{ $app->name }}</span>
                                    @if ($app->is_new_release) <span class="badge badge-success">New</span> @endif
                                </div>
                            </td>
                            <td><code class="text-muted text-[0.82rem]">/{{ $app->slug }}</code></td>
                            <td class="font-semibold tabular-nums">₹{{ number_format($app->sign_up_bonus) }}</td>
                            <td class="tabular-nums">₹{{ number_format($app->min_withdrawal) }}</td>
                            <td><span class="inline-flex items-center gap-1 font-semibold"><span class="text-gold"><x-icon name="star" class="w-4 h-4" style="fill:currentColor" /></span>{{ $app->rating }}</span></td>
                            <td class="text-muted">{{ $app->updated_at->diffForHumans() }}</td>
                            <td class="text-right pr-6">
                                <a href="{{ route('admin.apps.edit', $app) }}" class="btn btn-ghost btn-sm text-brand"><x-icon name="edit" class="w-4 h-4" />Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-10">No apps yet. <a href="{{ route('admin.apps.create') }}" class="text-brand font-semibold">Add your first app</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-admin>
