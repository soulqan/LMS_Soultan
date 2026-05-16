<x-layouts.app
    :title="config('app.name', 'LearningHub') . ' | Course Catalog'"
    meta-description="Browse the LearningHub course catalog and filter by category or level."
>
    <div data-catalog-page class="min-h-dvh bg-slate-50 text-slate-900">
        <x-site-header>
            <x-slot:search>
                <div class="relative hidden flex-1 max-w-md md:block">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <x-app-icon name="search" class="h-4 w-4" />
                    </span>
                    <input
                        type="search"
                        placeholder="Search courses"
                        data-course-search
                        class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </x-slot:search>
        </x-site-header>

        <main>
            <section class="border-b border-slate-200 bg-gradient-to-br from-blue-600 to-blue-700 text-white">
                <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:px-8 lg:py-16">
                    <div class="space-y-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-blue-100">Course catalog</p>
                        <h1 class="text-4xl font-semibold tracking-tight sm:text-5xl">
                            Upgrade Your Skills
                        </h1>
                        <p class="max-w-xl text-base leading-8 text-blue-50/90">
                            Browse the full catalog, filter by category or level, and open the course that fits your current learning stage.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-blue-700 transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white/70">
                                Back to Landing Page
                            </a>
                            <a href="{{ route('student.register') }}" class="inline-flex items-center justify-center rounded-xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/15 focus:outline-none focus:ring-2 focus:ring-white/70">
                                Create Account
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        @foreach ($categories->take(3) as $category)
                            <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                                <p class="text-3xl font-semibold text-white">{{ $category->courses_count }}</p>
                                <p class="mt-1 text-sm text-blue-50">{{ $category->name }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-[256px_minmax(0,1fr)]">
                    <aside class="sticky top-24 h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="space-y-6">
                            <div class="md:hidden">
                                <label for="mobile-search" class="mb-2 block text-sm font-semibold text-slate-700">Search courses</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                                        <x-app-icon name="search" class="h-4 w-4" />
                                    </span>
                                    <input
                                        id="mobile-search"
                                        type="search"
                                        placeholder="Search courses"
                                        data-course-search
                                        class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                </div>
                            </div>

                            <div>
                                <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Categories</h2>
                                <div class="mt-4 space-y-2">
                                    <button type="button" data-category-button="all" class="w-full rounded-xl bg-blue-50 px-4 py-3 text-left text-sm font-semibold text-blue-600 transition hover:bg-blue-100">
                                        All
                                    </button>
                                    @foreach ($categories as $category)
                                        <button
                                            type="button"
                                            data-category-button="{{ $category->slug }}"
                                            class="w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                                        >
                                            <span class="flex items-center justify-between gap-3">
                                                <span>{{ $category->name }}</span>
                                                <span class="text-xs text-slate-500">{{ $category->courses_count }}</span>
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Level</h2>
                                <div class="mt-4 space-y-3">
                                    @foreach ($filters['levels'] as $value => $label)
                                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700">
                                            <input
                                                type="checkbox"
                                                value="{{ $value }}"
                                                data-level-filter
                                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </aside>

                    <div class="space-y-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-600">Browse freely</p>
                                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">All available courses</h2>
                                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                                    Search by title, filter by category, and narrow by level to find the right learning path.
                                </p>
                            </div>
                            <p class="text-sm text-slate-500">
                                {{ $featuredCourses->count() }} courses available
                            </p>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                            @forelse ($featuredCourses as $item)
                                <x-course-card :course="$item['course']" :meta="$item['meta']" />
                            @empty
                                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-600">
                                    No available courses yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</x-layouts.app>
