@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col h-screen fixed left-0 top-0">
        <!-- Logo -->
        <div class="px-6 py-5 border-b border-gray-200">
            <a href="/" class="font-display font-bold text-xl text-primary-600">EduLibre</a>
        </div>

        <!-- Nav links -->
        <nav class="flex-1 px-3 py-4 overflow-y-auto">
            @foreach($navItems as $item)
                <a href="{{ $item['route'] }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5
                          {{ request()->routeIs($item['active']) ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}
                          transition-colors duration-150">
                    <x-icon name="{{ $item['icon'] }}" class="w-5 h-5 flex-shrink-0" />
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- User profile -->
        <div class="px-4 py-4 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary-600 text-xs font-bold text-white">
                    {{ collect(explode(' ', auth()->user()->name))->map(fn($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <main class="flex-1 pl-64 pb-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>
</div>
@endsection
