<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Services\LearningHubContent;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson, LearningHubContent $content): View
    {
        abort_unless($lesson->course_id === $course->id, 404);

        $course->load(['category', 'lessons' => fn ($query) => $query->orderBy('order')]);
        $lesson->load('course.category');

        $lessons = $course->lessons;
        $currentIndex = $lessons->search(fn (Lesson $item) => $item->id === $lesson->id);

        return view('lessons.show', [
            'course' => $course,
            'lesson' => $lesson,
            'previousLesson' => $currentIndex > 0 ? $lessons[$currentIndex - 1] : null,
            'nextLesson' => $currentIndex !== false && $currentIndex < $lessons->count() - 1 ? $lessons[$currentIndex + 1] : null,
            'player' => $content->playerMeta($course, $lessons),
        ]);
    }
}
