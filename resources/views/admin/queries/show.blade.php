<x-admin title="Query Detail">

    <div class="max-w-3xl mx-auto space-y-5">
        <a href="{{ route('admin.queries.index') }}" class="btn btn-ghost btn-sm"><x-icon name="chevron-left" class="w-4 h-4" /> Back to inbox</a>

        <div class="card p-6 sm:p-8">
            <div class="flex items-start gap-4 flex-wrap">
                <div class="w-12 h-12 rounded-2xl grid place-items-center text-white font-bold shrink-0"
                     style="background:linear-gradient(135deg,var(--brand),var(--brand-2));">
                    {{ strtoupper(substr($query->sender_name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <h2 class="font-display font-bold text-xl">{{ $query->sender_name }}</h2>
                    <a href="mailto:{{ $query->email }}" class="text-brand hover:underline text-sm break-all">{{ $query->email }}</a>
                </div>
                <span class="badge badge-success ml-auto"><x-icon name="check" class="w-3 h-3" /> Read</span>
            </div>

            <div class="mt-6 grid sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl" style="background:var(--surface-2);">
                    <p class="text-muted text-xs uppercase tracking-wide mb-1">Subject</p>
                    <p class="font-semibold">{{ $query->subject ?: '—' }}</p>
                </div>
                <div class="p-4 rounded-xl" style="background:var(--surface-2);">
                    <p class="text-muted text-xs uppercase tracking-wide mb-1">Received</p>
                    <p class="font-semibold">{{ optional($query->received_at ?? $query->created_at)->format('M d, Y · h:i A') }}</p>
                </div>
            </div>

            <div class="mt-4 p-5 rounded-xl leading-relaxed" style="background:var(--surface-2);">
                <p class="text-muted text-xs uppercase tracking-wide mb-2">Message</p>
                <p class="whitespace-pre-line text-text-soft">{{ $query->message }}</p>
            </div>

            @if ($query->attachmentUrl())
                <div class="mt-5 p-4 rounded-xl flex items-center gap-3" style="background:var(--surface-2);">
                    <span class="w-10 h-10 rounded-lg grid place-items-center text-brand shrink-0" style="background:color-mix(in srgb,var(--brand) 14%,transparent);"><x-icon name="download" class="w-5 h-5" /></span>
                    <div class="min-w-0">
                        <p class="text-xs uppercase tracking-wide text-muted">Attachment</p>
                        <p class="font-medium truncate">{{ $query->attachmentName() }}</p>
                    </div>
                    <a href="{{ $query->attachmentUrl() }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm ml-auto shrink-0"><x-icon name="external" class="w-4 h-4" /> Open</a>
                </div>
            @endif

            <div class="mt-6 flex gap-3 flex-wrap">
                <a href="mailto:{{ $query->email }}?subject=Re: {{ $query->subject }}" class="btn btn-primary"><x-icon name="send" class="w-4 h-4" /> Reply via Email</a>
                <x-delete-form :action="route('admin.queries.destroy', $query)" label="Delete Query" class="btn btn-danger" title="Delete this query?" message="This message will be permanently removed." />
            </div>
        </div>
    </div>

</x-admin>
