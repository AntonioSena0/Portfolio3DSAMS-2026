@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Admin</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Categorias</h1>
                <p class="mt-3 text-secondary-600">Organize o catálogo por áreas de conhecimento.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Criar categoria</a>
        </div>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($categories as $category)
                <a href="{{ route('admin.categories.show', $category) }}" class="rounded-xl border border-secondary-200 bg-white p-5 shadow-card hover:border-primary-200">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="inline-flex h-4 w-4 rounded" style="background-color: {{ $category->color ?: '#4F46E5' }}"></span>
                            <h2 class="mt-4 text-lg font-extrabold text-secondary-900">{{ $category->name }}</h2>
                            <p class="mt-2 line-clamp-2 text-sm text-secondary-600">{{ $category->description ?: 'Sem descrição.' }}</p>
                        </div>
                        <span class="rounded-lg bg-secondary-100 px-2 py-1 text-xs font-bold text-secondary-700">{{ $category->subjects_count }} matérias</span>
                    </div>
                </a>
            @empty
                <div class="rounded-xl border border-dashed border-secondary-300 bg-white p-8 text-center md:col-span-2 lg:col-span-3">
                    <h2 class="font-extrabold text-secondary-900">Nenhuma categoria criada</h2>
                    <p class="mt-2 text-sm text-secondary-600">Crie a primeira categoria para liberar a criação de matérias pelos professores.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.shadow-card', { y: 18, opacity: 0, duration: 0.45, stagger: 0.05, ease: 'power2.out' });
});
</script>
@endpush
