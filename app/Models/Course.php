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
        'is_available',
        'subtitle',
        'rating',
        'enrolled_count',
        'duration',
        'price',
        'about',
        'what_you_will_learn',
        'instructor_name',
        'instructor_bio',
        'chapters',
    ];

    protected function casts(): array
    {
        return [
            'level' => CourseLevel::class,
            'is_available' => 'boolean',
            'what_you_will_learn' => 'array',
            'rating' => 'float',
            'price' => 'float',
            'chapters' => 'array',
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

    public function students()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function formattedDuration(): string
    {
        $minutes = $this->lessons->sum('duration_minutes') ?: 0;
        if ($minutes === 0) {
            return '0m';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($hours > 0) {
            return $remainingMinutes > 0 ? "{$hours}h {$remainingMinutes}m" : "{$hours}h";
        }
        
        return "{$remainingMinutes}m";
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

    public function isFinishedBy(User $user): bool
    {
        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) {
            return false;
        }

        $lessonIds = $this->lessons()->pluck('id');
        $completedCount = $user->completedLessons()->whereIn('lesson_id', $lessonIds)->count();

        return $completedCount === $totalLessons;
    }
}
