@props([
    'title' => null,
    'eyebrow' => null,
    'backUrl' => null,
    'backLabel' => 'Back',
    'dark' => false,
])

@php
    $canAccessAdmin = auth()->check();

    if ($canAccessAdmin && class_exists(\Filament\Facades\Filament::class)) {
        try {
            $adminPanel = \Filament\Facades\Filament::getPanel('admin');
            $canAccessAdmin = auth()->user()->canAccessPanel($adminPanel);
        } catch (\Throwable $e) {
            $canAccessAdmin = false;
        }
    }

    $barClasses = $dark
        ? 'border-slate-800 bg-slate-950/95 text-white'
        : 'border-slate-200 bg-white/95 text-slate-900';

    $linkClasses = $dark
        ? 'text-slate-300 hover:text-white'
        : 'text-slate-600 hover:text-slate-900';

    $mutedClasses = $dark ? 'text-slate-400' : 'text-slate-500';
    $menuClasses = $dark
        ? 'border-slate-800 bg-slate-900 shadow-lg'
        : 'border-slate-200 bg-white shadow-lg';
    $menuItemClasses = $dark
        ? 'text-slate-300 hover:bg-slate-800 hover:text-white'
        : 'text-slate-700 hover:bg-slate-50';
@endphp

<header class="sticky top-0 z-50 border-b {{ $barClasses }}">
    <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-4 sm:px-6 lg:px-8">
        @if ($backUrl)
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 {{ $linkClasses }} transition">
                <x-app-icon name="chevron-left" class="h-5 w-5" />
                <span class="hidden sm:inline">{{ $backLabel }}</span>
            </a>

            <div class="min-w-0 flex-1">
                @if ($eyebrow)
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] {{ $mutedClasses }}">{{ $eyebrow }}</p>
                @endif
                <h1 class="truncate text-lg font-semibold {{ $dark ? 'text-white' : 'text-slate-900' }} sm:text-xl">
                    {{ $title }}
                </h1>
            </div>
        @else
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold {{ $dark ? 'text-white' : 'text-slate-900' }}">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white">
                    <x-app-icon name="book-open" class="h-5 w-5" />
                </span>
                <span class="text-lg">LearningHub</span>
            </a>
        @endif

        <div class="min-w-0 flex-1">
            @if (! $backUrl && isset($search))
                {{ $search }}
            @endif
        </div>

        <nav class="hidden items-center gap-6 text-sm font-medium md:flex">
            <a href="{{ route('home') }}" class="transition {{ $linkClasses }}">Home</a>
            <a href="{{ route('courses.index') }}" class="transition {{ $linkClasses }}">Courses</a>
            @if ($canAccessAdmin)
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="transition {{ $linkClasses }}">Admin</a>
            @endif
        </nav>

        <div class="hidden items-center gap-3 sm:flex">
            @guest
                <a href="{{ route('login') }}" class="rounded-xl border px-4 py-2 text-sm font-semibold transition {{ $dark ? 'border-slate-700 text-slate-200 hover:bg-slate-900' : 'border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}">
                    Login
                </a>
                <a href="{{ route('student.register') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Sign Up
                </a>
            @else
                <a href="{{ route('courses.index') }}" class="rounded-xl border px-4 py-2 text-sm font-semibold transition {{ $dark ? 'border-slate-700 text-slate-200 hover:bg-slate-900' : 'border-slate-200 text-slate-700 hover:border-slate-50 hover:bg-slate-50' }}">
                    My Courses
                </a>
            @endguest
        </div>

        <div class="relative md:hidden">
            <button type="button" data-mobile-menu-toggle class="inline-flex h-10 w-10 items-center justify-center rounded-xl border transition {{ $dark ? 'border-slate-800 text-slate-300 hover:bg-slate-900' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                <x-app-icon name="menu" class="h-5 w-5" />
            </button>

            <div data-mobile-menu class="absolute right-0 mt-3 hidden w-56 overflow-hidden rounded-xl border {{ $menuClasses }}">
                <a href="{{ route('home') }}" class="block px-4 py-3 text-sm transition {{ $menuItemClasses }}">Home</a>
                <a href="{{ route('courses.index') }}" class="block px-4 py-3 text-sm transition {{ $menuItemClasses }}">Courses</a>
                @if ($canAccessAdmin)
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="block px-4 py-3 text-sm transition {{ $menuItemClasses }}">Admin</a>
                @endif
                @guest
                    <a href="{{ route('login') }}" class="block px-4 py-3 text-sm transition {{ $menuItemClasses }}">Login</a>
                    <a href="{{ route('student.register') }}" class="block px-4 py-3 text-sm transition {{ $menuItemClasses }}">Sign Up</a>
                @else
                    <a href="{{ route('courses.index') }}" class="block px-4 py-3 text-sm transition {{ $menuItemClasses }}">My Courses</a>
                @endguest
            </div>
        </div>
    </div>

    @if (! $backUrl && isset($search))
        <div class="border-t {{ $dark ? 'border-slate-800' : 'border-slate-200' }} px-4 py-4 md:hidden">
            {{ $search }}
        </div>
    @endif
</header>
