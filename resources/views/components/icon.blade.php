@props([
    'name',
    'class' => 'h-5 w-5',
])

@php
    $svgClasses = 'shrink-0 ' . $class;
@endphp

@switch($name)
    @case('book-open')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 7v14" />
            <path d="M3 6.5A2.5 2.5 0 0 1 5.5 4H20v15.5A2.5 2.5 0 0 0 17.5 17H5.5A2.5 2.5 0 0 1 3 14.5v-8Z" />
            <path d="M12 7H6.5A2.5 2.5 0 0 0 4 9.5" />
        </svg>
        @break
    @case('search')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="11" cy="11" r="7" />
            <path d="m20 20-3.5-3.5" />
        </svg>
        @break
    @case('chevron-down')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m6 9 6 6 6-6" />
        </svg>
        @break
    @case('chevron-left')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m15 18-6-6 6-6" />
        </svg>
        @break
    @case('menu')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 7h16" />
            <path d="M4 12h16" />
            <path d="M4 17h16" />
        </svg>
        @break
    @case('play')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M8 5v14l11-7-11-7Z" />
        </svg>
        @break
    @case('check-circle')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="9" />
            <path d="m9 12 2 2 4-4" />
        </svg>
        @break
    @case('circle')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="9" />
        </svg>
        @break
    @case('star')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m12 3 2.9 6 6.6.9-4.8 4.7 1.1 6.6-5.8-3.1-5.8 3.1 1.1-6.6L2.5 9.9 9.1 9z" />
        </svg>
        @break
    @case('check')
        <svg {{ $attributes->merge(['class' => $svgClasses]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m20 6-11 11-5-5" />
        </svg>
        @break
@endswitch
