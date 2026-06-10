@extends('layouts.app')

@section('content')
<section class="subject-hero bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('catalog.index') }}" class="mb-6 inline-flex text-sm font-bold text-primary-700 hover:text-primary-900">Voltar ao catálogo</a>
        <div class="grid gap-8 lg:grid-cols-[1fr_360px]">
            <div>
                <span class="inline-flex rounded-lg px-3 py-1 text-xs font-bold text-white" style="background-color: {{ $subject->category->color ?? '#4f46e5' }}">{{ $subject->category->name ?? 'Geral' }}</span>
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-secondary-900 lg:text-5xl">{{ $subject->title }}</h1>
                <p class="mt-5 max-w-3xl text-lg leading-8 text-secondary-600">{{ $subject->description }}</p>
                <div class="mt-6 flex flex-wrap gap-3 text-sm font-semibold text-secondary-700">
                    <span class="rounded-lg bg-secondary-100 px-3 py-2">{{ $subject->active_videos_count }} aulas</span>
                    <span class="rounded-lg bg-secondary-100 px-3 py-2">Professor: {{ $subject->professor->name }}</span>
                    <span class="rounded-lg bg-secondary-100 px-3 py-2">Publicado em {{ optional($subject->published_at ?? $subject->created_at)->format('d/m/Y') }}</span>
                </div>
            </div>

            <aside class="rounded-xl border border-secondary-200 bg-secondary-50 p-5 shadow-card">
                <h2 class="text-lg font-extrabold text-secondary-900">Resumo da trilha</h2>
                <div class="mt-5 space-y-4">
                    <div>
                        <div class="mb-2 flex justify-between text-sm font-semibold text-secondary-700">
                            <span>Seu progresso</span>
                            <span>{{ $enrollment->progress_percent ?? 0 }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-secondary-200">
                            <div class="h-full rounded-full bg-primary-600" style="width: {{ $enrollment->progress_percent ?? 0 }}%"></div>
                        </div>
                    </div>
                    @if($videos->isNotEmpty())
                        <a href="{{ route('videos.show', ['subjectSlug' => $subject->slug, 'videoSlug' => $videos->first()->slug]) }}" class="flex w-full items-center justify-center rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">
                            {{ $enrollment ? 'Continuar estudando' : 'Começar primeira aula' }}
                        </a>
                    @else
                        <div class="rounded-lg border border-dashed border-secondary-300 bg-white p-4 text-sm text-secondary-600">Esta matéria ainda não possui aulas ativas.</div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-end justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Aulas</p>
                <h2 class="mt-2 text-3xl font-extrabold text-secondary-900">Sequência recomendada</h2>
            </div>
        </div>

        @if($videos->isEmpty())
            <div class="rounded-xl border border-dashed border-secondary-300 bg-white p-10 text-center">
                <h3 class="text-xl font-extrabold text-secondary-900">Nenhuma aula disponível</h3>
                <p class="mt-2 text-sm text-secondary-600">Volte em breve para acompanhar novas publicações.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($videos as $video)
                    <a href="{{ route('videos.show', ['subjectSlug' => $subject->slug, 'videoSlug' => $video->slug]) }}" class="lesson-card grid gap-4 rounded-xl border border-secondary-200 bg-white p-4 shadow-card transition hover:border-primary-200 hover:shadow-card-hover sm:grid-cols-[52px_1fr_auto] sm:items-center">
                        <span class="grid h-12 w-12 place-items-center rounded-lg bg-primary-50 text-sm font-extrabold text-primary-700">{{ $video->order }}</span>
                        <div>
                            <h3 class="font-extrabold text-secondary-900">{{ $video->title }}</h3>
                            <p class="mt-1 line-clamp-2 text-sm leading-6 text-secondary-600">{{ $video->description ?: 'Aula disponível para assistir no seu ritmo.' }}</p>
                        </div>
                        <div class="flex items-center gap-3 text-sm font-semibold">
                            <span class="text-secondary-500">{{ $video->formatted_duration }}</span>
                            @auth
                                @if($video->user_progress && $video->user_progress->is_completed)
                                    <span class="rounded-lg bg-green-50 px-3 py-1 text-green-700">Concluída</span>
                                @elseif($video->user_progress)
                                    <span class="rounded-lg bg-yellow-50 px-3 py-1 text-yellow-700">Em progresso</span>
                                @endif
                            @endauth
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.subject-hero h1, .subject-hero p, .subject-hero aside, .subject-hero a, .subject-hero span', { y: 20, opacity: 0, duration: 0.55, stagger: 0.04, ease: 'power2.out' });
    gsap.from('.lesson-card', { y: 18, opacity: 0, duration: 0.45, stagger: 0.06, ease: 'power2.out', scrollTrigger: { trigger: '.lesson-card', start: 'top 85%', once: true } });
});
</script>
@endpush
