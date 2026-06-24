<header class="sticky top-0 z-50 bg-white shadow-sm" x-data="{ open: false }">
    <div class="border-b border-[#DDD] bg-white">
        <div class="mx-auto flex h-11 max-w-7xl items-center justify-between px-4 text-xs text-[#5A5A5A] sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="font-bold uppercase tracking-wide text-[#313131]">Governo do Estado de São Paulo</a>
            <div class="hidden items-center gap-5 sm:flex">
                <a target="_blank" href="https://www.instagram.com/eteczonalesteoficial/" class="transition hover:text-[#B20000]">Instagram</a>
                <a target="_blank" href="https://www.youtube.com/@etecdazonaleste2949" class="transition hover:text-[#B20000]">YouTube</a>
            </div>
        </div>
    </div>

    <nav class="bg-[#B20000] text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <x-application-logo></x-application-logo>
                    <span class="text-lg font-bold leading-tight">ETEC Zona Leste</span>
                </a>

                <button type="button" class="inline-flex h-10 w-10 items-center justify-center border border-white/40 md:hidden" x-on:click="open = ! open" x-bind:aria-expanded="open.toString()" aria-label="Abrir menu">
                    <svg x-show="! open" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="square" />
                    </svg>
                    <svg x-cloak x-show="open" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 6l12 12M18 6L6 18" stroke-linecap="square" />
                    </svg>
                </button>

                <div class="hidden items-center gap-8 text-sm font-bold uppercase md:flex">
                    <a href="{{ route('home') }}" class="border-b-2 border-transparent py-5 transition hover:border-[#00C1CF]">Início</a>
                    <a href="{{ route('cursos') }}" class="border-b-2 border-transparent py-5 transition hover:border-[#00C1CF]">Cursos</a>
                    <a href="{{ route('eventos') }}" class="border-b-2 border-transparent py-5 transition hover:border-[#00C1CF]">Eventos</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="border border-white px-3 py-2 transition hover:bg-white hover:text-[#B20000]">Área interna</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="border border-white/60 px-3 py-2 text-xs transition hover:bg-white hover:text-[#B20000]">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="border border-white px-3 py-2 transition hover:bg-white hover:text-[#B20000]">Entrar</a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="border-t border-white/20 md:hidden" x-cloak x-show="open" x-transition>
            <div class="mx-auto grid max-w-7xl gap-1 px-4 py-3 text-sm font-bold uppercase">
                <a href="{{ route('home') }}" class="py-3">Início</a>
                <a href="{{ route('cursos') }}" class="py-3">Cursos</a>
                <a href="{{ route('eventos') }}" class="py-3">Eventos</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="py-3">Área interna</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="py-3 text-left">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="py-3">Entrar</a>
                @endauth
            </div>
        </div>
    </nav>
</header>
