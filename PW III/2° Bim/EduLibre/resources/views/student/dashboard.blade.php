@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="student-hero flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Painel do aluno</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Bem-vindo, {{ auth()->user()->name }}</h1>
                <p class="mt-3 max-w-2xl text-secondary-600">Continue seus estudos, acompanhe progresso e encontre novas matérias sem sair do fluxo.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex w-fit items-center justify-center rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Explorar matérias</a>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="stat-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Em andamento</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900" data-count="{{ $stats['in_progress'] ?? 0 }}">0</p>
            </div>
            <div class="stat-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Concluídas</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900" data-count="{{ $stats['completed'] ?? 0 }}">0</p>
            </div>
            <div class="stat-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Vídeos assistidos</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900" data-count="{{ $stats['videos_watched'] ?? 0 }}">0</p>
            </div>
            <div class="stat-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Horas de estudo</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900" data-count="{{ floor($stats['study_hours'] ?? 0) }}">0</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.1fr_.9fr]">
            <section class="dashboard-panel rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-extrabold text-secondary-900">Continuar assistindo</h2>
                    <a href="{{ route('student.continue') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Ver histórico</a>
                </div>

                @if($continueWatching->isEmpty())
                    <div class="rounded-lg border border-dashed border-secondary-300 bg-secondary-50 p-8 text-center">
                        <h3 class="font-extrabold text-secondary-900">Nada em andamento ainda</h3>
                        <p class="mt-2 text-sm text-secondary-600">Abra uma aula no catálogo para o progresso aparecer aqui.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($continueWatching as $progress)
                            @php
                                $percent = $progress->video && $progress->video->duration ? min(100, (int) (($progress->watched_seconds / $progress->video->duration) * 100)) : 0;
                            @endphp
                            @continue(!$progress->video || !$progress->video->subject)
                            <a href="{{ route('videos.show', ['subjectSlug' => $progress->video->subject->slug, 'videoSlug' => $progress->video->slug]) }}" class="study-row block rounded-lg border border-secondary-200 bg-secondary-50 p-4 transition hover:border-primary-200 hover:bg-white">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="font-bold text-secondary-900">{{ $progress->video->title }}</h3>
                                        <p class="mt-1 text-sm text-secondary-600">{{ $progress->video->subject->title }}</p>
                                    </div>
                                    <span class="text-sm font-bold text-primary-700">{{ $percent }}%</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-secondary-200">
                                    <div class="h-full rounded-full bg-primary-600" style="width: {{ $percent }}%"></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="dashboard-panel rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-extrabold text-secondary-900">Minhas matérias</h2>
                    <a href="{{ route('student.subjects') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Ver todas</a>
                </div>

                @if($enrolledSubjects->isEmpty())
                    <div class="rounded-lg border border-dashed border-secondary-300 bg-secondary-50 p-8 text-center">
                        <h3 class="font-extrabold text-secondary-900">Você ainda não começou uma matéria</h3>
                        <p class="mt-2 text-sm text-secondary-600">Escolha uma trilha e ela aparecerá aqui.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($enrolledSubjects as $enrollment)
                            @continue(!$enrollment->subject)
                            <a href="{{ route('subjects.show', $enrollment->subject->slug) }}" class="study-row flex items-center justify-between gap-4 rounded-lg border border-secondary-200 bg-secondary-50 p-4 transition hover:border-primary-200 hover:bg-white">
                                <div>
                                    <h3 class="font-bold text-secondary-900">{{ $enrollment->subject->title }}</h3>
                                    <p class="mt-1 text-sm text-secondary-600">Por {{ $enrollment->subject->professor->name ?? 'Professor' }}</p>
                                </div>
                                <span class="rounded-lg bg-primary-50 px-3 py-1 text-sm font-bold text-primary-700">{{ $enrollment->progress_percent }}%</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.student-hero > *', { y: 18, opacity: 0, duration: 0.55, stagger: 0.06, ease: 'power2.out' });
    gsap.from('.stat-card', { y: 18, opacity: 0, duration: 0.45, stagger: 0.05, ease: 'power2.out', delay: 0.12 });
    gsap.from('.dashboard-panel', { y: 22, opacity: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out', delay: 0.22 });
    document.querySelectorAll('[data-count]').forEach((el) => {
        gsap.to(el, {
            innerText: Number(el.dataset.count),
            duration: 1.1,
            snap: { innerText: 1 },
            ease: 'power2.out',
            onUpdate() { el.textContent = Number(el.innerText).toLocaleString('pt-BR'); }
        });
    });
});
</script>
@endpush
