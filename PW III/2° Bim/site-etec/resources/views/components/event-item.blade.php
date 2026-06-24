@props(['event'])

<article class="grid gap-5 border-b border-[#DDD] bg-white py-6 md:grid-cols-[10rem_1fr]">
    <div class="border-l-4 border-[#B20000] pl-4">
        <p class="text-xs font-bold uppercase text-[#5A5A5A]">{{ $event->category }}</p>
        <time datetime="{{ $event->event_date->format('Y-m-d') }}" class="mt-2 block text-2xl font-bold text-[#313131]">{{ $event->event_date->format('d/m') }}</time>
        <p class="text-sm text-[#5A5A5A]">{{ $event->event_date->format('Y') }}</p>
    </div>
    <div>
        <a href="{{ route('eventos.show', $event) }}" class="hover:underline">
            <h3 class="text-2xl font-bold text-[#B20000]">{{ $event->title }}</h3>
        </a>
        <p class="mt-3 leading-7 text-[#313131]">{{ $event->excerpt ?? Str::limit($event->content, 150) }}</p>
        @auth
            @if ($event->isReadBy(auth()->user()))
                <p class="mt-2 text-xs font-bold uppercase text-green-600">✓ Lido</p>
            @endif
        @endauth
    </div>
</article>
