<x-layouts.app :title="$lesson->title . ' | LearningHub'" :meta-description="\Illuminate\Support\Str::limit(strip_tags($lesson->content ?? $course->description), 155)">
    <div data-player-page class="min-h-dvh bg-slate-950 text-white">
        <header class="sticky top-0 z-50 border-b border-slate-800 bg-slate-950/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('course.show', $course) }}" class="inline-flex items-center gap-2 text-slate-300 transition hover:text-white">
                    <x-app-icon name="chevron-left" class="h-5 w-5" />
                    <span class="hidden sm:inline">Back</span>
                </a>

                <div class="min-w-0 flex-1">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $course->category->name }}</p>
                    <h1 class="truncate text-lg font-semibold text-white sm:text-xl">{{ $course->title }}</h1>
                </div>

                <div class="hidden items-center gap-4 text-sm text-slate-300 md:flex">
                    <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                    <a href="{{ route('courses.index') }}" class="transition hover:text-white">My Courses</a>
                </div>

                <div class="relative md:hidden">
                    <button type="button" data-mobile-menu-toggle class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-800 text-slate-300 transition hover:bg-slate-900">
                        <x-app-icon name="menu" class="h-5 w-5" />
                    </button>
                    <div data-mobile-menu class="absolute right-0 mt-3 hidden w-48 overflow-hidden rounded-xl border border-slate-800 bg-slate-900 shadow-lg">
                        <a href="{{ route('home') }}" class="block px-4 py-3 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white">Home</a>
                        <a href="{{ route('courses.index') }}" class="block px-4 py-3 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white">My Courses</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto grid max-w-7xl gap-6 px-4 py-6 lg:grid-cols-[minmax(0,1.7fr)_384px] lg:px-8">
            <section class="space-y-6">
                <div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900">
                    <div class="relative aspect-video bg-gradient-to-br from-slate-900 via-slate-950 to-black">
                        <iframe
                            data-player-video
                            src="{{ $lesson->embedUrl() }}"
                            title="{{ $lesson->title }}"
                            class="absolute inset-0 h-full w-full"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>

                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/55 via-transparent to-transparent"></div>
                        <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                            <span class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg">
                                <x-app-icon name="play" class="h-8 w-8" />
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 data-current-lesson-title class="text-2xl font-semibold text-white">{{ $lesson->title }}</h2>
                            <p class="mt-2 text-sm text-slate-400">{{ $player['subtitle'] }}</p>
                        </div>

                        <button
                            type="button"
                            data-mark-complete
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <x-app-icon name="check-circle" class="h-4 w-4" />
                            <span>Mark as Complete</span>
                        </button>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <button type="button" data-tab-target="description" class="rounded-xl bg-white px-4 py-2 text-sm font-medium text-slate-900 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Description
                        </button>
                        <button type="button" data-tab-target="resources" class="rounded-xl px-4 py-2 text-sm font-medium text-slate-300 transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Resources
                        </button>
                    </div>

                    <div class="mt-6 max-h-[22rem] overflow-y-auto pr-2">
                        <div data-tab-panel="description" class="space-y-4">
                            <p data-current-lesson-content class="text-sm leading-7 text-slate-300">{{ $lesson->content }}</p>

                            <div class="space-y-3">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">What You'll Learn</h3>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    @foreach ($player['learn'] as $item)
                                        <div class="flex items-start gap-3 rounded-xl border border-slate-800 bg-slate-950 p-4">
                                            <span class="mt-0.5 text-green-500">
                                                <x-app-icon name="check" class="h-4 w-4" />
                                            </span>
                                            <span class="text-sm leading-6 text-slate-300">{{ $item }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div data-tab-panel="resources" class="hidden space-y-3">
                            @foreach ($player['resources'] as $resource)
                                <a href="{{ $resource['href'] }}" class="flex items-center justify-between rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 transition hover:bg-slate-800">
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $resource['label'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $resource['meta'] }}</p>
                                    </div>
                                    <span class="text-sm text-blue-400">Download</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <aside class="lg:sticky lg:top-24 lg:h-[calc(100dvh-8rem)]">
                <div class="flex h-full flex-col overflow-hidden rounded-xl border border-slate-800 bg-slate-900">
                    <div class="border-b border-slate-800 bg-slate-900 px-4 py-4">
                        <p class="text-sm font-semibold text-white">Course Content</p>
                    </div>

                    <div class="flex-1 overflow-y-auto">
                        @foreach ($player['chapters'] as $chapter)
                            <div class="border-b border-slate-800">
                                <div class="bg-slate-950 px-4 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                                    {{ $chapter['title'] }}
                                </div>

                                <div class="space-y-1 p-2">
                                    @foreach ($chapter['lessons'] as $item)
                                        <button
                                            type="button"
                                            data-lesson-button
                                            data-lesson-id="{{ $item['id'] }}"
                                            data-lesson-title="{{ $item['title'] }}"
                                            data-lesson-duration="{{ $item['duration'] }}"
                                            data-lesson-content="{{ e($item['content']) }}"
                                            data-lesson-video="{{ $item['video_url'] ?? '' }}"
                                            data-lesson-completed="{{ $item['completed'] ? '1' : '0' }}"
                                            @class([
                                                'flex w-full items-start justify-between gap-3 rounded-xl px-3 py-3 text-left transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500',
                                                'bg-blue-600/10' => $item['id'] === $lesson->id,
                                            ])
                                        >
                                            <div class="flex items-start gap-3">
                                                <span data-lesson-icon class="mt-0.5 text-green-500">
                                                    @if ($item['completed'])
                                                        <x-app-icon name="check-circle" class="h-5 w-5" />
                                                    @else
                                                        <x-app-icon name="circle" class="h-5 w-5 text-slate-500" />
                                                    @endif
                                                </span>
                                                <span class="space-y-1">
                                                    <span class="block text-sm font-medium text-white">{{ $item['title'] }}</span>
                                                    <span class="block text-xs text-slate-400">Lesson {{ $item['id'] }}</span>
                                                </span>
                                            </div>
                                            <span class="rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-300">{{ $item['duration'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </main>

        <script>
            window.learningHubPlayer = {
                storageKey: 'lh-progress:{{ $course->id }}',
                currentLessonId: {{ $lesson->id }},
            };
        </script>
    </div>
</x-layouts.app>
