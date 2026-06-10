@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="professor-hero flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Professor</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Painel do professor</h1>
                <p class="mt-3 text-secondary-600">Gerencie suas matérias, aulas e acompanhe o alcance do seu conteúdo.</p>
            </div>
            <a href="{{ route('professor.subjects.create') }}" class="inline-flex rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">
                Criar matéria
            </a>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="professor-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Rascunhos</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $subjectsByStatus['draft'] ?? 0 }}</p>
            </div>
            <div class="professor-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Em revisão</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $subjectsByStatus['under_review'] ?? 0 }}</p>
            </div>
            <div class="professor-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Publicadas</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $subjectsByStatus['published'] ?? 0 }}</p>
            </div>
            <div class="professor-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Aulas ativas</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $totalVideos }}</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
            <section class="professor-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-extrabold text-secondary-900">Matérias recentes</h2>
                    <a href="{{ route('professor.subjects.index') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Ver todas</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentSubjects as $subject)
                        <a href="{{ route('professor.subjects.edit', $subject) }}" class="block rounded-lg border border-secondary-200 bg-secondary-50 p-4 hover:border-primary-200 hover:bg-white">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-bold text-secondary-900">{{ $subject->title }}</h3>
                                    <p class="mt-1 text-sm text-secondary-600">{{ $subject->videos_count }} aulas · {{ $subject->enrollments_count }} alunos</p>
                                </div>
                                <span class="rounded-lg px-2 py-1 text-xs font-bold {{ $subject->status === 'published' ? 'bg-green-50 text-green-700' : ($subject->status === 'under_review' ? 'bg-yellow-50 text-yellow-700' : 'bg-secondary-100 text-secondary-700') }}">{{ $subject->status }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-lg border border-dashed border-secondary-300 bg-secondary-50 p-8 text-center">
                            <h3 class="font-extrabold text-secondary-900">Nenhuma matéria ainda</h3>
                            <p class="mt-2 text-sm text-secondary-600">Crie sua primeira matéria para começar.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <aside class="professor-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <h2 class="text-xl font-extrabold text-secondary-900">Alcance</h2>
                <div class="mt-5 space-y-4">
                    <div class="rounded-lg bg-secondary-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Matrículas</p>
                        <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $totalEnrollments }}</p>
                    </div>
                    <div class="rounded-lg bg-secondary-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Visualizações</p>
                        <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $totalViews }}</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.professor-hero > *', { y: 18, opacity: 0, duration: 0.55, stagger: 0.06, ease: 'power2.out' });
    gsap.from('.professor-card', { y: 18, opacity: 0, duration: 0.45, stagger: 0.05, ease: 'power2.out', delay: 0.12 });
});
</script>
@endpush
