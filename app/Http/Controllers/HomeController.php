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
            ->get();

        $featuredCourses = Course::query()
            ->with(['category', 'lessons'])
            ->where('is_available', true)
            ->latest()
            ->get();

        $categorySpotlights = $categories
            ->map(function (Category $category) use ($content) {
                $course = Course::query()
                    ->with(['category', 'lessons'])
                    ->where('is_available', true)
                    ->where('category_id', $category->id)
                    ->latest()
                    ->first();

                if (! $course) {
                    return null;
                }

                return [
                    'category' => $category,
                    'course' => $course,
                    'meta' => $content->courseCardMeta($course),
                ];
            })
            ->filter()
            ->values();

        return view('home', [
            'categories' => $categories,
            'featuredCourses' => $featuredCourses,
            'categorySpotlights' => $categorySpotlights,
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
