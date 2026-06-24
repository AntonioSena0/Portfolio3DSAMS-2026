<x-app-layout>
    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase text-[#B20000]">Eventos e notícias</p>
            <h1 class="mt-2 text-4xl font-bold text-[#313131]">{{ $event->title }}</h1>
            <p class="mt-4 max-w-2xl text-[#5A5A5A]">{{ $event->event_date->format('d/m/Y') }} @if($event->category) — {{ $event->category }} @endif</p>
        </div>
    </section>

    <section class="py-14">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 border-l-4 border-green-600 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @auth
                <div class="mb-6 flex items-center gap-3">
                    @if ($event->isReadBy(auth()->user()))
                        <form action="{{ route('eventos.mark-as-unread', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="border border-[#B20000] px-4 py-2 text-sm font-bold text-[#B20000] transition hover:bg-[#B20000] hover:text-white">✓ Marcado como lido</button>
                        </form>
                    @else
                        <form action="{{ route('eventos.mark-as-read', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="border border-[#5A5A5A] px-4 py-2 text-sm font-bold text-[#5A5A5A] transition hover:border-[#B20000] hover:text-[#B20000]">Marcar como lido</button>
                        </form>
                    @endif
                </div>
            @endauth

            @if ($event->image_url)
                <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="mb-8 w-full object-cover">
            @endif

            @if ($event->excerpt)
                <p class="mb-6 text-lg leading-8 text-[#5A5A5A]">{{ $event->excerpt }}</p>
            @endif

            <div class="prose prose-lg max-w-none text-[#313131]">
                {{ nl2br(e($event->content)) }}
            </div>

            <div class="mt-10">
                <a href="{{ route('eventos') }}" class="text-sm font-bold uppercase text-[#B20000] hover:underline">← Voltar para eventos</a>
            </div>
        </div>
    </section>
</x-app-layout>
