@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('professor.videos.index', $subject) }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para aulas</a>
        <div class="mt-6 rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Editar aula</p>
            <h1 class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $video->title }}</h1>

            @if(session('status'))
                <div class="mt-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('professor.videos.update', [$subject, $video]) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="title" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Titulo</label>
                    <input id="title" name="title" value="{{ old('title', $video->title) }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label for="url" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">URL do video</label>
                    <input id="url" name="url" type="url" value="{{ old('url', $video->url) }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label for="duration" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Duracao em segundos</label>
                        <input id="duration" name="duration" type="number" min="1" value="{{ old('duration', $video->duration) }}" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    </div>
                    <div>
                        <label for="order" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Ordem</label>
                        <input id="order" name="order" type="number" min="1" value="{{ old('order', $video->order) }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    </div>
                    <div>
                        <label for="status" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Status</label>
                        <select id="status" name="status" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                            <option value="active" @selected(old('status', $video->status) === 'active')>Ativa</option>
                            <option value="inactive" @selected(old('status', $video->status) === 'inactive')>Inativa</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="description" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Descricao</label>
                    <textarea id="description" name="description" rows="5" class="w-full rounded-lg border border-secondary-200 bg-white px-3 py-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">{{ old('description', $video->description) }}</textarea>
                </div>
                <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Salvar alteracoes</button>
            </form>

            <form method="POST" action="{{ route('professor.videos.destroy', [$subject, $video]) }}" class="mt-4">
                @csrf
                @method('DELETE')
                <button class="w-full rounded-lg border border-red-200 bg-white px-5 py-3 text-sm font-bold text-red-700 hover:bg-red-50">Excluir aula</button>
            </form>
        </div>
    </div>
</section>
@endsection
