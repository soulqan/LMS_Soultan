<x-layouts.app :title="config('app.name', 'LearningHub') . ' | Course Catalog'">
    <div data-catalog-page class="min-h-dvh bg-white text-slate-900">
        <header class="sticky top-0 z-50 border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-slate-900">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white">
                        <x-app-icon name="book-open" class="h-5 w-5" />
                    </span>
                    <span class="text-lg">LearningHub</span>
                </a>

                <label class="relative hidden w-[384px] max-w-full lg:block">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <x-app-icon name="search" class="h-4 w-4" />
                    </span>
                    <input
                        type="search"
                        placeholder="Search courses"
                        data-course-search
                        class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </label>

                <div class="ml-auto flex items-center gap-3">
                    <a href="{{ route('courses.index') }}" class="hidden text-sm font-medium text-slate-700 transition hover:text-blue-600 sm:inline-flex">My Courses</a>

                    <div class="relative">
                        <button
                            type="button"
                            data-profile-toggle
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-2 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-600">A</span>
                            <x-app-icon name="chevron-down" class="h-4 w-4 text-slate-500" />
                        </button>

                        <div
                            data-profile-menu
                            class="absolute right-0 mt-3 hidden w-56 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg"
                        >
                            <a href="#" class="block px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">Profile</a>
                            <a href="#" class="block px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">Progress</a>
                            <a href="#" class="block px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">Settings</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="max-w-3xl">
                    <p class="text-sm font-medium uppercase tracking-[0.3em] text-blue-100">Minimalist LMS</p>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl">Upgrade Your Skills</h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-blue-50">
                        Learn from experts, follow a clean path, and keep your progress in sync with a focused, portfolio-ready learning experience.
                    </p>

                    <a
                        href="#catalog"
                        class="mt-8 inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-blue-600 transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white"
                    >
                        Explore Courses
                    </a>
                </div>
            </div>
        </section>

        <main id="catalog" class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[256px_minmax(0,1fr)]">
                <aside class="lg:sticky lg:top-24 lg:h-fit">
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="space-y-5">
                            <div>
                                <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-900">Categories</h2>
                                <div class="mt-3 grid gap-2">
                                    <button type="button" data-category-button="all" class="rounded-xl bg-blue-50 px-4 py-3 text-left text-sm font-medium text-blue-600 transition hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        All
                                    </button>
                                    @foreach ($categories as $category)
                                        <button
                                            type="button"
                                            data-category-button="{{ $category->slug }}"
                                            class="rounded-xl px-4 py-3 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            {{ $category->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-900">Level</h2>
                                <div class="mt-3 space-y-3">
                                    @foreach (['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'] as $value => $label)
                                        <label class="flex items-center gap-3 text-sm text-slate-700">
                                            <input type="checkbox" value="{{ $value }}" data-level-filter class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                <section class="space-y-6">
                    <div class="lg:hidden">
                        <label class="relative block">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                                <x-app-icon name="search" class="h-4 w-4" />
                            </span>
                            <input
                                type="search"
                                placeholder="Search courses"
                                data-course-search
                                class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </label>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($featuredCourses as $item)
                            <x-course-card :course="$item['course']" :meta="$item['meta']" data-course-card />
                        @endforeach
                    </div>
                </section>
            </div>
        </main>
    </div>
</x-layouts.app>
