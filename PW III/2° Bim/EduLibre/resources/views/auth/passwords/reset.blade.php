@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl place-items-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="auth-card w-full max-w-md rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Senha</p>
            <h1 class="mt-2 text-3xl font-extrabold text-secondary-900">Definir nova senha</h1>

            @if($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label for="email" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email ?? '') }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label for="password" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Nova senha</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Confirmar senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Redefinir senha</button>
            </form>
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
