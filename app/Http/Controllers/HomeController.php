<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\LearningHubContent;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(LearningHubContent $content): View
    {
        $categories = Category::query()
            ->withCount(['courses' => fn ($query) => $query->where('is_available', true)])
            ->orderBy('name')
            ->take(4)
            ->get();

        $featuredCourses = Course::query()
            ->with(['category', 'lessons'])
            ->where('is_available', true)
            ->latest()
            ->get();

        $featuredCourses = $featuredCourses->take(3)->map(fn (Course $course) => [
            'course' => $course,
            'meta' => $content->courseCardMeta($course),
        ]);

        return view('home', [
            'categories' => $categories,
            'featuredCourses' => $featuredCourses,
            'stats' => [
                'courses' => Course::query()->where('is_available', true)->count(),
                'lessons' => Lesson::query()
                    ->whereHas('course', fn ($query) => $query->where('is_available', true))
                    ->count(),
                'categories' => Category::query()
                    ->whereHas('courses', fn ($query) => $query->where('is_available', true))
                    ->count(),
            ],
        ]);
    }
}
