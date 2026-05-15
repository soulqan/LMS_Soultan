<x-layouts.app :title="'Courses | ' . config('app.name', 'LMS')">

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <x-section-heading eyebrow="Library" title="All courses" description="Browse the complete catalog across beginner, intermediate, and advanced levels." />
            <a href="{{ route('home') }}" class="text-sm font-medium text-zinc-600 transition hover:text-zinc-950">Back to home</a>
        </div>

        <div class="mt-10 flex flex-wrap gap-2">
            @foreach ($categories as $category)
                <span class="rounded-full border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-600">{{ $category->name }} · {{ $category->courses_count }}</span>
            @endforeach
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($courses as $course)
                <x-course-card :course="$course" />
            @empty
                <x-empty-state title="No courses found" description="Add a course in the admin panel to get started." />
            @endforelse
        </div>

        <div class="mt-10">
            {{ $courses->links() }}
        </div>
    </section>
</x-layouts.app>
