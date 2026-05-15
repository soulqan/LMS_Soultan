@props(['eyebrow' => null, 'title', 'description' => null])

<div class="max-w-2xl">
    @if ($eyebrow)
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">{{ $eyebrow }}</p>
    @endif

    <h2 class="mt-3 text-3xl font-semibold tracking-tight text-zinc-950 sm:text-4xl">{{ $title }}</h2>

    @if ($description)
        <p class="mt-4 text-base leading-7 text-zinc-600">{{ $description }}</p>
    @endif
</div>
