<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Services\LearningHubContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(LearningHubContent $content): View
    {
        $categories = Category::query()
            ->withCount(['courses' => fn ($query) => $query->where('is_available', true)])
            ->orderBy('name')
            ->get();

        $courses = Course::query()
            ->with(['category', 'lessons'])
            ->where('is_available', true)
            ->latest()
            ->get();

        $featuredCourses = $courses->map(fn (Course $course) => [
            'course' => $course,
            'meta' => $content->courseCardMeta($course),
        ]);

        return view('courses.index', [
            'categories' => $categories,
            'featuredCourses' => $featuredCourses,
            'filters' => $content->catalogFilters(),
        ]);
    }

    public function show(Course $course, LearningHubContent $content): View
    {
        $course->load(['category', 'lessons' => fn ($query) => $query->orderBy('order')]);

        $isEnrolled = auth()->check() 
            ? $course->students()->where('user_id', auth()->id())->exists() 
            : false;

        $firstLesson = null;
        if (auth()->check() && $isEnrolled) {
            $completedLessonIds = auth()->user()->completedLessons()->whereIn('lesson_id', $course->lessons->pluck('id'))->pluck('lesson_id')->toArray();
            $firstLesson = $course->lessons->first(fn ($l) => !in_array($l->id, $completedLessonIds));
        }
        if (!$firstLesson) {
            $firstLesson = $course->lessons->first();
        }

        // Automated/Dynamic calculations
        $avgRating = $course->ratings()->avg('rating');
        $ratingStr = $avgRating !== null ? number_format($avgRating, 1) : 'Rating not available';
        
        $studentsCount = $course->students()->count();
        $studentsStr = number_format($studentsCount);
        
        $durationStr = $course->formattedDuration();

        $isFinished = auth()->check() && $course->isFinishedBy(auth()->user());
            
        $userRating = auth()->check()
            ? $course->ratings()->where('user_id', auth()->id())->first()?->rating
            : null;

        $meta = [
            'subtitle' => $course->subtitle ?? 'A practical course built for focused progress.',
            'rating' => $ratingStr,
            'students' => $studentsStr,
            'duration' => $durationStr,
            'price' => number_format($course->price ?? 79.99, 2),
            'about' => $course->about ?? $course->description,
            'learn' => $course->what_you_will_learn ?? [],
            'instructor' => [
                'name' => $course->instructor_name ?? 'Ava Reynolds',
                'bio' => $course->instructor_bio ?? 'Senior product engineer focused on teaching practical systems and clear UI patterns.',
            ],
            'thumbnail' => $course->thumbnailUrl(),
        ];

        return view('courses.show', [
            'course' => $course,
            'firstLesson' => $firstLesson,
            'meta' => $meta,
            'isEnrolled' => $isEnrolled,
            'isFinished' => $isFinished,
            'userRating' => $userRating,
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'about' => 'nullable|string',
            'what_you_will_learn' => 'nullable|array',
            'what_you_will_learn.*' => 'nullable|string|max:255',
            'instructor_name' => 'required|string|max:255',
            'instructor_bio' => 'nullable|string',
        ]);

        if (isset($validated['what_you_will_learn'])) {
            $validated['what_you_will_learn'] = array_values(array_filter($validated['what_you_will_learn'], fn ($val) => !is_null($val) && trim($val) !== ''));
        } else {
            $validated['what_you_will_learn'] = [];
        }

        $course->update($validated);

        return redirect()
            ->route('courses.show', $course)
            ->with('status', 'Course updated successfully.');
    }

    public function enroll(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();
        
        $course->students()->syncWithoutDetaching([$user->id]);

        $firstLesson = $course->lessons()->orderBy('order')->first();
        
        if ($firstLesson) {
            return redirect()->route('course.player', [$course, $firstLesson])
                ->with('status', 'You have successfully enrolled in this course!');
        }

        return redirect()->route('courses.show', $course)
            ->with('status', 'You have successfully enrolled in this course!');
    }

    public function rate(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();

        if (!$course->students()->where('user_id', $user->id)->exists()) {
            return redirect()->route('courses.show', $course)
                ->withErrors(['rating' => 'You must be enrolled in this course to rate it.']);
        }

        if (!$course->isFinishedBy($user)) {
            return redirect()->route('courses.show', $course)
                ->withErrors(['rating' => 'You must finish all lessons in the course before submitting a rating.']);
        }

        if ($course->ratings()->where('user_id', $user->id)->exists()) {
            return redirect()->route('courses.show', $course)
                ->withErrors(['rating' => 'You have already rated this course. Ratings can only be submitted once.']);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $course->ratings()->create([
            'user_id' => $user->id,
            'rating' => $validated['rating'],
        ]);

        return redirect()->route('courses.show', $course)
            ->with('status', 'Thank you for rating this course!');
    }
}
