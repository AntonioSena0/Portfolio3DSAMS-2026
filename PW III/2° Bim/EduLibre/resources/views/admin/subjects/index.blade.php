@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="admin-subjects-hero flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Admin</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Materias para moderacao</h1>
                <p class="mt-3 max-w-2xl text-secondary-600">Revise materias enviadas pelos professores e publique o que estiver pronto para os alunos.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex w-fit rounded-lg border border-secondary-200 bg-white px-5 py-3 text-sm font-bold text-secondary-700 hover:border-primary-200">Voltar ao painel</a>
        </div>

        <form method="GET" action="{{ route('admin.subjects.index') }}" class="mt-8 grid gap-3 rounded-xl border border-secondary-200 bg-secondary-50 p-4 md:grid-cols-[1fr_220px_auto]">
            <div>
                <label for="search" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Busca</label>
                <input id="search" name="search" value="{{ request('search') }}" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
            </div>
            <div>
                <label for="status" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Status</label>
                <select id="status" name="status" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <option value="">Todos</option>
                    <option value="draft" @selected(request('status') === 'draft')>Rascunho</option>
                    <option value="under_review" @selected(request('status') === 'under_review')>Em revisao</option>
                    <option value="published" @selected(request('status') === 'published')>Publicada</option>
                    <option value="archived" @selected(request('status') === 'archived')>Arquivada</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="h-11 rounded-lg bg-primary-600 px-5 text-sm font-bold text-white hover:bg-primary-700">Filtrar</button>
                <a href="{{ route('admin.subjects.index') }}" class="grid h-11 place-items-center rounded-lg border border-secondary-200 bg-white px-4 text-sm font-bold text-secondary-700 hover:border-primary-200">Limpar</a>
            </div>
        </form>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if($subjects->isEmpty())
            <div class="empty-state rounded-xl border border-dashed border-secondary-300 bg-white p-10 text-center shadow-card">
                <h2 class="text-xl font-extrabold text-secondary-900">Nenhuma materia encontrada</h2>
                <p class="mt-2 text-sm text-secondary-600">Quando um professor enviar uma materia para revisao, ela aparecera aqui.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($subjects as $subject)
                    <article class="admin-subject-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-lg px-3 py-1 text-xs font-bold text-white" style="background-color: {{ $subject->category->color ?? '#4F46E5' }}">{{ $subject->category->name ?? 'Geral' }}</span>
                                    <span class="rounded-lg bg-secondary-100 px-3 py-1 text-xs font-bold text-secondary-700">{{ $subject->status }}</span>
                                    <span class="rounded-lg bg-secondary-100 px-3 py-1 text-xs font-bold text-secondary-700">{{ $subject->videos_count }} aulas</span>
                                </div>
                                <h2 class="mt-3 text-xl font-extrabold text-secondary-900">{{ $subject->title }}</h2>
                                <p class="mt-1 text-sm text-secondary-600">Professor: {{ $subject->professor->name ?? 'Professor' }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.subjects.show', $subject) }}" class="rounded-lg border border-secondary-200 bg-white px-4 py-2 text-sm font-bold text-secondary-700 hover:border-primary-200">Revisar</a>
                                @if($subject->status === 'under_review')
                                    <form method="POST" action="{{ route('admin.subjects.approve', $subject) }}">
                                        @csrf
                                        <button class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-bold text-white hover:bg-primary-700">Aprovar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.admin-subjects-hero > *', { y: 18, opacity: 0, duration: 0.55, stagger: 0.06, ease: 'power2.out' });
    gsap.from('.admin-subject-card, .empty-state', { y: 18, opacity: 0, duration: 0.45, stagger: 0.05, ease: 'power2.out', delay: 0.18 });
});
</script>
@endpush
