@props([
    'action',
    'label' => 'Del',
    'title' => 'Delete this item?',
    'message' => 'This action cannot be undone.',
    'icon' => true,
    'class' => 'btn btn-danger btn-sm',
])

<div x-data="{ open: false }" class="inline-block">
    <button type="button" @click="open = true" {{ $attributes->merge(['class' => $class]) }}>
        @if ($icon) <x-icon name="trash" class="w-4 h-4" /> @endif
        <span>{{ $label }}</span>
    </button>

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[90] grid place-items-center p-4"
             x-trap.inert.noscroll="open" @keydown.escape.window="open = false">
            <div x-show="open" x-transition.opacity @click="open = false"
                 class="absolute inset-0 bg-black/55 backdrop-blur-sm"></div>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="card relative w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl grid place-items-center mb-4"
                     style="background:color-mix(in srgb,var(--danger) 15%,transparent);color:var(--danger);">
                    <x-icon name="alert" class="w-7 h-7" />
                </div>
                <h3 class="font-display font-bold text-lg">{{ $title }}</h3>
                <p class="text-muted text-sm mt-1.5">{{ $message }}</p>

                <div class="flex gap-3 mt-6">
                    <button type="button" @click="open = false" class="btn btn-ghost flex-1">Cancel</button>
                    <form method="POST" action="{{ $action }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
