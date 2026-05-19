@props([
    'course',
    'meta' => [],
])

@php
    $summary = $meta['summary'] ?? ($course?->description ?? '');
    $title = $course?->title ?? 'Untitled Course';
    $buttonLabel = (mb_strlen($title) > 30 || mb_strlen($summary) > 120) ? 'Read More' : 'Learn Now';
@endphp

<article
    {{ $attributes->merge([
        'class' => 'group flex h-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg',
        'data-course-card' => true,
        'data-course-title' => strtolower($title),
        'data-course-category' => $course?->category?->slug,
        'data-course-level' => $course?->level?->value,
    ]) }}
>
    <a href="{{ $course ? route('course.show', $course) : '#' }}" class="block">
        <div class="aspect-video overflow-hidden bg-slate-100">
            <img
                src="{{ $meta['thumbnail'] ?? ($course?->thumbnailUrl() ?? '') }}"
                alt="{{ $title }}"
                loading="lazy"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
            >
        </div>
    </a>

    <div class="flex flex-1 flex-col p-4">
        <div class="flex items-center justify-between gap-3">
            <x-badge tone="blue">{{ $meta['category_badge'] ?? $course?->category?->name }}</x-badge>
            <x-badge :tone="$course?->level?->tone() ?? 'neutral'">{{ $course?->level?->label() ?? 'Course' }}</x-badge>
        </div>

        <div class="mt-4 flex flex-1 flex-col">
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                <p class="text-sm leading-6 text-slate-700">{{ $summary }}</p>
            </div>

            <div class="mt-auto pt-4">
                <p class="mb-3 text-xs font-medium uppercase tracking-[0.2em] text-slate-500">{{ $course?->category?->name }}</p>
                <a
                    href="{{ $course ? route('course.show', $course) : '#' }}"
                    class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    {{ $buttonLabel }}
                </a>
            </div>
        </div>
    </div>
</article>
