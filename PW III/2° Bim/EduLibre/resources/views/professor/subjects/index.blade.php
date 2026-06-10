@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Professor</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Minhas matérias</h1>
            </div>
            <a href="{{ route('professor.subjects.create') }}" class="rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Criar matéria</a>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($subjects as $subject)
                <a href="{{ route('professor.subjects.edit', $subject) }}" class="rounded-xl border border-secondary-200 bg-white p-5 shadow-card hover:border-primary-200">
                    <span class="rounded-lg bg-secondary-100 px-2 py-1 text-xs font-bold text-secondary-700">{{ $subject->status }}</span>
                    <h2 class="mt-4 text-lg font-extrabold text-secondary-900">{{ $subject->title }}</h2>
                    <p class="mt-2 text-sm text-secondary-600">{{ $subject->videos_count }} aulas · {{ $subject->enrollments_count }} alunos</p>
                </a>
            @empty
                <div class="rounded-xl border border-dashed border-secondary-300 bg-white p-8 text-center md:col-span-2 lg:col-span-3">
                    <h2 class="font-extrabold text-secondary-900">Nenhuma matéria criada</h2>
                    <p class="mt-2 text-sm text-secondary-600">Crie sua primeira matéria para começar.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $subjects->links() }}</div>
    </div>
</section>
@endsection
