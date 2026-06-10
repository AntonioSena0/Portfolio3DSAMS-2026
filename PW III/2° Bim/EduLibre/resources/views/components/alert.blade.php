@if (!empty($message))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 5000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="px-4 py-3 rounded-xl {{ $type === 'success' ? 'bg-emerald-50 text-emerald-700' : $type === 'error' ? 'bg-red-50 text-red-700' : $type === 'warning' ? 'bg-amber-50 text-amber-700' : 'bg-info-50 text-info-700' }} flex items-center gap-3">
        <div class="flex-shrink-0">
            @if ($type === 'success')
                <x-icon name="check" class="w-5 h-5" />
            @elseif ($type === 'error')
                <x-icon name="x" class="w-5 h-5" />
            @elseif ($type === 'warning')
                <x-icon name="alert-triangle" class="w-5 h-5" />
            @else
                <x-icon name="info" class="w-5 h-5" />
            @endif
        </div>
        <div>
            <p class="text-sm font-medium">{{ $message }}</p>
        </div>
    </div>
@endif