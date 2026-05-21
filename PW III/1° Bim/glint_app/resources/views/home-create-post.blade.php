<x-side-bar>
    <div class="hidden md:block min-w-0 flex-1">
        <p class="font-semibold text-sm truncate">{{ $user->username }}</p>
        <p class="text-xs text-blue-300">{{ $user->email }}</p>
    </div>
</x-side-bar>
<x-layout>
    <div class="ml-20 md:ml-64 w-full mx-auto flex flex-1 flex-col items-stretch py-8 px-4">

        <x-create-post>
            <div class="w-10 h-10 rounded-full bg-linear-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold">
                {{ $user->username[0] }}
            </div>
            <div>
                {{ $user->username }}
            </div>
        </x-create-post>

    </div>
</x-layout>

<script src="{{ asset('js/home.js') }}"></script>
