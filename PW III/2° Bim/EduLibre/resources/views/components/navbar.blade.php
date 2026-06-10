<nav x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = window.scrollY > 20"
     :class="scrolled ? 'bg-white/95 backdrop-blur shadow-sm' : 'bg-transparent'"
     class="fixed w-full top-0 z-50 transition-all duration-300">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="font-display font-bold text-xl text-primary-600">
                        EduLibre
                    </a>
                </div>

                <!-- Desktop menu -->
                <div class="hidden md:ml-10 md:flex md:items-center md:space-x-8">
                    @auth
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('student.dashboard') }}"
                               class="text-sm font-medium text-gray-500 hover:text-gray-900">
                                Dashboard
                            </a>
                            <a href="{{ route('catalog.index') }}"
                               class="text-sm font-medium text-gray-500 hover:text-gray-900">
                                Catálogo
                            </a>
                            <a href="{{ route('teachers') }}"
                               class="text-sm font-medium text-gray-500 hover:text-gray-900">
                                Professores
                            </a>
                            @if(auth()->user()->isProfessor() && auth()->user()->isActive())
                                <a href="{{ route('professor.dashboard') }}"
                                   class="text-sm font-medium text-gray-500 hover:text-gray-900">
                                    Professor
                                </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                   class="text-sm font-medium text-gray-500 hover:text-gray-900">
                                    Admin
                                </a>
                            @endif
                        </div>

                        <!-- User avatar and dropdown -->
                        <div class="relative">
                            <button @click="open = !open"
                                    class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 focus:outline-none">
                                <span class="grid h-8 w-8 place-items-center rounded-lg bg-primary-600 text-xs font-bold text-white">
                                    {{ collect(explode(' ', auth()->user()->name))->map(fn($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                                </span>
                                <span class="ml-2">{{ auth()->user()->name }}</span>
                                <svg class="ml-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <x-show.open>
                                <div class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                     x-show="open"
                                     x-transition:
                                     enter="transition ease-out duration-100"
                                     enter-from="transform opacity-0 scale-95"
                                     enter-to="transform opacity-100 scale-100"
                                     leave="transition ease-in duration-75"
                                     leave-from="transform opacity-100 scale-100"
                                     leave-to="transform opacity-0 scale-95">
                                    <div class="block px-4 py-2 text-sm text-gray-700"
                                           @click.prevent
                                           @click="open = false"
                                           href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Sair
                                    </div>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </x-show.open>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}"
                               class="text-sm font-medium text-gray-500 hover:text-gray-900">
                                Entrar
                            </a>
                            <a href="{{ route('register') }}"
                               class="text-sm font-medium text-gray-500 hover:text-gray-900 bg-primary-50 px-3 py-1.5 rounded-md text-primary-600 hover:bg-primary-100">
                                Registrar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="open = !open"
                        class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden" :class="{'hidden': !open, 'block': open}"
         x-show="open"
         x-transition>
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <nav class="px-3 pt-2 pb-2 space-y-1">
                    <a href="{{ route('student.dashboard') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                        Dashboard
                    </a>
                    <a href="{{ route('catalog.index') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                        Catálogo
                    </a>
                    <a href="{{ route('teachers') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                        Professores
                    </a>
                    @if(auth()->user()->isProfessor() && auth()->user()->isActive())
                        <a href="{{ route('professor.dashboard') }}"
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                            Professor
                        </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                            Admin
                        </a>
                    @endif
                </nav>

                <!-- User info and logout -->
                <div class="px-3 pt-4 pb-2 border-t border-gray-200">
                    <div class="flex items-center">
                        <span class="grid h-10 w-10 place-items-center rounded-lg bg-primary-600 text-xs font-bold text-white">
                            {{ collect(explode(' ', auth()->user()->name))->map(fn($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                        </span>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        <button @click="open = false"
                                @click.prevent="document.getElementById('logout-form-mobile').submit()"
                                class="w-full text-left text-sm font-medium text-gray-500 hover:text-gray-900">
                            Sair
                        </button>
                    </div>
                </div>
            @else
                <nav class="px-3 pt-2 pb-2 space-y-1">
                    <a href="{{ route('login') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 bg-primary-50 px-3 py-1.5 rounded-md text-primary-600 hover:bg-primary-100">
                        Registrar
                    </a>
                </nav>
            @endauth
        </div>
    </div>
</nav>
