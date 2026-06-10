@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('admin.subjects.index', ['status' => 'under_review']) }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para materias</a>

        <div class="admin-subject-review mt-6 grid gap-6 lg:grid-cols-[1fr_360px]">
            <div class="rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-lg px-3 py-1 text-xs font-bold text-white" style="background-color: {{ $subject->category->color ?? '#4F46E5' }}">{{ $subject->category->name ?? 'Geral' }}</span>
                    <span class="rounded-lg bg-white px-3 py-1 text-xs font-bold text-secondary-700">{{ $subject->status }}</span>
                </div>
                <h1 class="mt-4 text-3xl font-extrabold text-secondary-900">{{ $subject->title }}</h1>
                <p class="mt-4 text-sm leading-6 text-secondary-700">{{ $subject->description }}</p>

                <div class="mt-6 rounded-lg bg-white p-4">
                    <h2 class="font-extrabold text-secondary-900">Professor</h2>
                    <p class="mt-2 text-sm text-secondary-700">{{ $subject->professor->name }}</p>
                    <p class="mt-1 text-sm text-secondary-600">{{ $subject->professor->specialty }}</p>
                    @if($subject->professor->bio)
                        <p class="mt-3 text-sm leading-6 text-secondary-600">{{ $subject->professor->bio }}</p>
                    @endif
                </div>
            </div>

            <aside class="rounded-xl border border-secondary-200 bg-white p-5 shadow-card">
                <h2 class="text-lg font-extrabold text-secondary-900">Decisao editorial</h2>
                @if(session('status'))
                    <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
                @endif

                @if($subject->status === 'under_review')
                    <form method="POST" action="{{ route('admin.subjects.approve', $subject) }}" class="mt-5">
                        @csrf
                        <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Aprovar e publicar</button>
                    </form>

                    <form method="POST" action="{{ route('admin.subjects.reject', $subject) }}" class="mt-4">
                        @csrf
                        <label for="rejection_reason" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Motivo da rejeicao</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4" class="w-full rounded-lg border border-secondary-200 bg-white px-3 py-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100"></textarea>
                        <button class="mt-3 w-full rounded-lg border border-red-200 bg-white px-5 py-3 text-sm font-bold text-red-700 hover:bg-red-50">Rejeitar</button>
                    </form>
                @elseif($subject->status === 'published')
                    <p class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">Materia publicada no catalogo.</p>
                    <form method="POST" action="{{ route('admin.subjects.archive', $subject) }}" class="mt-4">
                        @csrf
                        <button class="w-full rounded-lg border border-secondary-200 bg-white px-5 py-3 text-sm font-bold text-secondary-700 hover:border-primary-200">Arquivar</button>
                    </form>
                @else
                    <p class="mt-4 rounded-lg bg-secondary-50 px-4 py-3 text-sm font-semibold text-secondary-700">Esta materia nao esta aguardando revisao.</p>
                @endif
            </aside>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-extrabold text-secondary-900">Aulas enviadas</h2>
        <div class="mt-5 space-y-3">
            @forelse($subject->videos->sortBy('order') as $video)
                <div class="rounded-xl border border-secondary-200 bg-white p-4 shadow-card">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-primary-700">Aula {{ $video->order }} · {{ $video->status }}</p>
                            <h3 class="mt-1 font-extrabold text-secondary-900">{{ $video->title }}</h3>
                        </div>
                        <span class="text-sm font-semibold text-secondary-600">{{ $video->formatted_duration }}</span>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-secondary-300 bg-white p-8 text-center">
                    <p class="text-sm font-semibold text-secondary-600">Nenhuma aula cadastrada nesta materia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.admin-subject-review > *', { y: 18, opacity: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out' });
});
</script>
@endpush
