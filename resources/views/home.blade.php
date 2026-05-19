<x-layouts.app
    :title="config('app.name', 'LearningHub') . ' | Focused learning for modern students'"
    meta-description="LearningHub is a calm, minimalist learning platform for focused learning, clear progress, and portfolio-ready courses."
>
    @php
        $firstSpotlight = $categorySpotlights->first();
    @endphp

    <div class="min-h-dvh bg-white text-slate-900">
        <x-site-header />

        <main>
            <section class="border-b border-slate-200 bg-gradient-to-br from-slate-50 via-white to-blue-50">
                <div class="mx-auto grid max-w-7xl gap-10 px-4 pb-16 pt-10 sm:px-6 lg:grid-cols-[1.08fr_0.92fr] lg:px-8 lg:pb-24 lg:pt-12">
                    <div class="space-y-8">
                        <span class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.28em] text-blue-600">
                            Minimalist LMS
                        </span>

                        <div class="space-y-5">
                            <h1 class="max-w-3xl text-4xl font-semibold tracking-tight sm:text-5xl lg:text-6xl">
                                Learn without distraction.
                                <span class="block text-blue-600">Build skills that feel real.</span>
                            </h1>
                            <p class="max-w-2xl text-base leading-8 text-slate-700 sm:text-lg">
                                LearningHub helps students move through focused video lessons, track progress offline, and discover courses without the clutter of a traditional course list homepage.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Explore Courses
                            </a>
                            @guest
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Sign In
                                </a>
                            @endguest
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <p class="text-3xl font-semibold text-slate-900">{{ number_format($stats['courses']) }}</p>
                                <p class="mt-1 text-sm text-slate-600">Available courses</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <p class="text-3xl font-semibold text-slate-900">{{ number_format($stats['lessons']) }}</p>
                                <p class="mt-1 text-sm text-slate-600">Video lessons</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <p class="text-3xl font-semibold text-slate-900">{{ number_format($stats['categories']) }}</p>
                                <p class="mt-1 text-sm text-slate-600">Learning tracks</p>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Focused flow</p>
                                <p class="mt-2 text-sm leading-7 text-slate-600">
                                    A landing page designed to introduce the platform first, not overwhelm learners with filters.
                                </p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Offline progress</p>
                                <p class="mt-2 text-sm leading-7 text-slate-600">
                                    Lesson completion stays in the browser so students can return to where they left off.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="space-y-4"
                        data-home-spotlight
                        data-home-spotlight-interval="3000"
                    >
                        @if ($firstSpotlight)
                            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl">
                                <div class="relative aspect-[4/5]">
                                    @foreach ($categorySpotlights as $index => $spotlight)
                                        <article
                                            class="absolute inset-0 transition duration-700 ease-out {{ $loop->first ? 'opacity-100' : 'pointer-events-none opacity-0' }}"
                                            data-home-spotlight-item
                                            data-home-spotlight-index="{{ $index }}"
                                        >
                                            <img
                                                src="{{ $spotlight['meta']['thumbnail'] }}"
                                                alt="{{ $spotlight['course']->title }}"
                                                class="h-full w-full object-cover"
                                                loading="lazy"
                                            >
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                                            <div class="absolute inset-x-0 bottom-0 p-6">
                                                <div class="flex items-center justify-between gap-3">
                                                    <x-badge tone="blue">{{ $spotlight['category']->name }}</x-badge>
                                                    <x-badge :tone="$spotlight['course']->level->tone()">{{ $spotlight['course']->level->label() }}</x-badge>
                                                </div>

                                                <h2 class="mt-4 text-2xl font-semibold text-white">{{ $spotlight['course']->title }}</h2>
                                                <p class="mt-2 text-sm leading-6 text-slate-200">{{ $spotlight['meta']['summary'] }}</p>

                                                <div class="mt-5 flex flex-wrap gap-3">
                                                    <a href="{{ route('course.show', $spotlight['course']) }}" class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                                                        View Course
                                                    </a>
                                                    <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/15">
                                                        Browse Catalog
                                                    </a>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
            
            <section id="about" class="bg-white">
                <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
                    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-600">Let's get to know each other</p>
                            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">About Me</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                                These are just highlights. The full catalog lives on the course browse page.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="benefits" class="border-t border-slate-200 bg-slate-50">
                <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-600">Why it works</p>
                            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">A cleaner path for students</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                                The homepage introduces the product, the catalog helps with discovery, and the player keeps the actual learning experience focused.
                            </p>
                        </div>

                        <a href="{{ route('courses.index') }}" class="text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                            Open the course catalog
                        </a>
                    </div>

                    <div class="mt-10 grid gap-6 md:grid-cols-3">
                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Video-first</p>
                            <h3 class="mt-3 text-xl font-semibold text-slate-900">Simple lessons, clear flow</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                Lessons are organized to help learners keep moving without extra friction.
                            </p>
                        </article>

                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Admin-friendly</p>
                            <h3 class="mt-3 text-xl font-semibold text-slate-900">Manage content in one place</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                Courses, lessons, and visibility are easy to control from the admin panel.
                            </p>
                        </article>

                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Offline progress</p>
                            <h3 class="mt-3 text-xl font-semibold text-slate-900">Keep learning state local</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                Progress is stored in the browser so students can come back where they left off.
                            </p>
                        </article>
                    </div>
                </div>
            </section>

            <footer id="social" class="border-t border-slate-200 bg-blue-800">
                <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-100">Follow and connect</p>
                    <h2 class="mt-3 text-3xl font-semibold tracking-tight text-white">Social channels</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-blue-50">
                        Keep in touch through the channels below for updates, announcements, and quick support.
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <a
                            href="https://instagram.com/"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group rounded-2xl border border-white/15 bg-white/10 p-5 transition hover:-translate-y-1 hover:bg-white/15 hover:shadow-lg"
                        >
                            <div class="flex items-center gap-4">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/15 text-white transition group-hover:bg-white/20">
                                    <x-app-icon name="instagram" class="h-6 w-6" />
                                </span>
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">Instagram</p>
                                    <p class="mt-1 text-sm text-white">See updates and visuals</p>
                                </div>
                            </div>
                        </a>

                        <a
                            href="mailto:hello@learninghub.test"
                            class="group rounded-2xl border border-white/15 bg-white/10 p-5 transition hover:-translate-y-1 hover:bg-white/15 hover:shadow-lg"
                        >
                            <div class="flex items-center gap-4">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/15 text-white transition group-hover:bg-white/20">
                                    <x-app-icon name="mail" class="h-6 w-6" />
                                </span>
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">Email</p>
                                    <p class="mt-1 text-sm text-white">hello@learninghub.test</p>
                                </div>
                            </div>
                        </a>

                        <a
                            href="https://wa.me/6281234567890"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group rounded-2xl border border-white/15 bg-white/10 p-5 transition hover:-translate-y-1 hover:bg-white/15 hover:shadow-lg"
                        >
                            <div class="flex items-center gap-4">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/15 text-white transition group-hover:bg-white/20">
                                    <x-app-icon name="whatsapp" class="h-6 w-6" />
                                </span>
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">WhatsApp</p>
                                    <p class="mt-1 text-sm text-white">Quick chat and support</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="mt-14 border-t border-white/15 pt-6 text-center text-sm text-white/65">
                        © {{ now()->year }} Createbox. All rights reserved.
                    </div>
                </div>
            </footer>
        </main>
    </div>
</x-layouts.app>
