<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('subtitle')->nullable();
            $table->decimal('rating', 3, 2)->default(4.8);
            $table->unsignedInteger('enrolled_count')->default(12543);
            $table->string('duration')->default('42 hours');
            $table->decimal('price', 8, 2)->default(79.99);
            $table->text('about')->nullable();
            $table->json('what_you_will_learn')->nullable();
            $table->string('instructor_name')->default('Ava Reynolds');
            $table->text('instructor_bio')->nullable();
        });

        // Populate existing courses using the LearningHubContent mock data
        $contentService = new \App\Services\LearningHubContent();
        $courses = \DB::table('courses')->get();
        foreach ($courses as $course) {
            $courseModel = new \App\Models\Course((array) $course);
            $courseModel->id = $course->id;
            try {
                $meta = $contentService->landingMeta($courseModel);
                \DB::table('courses')->where('id', $course->id)->update([
                    'subtitle' => $meta['subtitle'] ?? null,
                    'rating' => $meta['rating'] ?? 4.8,
                    'enrolled_count' => intval(str_replace(',', '', $meta['students'] ?? '12543')),
                    'duration' => $meta['duration'] ?? '42 hours',
                    'price' => $meta['price'] ?? 79.99,
                    'about' => $meta['about'] ?? $course->description,
                    'what_you_will_learn' => json_encode($meta['learn'] ?? []),
                    'instructor_name' => $meta['instructor']['name'] ?? 'Ava Reynolds',
                    'instructor_bio' => $meta['instructor']['bio'] ?? null,
                ]);
            } catch (\Exception $e) {
                // If anything fails, fallback to defaults
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'subtitle',
                'rating',
                'enrolled_count',
                'duration',
                'price',
                'about',
                'what_you_will_learn',
                'instructor_name',
                'instructor_bio',
            ]);
        });
    }
};
