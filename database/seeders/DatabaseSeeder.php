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

        $contentService = new \App\Services\LearningHubContent();

        foreach ($courses as $courseData) {
            $course = Course::query()->create([
                'category_id' => $categories[$courseData['category']]->id,
                'title' => $courseData['title'],
                'description' => $courseData['description'],
                'level' => $courseData['level'],
                'thumbnail' => null,
            ]);

            try {
                $meta = $contentService->landingMeta($course);
                $course->update([
                    'subtitle' => $meta['subtitle'] ?? null,
                    'rating' => $meta['rating'] ?? 4.8,
                    'enrolled_count' => intval(str_replace(',', '', $meta['students'] ?? '12543')),
                    'duration' => $meta['duration'] ?? '42 hours',
                    'price' => $meta['price'] ?? 79.99,
                    'about' => $meta['about'] ?? $course->description,
                    'what_you_will_learn' => $meta['learn'] ?? [],
                    'instructor_name' => $meta['instructor']['name'] ?? 'Ava Reynolds',
                    'instructor_bio' => $meta['instructor']['bio'] ?? null,
                ]);
            } catch (\Exception $e) {
                // Ignore fallback exceptions
            }

            foreach ($lessonTitles as $index => $lessonTitle) {
                $type = 'video';
                $quizQuestion = null;
                $quizOptions = null;
                $quizCorrectOption = null;
                $moduleContent = null;

                if ($index === 4) {
                    $type = 'quiz';
                    $quizQuestion = 'Which of the following is correct about ' . $course->title . '?';
                    $quizOptions = [
                        'It is a standard tool for developers.',
                        'It is never used in industry.',
                        'It requires no installation.',
                        'It only works on special operating systems.'
                    ];
                    $quizCorrectOption = 0;
                } elseif ($index === 8) {
                    $type = 'quiz';
                    $quizQuestion = 'What is the main advantage of ' . $lessonTitle . '?';
                    $quizOptions = [
                        'Saves time and increases code quality.',
                        'Makes the system slower.',
                        'Increases cost and complexity.',
                        'None of the options.'
                    ];
                    $quizCorrectOption = 0;
                } elseif ($index === 3) {
                    $type = 'module';
                    $moduleContent = [
                        [
                            'title' => 'Getting Started with the Module',
                            'items' => [
                                [
                                    'subtitle' => 'Module Setup Basics',
                                    'content' => "In this subsection, we cover the essentials of getting your development environment configured for the " . $course->title . " layout. This includes packages, assets, and standard directory folders.",
                                    'photo' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80',
                                ],
                                [
                                    'subtitle' => 'Recommended Practices',
                                    'content' => 'Always verify your Node and PHP versions first. A mismatch between local staging and actual production dependencies can introduce runtime syntax or class resolution errors.',
                                    'photo' => null,
                                ]
                            ]
                        ],
                        [
                            'title' => 'Structural Architecture',
                            'items' => [
                                [
                                    'subtitle' => 'Nested Hierarchy Layouts',
                                    'content' => 'E-learning components operate best with structured, sequential data flows. Ensuring that parent routers pass consistent models allows player sidebars and navigation panels to refresh instantly.',
                                    'photo' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=600&q=80',
                                ]
                            ]
                        ]
                    ];
                } elseif ($index === 7) {
                    $type = 'module';
                    $moduleContent = [
                        [
                            'title' => 'Module Integration and Polish',
                            'items' => [
                                [
                                    'subtitle' => 'Refining User Interaction',
                                    'content' => 'Adding dynamic transitions and custom styled elements transforms standard lessons into premium experiences. Focus on typography hierarchy and micro-animations.',
                                    'photo' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=600&q=80',
                                ]
                            ]
                        ]
                    ];
                }

                Lesson::query()->create([
                    'course_id' => $course->id,
                    'title' => $lessonTitle,
                    'type' => $type,
                    'video_url' => $type === 'video' ? ($index % 2 === 0
                        ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'
                        : 'https://drive.google.com/file/d/1dQw4w9WgXcQ/view') : null,
                    'content' => 'A focused lesson for ' . $course->title . ' that covers ' . $lessonTitle . ' with practical, portfolio-ready examples.',
                    'order' => $index + 1,
                    'duration_minutes' => [12, 15, 18, 20, 25, 30, 45][$index % 7],
                    'quiz_question' => $quizQuestion,
                    'quiz_options' => $quizOptions,
                    'quiz_correct_option' => $quizCorrectOption,
                    'module_content' => $moduleContent,
                ]);
            }
        }

        // Seed 10 student users
        $students = collect();
        for ($i = 1; $i <= 10; $i++) {
            $students->push(
                \App\Models\User::factory()->create([
                    'name' => "Student $i",
                    'email' => "student$i@example.com",
                    'role' => \App\Models\User::ROLE_STUDENT,
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                ])
            );
        }

        // Randomly enroll students in courses and seed completions and ratings
        $allCourses = Course::all();
        foreach ($allCourses as $c) {
            $enrolledStudents = $students->random(rand(3, 8));
            $lessonsForCourse = $c->lessons;
            foreach ($enrolledStudents as $student) {
                $c->students()->attach($student->id);

                $shouldFinish = rand(0, 1);
                $lessonsToComplete = $shouldFinish ? $lessonsForCourse : $lessonsForCourse->random(rand(0, $lessonsForCourse->count() - 1));

                foreach ($lessonsToComplete as $l) {
                    $student->completedLessons()->attach($l->id);
                }

                if ($shouldFinish) {
                    $c->ratings()->create([
                        'user_id' => $student->id,
                        'rating' => rand(3, 5),
                    ]);
                }
            }
        }
    }
}
