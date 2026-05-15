@props(['value' => 0])

<div class="space-y-2">
    <div class="flex items-center justify-between text-xs font-medium text-slate-600">
        <span>Progress</span>
        <span data-progress-label>{{ $value }}%</span>
    </div>
    <div class="h-2 overflow-hidden rounded-full bg-slate-200">
        <div class="h-full rounded-full bg-blue-600 transition-all duration-300" data-progress-fill style="width: {{ $value }}%"></div>
    </div>
</div>
