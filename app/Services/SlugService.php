<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    public function unique(string $value, string $table, ?string $ignoreSlug = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $suffix = 2;

        while ($this->exists($table, $slug, $ignoreSlug)) {
            $slug = sprintf('%s-%d', $base, $suffix);
            $suffix++;
        }

        return $slug;
    }

    private function exists(string $table, string $slug, ?string $ignoreSlug = null): bool
    {
        return \DB::table($table)
            ->where('slug', $slug)
            ->when($ignoreSlug, fn ($query) => $query->where('slug', '!=', $ignoreSlug))
            ->exists();
    }
}
