{{-- Only show pagination if there are multiple pages --}}
@if ($paginator->hasPages())
    <nav class="mt-6 flex items-center justify-between px-4 py-2 border-t border-secondary-100">
        <span class="text-sm text-secondary-500">
            {{ __('Mostrando ') }}{{ $paginator->firstItem() }}{{ __(' a ') }}{{ $paginator->lastItem() }}{{ __(' de ') }}{{ $paginator->total() }}{{ __(' resultados') }}
        </span>

        <div class="flex space-x-1">
            {{-- Previous page link --}}
            @if ($paginator->onFirstPage())
                <a href="#" class="px-3 py-2 rounded-xl text-sm font-medium text-secondary-300 hover:text-secondary-400">
                    <x-icon name="chevron-left" class="w-4 h-4" />
                </a>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-2 rounded-xl text-sm font-medium text-secondary-600 hover:text-secondary-700 hover:bg-secondary-50">
                    <x-icon name="chevron-left" class="w-4 h-4" />
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($paginator->currentPage() == $page)
                    <a href="#" class="px-3 py-2 rounded-xl text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                        {{ $page }}
                    </a>
                @else
                    <a href="{{ $url }}"
                       class="px-3 py-2 rounded-xl text-sm font-medium text-secondary-600 hover:text-secondary-700 hover:bg-secondary-50">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next page link --}}
            @if ($paginator->hasPages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-2 rounded-xl text-sm font-medium text-secondary-600 hover:text-secondary-700 hover:bg-secondary-50">
                    <x-icon name="chevron-right" class="w-4 h-4" />
                </a>
            @else
                <a href="#" class="px-3 py-2 rounded-xl text-sm font-medium text-secondary-300 hover:text-secondary-400">
                    <x-icon name="chevron-right" class="w-4 h-4" />
                </a>
            @endif
        </div>
    </nav>
@endif