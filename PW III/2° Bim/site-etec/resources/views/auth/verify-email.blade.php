<x-guest-layout>
    <p class="mb-5 text-sm leading-6 text-[#5A5A5A]">Antes de continuar, confirme seu email pelo link enviado. Se não recebeu, solicite um novo envio.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-bold text-[#007C85]">Um novo link de verificação foi enviado para o email cadastrado.</div>
    @endif

    <div class="flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>Reenviar email</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm font-bold text-[#B20000] hover:underline">Sair</button>
        </form>
    </div>
</x-guest-layout>
