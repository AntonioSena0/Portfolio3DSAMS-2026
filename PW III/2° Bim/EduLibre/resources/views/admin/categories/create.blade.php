@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('admin.categories.index') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para categorias</a>
        <div class="mt-6 rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Admin</p>
            <h1 class="mt-2 text-3xl font-extrabold text-secondary-900">Criar categoria</h1>
            <p class="mt-2 text-sm text-secondary-600">A categoria aparece nos filtros do catálogo e nos formulários de matéria.</p>

            @if($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="name" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Nome</label>
                    <input id="name" name="name" value="{{ old('name') }}" required maxlength="100" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label for="color" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Cor</label>
                    <div class="flex gap-3">
                        <input id="color" name="color" value="{{ old('color', '#4F46E5') }}" pattern="^#[0-9A-Fa-f]{6}$" class="h-11 flex-1 rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                        <input type="color" value="{{ old('color', '#4F46E5') }}" class="h-11 w-14 rounded-lg border border-secondary-200 bg-white p-1" oninput="document.getElementById('color').value = this.value">
                    </div>
                </div>
                <div>
                    <label for="description" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Descrição</label>
                    <textarea id="description" name="description" rows="5" class="w-full rounded-lg border border-secondary-200 bg-white px-3 py-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">{{ old('description') }}</textarea>
                </div>
                <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Criar categoria</button>
            </form>
        </div>
    </div>
</section>
@endsection
