<aside class="w-20 md:w-64 bg-linear-to-b from-blue-900 to-blue-800 text-white shadow-2xl border-r border-blue-700/50 flex flex-col h-screen fixed left-0 top-0 z-40">

    <div class="p-6 border-b border-blue-800/50">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Glint" class="h-8 w-auto max-h-8 object-contain">
            </div>
            <span class="text-xl font-bold bg-white bg-clip-text text-transparent hidden md:inline">Glint</span>
        </div>
    </div>

    <nav class="flex-1 py-8 px-2 space-y-1 md:px-4">
        <form method="get" action="{{ route('home.posts') }}">
            @csrf
            <button type="submit" class="w-full cursor-pointer group flex items-center space-x-3 md:space-x-4 p-3 rounded-2xl transition-all duration-200 hover:bg-blue-700/50 hover:backdrop-blur-sm hover:shadow-lg hover:translate-x-1 hover:scale-[1.02]">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-600/30 group-hover:bg-blue-500 rounded-xl flex items-center justify-center transition-all duration-200 shadow-md">
                    <i class="bi bi-house-door-fill text-xl md:text-2xl text-blue-300 group-hover:text-white"></i>
                </div>
                <span class="font-semibold text-sm md:text-base hidden md:inline">Início</span>
            </button>

        </form>

        <form method="get" action="{{ route('home.search') }}">
            @csrf

            <button type="submit" class="w-full cursor-pointer group flex items-center space-x-3 md:space-x-4 p-3 rounded-2xl transition-all duration-200 hover:bg-blue-700/50 hover:backdrop-blur-sm hover:shadow-lg hover:translate-x-1 hover:scale-[1.02]">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-600/30 group-hover:bg-blue-500 rounded-xl flex items-center justify-center transition-all duration-200 shadow-md relative">
                    <i class="bi bi-search text-xl md:text-2xl text-blue-300 group-hover:text-white"></i>
                </div>
                <span class="font-semibold text-sm md:text-base hidden md:inline">Buscar</span>
            </button>

        </form>

        <form method="get" action="{{ route('home.posts.create') }}">

            <button type="submit" class="w-full cursor-pointer group flex items-center space-x-3 md:space-x-4 p-3 rounded-2xl transition-all duration-200 hover:bg-green-600/80 hover:shadow-lg hover:translate-x-1 hover:scale-[1.02] bg-linear-to-r hover:from-green-500 hover:to-emerald-500">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-600/30 group-hover:bg-white rounded-xl flex items-center justify-center transition-all duration-200 shadow-lg">
                    <i class="bi bi-plus-circle-fill text-xl md:text-2xl text-green-600"></i>
                </div>
                <span class="font-bold text-sm md:text-base hidden md:inline whitespace-nowrap">Postar</span>
            </button>

        </form>

    </nav>

    <div class="p-4 border-t border-blue-800/50 space-y-3">
        <a href="{{ route('perfil') }}" class="flex items-center space-x-3 p-3 rounded-2xl hover:bg-blue-700/50 transition-all duration-200 group">
            <div class="w-10 h-10 bg-linear-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="bi bi-person-circle text-lg text-white"></i>
            </div>
            {{ $slot }}
            <i class="bi bi-chevron-right text-blue-400 group-hover:text-white hidden md:block"></i>
        </a>

        <form method="POST" action="{{ route('auth.logout') }}">
            @csrf
            <button type="submit" class="cursor-pointer flex items-center justify-center p-3 w-full rounded-2xl hover:bg-red-600/50 transition-all duration-200 group">
                <i class="bi bi-box-arrow-right text-xl text-blue-300 group-hover:text-red-400"></i>
                <span class="font-semibold text-xs hidden md:inline ml-3">Sair</span>
            </button>
        </form>

    </div>
</aside>
