<header class="bg-[#B20000] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}">
                    <span class="text-xl font-bold">ETEC Zona Leste</span>
                </a>
            </div>

            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    Início
                </x-nav-link>
                <x-nav-link :href="route('cursos')" :active="request()->routeIs('cursos')">
                    Cursos
                </x-nav-link>
                <x-nav-link :href="route('eventos')" :active="request()->routeIs('eventos')">
                    Eventos
                </x-nav-link>
                <!-- Additional links if needed -->
            </div>
        </div>
    </div>
</header>
