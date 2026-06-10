@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <a href="{{ route('professor.videos.index', $subject) }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para aulas</a>
                <h1 class="mt-3 text-3xl font-extrabold text-secondary-900">{{ $video->title }}</h1>
                <p class="mt-2 text-sm text-secondary-600">{{ $subject->title }} · Aula {{ $video->order }}</p>
            </div>
            <a href="{{ route('professor.videos.edit', [$subject, $video]) }}" class="rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Editar aula</a>
        </div>

        <div class="video-wrapper mt-8 overflow-hidden rounded-xl border border-secondary-200 bg-secondary-950 shadow-card">
            <iframe src="{{ $video->url }}" title="{{ $video->title }}" class="aspect-video w-full" allowfullscreen></iframe>
        </div>

        <div class="mt-6 rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <div class="flex flex-wrap gap-2">
                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-secondary-600">Status: {{ $video->status === 'active' ? 'ativa' : 'inativa' }}</span>
                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-secondary-600">Duracao: {{ $video->formatted_duration }}</span>
                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-secondary-600">Visualizacoes: {{ $video->views_count }}</span>
            </div>
            <p class="mt-4 text-sm leading-6 text-secondary-700">{{ $video->description ?: 'Sem descricao adicional.' }}</p>
        </div>
    </div>
</section>
@endsection
