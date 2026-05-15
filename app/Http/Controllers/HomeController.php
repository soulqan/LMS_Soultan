<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Services\LearningHubContent;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(LearningHubContent $content): View
    {
        $categories = Category::query()
            ->withCount('courses')
            ->orderBy('name')
            ->get();

        $featuredCourses = Course::query()
            ->with(['category', 'lessons'])
            ->latest()
            ->get();

        $featuredCourses = $featuredCourses->take(8)->map(fn (Course $course) => [
            'course' => $course,
            'meta' => $content->courseCardMeta($course),
        ]);

        return view('home', [
            'categories' => $categories,
            'featuredCourses' => $featuredCourses,
            'filters' => $content->catalogFilters(),
        ]);
    }
}
