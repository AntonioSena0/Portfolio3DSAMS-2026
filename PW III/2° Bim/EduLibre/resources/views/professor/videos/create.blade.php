@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('professor.videos.index', $subject) }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para aulas</a>
        <div class="mt-6 rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Nova aula</p>
            <h1 class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $subject->title }}</h1>

            @if($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('professor.videos.store', $subject) }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="title" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Titulo</label>
                    <input id="title" name="title" value="{{ old('title') }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label for="url" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">URL do video</label>
                    <input id="url" name="url" type="url" value="{{ old('url') }}" required placeholder="https://www.youtube.com/embed/..." class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label for="duration" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Duracao em segundos</label>
                        <input id="duration" name="duration" type="number" min="1" value="{{ old('duration') }}" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    </div>
                    <div>
                        <label for="order" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Ordem</label>
                        <input id="order" name="order" type="number" min="1" value="{{ old('order', $nextOrder) }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    </div>
                    <div>
                        <label for="status" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Status</label>
                        <select id="status" name="status" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                            <option value="active" @selected(old('status', 'active') === 'active')>Ativa</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inativa</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="description" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Descricao</label>
                    <textarea id="description" name="description" rows="5" class="w-full rounded-lg border border-secondary-200 bg-white px-3 py-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">{{ old('description') }}</textarea>
                </div>
                <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Salvar aula</button>
            </form>
        </div>
    </div>
</section>
@endsection
