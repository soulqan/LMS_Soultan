<?php

namespace Database\Factories;

use App\Enums\CourseLevel;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraphs(3, true),
            'thumbnail' => null,
            'level' => fake()->randomElement(CourseLevel::cases()),
        ];
    }
}
