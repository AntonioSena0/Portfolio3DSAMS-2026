<x-guest-layout>
    <p class="mb-5 text-sm leading-6 text-[#5A5A5A]">Informe seu email para receber o link de redefinição de senha.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex justify-end">
            <x-primary-button>Enviar link</x-primary-button>
        </div>
    </form>
</x-guest-layout>
