<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseEditingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_course(): void
    {
        $course = Course::factory()->create();

        $this->patch(route('courses.update', $course), [
            'title' => 'Updated Title',
        ])
            ->assertRedirect(route('login'));
    }

    public function test_student_cannot_update_course(): void
    {
        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);
        $course = Course::factory()->create([
            'title' => 'Original Title',
        ]);

        $this->actingAs($student)
            ->patch(route('courses.update', $course), [
                'title' => 'Updated Title',
                'price' => 49.99,
                'instructor_name' => 'New Instructor',
            ])
            ->assertStatus(403);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Original Title',
        ]);
    }

    public function test_admin_can_update_course_details(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $course = Course::factory()->create([
            'title' => 'Original Title',
            'subtitle' => 'Original Subtitle',
            'price' => 19.99,
            'about' => 'Original about text',
            'what_you_will_learn' => ['Original point 1'],
            'instructor_name' => 'Original Instructor',
            'instructor_bio' => 'Original Instructor Bio',
        ]);

        $this->actingAs($admin)
            ->patch(route('courses.update', $course), [
                'title' => 'Updated Title',
                'subtitle' => 'Updated Subtitle',
                'price' => 29.99,
                'about' => 'Updated about text',
                'what_you_will_learn' => ['Updated point 1', 'Updated point 2', ''],
                'instructor_name' => 'Updated Instructor',
                'instructor_bio' => 'Updated Instructor Bio',
            ])
            ->assertRedirect(route('courses.show', $course))
            ->assertSessionHas('status', 'Course updated successfully.');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Updated Title',
            'subtitle' => 'Updated Subtitle',
            'price' => 29.99,
            'about' => 'Updated about text',
            'instructor_name' => 'Updated Instructor',
            'instructor_bio' => 'Updated Instructor Bio',
        ]);

        $updatedCourse = Course::find($course->id);
        $this->assertEquals(['Updated point 1', 'Updated point 2'], $updatedCourse->what_you_will_learn);
    }

    public function test_update_requires_valid_data(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $course = Course::factory()->create();

        $this->actingAs($admin)
            ->patch(route('courses.update', $course), [
                'title' => '', // Required
                'price' => 'free', // Numeric required
            ])
            ->assertSessionHasErrors(['title', 'price']);
    }

    public function test_student_can_enroll_in_course(): void
    {
        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);

        $this->actingAs($student)
            ->post(route('courses.enroll', $course))
            ->assertRedirect(route('course.player', [$course, $lesson]))
            ->assertSessionHas('status', 'You have successfully enrolled in this course!');

        $this->assertTrue($course->students()->where('user_id', $student->id)->exists());
    }

    public function test_student_can_submit_rating(): void
    {
        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);
        $course = Course::factory()->create();
        $course->students()->attach($student->id); // Enrolled

        $lesson = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
        $student->completedLessons()->attach($lesson->id);

        $this->actingAs($student)
            ->post(route('courses.rate', $course), [
                'rating' => 5,
            ])
            ->assertRedirect(route('courses.show', $course))
            ->assertSessionHas('status', 'Thank you for rating this course!');

        $this->assertDatabaseHas('ratings', [
            'course_id' => $course->id,
            'user_id' => $student->id,
            'rating' => 5,
        ]);
    }

    public function test_non_enrolled_student_cannot_submit_rating(): void
    {
        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);
        $course = Course::factory()->create();

        $this->actingAs($student)
            ->post(route('courses.rate', $course), [
                'rating' => 5,
            ])
            ->assertRedirect(route('courses.show', $course))
            ->assertSessionHasErrors(['rating']);

        $this->assertDatabaseMissing('ratings', [
            'course_id' => $course->id,
            'user_id' => $student->id,
        ]);
    }

    public function test_rating_and_enrollment_counts_are_dynamic_on_show_page(): void
    {
        $student1 = User::factory()->create();
        $student2 = User::factory()->create();

        $course = Course::factory()->create();
        
        // No ratings yet
        $this->get(route('courses.show', $course))
            ->assertSee('Rating not available')
            ->assertSee('0'); // 0 students enrolled

        // Enroll students
        $course->students()->attach([$student1->id, $student2->id]);

        // Submit ratings
        $course->ratings()->create(['user_id' => $student1->id, 'rating' => 5]);
        $course->ratings()->create(['user_id' => $student2->id, 'rating' => 4]);

        $this->get(route('courses.show', $course))
            ->assertSee('4.5') // (5+4)/2
            ->assertSee('2'); // 2 students enrolled
    }

    public function test_duration_formatting_and_summation(): void
    {
        $course = Course::factory()->create();
        
        Lesson::factory()->create(['course_id' => $course->id, 'duration_minutes' => 45, 'order' => 1]);
        Lesson::factory()->create(['course_id' => $course->id, 'duration_minutes' => 35, 'order' => 2]); // Total = 80m = 1h 20m

        $this->get(route('courses.show', $course))
            ->assertSee('1h 20m');
    }

    public function test_non_admin_cannot_view_unavailable_course(): void
    {
        $course = Course::factory()->create(['is_available' => false]);
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);

        // Guest cannot view
        $this->get(route('courses.show', $course))
            ->assertStatus(404);

        // Student cannot view
        $this->actingAs($student)
            ->get(route('courses.show', $course))
            ->assertStatus(404);

        // Admin can view
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin)
            ->get(route('courses.show', $course))
            ->assertStatus(200);
    }
}
