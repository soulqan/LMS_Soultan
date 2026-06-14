<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson): View|RedirectResponse
    {
        abort_unless($lesson->course_id === $course->id, 404);

        $user = auth()->user();
        $isEnrolled = $course->students()->where('user_id', $user->id)->exists();
        $isAdmin = $user && $user->isAdmin();

        if (!$isEnrolled && !$isAdmin) {
            return redirect()->route('courses.show', $course)
                ->withErrors(['enrollment' => 'You must be enrolled in this course to view its lessons.']);
        }

        $course->load(['category', 'lessons' => fn ($query) => $query->orderBy('order')]);
        $lesson->load('course.category');

        $lessons = $course->lessons;
        $currentIndex = $lessons->search(fn (Lesson $item) => $item->id === $lesson->id);

        // Fetch completed lesson IDs in database for current user
        $completedLessonIds = $user->completedLessons()->whereIn('lesson_id', $lessons->pluck('id'))->pluck('lesson_id')->toArray();

        // Determine the sequence: find the first uncompleted lesson
        $firstUncompletedIndex = null;
        foreach ($lessons as $index => $item) {
            if (!in_array($item->id, $completedLessonIds)) {
                $firstUncompletedIndex = $index;
                break;
            }
        }

        // Redirect to the first uncompleted lesson if they try to access a locked lesson
        if (!$isAdmin && $firstUncompletedIndex !== null && $currentIndex > $firstUncompletedIndex) {
            $unlockedLesson = $lessons[$firstUncompletedIndex];
            return redirect()->route('course.player', [$course, $unlockedLesson->slug])
                ->withErrors(['sequence' => 'You must complete the previous lessons first.']);
        }

        $chapters = $lessons
            ->groupBy(fn ($l) => $l->chapter_title ?: 'Getting Started')
            ->map(function (\Illuminate\Support\Collection $groupedLessons, string $chapterTitle) use ($lessons, $completedLessonIds, $firstUncompletedIndex) {
                return [
                    'title' => $chapterTitle,
                    'lessons' => $groupedLessons->map(function ($l) use ($lessons, $completedLessonIds, $firstUncompletedIndex) {
                        $itemIndex = $lessons->search(fn ($item) => $item->id === $l->id);

                        return [
                            'id' => $l->id,
                            'title' => $l->title,
                            'slug' => $l->slug,
                            'duration' => $l->duration_minutes . 'm',
                            'completed' => in_array($l->id, $completedLessonIds),
                            'locked' => ($firstUncompletedIndex !== null && $itemIndex > $firstUncompletedIndex),
                            'video_url' => $l->video_url,
                            'content' => $l->content,
                        ];
                    })->values()->toArray(),
                ];
            })
            ->values()
            ->toArray();

        $player = [
            'subtitle' => $course->subtitle ?? 'A practical course built for focused progress.',
            'learn' => $course->what_you_will_learn ?? [],
            'chapters' => $chapters,
        ];

        $isCompleted = in_array($lesson->id, $completedLessonIds);

        return view('lessons.show', [
            'course' => $course,
            'lesson' => $lesson,
            'previousLesson' => $currentIndex > 0 ? $lessons[$currentIndex - 1] : null,
            'nextLesson' => $currentIndex !== false && $currentIndex < $lessons->count() - 1 ? $lessons[$currentIndex + 1] : null,
            'player' => $player,
            'isCompleted' => $isCompleted,
        ]);
    }

    public function complete(Request $request, Course $course, Lesson $lesson)
    {
        $user = $request->user();
        
        if (!$course->students()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Not enrolled'], 403);
        }

        $lessons = $course->lessons()->orderBy('order')->get();
        $completedLessonIds = $user->completedLessons()->whereIn('lesson_id', $lessons->pluck('id'))->pluck('lesson_id')->toArray();

        $firstUncompletedIndex = null;
        foreach ($lessons as $index => $item) {
            if (!in_array($item->id, $completedLessonIds)) {
                $firstUncompletedIndex = $index;
                break;
            }
        }

        $currentIndex = $lessons->search(fn (Lesson $item) => $item->id === $lesson->id);

        if (!$user->isAdmin() && $firstUncompletedIndex !== null && $currentIndex > $firstUncompletedIndex) {
            return response()->json(['error' => 'You must complete the previous lessons first.'], 400);
        }

        $user->completedLessons()->syncWithoutDetaching([$lesson->id]);

        $courseFinished = $course->isFinishedBy($user);

        return response()->json([
            'completed' => true,
            'course_finished' => $courseFinished,
        ]);
    }

    public function submitQuiz(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $user = $request->user();

        if (!$course->students()->where('user_id', $user->id)->exists()) {
            return redirect()->route('course.player', [$course, $lesson])
                ->withErrors(['quiz' => 'You must be enrolled to submit quizzes.']);
        }

        $lessons = $course->lessons()->orderBy('order')->get();
        $completedLessonIds = $user->completedLessons()->whereIn('lesson_id', $lessons->pluck('id'))->pluck('lesson_id')->toArray();

        $firstUncompletedIndex = null;
        foreach ($lessons as $index => $item) {
            if (!in_array($item->id, $completedLessonIds)) {
                $firstUncompletedIndex = $index;
                break;
            }
        }

        $currentIndex = $lessons->search(fn (Lesson $item) => $item->id === $lesson->id);

        if (!$user->isAdmin() && $firstUncompletedIndex !== null && $currentIndex > $firstUncompletedIndex) {
            return redirect()->route('course.player', [$course, $lessons[$firstUncompletedIndex]->slug])
                ->withErrors(['sequence' => 'You must complete the previous lessons first.']);
        }

        $validated = $request->validate([
            'answer' => 'required|integer|min:0|max:3',
        ]);

        $user->completedLessons()->syncWithoutDetaching([$lesson->id]);

        $isCorrect = (int) $validated['answer'] === (int) $lesson->quiz_correct_option;

        if ($isCorrect) {
            return redirect()->route('course.player', [$course, $lesson])
                ->with('status', 'Correct! That is the right answer.')
                ->with('quiz_result', 'correct');
        }

        return redirect()->route('course.player', [$course, $lesson])
            ->with('status', 'Submitted! The lesson is marked as complete.')
            ->with('quiz_result', 'incorrect');
    }

    public function update(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        if (!$request->user()?->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:video,module,quiz',
            'content' => 'nullable|string',
            'video_url' => 'nullable|string|max:255',
            'quiz_question' => 'nullable|string',
            'quiz_options' => 'nullable|array',
            'quiz_options.*' => 'nullable|string|max:255',
            'quiz_correct_option' => 'nullable|integer|min:0|max:3',
            'module_content' => 'nullable|array',
            'module_content.*.title' => 'required|string|max:255',
            'module_content.*.items' => 'nullable|array',
            'module_content.*.items.*.subtitle' => 'nullable|string|max:255',
            'module_content.*.items.*.content' => 'nullable|string',
            'module_content.*.items.*.photo' => 'nullable|string|max:2000',
        ]);

        if ($validated['type'] === 'quiz' && isset($validated['quiz_options'])) {
            $validated['quiz_options'] = array_values(array_filter($validated['quiz_options'], fn ($val) => !is_null($val) && trim($val) !== ''));
        }

        if ($validated['type'] === 'module' && isset($validated['module_content'])) {
            $validated['module_content'] = array_values(array_map(function ($section) {
                if (isset($section['items']) && is_array($section['items'])) {
                    $section['items'] = array_values(array_filter($section['items'], function ($item) {
                        return !empty($item['subtitle']) || !empty($item['content']) || !empty($item['photo']);
                    }));
                } else {
                    $section['items'] = [];
                }
                return $section;
            }, $validated['module_content']));
        }

        $lesson->update($validated);

        return redirect()->route('course.player', [$course, $lesson])
            ->with('status', 'Lesson updated successfully.');
    }
}
