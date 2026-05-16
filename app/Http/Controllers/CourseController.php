<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Services\LearningHubContent;
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

        $firstLesson = $course->lessons->first();

        return view('courses.show', [
            'course' => $course,
            'firstLesson' => $firstLesson,
            'meta' => $content->landingMeta($course),
        ]);
    }
}
