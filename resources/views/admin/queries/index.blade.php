<x-admin title="User Support Inquiries">

    <div class="card overflow-hidden">
        <div class="flex items-center gap-2.5 px-6 py-5">
            <span class="text-brand"><x-icon name="inbox" class="w-5 h-5" /></span>
            <h2 class="font-display font-bold text-lg">Received Contact Queries</h2>
            <span class="badge badge-brand ml-1">{{ $queries->total() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table min-w-[820px]">
                <thead>
                    <tr>
                        <th>ID</th><th>Sender</th><th>Email</th><th>Subject</th>
                        <th>Message</th><th>Attachment</th><th>Received</th><th class="text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($queries as $q)
                        <tr class="{{ $q->is_read ? '' : 'font-medium' }}">
                            <td class="text-muted">#{{ $q->id }}</td>
                            <td>
                                <span class="inline-flex items-center gap-2">
                                    @unless ($q->is_read)<span class="w-2 h-2 rounded-full bg-brand shrink-0" title="Unread"></span>@endunless
                                    <span class="font-semibold whitespace-nowrap">{{ $q->sender_name }}</span>
                                </span>
                            </td>
                            <td><a href="mailto:{{ $q->email }}" class="text-brand hover:underline break-all text-sm">{{ $q->email }}</a></td>
                            <td class="max-w-[200px]"><span class="font-medium">{{ $q->subject ?: '—' }}</span></td>
                            <td class="max-w-[260px]"><span class="text-muted text-sm">{{ $q->excerpt() }}</span></td>
                            <td>
                                @if ($q->attachmentUrl())
                                    <a href="{{ $q->attachmentUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-brand text-sm hover:underline"><x-icon name="download" class="w-4 h-4" /> File</a>
                                @else
                                    <span class="text-muted text-sm">No File</span>
                                @endif
                            </td>
                            <td class="text-muted text-sm whitespace-nowrap">{{ optional($q->received_at ?? $q->created_at)->format('M d, Y') }}<br><span class="opacity-70">{{ optional($q->received_at ?? $q->created_at)->format('h:i A') }}</span></td>
                            <td class="text-right pr-6">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.queries.show', $q) }}" class="btn btn-ghost btn-sm text-brand"><x-icon name="eye" class="w-4 h-4" />View</a>
                                    <x-delete-form :action="route('admin.queries.destroy', $q)" title="Delete this query?" message="The message from {{ $q->sender_name }} will be removed." />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-14">
                            <div class="flex flex-col items-center gap-3">
                                <span class="opacity-50"><x-icon name="inbox" class="w-10 h-10" /></span>
                                <p>No user queries received yet.</p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($queries->hasPages())
            <div class="px-6 py-5">{{ $queries->links() }}</div>
        @endif
    </div>

</x-admin>
