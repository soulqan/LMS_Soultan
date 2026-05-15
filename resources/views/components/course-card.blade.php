@props([
    'course',
    'meta' => [],
])

<article
    {{ $attributes->merge([
        'class' => 'group overflow-hidden rounded-xl border border-slate-200 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg',
        'data-course-title' => strtolower($course->title),
        'data-course-category' => $course->category?->slug,
        'data-course-level' => $course->level->value,
    ]) }}
>
    <a href="{{ route('course.show', $course) }}" class="block">
        <div class="aspect-video overflow-hidden bg-slate-100">
            <img
                src="{{ $meta['thumbnail'] ?? $course->thumbnailUrl() }}"
                alt="{{ $course->title }}"
                loading="lazy"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
            >
        </div>
    </a>

    <div class="space-y-4 p-4">
        <div class="flex items-center justify-between gap-3">
            <x-badge tone="blue">{{ $meta['category_badge'] ?? $course->category?->name }}</x-badge>
            <x-badge :tone="$course->level->tone()">{{ $course->level->label() }}</x-badge>
        </div>

        <div class="space-y-2">
            <h3 class="text-lg font-semibold text-slate-900">{{ $course->title }}</h3>
            <p class="text-sm leading-6 text-slate-700">{{ $meta['summary'] ?? $course->description }}</p>
        </div>

        <div class="space-y-3">
            <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500">{{ $course->category?->name }}</p>
            <a
                href="{{ route('course.show', $course) }}"
                class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                Learn Now
            </a>
        </div>
    </div>
</article>
