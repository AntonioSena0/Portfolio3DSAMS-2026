<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EduLibre') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-secondary-900 bg-secondary-50">
    <div class="min-h-screen">
        <nav x-data="{ open: false, scrolled: false }"
             @scroll.window="scrolled = window.scrollY > 12"
             class="fixed inset-x-0 top-0 z-50 border-b transition-all duration-300"
             :class="scrolled ? 'border-secondary-200 bg-white/95 shadow-sm backdrop-blur' : 'border-transparent bg-white/85 backdrop-blur'">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary-600 text-sm font-extrabold text-white">EL</span>
                    <span class="text-lg font-extrabold tracking-tight text-secondary-900">EduLibre</span>
                </a>

                <div class="hidden items-center gap-1 md:flex">
                    <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700' : 'text-secondary-600 hover:bg-secondary-100 hover:text-secondary-900' }}">Início</a>
                    <a href="{{ route('catalog.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('catalog.*') || request()->routeIs('subjects.show') || request()->routeIs('videos.show') ? 'bg-primary-50 text-primary-700' : 'text-secondary-600 hover:bg-secondary-100 hover:text-secondary-900' }}">Catálogo</a>
                    <a href="{{ route('teachers') }}" class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('teachers') ? 'bg-primary-50 text-primary-700' : 'text-secondary-600 hover:bg-secondary-100 hover:text-secondary-900' }}">Professores</a>
                    <a href="{{ route('about') }}" class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('about') ? 'bg-primary-50 text-primary-700' : 'text-secondary-600 hover:bg-secondary-100 hover:text-secondary-900' }}">Sobre</a>
                    <a href="{{ route('contact') }}" class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('contact') ? 'bg-primary-50 text-primary-700' : 'text-secondary-600 hover:bg-secondary-100 hover:text-secondary-900' }}">Contato</a>
                </div>

                <div class="hidden items-center gap-3 md:flex">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isProfessor() ? route('professor.dashboard') : route('student.dashboard')) }}"
                           class="rounded-lg bg-secondary-900 px-4 py-2 text-sm font-semibold text-white hover:bg-secondary-700">
                            Painel
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg px-3 py-2 text-sm font-semibold text-secondary-600 hover:bg-secondary-100">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-secondary-600 hover:bg-secondary-100">Entrar</a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Criar conta</a>
                    @endauth
                </div>

                <button type="button" @click="open = !open" class="grid h-10 w-10 place-items-center rounded-lg border border-secondary-200 text-secondary-700 md:hidden">
                    <span x-show="!open">☰</span>
                    <span x-show="open">×</span>
                </button>
            </div>

            <div x-show="open" x-transition class="border-t border-secondary-200 bg-white px-4 py-4 md:hidden">
                <div class="grid gap-2">
                    <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-secondary-700">Início</a>
                    <a href="{{ route('catalog.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-secondary-700">Catálogo</a>
                    <a href="{{ route('teachers') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-secondary-700">Professores</a>
                    <a href="{{ route('about') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-secondary-700">Sobre</a>
                    <a href="{{ route('contact') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-secondary-700">Contato</a>
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isProfessor() ? route('professor.dashboard') : route('student.dashboard')) }}" class="rounded-lg bg-secondary-900 px-3 py-2 text-sm font-semibold text-white">Painel</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-secondary-700">Entrar</a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white">Criar conta</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="pt-16">
            @yield('content')
        </main>

        <footer class="border-t border-secondary-200 bg-white">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-[1.5fr_1fr_1fr] lg:px-8">
                <div>
                    <div class="mb-3 flex items-center gap-3">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-primary-600 text-xs font-extrabold text-white">EL</span>
                        <span class="font-extrabold">EduLibre</span>
                    </div>
                    <p class="max-w-md text-sm leading-6 text-secondary-600">Ensino gratuito, aberto e organizado para alunos que precisam avançar no próprio ritmo.</p>
                </div>
                <div>
                    <h3 class="mb-3 text-sm font-bold">Navegação</h3>
                    <div class="grid gap-2 text-sm text-secondary-600">
                        <a href="{{ route('catalog.index') }}" class="hover:text-primary-700">Catálogo</a>
                        <a href="{{ route('teachers') }}" class="hover:text-primary-700">Professores</a>
                        <a href="{{ route('contact') }}" class="hover:text-primary-700">Contato</a>
                    </div>
                </div>
                <div>
                    <h3 class="mb-3 text-sm font-bold">Acesso rápido</h3>
                    <div class="grid gap-2 text-sm text-secondary-600">
                        <a href="{{ route('register') }}" class="hover:text-primary-700">Criar conta</a>
                        <a href="{{ route('login') }}" class="hover:text-primary-700">Entrar</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
