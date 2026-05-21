<div
    id="{{ $id }}"
    class="js-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/40"
>
    <div class="bg-white rounded-2xl shadow-lg w-[50%] max-w-2xl max-h-[90vh] mx-4 flex flex-col">
        <div class="flex items-center justify-between px-5 py-3 border-b shrink-0">
            <h2 class="text-base font-semibold text-gray-900">{{ $title }}</h2>
            <button
                type="button"
                class="js-close-modal text-gray-400 hover:text-gray-600 cursor-pointer"
            >
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <div class="px-5 py-4 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>
