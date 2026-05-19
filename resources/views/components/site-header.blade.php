@props([
    'title' => null,
    'eyebrow' => null,
    'backUrl' => null,
    'backLabel' => 'Back',
    'dark' => false,
])

@php
    $authUser = auth()->user();
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
    $accountButtonClasses = $dark
        ? 'border-slate-800 bg-slate-900 text-white hover:bg-slate-800'
        : 'border-slate-200 bg-white text-slate-900 hover:bg-slate-50';
@endphp

<header class="sticky top-0 z-50 border-b {{ $barClasses }}">
    <div class="mx-auto flex min-h-20 max-w-7xl items-center gap-4 px-4 py-4 sm:px-6 lg:px-8">
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
                    Sign In
                </a>
            @else
                <div class="relative">
                    <button
                        type="button"
                        data-profile-toggle
                        class="flex min-w-[14rem] max-w-[18rem] items-center gap-3 rounded-xl border px-3 py-2 text-left text-sm font-medium transition {{ $accountButtonClasses }}"
                    >
                        <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">
                            {{ strtoupper(mb_substr($authUser->name, 0, 1)) }}
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate font-semibold">{{ $authUser->name }}</span>
                            <span class="block truncate text-xs {{ $dark ? 'text-slate-400' : 'text-slate-500' }}">{{ $authUser->email }}</span>
                        </span>
                        <x-app-icon name="chevron-down" class="h-4 w-4 shrink-0 opacity-80" />
                        <span class="sr-only">Open profile menu</span>
                    </button>

                    <div data-profile-menu class="absolute right-0 mt-3 hidden w-56 overflow-hidden rounded-xl border {{ $menuClasses }}">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-sm transition {{ $menuItemClasses }}">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-3 text-left text-sm transition {{ $menuItemClasses }}">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
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
                    <a href="{{ route('login') }}" class="block px-4 py-3 text-sm transition {{ $menuItemClasses }}">Sign In</a>
                @else
                    <div class="border-b {{ $dark ? 'border-slate-800' : 'border-slate-200' }} px-4 py-3">
                        <p class="truncate text-sm font-semibold {{ $dark ? 'text-white' : 'text-slate-900' }}">{{ $authUser->name }}</p>
                        <p class="truncate text-xs {{ $dark ? 'text-slate-400' : 'text-slate-500' }}">{{ $authUser->email }}</p>
                    </div>
                    <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-sm transition {{ $menuItemClasses }}">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-3 text-left text-sm transition {{ $menuItemClasses }}">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</header>
