<?php

namespace App\Enums;

enum CourseLevel: string
{
    case Beginner = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';

    public function label(): string
    {
        return match ($this) {
            self::Beginner => 'Beginner',
            self::Intermediate => 'Intermediate',
            self::Advanced => 'Advanced',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Beginner => 'bg-green-50 text-green-600 ring-green-100',
            self::Intermediate => 'bg-yellow-50 text-yellow-700 ring-yellow-100',
            self::Advanced => 'bg-red-50 text-red-600 ring-red-100',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Beginner => 'green',
            self::Intermediate => 'yellow',
            self::Advanced => 'red',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $level) => [$level->value => $level->label()])
            ->all();
    }
}
