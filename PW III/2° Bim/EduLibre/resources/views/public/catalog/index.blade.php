@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="catalog-hero mb-8">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Catálogo</p>
            <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Encontre uma matéria sem perder tempo</h1>
            <p class="mt-4 max-w-2xl text-secondary-600">Use busca, categoria e ordenação. Tudo é renderizado no servidor para ficar rápido e previsível.</p>
        </div>

        <form method="GET" action="{{ route('catalog.index') }}" class="filter-bar grid gap-3 rounded-xl border border-secondary-200 bg-secondary-50 p-4 md:grid-cols-[1fr_220px_180px_auto]">
            <div>
                <label for="search" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Busca</label>
                <input id="search" name="search" value="{{ request('search') }}" type="search" placeholder="Título ou descrição" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
            </div>
            <div>
                <label for="category_id" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Categoria</label>
                <select id="category_id" name="category_id" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="sort" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Ordem</label>
                <select id="sort" name="sort" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    <option value="latest" @selected(request('sort', 'latest') === 'latest')>Recentes</option>
                    <option value="most-videos" @selected(request('sort') === 'most-videos')>Mais aulas</option>
                    <option value="title-asc" @selected(request('sort') === 'title-asc')>A-Z</option>
                    <option value="title-desc" @selected(request('sort') === 'title-desc')>Z-A</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="h-11 rounded-lg bg-primary-600 px-5 text-sm font-bold text-white hover:bg-primary-700">Filtrar</button>
                <a href="{{ route('catalog.index') }}" class="grid h-11 place-items-center rounded-lg border border-secondary-200 bg-white px-4 text-sm font-bold text-secondary-700 hover:border-primary-200">Limpar</a>
            </div>
        </form>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-5 flex items-center justify-between">
            <p class="text-sm font-semibold text-secondary-600">{{ $subjects->total() }} matéria(s) encontrada(s)</p>
            <p class="text-sm text-secondary-500">Página {{ $subjects->currentPage() }} de {{ $subjects->lastPage() }}</p>
        </div>

        @if($subjects->isEmpty())
            <div class="rounded-xl border border-dashed border-secondary-300 bg-white p-10 text-center">
                <h2 class="text-xl font-extrabold text-secondary-900">Nenhuma matéria encontrada</h2>
                <p class="mt-2 text-sm text-secondary-600">Tente remover filtros ou buscar por um termo mais amplo.</p>
            </div>
        @else
            <div class="catalog-results grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($subjects as $subject)
                    <a href="{{ route('subjects.show', $subject->slug) }}" class="catalog-card group rounded-xl border border-secondary-200 bg-white p-5 shadow-card transition hover:-translate-y-1 hover:border-primary-200 hover:shadow-card-hover">
                        <div class="mb-5 flex items-center justify-between">
                            <span class="rounded-lg px-3 py-1 text-xs font-bold text-white" style="background-color: {{ $subject->category->color ?? '#4f46e5' }}">{{ $subject->category->name ?? 'Geral' }}</span>
                            <span class="rounded-lg bg-secondary-100 px-3 py-1 text-xs font-bold text-secondary-600">{{ $subject->videos_count }} aulas</span>
                        </div>
                        <h2 class="text-lg font-extrabold text-secondary-900 group-hover:text-primary-700">{{ $subject->title }}</h2>
                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-secondary-600">{{ $subject->description }}</p>
                        <div class="mt-5 flex items-center justify-between border-t border-secondary-100 pt-4">
                            <span class="truncate text-sm font-semibold text-secondary-700">{{ $subject->professor->name ?? 'Professor' }}</span>
                            <span class="text-sm font-bold text-primary-700">Estudar</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
