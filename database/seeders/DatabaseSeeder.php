<?php

namespace Database\Seeders;

use App\Enums\CourseLevel;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        $categories = collect([
            'Web Development',
            'Backend Development',
            'Design',
            'Data Science',
            'Mobile Development',
        ])->mapWithKeys(fn (string $name) => [
            $name => Category::query()->create(['name' => $name]),
        ]);

        $courses = [
            [
                'title' => 'Dummy Course',
                'category' => 'Web Development',
                'level' => CourseLevel::Beginner,
                'description' => 'A simple starter course used for testing the LMS flow, from catalog browsing to lesson playback.',
            ],
            [
                'title' => 'Web Development Bootcamp',
                'category' => 'Web Development',
                'level' => CourseLevel::Beginner,
                'description' => 'Build a clean, modern web foundation with practical lessons, responsive layouts, and portfolio-ready outcomes.',
            ],
            [
                'title' => 'React & JavaScript',
                'category' => 'Web Development',
                'level' => CourseLevel::Intermediate,
                'description' => 'Learn component thinking, state management, and modern JavaScript workflows with confidence.',
            ],
            [
                'title' => 'Node.js Backend',
                'category' => 'Backend Development',
                'level' => CourseLevel::Intermediate,
                'description' => 'Create reliable server-side APIs, data flows, and backend structure for real applications.',
            ],
            [
                'title' => 'UI/UX Design',
                'category' => 'Design',
                'level' => CourseLevel::Beginner,
                'description' => 'Design calm, usable experiences with strong hierarchy, spacing, and clear interactions.',
            ],
            [
                'title' => 'Python Data Science',
                'category' => 'Data Science',
                'level' => CourseLevel::Intermediate,
                'description' => 'Use Python to clean, explore, and communicate data in a practical workflow.',
            ],
            [
                'title' => 'Mobile Development',
                'category' => 'Mobile Development',
                'level' => CourseLevel::Beginner,
                'description' => 'Build mobile-first experiences with a strong focus on clarity, touch interactions, and reliability.',
            ],
            [
                'title' => 'Machine Learning',
                'category' => 'Data Science',
                'level' => CourseLevel::Advanced,
                'description' => 'Understand how machine learning models are framed, trained, evaluated, and applied.',
            ],
            [
                'title' => 'Fullstack Development',
                'category' => 'Web Development',
                'level' => CourseLevel::Advanced,
                'description' => 'Connect frontend, backend, and deployment thinking into one clear development flow.',
            ],
        ];

        $lessonTitles = [
            'Setup and orientation',
            'Project structure and tooling',
            'Core principles',
            'Building the base UI',
            'Working with components',
            'Data and state',
            'Forms and validation',
            'Accessibility and polish',
            'Debugging and testing',
            'Deployment checklist',
            'Project walkthrough',
            'Next steps and review',
        ];

        foreach ($courses as $courseData) {
            $course = Course::query()->create([
                'category_id' => $categories[$courseData['category']]->id,
                'title' => $courseData['title'],
                'description' => $courseData['description'],
                'level' => $courseData['level'],
                'thumbnail' => null,
            ]);

            foreach ($lessonTitles as $index => $lessonTitle) {
                Lesson::query()->create([
                    'course_id' => $course->id,
                    'title' => $lessonTitle,
                    'video_url' => $index % 2 === 0
                        ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'
                        : 'https://drive.google.com/file/d/1dQw4w9WgXcQ/view',
                    'content' => 'A focused lesson for ' . $course->title . ' that covers ' . $lessonTitle . ' with practical, portfolio-ready examples.',
                    'order' => $index + 1,
                ]);
            }
        }
    }
}
