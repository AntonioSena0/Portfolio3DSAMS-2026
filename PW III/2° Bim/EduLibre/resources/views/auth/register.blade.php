@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl place-items-center px-4 py-12 sm:px-6 lg:px-8">
        <div x-data="{ role: '{{ old('role', 'student') }}' }" class="auth-card w-full max-w-xl rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Cadastro</p>
            <h1 class="mt-2 text-3xl font-extrabold text-secondary-900">Crie sua conta gratuita</h1>
            <p class="mt-2 text-sm text-secondary-600">Alunos entram direto. Professores aguardam aprovação administrativa.</p>

            @if($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Nome</label>
                        <input id="name" name="name" value="{{ old('name') }}" required autofocus class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">E-mail</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    </div>
                </div>
                <div>
                    <label for="role" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Tipo de conta</label>
                    <select id="role" name="role" x-model="role" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                        <option value="student">Sou aluno</option>
                        <option value="professor">Sou professor</option>
                    </select>
                </div>
                <div x-show="role === 'professor'" x-transition class="grid gap-4">
                    <div>
                        <label for="specialty" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Especialidade</label>
                        <input id="specialty" name="specialty" value="{{ old('specialty') }}" :required="role === 'professor'" :disabled="role !== 'professor'" placeholder="Ex: Matemática, História, Programação" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    </div>
                    <div>
                        <label for="bio" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Bio</label>
                        <textarea id="bio" name="bio" rows="3" :disabled="role !== 'professor'" class="w-full rounded-lg border border-secondary-200 bg-white px-3 py-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">{{ old('bio') }}</textarea>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Senha</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Confirmar senha</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    </div>
                </div>
                <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Criar conta</button>
            </form>

            <p class="mt-5 text-center text-sm text-secondary-600">Já tem conta? <a href="{{ route('login') }}" class="font-bold text-primary-700 hover:text-primary-900">Entrar</a></p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.auth-card', { y: 24, opacity: 0, duration: 0.55, ease: 'power2.out' });
});
</script>
@endpush
