<x-layouts.app :title="$lesson->title . ' | LearningHub'" :meta-description="\Illuminate\Support\Str::limit(strip_tags($lesson->content ?? $course->description), 155)">
    <div data-player-page class="min-h-dvh bg-slate-950 text-white">
        @if (auth()->check() && auth()->user()->isAdmin())
            <form id="lesson-edit-form" method="POST" action="{{ route('lessons.update', [$course, $lesson]) }}">
                @csrf
                @method('PATCH')
        @endif

        <x-site-header
            :title="$course->title"
            :eyebrow="$course->category->name"
            :back-url="route('course.show', $course)"
            back-label="Back"
            dark
        />

        <main class="mx-auto grid max-w-7xl gap-6 px-4 py-6 lg:grid-cols-[minmax(0,1.7fr)_384px] lg:px-8">
            <section class="space-y-6">
                @if (session('status'))
                    <div class="mb-4 rounded-xl border border-green-900 bg-green-950/40 p-4 text-sm font-medium text-green-300 flex items-center gap-2">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-green-400"><circle cx="12" cy="12" r="9"></circle><path d="m9 12 2 2 4-4"></path></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-900 bg-red-950/40 p-4 text-sm font-medium text-red-300">
                        <p class="font-semibold mb-2">Feedback:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($lesson->type === 'video' || $lesson->type === 'quiz')
                    <div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900 mb-6">
                        <!-- Dynamic Lesson Type Renderer -->
                        <div class="view-element">
                            @if ($lesson->type === 'video')
                                <div class="relative aspect-video bg-gradient-to-br from-slate-900 via-slate-950 to-black">
                                    @if ($lesson->video_url)
                                        <iframe
                                            data-player-video
                                            src="{{ $lesson->embedUrl() }}"
                                            title="{{ $lesson->title }}"
                                            class="absolute inset-0 h-full w-full"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                    @else
                                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-500 space-y-2">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="h-10 w-10 text-slate-600"><rect width="20" height="15" x="2" y="3" rx="2"></rect><path d="m10 11 5 3-5 3v-6Z"></path></svg>
                                            <span class="text-sm">No video URL provided.</span>
                                        </div>
                                    @endif
                                </div>
                            @elseif ($lesson->type === 'quiz')
                                <div class="p-8 sm:p-12 min-h-[350px] flex flex-col justify-center bg-slate-900/60">
                                    @if ($isCompleted)
                                        <div class="rounded-xl border border-green-900 bg-green-950/40 p-6 text-center space-y-4 max-w-xl mx-auto w-full">
                                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-500/10 text-green-400">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><path d="m22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m22 4-10 10.01-3-3"></path></svg>
                                            </span>
                                            <div>
                                                <h4 class="text-lg font-semibold text-white">Quiz Completed!</h4>
                                                <p class="mt-1 text-sm text-green-300">You have successfully passed the quiz for this lesson.</p>
                                            </div>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('lessons.quiz', [$course, $lesson]) }}" class="max-w-xl mx-auto space-y-6 w-full">
                                            @csrf
                                            <div>
                                                <span class="inline-flex rounded-full bg-blue-600/10 px-3 py-1 text-xs font-semibold tracking-wider text-blue-400 uppercase mb-2">Quiz Question</span>
                                                <h3 class="text-lg font-medium text-white">{{ $lesson->quiz_question ?? 'No question configured.' }}</h3>
                                            </div>

                                            @if ($lesson->quiz_options)
                                                <div class="space-y-3">
                                                    @foreach ($lesson->quiz_options as $optIdx => $option)
                                                        <label class="flex items-center gap-3 rounded-xl border border-slate-800 bg-slate-950 p-4 transition hover:bg-slate-800 cursor-pointer">
                                                            <input type="radio" name="answer" value="{{ $optIdx }}" required class="h-4 w-4 border-slate-700 bg-slate-900 text-blue-600 focus:ring-blue-500 focus:ring-offset-slate-950">
                                                            <span class="text-sm text-slate-300">{{ $option }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <button 
                                                type="submit"
                                                class="w-full inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            >
                                                Submit Answer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-2xl font-semibold text-white view-element">{{ $lesson->title }}</h2>
                                @if (auth()->check() && auth()->user()->isAdmin())
                                    <div class="flex gap-2">
                                        <button type="button" data-lesson-edit-toggle class="rounded-xl bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 transition">
                                            Edit Lesson
                                        </button>
                                        <button type="submit" data-lesson-edit-save class="hidden rounded-xl bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700 transition">
                                            Save Changes
                                        </button>
                                        <button type="button" data-lesson-edit-cancel class="hidden rounded-xl bg-slate-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 transition">
                                            Cancel
                                        </button>
                                    </div>
                                @endif
                            </div>
                            @if (auth()->check() && auth()->user()->isAdmin())
                                <div class="edit-element hidden mb-3 mt-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-450 mb-1">Lesson Title</label>
                                    <input type="text" name="title" value="{{ $lesson->title }}" required class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-white focus:border-blue-500 focus:outline-none text-xl">
                                </div>
                            @endif
                            <p class="mt-2 text-sm text-slate-400">{{ $player['subtitle'] }}</p>
                        </div>

                        <!-- Mark Complete Button (hide for Quiz since it requires passing the quiz) -->
                        @if ($lesson->type === 'video' || $lesson->type === 'quiz')
                            <button
                                type="button"
                                data-mark-complete
                                data-complete-url="{{ route('lessons.complete', [$course, $lesson]) }}"
                                @class([
                                    'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500',
                                    'bg-green-600 hover:bg-green-700' => $isCompleted,
                                    'bg-blue-600 hover:bg-blue-700' => !$isCompleted,
                                ])
                            >
                                <x-app-icon name="check-circle" class="h-4 w-4" />
                                <span data-complete-text>{{ $isCompleted ? 'Completed' : 'Mark as Complete' }}</span>
                            </button>
                        @endif
                    </div>

                    <!-- Dynamic Module Content Renderer -->
                    @if ($lesson->type === 'module')
                        @if (!empty($lesson->module_content) && is_array($lesson->module_content))
                            <div class="mt-8 space-y-8 border-t border-slate-800 pt-6 view-element">
                                @foreach ($lesson->module_content as $section)
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3 border-b border-slate-850 pb-3">
                                            <span class="h-5 w-1.5 rounded-full bg-blue-500"></span>
                                            <h3 class="text-lg font-semibold text-white">{{ $section['title'] }}</h3>
                                        </div>
                                        
                                        @if (!empty($section['items']) && is_array($section['items']))
                                            <div class="space-y-6">
                                                @foreach ($section['items'] as $item)
                                                    <div class="rounded-xl border border-slate-800 bg-slate-950/40 p-5 space-y-4">
                                                        @if (!empty($item['photo']))
                                                            <div class="grid gap-6 md:grid-cols-2 items-start">
                                                                <div class="space-y-2">
                                                                    @if (!empty($item['subtitle']))
                                                                        <h4 class="text-md font-medium text-blue-400">{{ $item['subtitle'] }}</h4>
                                                                    @endif
                                                                    @if (!empty($item['content']))
                                                                        <p class="text-sm leading-relaxed text-slate-300 whitespace-pre-line">{{ $item['content'] }}</p>
                                                                    @endif
                                                                </div>
                                                                <img src="{{ $item['photo'] }}" alt="{{ $item['subtitle'] ?? 'Subsection photo' }}" class="w-full max-h-64 object-cover rounded-lg border border-slate-800 shadow-md">
                                                            </div>
                                                        @else
                                                            <div class="space-y-2">
                                                                @if (!empty($item['subtitle']))
                                                                    <h4 class="text-md font-medium text-blue-400">{{ $item['subtitle'] }}</h4>
                                                                @endif
                                                                @if (!empty($item['content']))
                                                                    <p class="text-sm leading-relaxed text-slate-300 whitespace-pre-line">{{ $item['content'] }}</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @if (auth()->check() && auth()->user()->isAdmin())
                        <div class="edit-element hidden my-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-450 mb-1">Lesson Type</label>
                                <select name="type" id="lesson-type-select" required class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-white focus:border-blue-500 focus:outline-none">
                                    <option value="video" {{ $lesson->type === 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="module" {{ $lesson->type === 'module' ? 'selected' : '' }}>Module</option>
                                    <option value="quiz" {{ $lesson->type === 'quiz' ? 'selected' : '' }}>Quiz</option>
                                </select>
                            </div>
                            
                            <div id="edit-video-url-group">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-450 mb-1">Video URL (Video only)</label>
                                <input type="text" name="video_url" value="{{ $lesson->video_url }}" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-white focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>

                        <!-- Module details edit group -->
                        <div class="edit-element hidden my-4 border-t border-slate-800 pt-4 space-y-4" id="edit-module-details-group">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-blue-400">Module Structure Configuration</h4>
                                <button type="button" id="add-module-section" class="inline-flex items-center gap-1 rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-medium text-slate-300 hover:bg-slate-700 transition">
                                    + Add Section
                                </button>
                            </div>

                            <div id="module-sections-container" class="space-y-6">
                                @if (!empty($lesson->module_content) && is_array($lesson->module_content))
                                    @foreach ($lesson->module_content as $secIdx => $section)
                                        <div class="section-row border border-slate-800 rounded-xl p-4 bg-slate-900/40 space-y-4" data-section-index="{{ $secIdx }}">
                                            <div class="flex items-center justify-between gap-4">
                                                <div class="flex-1">
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Section Title</label>
                                                    <input type="text" name="module_content[{{ $secIdx }}][title]" value="{{ $section['title'] }}" required class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-white text-sm focus:border-blue-500 focus:outline-none">
                                                </div>
                                                <button type="button" class="remove-section-btn mt-5 rounded-lg bg-red-950/65 p-2 text-red-400 hover:bg-red-900/40 transition">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                                                </button>
                                            </div>

                                            <div class="items-container pl-6 border-l border-slate-800 space-y-4">
                                                @if (!empty($section['items']) && is_array($section['items']))
                                                    @foreach ($section['items'] as $itemIdx => $item)
                                                        <div class="item-row border border-slate-850 rounded-lg p-3 bg-slate-950/20 space-y-3" data-item-index="{{ $itemIdx }}">
                                                            <div class="flex items-center justify-between">
                                                                <h5 class="text-xs font-semibold text-slate-450">Subsection Item</h5>
                                                                <button type="button" class="remove-item-btn rounded bg-red-950/40 px-2 py-1 text-[10px] text-red-400 hover:bg-red-950/60 transition">
                                                                    Remove Item
                                                                </button>
                                                            </div>
                                                            <div class="grid gap-3 sm:grid-cols-2">
                                                                <div>
                                                                    <label class="block text-xs text-slate-400 mb-1">Subtitle</label>
                                                                    <input type="text" name="module_content[{{ $secIdx }}][items][{{ $itemIdx }}][subtitle]" value="{{ $item['subtitle'] ?? '' }}" class="w-full rounded border border-slate-700 bg-slate-850 px-2 py-1 text-xs text-white focus:border-blue-500 focus:outline-none">
                                                                </div>
                                                                <div>
                                                                    <label class="block text-xs text-slate-400 mb-1">Image URL</label>
                                                                    <input type="text" name="module_content[{{ $secIdx }}][items][{{ $itemIdx }}][photo]" value="{{ $item['photo'] ?? '' }}" class="w-full rounded border border-slate-700 bg-slate-850 px-2 py-1 text-xs text-white focus:border-blue-500 focus:outline-none">
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs text-slate-400 mb-1">Content</label>
                                                                <textarea name="module_content[{{ $secIdx }}][items][{{ $itemIdx }}][content]" rows="3" class="w-full rounded border border-slate-700 bg-slate-850 px-2 py-1 text-xs text-white focus:border-blue-500 focus:outline-none">{{ $item['content'] ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>

                                            <button type="button" class="add-item-btn inline-flex items-center gap-1 rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-medium text-slate-300 hover:bg-slate-700 transition">
                                                + Add Subsection Item
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Quiz details edit group -->
                        <div class="edit-element hidden my-4 border-t border-slate-800 pt-4 space-y-4" id="edit-quiz-details-group">
                            <h4 class="text-sm font-semibold text-blue-400">Quiz Configuration</h4>
                            
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-450 mb-1">Quiz Question</label>
                                <input type="text" name="quiz_question" value="{{ $lesson->quiz_question }}" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-white focus:border-blue-500 focus:outline-none">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-450">Choices (Exactly 4)</label>
                                @for ($optIdx = 0; $optIdx < 4; $optIdx++)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-slate-500 font-bold w-6">{{ $optIdx + 1 }}.</span>
                                        <input type="text" name="quiz_options[]" value="{{ isset($lesson->quiz_options[$optIdx]) ? $lesson->quiz_options[$optIdx] : '' }}" class="flex-1 rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-white focus:border-blue-500 focus:outline-none">
                                    </div>
                                @endfor
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-450 mb-1">Correct Answer</label>
                                <select name="quiz_correct_option" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-white focus:border-blue-500 focus:outline-none">
                                    @for ($optIdx = 0; $optIdx < 4; $optIdx++)
                                        <option value="{{ $optIdx }}" {{ (int) $lesson->quiz_correct_option === $optIdx ? 'selected' : '' }}>Option {{ $optIdx + 1 }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 pt-6 border-t border-slate-800 space-y-6">
                        <div class="space-y-3">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-450">About This Lesson</h3>
                                @if ($lesson->type === 'module')
                                    <button
                                        type="button"
                                        data-mark-complete
                                        data-complete-url="{{ route('lessons.complete', [$course, $lesson]) }}"
                                        @class([
                                            'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500',
                                            'bg-green-600 hover:bg-green-700' => $isCompleted,
                                            'bg-blue-600 hover:bg-blue-700' => !$isCompleted,
                                        ])
                                    >
                                        <x-app-icon name="check-circle" class="h-4 w-4" />
                                        <span data-complete-text>{{ $isCompleted ? 'Completed' : 'Mark as Complete' }}</span>
                                    </button>
                                @endif
                            </div>
                            <p class="text-sm leading-relaxed text-slate-300 view-element">{{ $lesson->content }}</p>
                            @if (auth()->check() && auth()->user()->isAdmin())
                                <div class="edit-element hidden">
                                    <textarea name="content" rows="6" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-white text-sm focus:border-blue-500 focus:outline-none">{{ $lesson->content }}</textarea>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4 pt-4 border-t border-slate-800/60">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">What You'll Learn</h3>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach ($player['learn'] as $item)
                                    <div class="flex items-start gap-3 rounded-xl border border-slate-800 bg-slate-950/40 p-4">
                                        <span class="mt-0.5 text-green-500">
                                            <x-app-icon name="check" class="h-4 w-4" />
                                        </span>
                                        <span class="text-sm leading-6 text-slate-300">{{ $item }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-slate-800/60">
                            @if ($previousLesson)
                                <a
                                    href="{{ route('course.player', [$course, $previousLesson]) }}"
                                    class="inline-flex items-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-200 transition-colors"
                                >
                                    <x-app-icon name="chevron-left" class="h-4 w-4" />
                                    <span>Previous Lesson</span>
                                </a>
                            @else
                                <div></div>
                            @endif

                            @if ($nextLesson && (auth()->user()?->isAdmin() || $isCompleted))
                                <a
                                    href="{{ route('course.player', [$course, $nextLesson]) }}"
                                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors"
                                >
                                    <span>Next Lesson</span>
                                    <x-app-icon name="chevron-right" class="h-4 w-4" />
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <aside class="lg:sticky lg:top-[96px] lg:h-[calc(100dvh-8rem)]">
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
                                        @php
                                            $isLocked = !auth()->user()?->isAdmin() && !empty($item['locked']);
                                        @endphp
                                        <a
                                            @if (!$isLocked)
                                                href="{{ route('course.player', [$course, $item['slug']]) }}"
                                            @endif
                                            data-lesson-button
                                            data-lesson-id="{{ $item['id'] }}"
                                            data-lesson-completed="{{ $item['completed'] ? '1' : '0' }}"
                                            data-lesson-locked="{{ $isLocked ? '1' : '0' }}"
                                            @class([
                                                'flex w-full items-start justify-between gap-3 rounded-xl px-3 py-3 text-left transition focus:outline-none focus:ring-2 focus:ring-blue-500',
                                                'bg-blue-600/10' => $item['id'] === $lesson->id,
                                                'hover:bg-slate-800 cursor-pointer' => !$isLocked,
                                                'opacity-50 cursor-not-allowed pointer-events-none' => $isLocked,
                                            ])
                                        >
                                            <div class="flex items-start gap-3">
                                                <span data-lesson-icon class="mt-0.5 text-green-500">
                                                    @if ($item['completed'])
                                                        <x-app-icon name="check-circle" class="h-5 w-5" />
                                                    @elseif ($isLocked)
                                                        <x-app-icon name="lock" class="h-5 w-5 text-slate-500" />
                                                    @else
                                                        <x-app-icon name="circle" class="h-5 w-5 text-slate-650" />
                                                    @endif
                                                </span>
                                                <span class="space-y-1">
                                                    <span class="block text-sm font-medium text-white">{{ $item['title'] }}</span>
                                                    <span class="block text-xs text-slate-450">Lesson {{ $item['id'] }}</span>
                                                </span>
                                            </div>
                                            <span class="rounded-full bg-slate-850 px-2.5 py-1 text-xs font-medium text-slate-300">{{ $item['duration'] }}</span>
                                        </a>
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

        @if (auth()->check() && auth()->user()->isAdmin())
            </form>
        @endif
    </div>
</x-layouts.app>
