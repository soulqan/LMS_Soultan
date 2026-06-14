<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonExecutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_mark_non_quiz_lesson_complete(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'type' => 'video',
            'order' => 1,
        ]);

        $this->actingAs($student)
            ->post(route('lessons.complete', [$course, $lesson]))
            ->assertOk()
            ->assertJson([
                'completed' => true,
                'course_finished' => true,
            ]);

        $this->assertTrue($student->completedLessons()->where('lesson_id', $lesson->id)->exists());
    }

    public function test_non_enrolled_student_cannot_mark_lesson_complete(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();

        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'type' => 'video',
            'order' => 1,
        ]);

        $this->actingAs($student)
            ->post(route('lessons.complete', [$course, $lesson]))
            ->assertStatus(403);

        $this->assertFalse($student->completedLessons()->where('lesson_id', $lesson->id)->exists());
    }

    public function test_quiz_lesson_correct_answer_marks_it_complete(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'type' => 'quiz',
            'quiz_question' => 'Select 2.',
            'quiz_options' => ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
            'quiz_correct_option' => 1,
            'order' => 1,
        ]);

        $this->actingAs($student)
            ->post(route('lessons.quiz', [$course, $lesson]), [
                'answer' => 1,
            ])
            ->assertRedirect(route('course.player', [$course, $lesson]))
            ->assertSessionHas('status', 'Correct! That is the right answer.')
            ->assertSessionHas('quiz_result', 'correct');

        $this->assertTrue($student->completedLessons()->where('lesson_id', $lesson->id)->exists());
    }

    public function test_quiz_lesson_incorrect_answer_marks_it_complete(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'type' => 'quiz',
            'quiz_question' => 'Select 2.',
            'quiz_options' => ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
            'quiz_correct_option' => 1,
            'order' => 1,
        ]);

        $this->actingAs($student)
            ->post(route('lessons.quiz', [$course, $lesson]), [
                'answer' => 0,
            ])
            ->assertRedirect(route('course.player', [$course, $lesson]))
            ->assertSessionHas('status', 'Submitted! The lesson is marked as complete.')
            ->assertSessionHas('quiz_result', 'incorrect');

        $this->assertTrue($student->completedLessons()->where('lesson_id', $lesson->id)->exists());
    }

    public function test_rating_widget_only_visible_for_completed_course(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson1 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);

        $this->actingAs($student)
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertDontSee('Your Rating')
            ->assertDontSee('data-star-rating-container');

        $student->completedLessons()->attach($lesson1->id);

        $this->actingAs($student)
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertDontSee('Your Rating')
            ->assertDontSee('data-star-rating-container');

        $student->completedLessons()->attach($lesson2->id);

        $this->actingAs($student)
            ->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('Your Rating')
            ->assertSee('data-star-rating-container');
    }

    public function test_student_can_only_rate_once(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $student->completedLessons()->attach($lesson->id);

        $this->actingAs($student)
            ->post(route('courses.rate', $course), ['rating' => 5])
            ->assertRedirect(route('courses.show', $course))
            ->assertSessionHas('status', 'Thank you for rating this course!');

        $this->assertDatabaseHas('ratings', [
            'course_id' => $course->id,
            'user_id' => $student->id,
            'rating' => 5,
        ]);

        $this->actingAs($student)
            ->post(route('courses.rate', $course), ['rating' => 4])
            ->assertRedirect(route('courses.show', $course))
            ->assertSessionHasErrors(['rating']);

        $this->assertDatabaseHas('ratings', [
            'course_id' => $course->id,
            'user_id' => $student->id,
            'rating' => 5,
        ]);
        $this->assertDatabaseMissing('ratings', [
            'course_id' => $course->id,
            'user_id' => $student->id,
            'rating' => 4,
        ]);
    }

    public function test_admin_can_update_lesson_attributes(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Old Title',
            'type' => 'video',
            'order' => 1,
        ]);

        $this->actingAs($admin)
            ->patch(route('lessons.update', [$course, $lesson]), [
                'title' => 'New Title',
                'type' => 'quiz',
                'content' => 'Quiz description content',
                'quiz_question' => 'New Quiz Question?',
                'quiz_options' => ['Option A', 'Option B', 'Option C', 'Option D'],
                'quiz_correct_option' => 2,
            ])
            ->assertRedirect(route('course.player', [$course, $lesson]))
            ->assertSessionHas('status', 'Lesson updated successfully.');

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'New Title',
            'type' => 'quiz',
            'content' => 'Quiz description content',
            'quiz_question' => 'New Quiz Question?',
            'quiz_correct_option' => 2,
        ]);

        $updatedLesson = Lesson::find($lesson->id);
        $this->assertEquals(['Option A', 'Option B', 'Option C', 'Option D'], $updatedLesson->quiz_options);
    }

    public function test_student_cannot_update_lesson_attributes(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Old Title',
            'type' => 'video',
            'order' => 1,
        ]);

        $this->actingAs($student)
            ->patch(route('lessons.update', [$course, $lesson]), [
                'title' => 'New Title',
            ])
            ->assertStatus(403);

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'Old Title',
        ]);
    }

    public function test_admin_can_update_lesson_module_content(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Module Lesson',
            'type' => 'module',
            'order' => 1,
        ]);

        $moduleContentData = [
            [
                'title' => 'Intro to Design',
                'items' => [
                    [
                        'subtitle' => 'The Grid System',
                        'content' => 'Layout principles details here.',
                        'photo' => 'https://unsplash.com/photos/grid.jpg',
                    ],
                    [
                        'subtitle' => 'Typography',
                        'content' => 'Inter font is highly readable.',
                        'photo' => '',
                    ]
                ]
            ],
            [
                'title' => 'Advanced Styling',
                'items' => []
            ]
        ];

        $this->actingAs($admin)
            ->patch(route('lessons.update', [$course, $lesson]), [
                'title' => 'Updated Module Title',
                'type' => 'module',
                'content' => 'Overview text',
                'module_content' => $moduleContentData,
            ])
            ->assertRedirect(route('course.player', [$course, $lesson]))
            ->assertSessionHas('status', 'Lesson updated successfully.');

        $updatedLesson = Lesson::find($lesson->id);
        $this->assertEquals('Updated Module Title', $updatedLesson->title);
        $this->assertEquals('module', $updatedLesson->type);
        
        $this->assertCount(2, $updatedLesson->module_content);
        $this->assertEquals('Intro to Design', $updatedLesson->module_content[0]['title']);
        $this->assertCount(2, $updatedLesson->module_content[0]['items']);
        $this->assertEquals('The Grid System', $updatedLesson->module_content[0]['items'][0]['subtitle']);
        $this->assertEquals('Layout principles details here.', $updatedLesson->module_content[0]['items'][0]['content']);
        $this->assertEquals('https://unsplash.com/photos/grid.jpg', $updatedLesson->module_content[0]['items'][0]['photo']);
        $this->assertEquals('Typography', $updatedLesson->module_content[0]['items'][1]['subtitle']);
        $this->assertEquals('Inter font is highly readable.', $updatedLesson->module_content[0]['items'][1]['content']);
    }

    public function test_admin_cannot_update_lesson_module_content_with_invalid_data(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Module Lesson',
            'type' => 'module',
            'order' => 1,
        ]);

        $invalidModuleContent = [
            [
                'title' => '',
                'items' => [
                    [
                        'subtitle' => 'Sub',
                    ]
                ]
            ]
        ];

        $this->actingAs($admin)
            ->patch(route('lessons.update', [$course, $lesson]), [
                'title' => 'Updated Module Title',
                'type' => 'module',
                'module_content' => $invalidModuleContent,
            ])
            ->assertSessionHasErrors(['module_content.0.title']);
    }

    public function test_student_cannot_access_locked_lesson_redirects_to_first_uncompleted_lesson(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson1 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1, 'slug' => 'lesson-1']);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2, 'slug' => 'lesson-2']);

        // lesson1 is not completed, so lesson2 is locked.
        $this->actingAs($student)
            ->get(route('course.player', [$course, $lesson2]))
            ->assertRedirect(route('course.player', [$course, $lesson1]))
            ->assertSessionHasErrors(['sequence']);
    }

    public function test_student_cannot_complete_locked_lesson(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson1 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);

        $this->actingAs($student)
            ->post(route('lessons.complete', [$course, $lesson2]))
            ->assertStatus(400)
            ->assertJson(['error' => 'You must complete the previous lessons first.']);

        $this->assertFalse($student->completedLessons()->where('lesson_id', $lesson2->id)->exists());
    }

    public function test_student_cannot_submit_quiz_on_locked_lesson(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson1 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $lesson2 = Lesson::factory()->create([
            'course_id' => $course->id,
            'type' => 'quiz',
            'quiz_question' => 'Select 2.',
            'quiz_options' => ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
            'quiz_correct_option' => 1,
            'order' => 2,
        ]);

        $this->actingAs($student)
            ->post(route('lessons.quiz', [$course, $lesson2]), [
                'answer' => 1,
            ])
            ->assertRedirect(route('course.player', [$course, $lesson1]))
            ->assertSessionHasErrors(['sequence']);

        $this->assertFalse($student->completedLessons()->where('lesson_id', $lesson2->id)->exists());
    }

    public function test_admin_can_access_and_complete_lessons_out_of_sequence(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $course = Course::factory()->create();
        $course->students()->attach($admin->id);

        $lesson1 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);

        // Admin can view locked lesson2
        $this->actingAs($admin)
            ->get(route('course.player', [$course, $lesson2]))
            ->assertOk();

        // Admin can complete locked lesson2
        $this->actingAs($admin)
            ->post(route('lessons.complete', [$course, $lesson2]))
            ->assertOk()
            ->assertJson([
                'completed' => true,
            ]);

        $this->assertTrue($admin->completedLessons()->where('lesson_id', $lesson2->id)->exists());
    }

    public function test_lesson_navigation_buttons_visibility(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson1 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1, 'title' => 'First Lesson']);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2, 'title' => 'Second Lesson']);

        // 1. Viewing first lesson when not completed:
        // - Previous Lesson should NOT be visible.
        // - Next Lesson should NOT be visible.
        $this->actingAs($student)
            ->get(route('course.player', [$course, $lesson1]))
            ->assertOk()
            ->assertDontSee('Previous Lesson')
            ->assertDontSee('Next Lesson');

        // 2. Mark first lesson as completed.
        $student->completedLessons()->attach($lesson1->id);

        // 3. Viewing first lesson when completed:
        // - Previous Lesson should NOT be visible.
        // - Next Lesson should be visible.
        $this->actingAs($student)
            ->get(route('course.player', [$course, $lesson1]))
            ->assertOk()
            ->assertDontSee('Previous Lesson')
            ->assertSee('Next Lesson');

        // 4. Viewing second lesson when first lesson is completed but second is not:
        // - Previous Lesson should be visible.
        // - Next Lesson should NOT be visible.
        $this->actingAs($student)
            ->get(route('course.player', [$course, $lesson2]))
            ->assertOk()
            ->assertSee('Previous Lesson')
            ->assertDontSee('Next Lesson');
    }

    public function test_lesson_chapter_title_grouping_rendering(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id);

        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Main Title',
            'chapter_title' => 'My Custom Section Title',
            'order' => 1,
        ]);

        $this->actingAs($student)
            ->get(route('course.player', [$course, $lesson]))
            ->assertOk()
            ->assertSee('My Custom Section Title');
    }

    public function test_non_enrolled_student_cannot_view_lessons(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);

        // Student is not enrolled, so they should be redirected with an error
        $this->actingAs($student)
            ->get(route('course.player', [$course, $lesson]))
            ->assertRedirect(route('courses.show', $course))
            ->assertSessionHasErrors(['enrollment']);
    }
}
