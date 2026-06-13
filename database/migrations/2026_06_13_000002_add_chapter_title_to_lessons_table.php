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
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('chapter_title')->nullable();
        });

        // Initialize chapter_title for existing lessons based on chunking by 3
        $chapterNames = ['Getting Started', 'Core Concepts', 'Advanced Topics', 'Real-World Projects'];
        $lessons = \DB::table('lessons')->orderBy('order')->get();
        foreach ($lessons as $index => $lesson) {
            $chapterIndex = min(3, (int) floor($index / 3));
            \DB::table('lessons')->where('id', $lesson->id)->update([
                'chapter_title' => $chapterNames[$chapterIndex] ?? 'Getting Started'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('chapter_title');
        });
    }
};
