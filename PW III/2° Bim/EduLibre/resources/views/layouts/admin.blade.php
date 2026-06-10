@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-60 bg-white border-r border-gray-200 flex flex-col h-screen fixed left-0 top-0">
        <!-- Logo -->
        <div class="px-5 py-4 border-b border-gray-200">
            <a href="/" class="font-display font-bold text-xl text-primary-600">EduLibre Admin</a>
        </div>

        <!-- Nav links -->
        <nav class="flex-1 px-3 py-4 overflow-y-auto">
            @foreach($navItems as $item)
                <a href="{{ $item['route'] }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium mb-0.5
                          {{ request()->routeIs($item['active']) ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}
                          transition-colors duration-150">
                    <x-icon name="{{ $item['icon'] }}" class="w-4 h-4 flex-shrink-0" />
                    <span class="ml-2">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 pl-60 pb-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm text-gray-500">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center">
                            <x-icon name="home" class="w-4 h-4" />
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1">{{ $breadcrumb ?? '' }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{ $slot }}
        </div>
    </main>
</div>
@endsection
