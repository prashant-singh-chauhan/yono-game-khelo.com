<x-admin title="Manage Applications">

    <div class="card p-4 sm:p-6">
        {{-- Toolbar --}}
        <form method="GET" class="flex flex-col md:flex-row gap-3 mb-6">
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-muted"><x-icon name="search" class="w-5 h-5" /></span>
                <input type="search" name="search" value="{{ $search }}"
                       class="field !pl-11" placeholder="Search app name or slug…">
            </div>
            <select name="category" class="field md:w-56" onchange="this.form.submit()">
                <option value="" {{ $category === '' ? 'selected' : '' }}>All Categories</option>
                <option value="new" {{ $category === 'new' ? 'selected' : '' }}>New Release</option>
                <option value="other" {{ $category === 'other' ? 'selected' : '' }}>Other Category</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-ghost md:hidden flex-1">Filter</button>
                <a href="{{ route('admin.apps.create') }}" class="btn btn-primary flex-1 md:flex-none">
                    <x-icon name="plus" class="w-4.5 h-4.5" /> Add New App
                </a>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto -mx-4 sm:-mx-6 px-4 sm:px-6">
            <table class="data-table min-w-[860px]">
                <thead>
                    <tr>
                        <th>ID</th><th>App Name</th><th>Slug</th><th>Category</th>
                        <th>Bonus</th><th>Withdraw</th><th>Rating</th><th>Votes</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($apps as $app)
                        <tr>
                            <td class="text-muted font-medium">#{{ $app->id }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-app-logo :app="$app" size="w-9 h-9" />
                                    <span class="font-semibold whitespace-nowrap">{{ $app->name }}</span>
                                </div>
                            </td>
                            <td><code class="text-muted text-[0.82rem]">/{{ $app->slug }}</code></td>
                            <td>
                                @if ($app->is_new_release)
                                    <span class="badge badge-success">New Release</span>
                                @else
                                    <span class="badge badge-brand">Other</span>
                                @endif
                            </td>
                            <td class="font-semibold tabular-nums">₹{{ number_format($app->sign_up_bonus) }}</td>
                            <td class="tabular-nums">₹{{ number_format($app->min_withdrawal) }}</td>
                            <td><span class="inline-flex items-center gap-1 font-semibold"><span class="text-gold"><x-icon name="star" class="w-4 h-4" style="fill:currentColor" /></span>{{ $app->rating }}</span></td>
                            <td class="text-muted tabular-nums">{{ $app->votes }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('app.show', $app) }}" target="_blank" class="btn btn-ghost btn-sm"><x-icon name="external" class="w-4 h-4" />View</a>
                                    <a href="{{ route('admin.apps.edit', $app) }}" class="btn btn-ghost btn-sm text-brand"><x-icon name="edit" class="w-4 h-4" />Edit</a>
                                    <x-delete-form :action="route('admin.apps.destroy', $app)"
                                                   title="Delete {{ $app->name }}?"
                                                   message="This app and its reviews will be permanently removed." />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-14">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-muted opacity-50"><x-icon name="gamepad" class="w-10 h-10" /></span>
                                <p>No applications match your search.</p>
                                <a href="{{ route('admin.apps.create') }}" class="btn btn-primary btn-sm"><x-icon name="plus" class="w-4 h-4" />Add New App</a>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($apps->hasPages())
            <div class="mt-6">{{ $apps->links() }}</div>
        @endif
    </div>

</x-admin>
