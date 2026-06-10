@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('professor.subjects.index') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para matérias</a>
        <div class="mt-6 rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Matéria</p>
                    <h1 class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $subject->title }}</h1>
                    <p class="mt-2 text-sm text-secondary-600">Status: {{ $subject->status }}</p>
                </div>
                <a href="{{ route('professor.videos.index', $subject) }}" class="rounded-lg border border-secondary-200 bg-white px-4 py-2 text-sm font-bold text-secondary-700 hover:border-primary-200">Gerenciar aulas</a>
            </div>

            @if(session('status'))
                <div class="mt-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('professor.subjects.update', $subject) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="title" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Título</label>
                    <input id="title" name="title" value="{{ old('title', $subject->title) }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label for="category_id" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Categoria</label>
                    <select id="category_id" name="category_id" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $subject->category_id) === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="description" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Descrição</label>
                    <textarea id="description" name="description" rows="7" required class="w-full rounded-lg border border-secondary-200 bg-white px-3 py-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">{{ old('description', $subject->description) }}</textarea>
                </div>
                <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Salvar alterações</button>
            </form>

            <form method="POST" action="{{ route('professor.subjects.submit', $subject) }}" class="mt-4">
                @csrf
                <button class="w-full rounded-lg border border-primary-200 bg-white px-5 py-3 text-sm font-bold text-primary-700 hover:bg-primary-50">Enviar para revisão</button>
            </form>
        </div>
    </div>
</section>
@endsection
