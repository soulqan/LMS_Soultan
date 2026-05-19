<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsPublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_renders_the_home_page(): void
    {
        $category = Category::factory()->create();
        $visibleCourse = Course::factory()->for($category)->create([
            'title' => 'Visible Course',
            'is_available' => true,
        ]);

        $hiddenCourse = Course::factory()->for($category)->create([
            'title' => 'Hidden Course',
            'is_available' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Learn without distraction')
            ->assertSee($visibleCourse->title)
            ->assertDontSee($hiddenCourse->title);
    }

    public function test_it_renders_the_course_catalog(): void
    {
        $category = Category::factory()->create();
        $course = Course::factory()->for($category)->create([
            'title' => 'Catalog Course',
            'is_available' => true,
        ]);

        $this->get(route('courses.index'))
            ->assertOk()
            ->assertSee('Upgrade Your Skills')
            ->assertSee('Catalog Course');
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
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('lessons.show', [$course, $lesson]))
            ->assertOk()
            ->assertSee($lesson->title);
    }
}
