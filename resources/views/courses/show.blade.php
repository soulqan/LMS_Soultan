<x-layouts.app :title="$course->title . ' | LearningHub'" :meta-description="\Illuminate\Support\Str::limit($course->description, 155)">
    <div class="min-h-dvh bg-white text-slate-900">
        @if (auth()->check() && auth()->user()->isAdmin())
            <form id="course-edit-form" method="POST" action="{{ route('courses.update', $course) }}">
                @csrf
                @method('PATCH')
        @endif

        <x-site-header
            :title="$course->title"
            :eyebrow="$course->category->name"
            :back-url="route('home')"
            back-label="Back"
        />

        <main class="mx-auto max-w-4xl px-4 pb-32 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800 flex items-center gap-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-green-600"><circle cx="12" cy="12" r="9"></circle><path d="m9 12 2 2 4-4"></path></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">
                    <p class="font-semibold mb-2">Please correct the following errors:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-medium uppercase tracking-[0.25em] text-slate-500">{{ $course->category->name }}</p>
                                <h2 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl view-element">{{ $course->title }}</h2>
                            </div>
                            @if (auth()->check() && auth()->user()->isAdmin())
                                <div class="flex gap-2 shrink-0">
                                    <button type="button" data-edit-toggle class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition">
                                        Edit Page Content
                                    </button>
                                    <button type="submit" data-edit-save class="hidden rounded-xl bg-green-600 px-4 py-2 text-xs font-semibold text-white hover:bg-green-700 transition">
                                        Save Changes
                                    </button>
                                    <button type="button" data-edit-cancel class="hidden rounded-xl bg-slate-600 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                                        Cancel
                                    </button>
                                </div>
                            @endif
                        </div>
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="edit-element hidden">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Course Title</label>
                                <input type="text" name="title" value="{{ $course->title }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none text-2xl font-semibold">
                            </div>
                        @endif

                        <p class="max-w-3xl text-base leading-7 text-slate-700 view-element">{{ $meta['subtitle'] }}</p>
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="edit-element hidden">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Subtitle</label>
                                <input type="text" name="subtitle" value="{{ $course->subtitle ?? $meta['subtitle'] }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none">
                            </div>
                        @endif
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
                        <p class="text-base leading-7 text-slate-700 view-element">{{ $meta['about'] }}</p>
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="edit-element hidden">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">About the course</label>
                                <textarea name="about" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none">{{ $course->about ?? $meta['about'] }}</textarea>
                            </div>
                        @endif
                    </section>

                    <section class="space-y-4">
                        <h3 class="text-xl font-semibold text-slate-900">What you'll learn</h3>
                        <div class="grid gap-3 sm:grid-cols-2 view-element">
                            @foreach ($meta['learn'] as $item)
                                <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4">
                                    <span class="mt-0.5 text-green-600">
                                        <x-app-icon name="check-circle" class="h-5 w-5" />
                                    </span>
                                    <span class="text-sm leading-6 text-slate-700">{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                        @if (auth()->check() && auth()->user()->isAdmin())
                            <div class="edit-element hidden space-y-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">What you'll learn points</label>
                                <div id="learn-items-container" class="space-y-2">
                                    @foreach ($meta['learn'] as $item)
                                        <div class="flex items-center gap-2 learn-item-row">
                                            <input type="text" name="what_you_will_learn[]" value="{{ $item }}" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none">
                                            <button type="button" class="remove-learn-item rounded-lg bg-red-100 p-2 text-red-600 hover:bg-red-200 transition">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="add-learn-item" class="mt-2 inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
                                    Add Point
                                </button>
                            </div>
                        @endif
                    </section>

                    <!-- Interactive Star Rating Widget -->
                    @if (auth()->check() && $isEnrolled && $isFinished)
                        <section class="rounded-xl border border-slate-200 bg-slate-50 p-6 space-y-4">
                            <h3 class="text-lg font-semibold text-slate-900">Your Rating</h3>
                            
                            <form id="rating-form" method="POST" action="{{ route('courses.rate', $course) }}" class="m-0 p-0">
                                @csrf
                                <input type="hidden" name="rating" id="selected-rating" value="{{ $userRating ?? '' }}">
                                
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-1" data-star-rating-container>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <button 
                                                type="button" 
                                                data-rating-val="{{ $i }}" 
                                                class="star-button transition-transform hover:scale-110 focus:outline-none"
                                            >
                                                <svg 
                                                    viewBox="0 0 24 24" 
                                                    fill="{{ $userRating !== null && $i <= $userRating ? '#eab308' : 'none' }}" 
                                                    stroke="{{ $userRating !== null && $i <= $userRating ? '#eab308' : 'currentColor' }}" 
                                                    stroke-width="1.75" 
                                                    stroke-linecap="round" 
                                                    stroke-linejoin="round" 
                                                    class="h-8 w-8 text-slate-400 star-svg"
                                                >
                                                    <path d="m12 3 2.9 6 6.6.9-4.8 4.7 1.1 6.6-5.8-3.1-5.8 3.1 1.1-6.6L2.5 9.9 9.1 9z" />
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-slate-600 font-medium" id="rating-status-text">
                                        @if ($userRating)
                                            You rated this: {{ $userRating }} stars
                                        @else
                                            Click a star to rate this course
                                        @endif
                                    </span>
                                </div>
                            </form>
                        </section>
                    @endif

                    <section class="rounded-xl border border-slate-200 bg-slate-50 p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                                {{ strtoupper(substr($meta['instructor']['name'] ?? 'A', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 view-element">{{ $meta['instructor']['name'] }}</h3>
                                @if (auth()->check() && auth()->user()->isAdmin())
                                    <div class="edit-element hidden mb-3">
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Instructor Name</label>
                                        <input type="text" name="instructor_name" value="{{ $course->instructor_name ?? $meta['instructor']['name'] }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none">
                                    </div>
                                @endif

                                <p class="mt-2 text-sm leading-6 text-slate-700 view-element">{{ $meta['instructor']['bio'] }}</p>
                                @if (auth()->check() && auth()->user()->isAdmin())
                                    <div class="edit-element hidden">
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Instructor Bio</label>
                                        <textarea name="instructor_bio" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none">{{ $course->instructor_bio ?? $meta['instructor']['bio'] }}</textarea>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
            </section>
        </main>

        <div class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white shadow-lg">
            <div class="mx-auto flex max-w-4xl items-center gap-4 px-4 py-4 sm:px-6 lg:px-8 justify-between">
                <div class="flex items-center gap-4">
                    <div class="view-element">
                        <p class="text-sm text-slate-500">Price</p>
                        <p class="text-xl font-semibold text-slate-900">${{ $meta['price'] }}</p>
                    </div>
                    @if (auth()->check() && auth()->user()->isAdmin())
                        <div class="edit-element hidden">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Price ($)</label>
                            <input type="number" name="price" value="{{ $course->price ?? 79.99 }}" min="0" step="0.01" required class="w-32 rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none">
                        </div>
                    @endif
                </div>

                @guest
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none md:px-6"
                    >
                        Sign In to Enroll
                    </a>
                @else
                    @if ($isEnrolled)
                        <a
                            href="{{ $firstLesson ? route('course.player', [$course, $firstLesson]) : '#' }}"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-950 focus:outline-none md:px-6"
                        >
                            Resume Course
                        </a>
                    @else
                        <form method="POST" action="{{ route('courses.enroll', $course) }}" class="m-0 p-0 inline">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none md:px-6"
                            >
                                Enroll Now
                            </button>
                        </form>
                    @endif
                @endguest
            </div>
        </div>

        @if (auth()->check() && auth()->user()->isAdmin())
            </form>
        @endif
    </div>
</x-layouts.app>
