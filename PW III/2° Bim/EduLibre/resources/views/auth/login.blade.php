@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl place-items-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="auth-card w-full max-w-md rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Entrar</p>
            <h1 class="mt-2 text-3xl font-extrabold text-secondary-900">Acesse sua conta</h1>
            <p class="mt-2 text-sm text-secondary-600">Continue seus estudos ou gerencie seus conteúdos.</p>

            @if(session('status'))
                <div class="mt-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="email" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label for="password" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Senha</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div class="flex items-center justify-between gap-4">
                    <label class="flex items-center gap-2 text-sm font-semibold text-secondary-600">
                        <input type="checkbox" name="remember" class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                        Lembrar de mim
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Esqueci a senha</a>
                </div>
                <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Entrar</button>
            </form>

            <p class="mt-5 text-center text-sm text-secondary-600">Ainda não tem conta? <a href="{{ route('register') }}" class="font-bold text-primary-700 hover:text-primary-900">Criar conta</a></p>
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
