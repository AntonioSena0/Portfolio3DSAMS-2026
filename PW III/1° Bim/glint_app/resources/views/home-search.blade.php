<x-side-bar>
    <div class="hidden md:block min-w-0 flex-1">
        <p class="font-semibold text-sm truncate">{{ $user->username }}</p>
        <p class="text-xs text-blue-300">{{ $user->email }}</p>
    </div>
</x-side-bar>
<x-layout>
    <div class="ml-20 md:ml-64 w-full mx-auto flex flex-1 flex-col items-stretch py-8 px-4">

        <x-search :searchUsers="$searchUsers" :search-posts="$searchPosts" :term="$term"></x-search>

    </div>
</x-layout>

<script src="{{ asset('js/like.js') }}"></script>
<script src="{{ asset('js/comment.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/follow.js') }}"></script>
