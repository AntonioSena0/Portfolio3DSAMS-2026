@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="admin-hero flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Admin</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Painel administrativo</h1>
                <p class="mt-3 text-secondary-600">Aprove professores e acompanhe a saúde da plataforma.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.categories.create') }}" class="inline-flex rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">
                    Criar categoria
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'professor', 'status' => 'pending']) }}" class="inline-flex rounded-lg border border-secondary-200 bg-white px-5 py-3 text-sm font-bold text-secondary-700 hover:border-primary-200">
                    Validar professores
                </a>
                <a href="{{ route('admin.subjects.index', ['status' => 'under_review']) }}" class="inline-flex rounded-lg border border-secondary-200 bg-white px-5 py-3 text-sm font-bold text-secondary-700 hover:border-primary-200">
                    Aprovar materias
                </a>
            </div>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="admin-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Usuários</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $totalUsers }}</p>
            </div>
            <div class="admin-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Professores pendentes</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $pendingProfessors }}</p>
            </div>
            <div class="admin-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Matérias em revisão</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $pendingSubjects }}</p>
            </div>
            <div class="admin-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Aulas ativas</p>
                <p class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $activeVideos }}</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <section class="admin-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card lg:col-span-2">
                <h2 class="text-xl font-extrabold text-secondary-900">Ações rápidas</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-4">
                    <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-primary-600 px-4 py-3 text-center text-sm font-bold text-white hover:bg-primary-700">Criar categoria</a>
                    <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-secondary-200 bg-secondary-50 px-4 py-3 text-center text-sm font-bold text-secondary-700 hover:border-primary-200">Ver categorias</a>
                    <a href="{{ route('admin.users.index', ['role' => 'professor', 'status' => 'pending']) }}" class="rounded-lg border border-secondary-200 bg-secondary-50 px-4 py-3 text-center text-sm font-bold text-secondary-700 hover:border-primary-200">Validar professores</a>
                    <a href="{{ route('admin.subjects.index', ['status' => 'under_review']) }}" class="rounded-lg border border-secondary-200 bg-secondary-50 px-4 py-3 text-center text-sm font-bold text-secondary-700 hover:border-primary-200">Aprovar materias</a>
                </div>
            </section>

            <section class="admin-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <h2 class="text-xl font-extrabold text-secondary-900">Usuários recentes</h2>
                <div class="mt-4 space-y-3">
                    @forelse($recentUsers as $user)
                        <a href="{{ route('admin.users.show', $user) }}" class="flex items-center justify-between rounded-lg bg-secondary-50 p-3 hover:bg-primary-50">
                            <span class="font-semibold text-secondary-900">{{ $user->name }}</span>
                            <span class="rounded-lg px-2 py-1 text-xs font-bold {{ $user->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700' }}">{{ $user->role }} · {{ $user->status }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-secondary-600">Nenhum usuário cadastrado.</p>
                    @endforelse
                </div>
            </section>

            <section class="admin-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <h2 class="text-xl font-extrabold text-secondary-900">Matérias recentes</h2>
                <div class="mt-4 space-y-3">
                    @forelse($recentSubjects as $subject)
                        <div class="rounded-lg bg-secondary-50 p-3">
                            <p class="font-semibold text-secondary-900">{{ $subject->title }}</p>
                            <p class="mt-1 text-sm text-secondary-600">{{ $subject->professor->name ?? 'Professor' }} · {{ $subject->status }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-secondary-600">Nenhuma matéria cadastrada.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.admin-hero > *', { y: 18, opacity: 0, duration: 0.55, stagger: 0.06, ease: 'power2.out' });
    gsap.from('.admin-card', { y: 18, opacity: 0, duration: 0.45, stagger: 0.05, ease: 'power2.out', delay: 0.12 });
});
</script>
@endpush
