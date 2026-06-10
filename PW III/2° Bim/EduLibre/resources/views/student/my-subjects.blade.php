@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="student-subjects-hero flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Aluno</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Minhas materias</h1>
                <p class="mt-3 max-w-2xl text-secondary-600">Acompanhe as trilhas que voce iniciou e retome os estudos pelo ponto certo.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex w-fit items-center justify-center rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Explorar catalogo</a>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if($enrollments->isEmpty())
            <div class="empty-state rounded-xl border border-dashed border-secondary-300 bg-white p-10 text-center shadow-card">
                <h2 class="text-xl font-extrabold text-secondary-900">Voce ainda nao iniciou nenhuma materia</h2>
                <p class="mt-2 text-sm text-secondary-600">Abra uma materia publicada no catalogo e comece a primeira aula para sua matricula aparecer aqui.</p>
                <a href="{{ route('catalog.index') }}" class="mt-5 inline-flex rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Ver materias disponiveis</a>
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($enrollments as $enrollment)
                    <a href="{{ route('subjects.show', $enrollment->subject->slug) }}" class="student-subject-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card transition hover:-translate-y-1 hover:border-primary-200 hover:shadow-card-hover">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <span class="rounded-lg px-3 py-1 text-xs font-bold text-white" style="background-color: {{ $enrollment->subject->category->color ?? '#4F46E5' }}">{{ $enrollment->subject->category->name ?? 'Geral' }}</span>
                            <span class="rounded-lg bg-secondary-100 px-3 py-1 text-xs font-bold text-secondary-600">{{ $enrollment->status === 'completed' ? 'Concluida' : 'Em andamento' }}</span>
                        </div>
                        <h2 class="text-lg font-extrabold text-secondary-900">{{ $enrollment->subject->title }}</h2>
                        <p class="mt-2 text-sm text-secondary-600">Professor: {{ $enrollment->subject->professor->name }}</p>
                        <div class="mt-5">
                            <div class="mb-2 flex justify-between text-sm font-semibold text-secondary-700">
                                <span>Progresso</span>
                                <span>{{ $enrollment->progress_percent }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-secondary-200">
                                <div class="h-full rounded-full bg-primary-600" style="width: {{ $enrollment->progress_percent }}%"></div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.student-subjects-hero > *', { y: 18, opacity: 0, duration: 0.55, stagger: 0.06, ease: 'power2.out' });
    gsap.from('.student-subject-card, .empty-state', { y: 18, opacity: 0, duration: 0.45, stagger: 0.05, ease: 'power2.out', delay: 0.18 });
});
</script>
@endpush
