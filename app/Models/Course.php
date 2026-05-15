<?php

namespace App\Models;

use App\Enums\CourseLevel;
use App\Services\SlugService;
use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'level' => CourseLevel::class,
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function thumbnailUrl(): string
    {
        if (! $this->thumbnail) {
            return 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80';
        }

        if (Str::startsWith($this->thumbnail, ['http://', 'https://'])) {
            return $this->thumbnail;
        }

        return asset('storage/' . ltrim($this->thumbnail, '/'));
    }

    protected static function booted(): void
    {
        static::saving(function (Course $course) {
            if (! $course->slug) {
                $course->slug = app(SlugService::class)->unique($course->title, 'courses', $course->getOriginal('slug'));
            }
        });
    }
}
