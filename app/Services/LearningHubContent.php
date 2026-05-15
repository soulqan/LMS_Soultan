<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LearningHubContent
{
    public function catalogFilters(): array
    {
        return [
            'all' => 'All',
            'web-development' => 'Web Development',
            'backend-development' => 'Backend Development',
            'design' => 'Design',
            'data-science' => 'Data Science',
            'mobile-development' => 'Mobile Development',
        ];
    }

    public function courseCardMeta(Course $course): array
    {
        $meta = $this->metaFor($course);

        return [
            'category_badge' => $meta['category_badge'],
            'level_label' => $course->level->label(),
            'level_tone' => $course->level->tone(),
            'thumbnail' => $meta['thumbnail'],
            'summary' => $meta['summary'],
            'category_slug' => $meta['category_slug'],
        ];
    }

    public function landingMeta(Course $course): array
    {
        $meta = $this->metaFor($course);

        return [
            'subtitle' => $meta['subtitle'],
            'rating' => '4.8',
            'students' => '12,543',
            'duration' => '42 hours',
            'price' => '79.99',
            'about' => $meta['about'],
            'learn' => $meta['learn'],
            'instructor' => $meta['instructor'],
            'thumbnail' => $meta['thumbnail'],
        ];
    }

    public function playerMeta(Course $course, Collection $lessons): array
    {
        $meta = $this->metaFor($course);
        $chapterNames = [
            'Getting Started',
            'Core Concepts',
            'Advanced Topics',
            'Real-World Projects',
        ];
        $durations = [
            '18:20', '16:42', '21:10',
            '19:35', '24:08', '17:50',
            '22:14', '20:03', '23:39',
            '18:57', '25:12', '26:30',
        ];

        $chapters = $lessons
            ->values()
            ->chunk(3)
            ->map(function (Collection $chunk, int $index) use ($chapterNames, $durations) {
                return [
                    'title' => $chapterNames[$index] ?? 'Chapter ' . ($index + 1),
                    'lessons' => $chunk->values()->map(function ($lesson, int $lessonIndex) use ($durations, $index) {
                        $durationIndex = ($index * 3) + $lessonIndex;

                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'duration' => $durations[$durationIndex] ?? '18:00',
                            'completed' => $lesson->order <= 2,
                            'video_url' => $lesson->video_url,
                            'content' => $lesson->content,
                        ];
                    }),
                ];
            })
            ->values();

        return [
            'subtitle' => $meta['subtitle'],
            'description' => $meta['about'],
            'learn' => $meta['learn'],
            'resources' => [
                [
                    'label' => 'Course workbook',
                    'meta' => 'PDF',
                    'href' => '#',
                ],
                [
                    'label' => 'Source assets',
                    'meta' => 'ZIP',
                    'href' => '#',
                ],
                [
                    'label' => 'Checklist',
                    'meta' => 'MD',
                    'href' => '#',
                ],
            ],
            'chapters' => $chapters,
        ];
    }

    private function metaFor(Course $course): array
    {
        return $this->catalogData()[Str::slug($course->title)] ?? [
            'category_badge' => $course->category?->name ?? 'Learning',
            'category_slug' => Str::slug($course->category?->name ?? 'learning'),
            'thumbnail' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
            'summary' => $course->description,
            'subtitle' => 'A practical course built for focused progress.',
            'about' => $course->description,
            'learn' => [
                'Build with a clean, production-style structure.',
                'Ship a responsive interface that works on every screen size.',
                'Practice patterns you can reuse in real client projects.',
                'Keep learner progress organized and easy to resume.',
                'Connect content, media, and navigation with a polished flow.',
                'Create a portfolio-ready learning experience.',
            ],
            'instructor' => [
                'name' => 'Ava Reynolds',
                'bio' => 'Senior product engineer focused on teaching practical systems and clear UI patterns.',
            ],
        ];
    }

    private function catalogData(): array
    {
        return [
            'web-development-bootcamp' => [
                'category_badge' => 'Web Development',
                'category_slug' => 'web-development',
                'thumbnail' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=1200&q=80',
                'summary' => 'A structured path from fundamentals to shipping polished web apps.',
                'subtitle' => 'Build modern web apps from first principles.',
                'about' => 'Learn the core workflow for designing, building, and launching modern web products with confidence.',
                'learn' => [
                    'Set up a reliable project structure.',
                    'Design responsive interfaces with Tailwind.',
                    'Work with APIs and browser state.',
                    'Ship accessible layouts and components.',
                    'Build reusable features with clean code.',
                    'Prepare work for deployment and iteration.',
                ],
                'instructor' => [
                    'name' => 'Maya Chen',
                    'bio' => 'Frontend architect who teaches practical build systems and accessible UI implementation.',
                ],
            ],
            'react-javascript' => [
                'category_badge' => 'Web Development',
                'category_slug' => 'web-development',
                'thumbnail' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=1200&q=80',
                'summary' => 'Learn React fundamentals and state patterns with confidence.',
                'subtitle' => 'Learn React and modern JavaScript together.',
                'about' => 'A hands-on course covering component thinking, state management, and common JavaScript workflows.',
                'learn' => [
                    'Use modern JavaScript syntax cleanly.',
                    'Split interfaces into reusable components.',
                    'Handle forms, events, and state updates.',
                    'Fetch and display data from APIs.',
                    'Structure a scalable React application.',
                    'Think in flows instead of isolated widgets.',
                ],
                'instructor' => [
                    'name' => 'Noah Patel',
                    'bio' => 'React educator and UI engineer with a sharp focus on maintainable frontend patterns.',
                ],
            ],
            'nodejs-backend' => [
                'category_badge' => 'Backend Development',
                'category_slug' => 'backend-development',
                'thumbnail' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80',
                'summary' => 'Create APIs and server logic with Node.js best practices.',
                'subtitle' => 'Design the backend that powers the product.',
                'about' => 'Explore request handling, API design, and the server-side patterns that keep applications reliable.',
                'learn' => [
                    'Plan clean route and controller boundaries.',
                    'Validate incoming data predictably.',
                    'Structure service layers for maintainability.',
                    'Model data access clearly.',
                    'Handle errors and API responses gracefully.',
                    'Prepare a backend for real-world usage.',
                ],
                'instructor' => [
                    'name' => 'Daniel Brooks',
                    'bio' => 'Backend engineer who builds APIs with a focus on clarity, stability, and performance.',
                ],
            ],
            'ui-ux-design' => [
                'category_badge' => 'Design',
                'category_slug' => 'design',
                'thumbnail' => 'https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1200&q=80',
                'summary' => 'Design interfaces that feel calm, functional, and purposeful.',
                'subtitle' => 'Create interfaces people enjoy using.',
                'about' => 'Build a practical design process that turns user needs into clear, polished digital experiences.',
                'learn' => [
                    'Explore layout, spacing, and hierarchy.',
                    'Create consistent reusable patterns.',
                    'Use typography and color with intent.',
                    'Refine flows with user experience thinking.',
                    'Prepare handoff-friendly design systems.',
                    'Review and improve product decisions visually.',
                ],
                'instructor' => [
                    'name' => 'Sophia Martinez',
                    'bio' => 'Product designer helping teams simplify interfaces and sharpen visual systems.',
                ],
            ],
            'python-data-science' => [
                'category_badge' => 'Data Science',
                'category_slug' => 'data-science',
                'thumbnail' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80',
                'summary' => 'Use Python to explore, clean, and understand data effectively.',
                'subtitle' => 'Turn data into useful decisions.',
                'about' => 'Learn a practical data science workflow that moves from raw datasets to clear, meaningful insights.',
                'learn' => [
                    'Load and inspect datasets quickly.',
                    'Clean and prepare messy data.',
                    'Visualize trends and patterns.',
                    'Use notebooks for exploratory analysis.',
                    'Tell a data story with clarity.',
                    'Build foundations for ML workflows.',
                ],
                'instructor' => [
                    'name' => 'Emma Wilson',
                    'bio' => 'Data scientist who translates complex datasets into decisions and repeatable workflows.',
                ],
            ],
            'mobile-development' => [
                'category_badge' => 'Mobile Development',
                'category_slug' => 'mobile-development',
                'thumbnail' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9d?auto=format&fit=crop&w=1200&q=80',
                'summary' => 'Build polished mobile experiences with practical app architecture.',
                'subtitle' => 'Create apps that feel native and responsive.',
                'about' => 'Focus on app structure, state, and mobile-first thinking so your product feels smooth on device.',
                'learn' => [
                    'Plan layouts for smaller screens first.',
                    'Build touch-friendly navigation.',
                    'Connect app screens with state flow.',
                    'Prepare for offline-friendly behavior.',
                    'Handle media and content carefully.',
                    'Ship something users can actually open and use.',
                ],
                'instructor' => [
                    'name' => 'Oliver Grant',
                    'bio' => 'Mobile product developer who focuses on fast, dependable, and intuitive app experiences.',
                ],
            ],
            'machine-learning' => [
                'category_badge' => 'Data Science',
                'category_slug' => 'data-science',
                'thumbnail' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
                'summary' => 'Introduce ML concepts with examples that feel practical, not abstract.',
                'subtitle' => 'Understand the core ideas behind machine learning.',
                'about' => 'Learn the practical building blocks of machine learning, from problem framing to evaluating results.',
                'learn' => [
                    'Frame predictive problems correctly.',
                    'Understand train and test splits.',
                    'Measure model quality responsibly.',
                    'Work with features and labels clearly.',
                    'Avoid common beginner mistakes.',
                    'Apply ML concepts to real product questions.',
                ],
                'instructor' => [
                    'name' => 'Grace Kim',
                    'bio' => 'ML educator who helps learners connect statistics, software, and product thinking.',
                ],
            ],
            'fullstack-development' => [
                'category_badge' => 'Web Development',
                'category_slug' => 'web-development',
                'thumbnail' => 'https://images.unsplash.com/photo-1484417894907-623942c8ee29?auto=format&fit=crop&w=1200&q=80',
                'summary' => 'Combine frontend and backend skills into a complete product workflow.',
                'subtitle' => 'Connect client, server, and deployment thinking.',
                'about' => 'See how interface design, API work, and app structure fit together in a real fullstack workflow.',
                'learn' => [
                    'Connect frontend views to backend data.',
                    'Use cohesive folder structure across layers.',
                    'Keep state and persistence aligned.',
                    'Work with forms and validation end to end.',
                    'Plan features from UI through storage.',
                    'Deliver a complete portfolio-grade project.',
                ],
                'instructor' => [
                    'name' => 'Aiden Moore',
                    'bio' => 'Fullstack engineer and mentor focused on shipping real applications with strong fundamentals.',
                ],
            ],
        ];
    }
}
