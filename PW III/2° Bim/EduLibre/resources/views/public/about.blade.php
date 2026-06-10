@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="about-hero max-w-3xl">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Sobre</p>
            <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900 lg:text-5xl">Educação gratuita precisa ser simples de acessar.</h1>
            <p class="mt-5 text-lg leading-8 text-secondary-600">A EduLibre foi desenhada para reduzir fricção: o aluno encontra uma matéria, assiste às aulas e acompanha o progresso; o professor publica conteúdo com um fluxo claro de aprovação.</p>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto grid max-w-7xl gap-5 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
        <article class="about-card rounded-xl border border-secondary-200 bg-white p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Missão</p>
            <h2 class="mt-3 text-2xl font-extrabold text-secondary-900">Democratizar o ensino</h2>
            <p class="mt-3 text-sm leading-6 text-secondary-600">Conectar professores voluntários a estudantes que precisam de conteúdo organizado, gratuito e acessível.</p>
        </article>
        <article class="about-card rounded-xl border border-secondary-200 bg-white p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Experiência</p>
            <h2 class="mt-3 text-2xl font-extrabold text-secondary-900">Ritmo individual</h2>
            <p class="mt-3 text-sm leading-6 text-secondary-600">Progresso por vídeo e por matéria para o aluno retomar de onde parou sem confusão.</p>
        </article>
        <article class="about-card rounded-xl border border-secondary-200 bg-white p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Qualidade</p>
            <h2 class="mt-3 text-2xl font-extrabold text-secondary-900">Curadoria ativa</h2>
            <p class="mt-3 text-sm leading-6 text-secondary-600">Professores e matérias passam por aprovação para manter o catálogo confiável.</p>
        </article>
    </div>
</section>

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[.8fr_1.2fr]">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Como funciona</p>
                <h2 class="mt-2 text-3xl font-extrabold text-secondary-900">Um fluxo objetivo para aprender ou ensinar</h2>
            </div>
            <div class="space-y-3">
                @foreach(['Aluno cria uma conta gratuita e acessa o catálogo.', 'Professor aprovado cria matérias e organiza vídeos.', 'Admin revisa conteúdos e mantém a plataforma saudável.', 'Progresso, comentários e avaliações ajudam a comunidade a evoluir.'] as $index => $step)
                    <div class="about-step flex gap-4 rounded-xl border border-secondary-200 bg-secondary-50 p-4">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-primary-600 text-sm font-extrabold text-white">{{ $index + 1 }}</span>
                        <p class="text-sm font-semibold leading-6 text-secondary-800">{{ $step }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.about-hero > *', { y: 20, opacity: 0, duration: 0.6, stagger: 0.07, ease: 'power2.out' });
    gsap.from('.about-card', { y: 20, opacity: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out', scrollTrigger: { trigger: '.about-card', start: 'top 85%', once: true } });
    gsap.from('.about-step', { x: 20, opacity: 0, duration: 0.45, stagger: 0.07, ease: 'power2.out', scrollTrigger: { trigger: '.about-step', start: 'top 85%', once: true } });
});
</script>
@endpush
