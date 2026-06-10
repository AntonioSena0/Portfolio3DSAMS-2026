@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="teachers-hero mb-8">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Professores</p>
            <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Quem transforma conhecimento em aula</h1>
            <p class="mt-4 max-w-2xl text-secondary-600">Conheça professores ativos e encontre especialidades alinhadas ao que você quer aprender.</p>
        </div>

        <form method="GET" action="{{ route('teachers') }}" class="teacher-filter grid gap-3 rounded-xl border border-secondary-200 bg-secondary-50 p-4 md:grid-cols-[1fr_260px_auto]">
            <div>
                <label for="search" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Buscar</label>
                <input id="search" name="search" value="{{ request('search') }}" type="search" placeholder="Nome ou especialidade" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
            </div>
            <div>
                <label for="specialty" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Especialidade</label>
                <input id="specialty" name="specialty" value="{{ request('specialty') }}" type="text" placeholder="Ex: programação" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
            </div>
            <div class="flex items-end gap-2">
                <button class="h-11 rounded-lg bg-primary-600 px-5 text-sm font-bold text-white hover:bg-primary-700">Filtrar</button>
                <a href="{{ route('teachers') }}" class="grid h-11 place-items-center rounded-lg border border-secondary-200 bg-white px-4 text-sm font-bold text-secondary-700 hover:border-primary-200">Limpar</a>
            </div>
        </form>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if($teachers->isEmpty())
            <div class="rounded-xl border border-dashed border-secondary-300 bg-white p-10 text-center">
                <h2 class="text-xl font-extrabold text-secondary-900">Nenhum professor encontrado</h2>
                <p class="mt-2 text-sm text-secondary-600">Tente buscar por outro nome ou especialidade.</p>
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($teachers as $teacher)
                    <article class="teacher-card rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                        <div class="flex items-start gap-4">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-lg bg-primary-600 text-sm font-extrabold text-white">
                                {{ collect(explode(' ', $teacher->name))->map(fn($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                            </span>
                            <div class="min-w-0">
                                <h2 class="truncate text-lg font-extrabold text-secondary-900">{{ $teacher->name }}</h2>
                                <p class="mt-1 text-sm font-semibold text-primary-700">{{ $teacher->specialty ?: 'Professor voluntário' }}</p>
                            </div>
                        </div>
                        <p class="mt-4 line-clamp-4 text-sm leading-6 text-secondary-600">{{ $teacher->bio ?: 'Professor cadastrado na EduLibre para compartilhar conhecimento de forma gratuita.' }}</p>
                        <div class="mt-5 grid grid-cols-2 gap-3 border-t border-secondary-100 pt-4 text-sm">
                            <div class="rounded-lg bg-secondary-50 p-3">
                                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Matérias</p>
                                <p class="mt-1 text-xl font-extrabold text-secondary-900">{{ $teacher->subjects_count }}</p>
                            </div>
                            <div class="rounded-lg bg-secondary-50 p-3">
                                <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Aulas</p>
                                <p class="mt-1 text-xl font-extrabold text-secondary-900">{{ $teacher->videos_count }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.teachers-hero > *', { y: 18, opacity: 0, duration: 0.55, stagger: 0.06, ease: 'power2.out' });
    gsap.from('.teacher-filter', { y: 18, opacity: 0, duration: 0.55, ease: 'power2.out', delay: 0.12 });
    gsap.from('.teacher-card', { y: 18, opacity: 0, duration: 0.45, stagger: 0.05, ease: 'power2.out', delay: 0.22 });
});
</script>
@endpush
