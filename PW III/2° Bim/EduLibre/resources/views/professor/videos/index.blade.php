@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <a href="{{ route('professor.subjects.edit', $subject) }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para materia</a>
                <h1 class="mt-3 text-3xl font-extrabold text-secondary-900">Aulas de {{ $subject->title }}</h1>
                <p class="mt-2 text-sm text-secondary-600">Organize as aulas que aparecem para os alunos quando a materia for publicada.</p>
            </div>
            <a href="{{ route('professor.videos.create', $subject) }}" class="rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Nova aula</a>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
        @endif

        <div class="mt-8 space-y-3" x-data="{ videos: @js($videos->pluck('id')->values()) }">
            @forelse($videos as $video)
                <article class="reveal-card rounded-xl border border-secondary-200 bg-secondary-50 p-5 shadow-card">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-secondary-600">Aula {{ $video->order }}</span>
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $video->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' }}">{{ $video->status === 'active' ? 'Ativa' : 'Inativa' }}</span>
                            </div>
                            <h2 class="mt-3 text-lg font-extrabold text-secondary-900">{{ $video->title }}</h2>
                            <p class="mt-1 text-sm text-secondary-600">{{ $video->description ?: 'Sem descricao adicional.' }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('professor.videos.show', [$subject, $video]) }}" class="rounded-lg border border-secondary-200 bg-white px-4 py-2 text-sm font-bold text-secondary-700 hover:border-primary-200">Ver</a>
                            <a href="{{ route('professor.videos.edit', [$subject, $video]) }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-bold text-white hover:bg-primary-700">Editar</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-secondary-300 bg-secondary-50 p-8 text-center">
                    <h2 class="text-xl font-extrabold text-secondary-900">Nenhuma aula criada ainda</h2>
                    <p class="mt-2 text-sm text-secondary-600">Crie a primeira aula para poder enviar a materia para revisao.</p>
                    <a href="{{ route('professor.videos.create', $subject) }}" class="mt-5 inline-flex rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Criar primeira aula</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
