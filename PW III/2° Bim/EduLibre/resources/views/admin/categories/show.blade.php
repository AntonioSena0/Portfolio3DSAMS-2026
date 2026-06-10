@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('admin.categories.index') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para categorias</a>

        @if(session('status'))
            <div class="mt-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
        @endif

        <div class="mt-6 rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <span class="inline-flex h-5 w-5 rounded" style="background-color: {{ $category->color ?: '#4F46E5' }}"></span>
                    <h1 class="mt-3 text-3xl font-extrabold text-secondary-900">{{ $category->name }}</h1>
                    <p class="mt-2 text-secondary-600">{{ $category->description ?: 'Sem descrição.' }}</p>
                </div>
                <span class="rounded-lg bg-white px-3 py-2 text-sm font-bold text-secondary-700">{{ $category->subjects_count }} matérias</span>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Editar</a>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-lg border border-red-200 bg-white px-5 py-3 text-sm font-bold text-red-700 hover:bg-red-50">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
