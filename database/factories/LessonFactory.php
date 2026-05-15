<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(4),
            'slug' => fake()->unique()->slug(),
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'content' => fake()->paragraphs(2, true),
            'order' => fake()->numberBetween(1, 12),
        ];
    }
}
