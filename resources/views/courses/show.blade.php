<x-layouts.app :title="$course->title . ' | LearningHub'" :meta-description="\Illuminate\Support\Str::limit($course->description, 155)">
    <div class="min-h-dvh bg-white text-slate-900">
        <x-site-header
            :title="$course->title"
            :eyebrow="$course->category->name"
            :back-url="route('home')"
            back-label="Back"
        />

        <main class="mx-auto max-w-4xl px-4 pb-32 py-8 sm:px-6 lg:px-8">
            <section class="space-y-8">
                <div class="relative overflow-hidden rounded-xl">
                    <img
                        src="{{ $meta['thumbnail'] }}"
                        alt="{{ $course->title }}"
                        class="aspect-video w-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/20 to-transparent"></div>
                    <a
                        href="{{ $firstLesson ? route('course.player', [$course, $firstLesson]) : '#' }}"
                        class="absolute inset-0 flex items-center justify-center"
                    >
                        <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 text-white transition hover:scale-105 hover:bg-blue-700 sm:h-20 sm:w-20">
                            <x-app-icon name="play" class="h-7 w-7 sm:h-8 sm:w-8" />
                        </span>
                    </a>
                </div>

                <div class="space-y-8">
                    <div class="space-y-4">
                        <p class="text-sm font-medium uppercase tracking-[0.25em] text-slate-500">{{ $course->category->name }}</p>
                        <h2 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $course->title }}</h2>
                        <p class="max-w-3xl text-base leading-7 text-slate-700">{{ $meta['subtitle'] }}</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="flex items-center gap-2 text-sm font-medium text-slate-600">
                                <x-app-icon name="star" class="h-4 w-4 text-yellow-500" />
                                <span>{{ $meta['rating'] }}</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-700">Average rating</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="text-sm font-medium text-slate-600">{{ $meta['students'] }}</div>
                            <p class="mt-2 text-sm text-slate-700">Students enrolled</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="text-sm font-medium text-slate-600">{{ $meta['duration'] }}</div>
                            <p class="mt-2 text-sm text-slate-700">Total duration</p>
                        </div>
                    </div>

                    <section class="space-y-3">
                        <h3 class="text-xl font-semibold text-slate-900">About this course</h3>
                        <p class="text-base leading-7 text-slate-700">{{ $meta['about'] }}</p>
                    </section>

                    <section class="space-y-4">
                        <h3 class="text-xl font-semibold text-slate-900">What you'll learn</h3>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach ($meta['learn'] as $item)
                                <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4">
                                    <span class="mt-0.5 text-green-600">
                                        <x-app-icon name="check-circle" class="h-5 w-5" />
                                    </span>
                                    <span class="text-sm leading-6 text-slate-700">{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-xl border border-slate-200 bg-slate-50 p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                                {{ strtoupper(substr($meta['instructor']['name'], 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $meta['instructor']['name'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-700">{{ $meta['instructor']['bio'] }}</p>
                            </div>
                        </div>
                    </section>
                </div>
            </section>
        </main>

        <div class="fixed inset-x-0 bottom-0 z-50 border-t border-slate-200 bg-white shadow-lg">
            <div class="mx-auto flex max-w-4xl items-center gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="hidden md:block">
                    <p class="text-sm text-slate-500">Price</p>
                    <p class="text-xl font-semibold text-slate-900">${{ $meta['price'] }}</p>
                </div>

                <a
                    href="{{ $firstLesson ? route('course.player', [$course, $firstLesson]) : '#' }}"
                    class="inline-flex flex-1 items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 md:flex-none md:px-6"
                >
                    Enroll Now
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
