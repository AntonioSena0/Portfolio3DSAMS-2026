<header class="w-full shadow-2xl h-40 mb-2 bg-white">

    <div class="flex flex-1 h-full justify-around items-center">

        <img src="{{ asset('img/logo.png') }}" alt="Logo Glint" class="h-12 md:h-14 w-auto object-contain max-w-[160px]">

        <ul class="flex gap-5">
            <a href="{{ route('login') }}"><x-button class="text-white font-bold">Entrar</x-button></a>
            <a href="{{ route('register') }}"><x-button class="text-white font-bold">Cadastrar-se</x-button></a>
        </ul>

    </div>

</header>
