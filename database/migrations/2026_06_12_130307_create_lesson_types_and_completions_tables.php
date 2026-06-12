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
            $table->string('type')->default('video');
            $table->text('quiz_question')->nullable();
            $table->json('quiz_options')->nullable();
            $table->integer('quiz_correct_option')->nullable();
        });

        Schema::create('lesson_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_user');
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['type', 'quiz_question', 'quiz_options', 'quiz_correct_option']);
        });
    }
};
