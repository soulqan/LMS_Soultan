<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->input('course_id') ?? $this->route('course')?->id;
        $lessonId = $this->route('lesson')?->id;

        return [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('lessons', 'slug')
                    ->where(fn ($query) => $query->where('course_id', $courseId))
                    ->ignore($lessonId),
            ],
            'video_url' => ['nullable', 'url', 'max:2048'],
            'content' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:1'],
        ];
    }
}
