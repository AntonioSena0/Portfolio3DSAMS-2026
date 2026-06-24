<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-[#5A5A5A]">
            <input id="remember_me" type="checkbox" class="border-[#DDD] text-[#B20000] focus:ring-[#B20000]" name="remember">
            <span>Lembrar acesso</span>
        </label>

        <div class="flex items-center justify-between gap-4">
            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-[#B20000] hover:underline" href="{{ route('password.request') }}">Esqueci minha senha</a>
            @endif

            <x-primary-button>Entrar</x-primary-button>
        </div>
    </form>
</x-guest-layout>
