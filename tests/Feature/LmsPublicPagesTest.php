<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsPublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_renders_the_home_page(): void
    {
        $category = Category::factory()->create();
        Course::factory()->for($category)->create();

        $this->get('/')
            ->assertOk()
            ->assertSee($category->name);
    }

    public function test_it_renders_a_course_page_and_lesson_page(): void
    {
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->for($course)->create([
            'order' => 1,
        ]);

        $this->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee($course->title);

        $this->get(route('lessons.show', [$course, $lesson]))
            ->assertOk()
            ->assertSee($lesson->title);
    }
}
