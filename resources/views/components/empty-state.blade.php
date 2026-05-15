@props(['title', 'description'])

<div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center">
    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $description }}</p>
    @if ($slot->isNotEmpty())
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</div>
