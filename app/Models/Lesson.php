<?php

namespace App\Models;

use App\Services\SlugService;
use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Lesson extends Model
{
    /** @use HasFactory<LessonFactory> */
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'video_url',
        'content',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function embedUrl(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        if (preg_match('/youtu\.be\/([A-Za-z0-9_-]+)/', $this->video_url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1];
        }

        if (preg_match('/(?:v=|\/d\/|\/file\/d\/)([A-Za-z0-9_-]+)/', $this->video_url, $matches)) {
            return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
        }

        if (preg_match('/youtube\.com\/watch\?v=([A-Za-z0-9_-]+)/', $this->video_url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1];
        }

        if (Str::contains($this->video_url, '/embed/')) {
            return $this->video_url;
        }

        return $this->video_url;
    }

    protected static function booted(): void
    {
        static::saving(function (Lesson $lesson) {
            if (! $lesson->slug) {
                $lesson->slug = app(SlugService::class)->unique($lesson->title, 'lessons', $lesson->getOriginal('slug'));
            }
        });
    }
}
