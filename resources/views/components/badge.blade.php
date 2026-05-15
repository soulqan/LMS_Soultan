@props(['tone' => 'neutral'])

@php
    $classes = [
        'neutral' => 'bg-slate-100 text-slate-700 ring-slate-200',
        'blue' => 'bg-blue-50 text-blue-600 ring-blue-100',
        'green' => 'bg-green-50 text-green-600 ring-green-100',
        'yellow' => 'bg-yellow-50 text-yellow-700 ring-yellow-100',
        'red' => 'bg-red-50 text-red-600 ring-red-100',
        'dark' => 'bg-slate-950 text-white ring-slate-950',
    ][$tone] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset ' . $classes]) }}>
    {{ $slot }}
</span>
