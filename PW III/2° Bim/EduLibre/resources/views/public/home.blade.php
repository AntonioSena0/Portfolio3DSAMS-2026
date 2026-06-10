@extends('layouts.app')

@section('content')
<section class="overflow-hidden bg-white">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[1.05fr_.95fr] lg:px-8 lg:py-20">
        <div class="hero-copy flex flex-col justify-center">
            <div class="mb-5 inline-flex w-fit items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-primary-700">
                Plataforma gratuita
            </div>
            <h1 class="max-w-3xl text-4xl font-extrabold leading-tight tracking-tight text-secondary-900 sm:text-5xl lg:text-6xl">
                Aprenda com clareza, sem pagar para continuar.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-secondary-600">
                A EduLibre organiza matérias, aulas e progresso em uma experiência direta para quem estuda no celular, no intervalo ou depois do trabalho.
            </p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-primary-700">
                    Explorar catálogo
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg border border-secondary-200 bg-white px-5 py-3 text-sm font-bold text-secondary-800 transition hover:border-primary-300 hover:text-primary-700">
                    Começar grátis
                </a>
            </div>
        </div>

        <div class="hero-panel relative">
            <div class="rounded-xl border border-secondary-200 bg-secondary-50 p-4 shadow-card">
            <div class="mb-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Matéria em destaque</p>
                        <h2 class="mt-1 text-xl font-extrabold text-secondary-900">{{ $subjects->first()->title ?? 'Catálogo em construção' }}</h2>
                    </div>
                    <span class="rounded-lg bg-success px-3 py-1 text-xs font-bold text-white">{{ $subjects->first()->videos_count ?? 0 }} aulas</span>
                </div>
                <div class="space-y-3">
                    @foreach($subjects->take(4) as $index => $lesson)
                        <div class="lesson-row flex items-center gap-3 rounded-lg bg-white p-3">
                            <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary-600 text-sm font-bold text-white">{{ $index + 1 }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-bold text-secondary-900">{{ $lesson->title }}</p>
                                <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-secondary-100">
                                    <div class="h-full rounded-full bg-primary-600" style="width: {{ min(100, max(12, ($lesson->videos_count ?? 0) * 20)) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if($subjects->isEmpty())
                        <div class="lesson-row rounded-lg bg-white p-4 text-sm text-secondary-600">Publique e aprove matérias para preencher esta área automaticamente.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-secondary-200 bg-secondary-50">
    <div class="mx-auto grid max-w-7xl gap-4 px-4 py-8 sm:grid-cols-3 sm:px-6 lg:px-8">
        <div class="stat-card rounded-lg bg-white p-5 shadow-card">
            <p class="text-3xl font-extrabold text-secondary-900" data-count="{{ $stats['students'] }}">0</p>
            <p class="mt-1 text-sm text-secondary-600">Alunos cadastrados</p>
        </div>
        <div class="stat-card rounded-lg bg-white p-5 shadow-card">
            <p class="text-3xl font-extrabold text-secondary-900" data-count="{{ $stats['subjects'] }}">0</p>
            <p class="mt-1 text-sm text-secondary-600">Matérias publicadas</p>
        </div>
        <div class="stat-card rounded-lg bg-white p-5 shadow-card">
            <p class="text-3xl font-extrabold text-secondary-900" data-count="{{ $stats['videos'] }}">0</p>
            <p class="mt-1 text-sm text-secondary-600">Aulas ativas</p>
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Catálogo</p>
                <h2 class="mt-2 text-3xl font-extrabold text-secondary-900">Matérias para começar hoje</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Ver todas</a>
        </div>

        @if($subjects->isEmpty())
            <div class="rounded-xl border border-dashed border-secondary-300 bg-secondary-50 p-8 text-center">
                <h3 class="text-lg font-bold text-secondary-900">Nenhuma matéria publicada ainda</h3>
                <p class="mt-2 text-sm text-secondary-600">Assim que o admin aprovar conteúdos, eles aparecem aqui automaticamente.</p>
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($subjects as $subject)
                    <a href="{{ route('subjects.show', $subject->slug) }}" class="subject-card group rounded-xl border border-secondary-200 bg-white p-5 shadow-card transition hover:-translate-y-1 hover:border-primary-200 hover:shadow-card-hover">
                        <div class="mb-4 flex items-start justify-between gap-4">
                            <span class="rounded-lg px-3 py-1 text-xs font-bold text-white" style="background-color: {{ $subject->category->color ?? '#4f46e5' }}">{{ $subject->category->name ?? 'Geral' }}</span>
                            <span class="text-xs font-semibold text-secondary-500">{{ $subject->videos_count }} aulas</span>
                        </div>
                        <h3 class="text-lg font-extrabold text-secondary-900 group-hover:text-primary-700">{{ $subject->title }}</h3>
                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-secondary-600">{{ $subject->description }}</p>
                        <div class="mt-5 flex items-center justify-between border-t border-secondary-100 pt-4">
                            <span class="text-sm font-semibold text-secondary-700">{{ $subject->professor->name ?? 'Professor' }}</span>
                            <span class="text-sm font-bold text-primary-700">Abrir</span>
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
    gsap.from('.hero-copy > *', { y: 24, opacity: 0, duration: 0.7, stagger: 0.08, ease: 'power3.out' });
    gsap.from('.hero-panel', { x: 32, opacity: 0, duration: 0.8, ease: 'power3.out', delay: 0.15 });
    gsap.from('.lesson-row', { x: 18, opacity: 0, duration: 0.45, stagger: 0.08, ease: 'power2.out', delay: 0.35 });
    document.querySelectorAll('[data-count]').forEach((el) => {
        gsap.to(el, {
            innerText: Number(el.dataset.count),
            duration: 1.4,
            snap: { innerText: 1 },
            ease: 'power2.out',
            scrollTrigger: { trigger: el, once: true },
            onUpdate() { el.textContent = Number(el.innerText).toLocaleString('pt-BR'); }
        });
    });
    gsap.from('.subject-card', { y: 22, opacity: 0, duration: 0.5, stagger: 0.06, ease: 'power2.out', scrollTrigger: { trigger: '.subject-card', start: 'top 85%', once: true } });
});
</script>
@endpush
