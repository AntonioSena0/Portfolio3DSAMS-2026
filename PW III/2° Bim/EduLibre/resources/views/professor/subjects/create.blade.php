@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('professor.dashboard') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar ao painel</a>
        <div class="mt-6 rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Professor</p>
            <h1 class="mt-2 text-3xl font-extrabold text-secondary-900">Criar matéria</h1>
            <p class="mt-2 text-sm text-secondary-600">Crie o rascunho da matéria. Depois você poderá adicionar aulas e enviar para revisão.</p>

            @if($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
            @endif

            @if($categories->isEmpty())
                <div class="mt-5 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm font-semibold text-yellow-800">
                    Nenhuma categoria cadastrada. Peça para um admin criar uma categoria antes de publicar matérias.
                </div>
            @endif

            <form method="POST" action="{{ route('professor.subjects.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="title" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Título</label>
                    <input id="title" name="title" value="{{ old('title') }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label for="category_id" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Categoria</label>
                    <select id="category_id" name="category_id" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                        <option value="">Selecione</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="description" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Descrição</label>
                    <textarea id="description" name="description" rows="7" required class="w-full rounded-lg border border-secondary-200 bg-white px-3 py-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">{{ old('description') }}</textarea>
                </div>
                <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700" @disabled($categories->isEmpty())>Salvar rascunho</button>
            </form>
        </div>
    </div>
</section>
@endsection
