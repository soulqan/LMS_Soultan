@props(['lesson'])

<div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-950">
    @if ($lesson->embedUrl())
        <iframe
            src="{{ $lesson->embedUrl() }}"
            title="{{ $lesson->title }}"
            class="aspect-video w-full"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
        ></iframe>
    @else
        <div class="flex aspect-video items-center justify-center p-10 text-center bg-gradient-to-br from-slate-900 to-slate-950">
            <div>
                <p class="text-lg font-semibold text-white">No video link yet</p>
                <p class="mt-2 text-sm text-slate-400">Add a YouTube unlisted or Google Drive link to this lesson.</p>
            </div>
        </div>
    @endif
</div>
