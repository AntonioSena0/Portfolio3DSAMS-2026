@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="video-hero mb-8">
            <a href="{{ route('subjects.show', $subject->slug) }}" class="mb-4 inline-flex text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para {{ $subject->title }}</a>
            <h1 class="text-4xl font-extrabold tracking-tight text-secondary-900">{{ $video->title }}</h1>
            <p class="mt-3 text-sm font-semibold text-secondary-600">Por {{ $video->professor->name }} · {{ $video->formatted_duration }} · {{ $video->views_count }} visualizações</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
            <div class="player-card overflow-hidden rounded-xl border border-secondary-200 bg-secondary-950 shadow-card">
                <div class="relative aspect-video">
                    <iframe src="{{ $video->url }}" title="{{ $video->title }}" class="absolute inset-0 h-full w-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>

            <aside class="player-card rounded-xl border border-secondary-200 bg-secondary-50 p-5 shadow-card">
                <h2 class="text-lg font-extrabold text-secondary-900">Progresso da aula</h2>
                @php
                    $percent = $userProgress && $video->duration ? min(100, (int) (($userProgress->watched_seconds / $video->duration) * 100)) : 0;
                @endphp
                <div class="mt-4">
                    <div class="mb-2 flex justify-between text-sm font-semibold text-secondary-700">
                        <span>Assistido</span>
                        <span>{{ $userProgress?->is_completed ? 'Concluído' : $percent . '%' }}</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-secondary-200">
                        <div class="h-full rounded-full bg-primary-600" style="width: {{ $userProgress?->is_completed ? 100 : $percent }}%"></div>
                    </div>
                </div>

                <div class="mt-5 grid gap-3">
                    @if($prevVideo)
                        <a href="{{ route('videos.show', ['subjectSlug' => $subject->slug, 'videoSlug' => $prevVideo->slug]) }}" class="rounded-lg border border-secondary-200 bg-white px-4 py-3 text-sm font-bold text-secondary-800 hover:border-primary-200">Aula anterior</a>
                    @endif
                    @if($nextVideo)
                        <a href="{{ route('videos.show', ['subjectSlug' => $subject->slug, 'videoSlug' => $nextVideo->slug]) }}" class="rounded-lg bg-primary-600 px-4 py-3 text-center text-sm font-bold text-white hover:bg-primary-700">Próxima aula</a>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_360px] lg:px-8">
        <article class="content-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
            <h2 class="text-xl font-extrabold text-secondary-900">Sobre esta aula</h2>
            <p class="mt-3 leading-7 text-secondary-600">{{ $video->description ?: 'Aula disponível para estudo gratuito na EduLibre.' }}</p>
        </article>

        <aside class="content-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
            <h2 class="text-xl font-extrabold text-secondary-900">Avaliação</h2>
            <p class="mt-2 text-sm text-secondary-600">Média atual: <strong>{{ number_format($averageRating ?? 0, 1, ',', '.') }}</strong> de 5</p>
            @auth
                @if($userRating)
                    <p class="mt-3 rounded-lg bg-primary-50 px-3 py-2 text-sm font-bold text-primary-700">Sua nota: {{ $userRating->score }}</p>
                @else
                    <form method="POST" action="{{ route('student.ratings.store', $video) }}" class="mt-4 flex gap-2">
                        @csrf
                        <select name="score" class="h-10 flex-1 rounded-lg border border-secondary-200 px-3 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} estrela{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                        <button class="rounded-lg bg-primary-600 px-4 text-sm font-bold text-white">Enviar</button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="mt-4 inline-flex rounded-lg border border-secondary-200 px-4 py-2 text-sm font-bold text-secondary-800 hover:border-primary-200">Entrar para avaliar</a>
            @endauth
        </aside>

        <section class="content-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card lg:col-span-2">
            <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <h2 class="text-xl font-extrabold text-secondary-900">Comentários</h2>
                @auth
                    <form method="POST" action="{{ route('student.comments.store', $video) }}" class="flex flex-1 gap-2 sm:max-w-xl">
                        @csrf
                        <input name="content" maxlength="1000" required placeholder="Escreva um comentário" class="h-11 flex-1 rounded-lg border border-secondary-200 px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                        <button class="rounded-lg bg-primary-600 px-4 text-sm font-bold text-white">Enviar</button>
                    </form>
                @endauth
            </div>

            @if($comments->isEmpty())
                <div class="rounded-lg border border-dashed border-secondary-300 bg-secondary-50 p-6 text-center text-sm text-secondary-600">Nenhum comentário ainda.</div>
            @else
                <div class="space-y-3">
                    @foreach($comments as $comment)
                        <article class="comment-card rounded-lg bg-secondary-50 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary-600 text-xs font-bold text-white">
                                        {{ collect(explode(' ', $comment->user->name))->map(fn($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                                    </span>
                                    <strong class="text-sm text-secondary-900">{{ $comment->user->name }}</strong>
                                </div>
                                <span class="text-xs text-secondary-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-secondary-700">{{ $comment->content }}</p>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.video-hero > *', { y: 18, opacity: 0, duration: 0.55, stagger: 0.06, ease: 'power2.out' });
    gsap.from('.player-card', { y: 22, opacity: 0, duration: 0.55, stagger: 0.08, ease: 'power2.out', delay: 0.12 });
    gsap.from('.content-card', { y: 20, opacity: 0, duration: 0.5, stagger: 0.07, ease: 'power2.out', scrollTrigger: { trigger: '.content-card', start: 'top 85%', once: true } });
});
</script>
@endpush
