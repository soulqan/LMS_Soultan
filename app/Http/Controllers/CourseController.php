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
        $featuredCourses = Course::query()
            ->with(['category', 'lessons'])
            ->latest()
            ->get();

        $categories = Category::query()
            ->withCount('courses')
            ->orderBy('name')
            ->get();

        $featuredCourses = $featuredCourses->take(8)->map(fn (Course $course) => [
            'course' => $course,
            'meta' => $content->courseCardMeta($course),
        ]);

        return view('home', [
            'featuredCourses' => $featuredCourses,
            'categories' => $categories,
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
