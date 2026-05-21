<a
    href="{{ route('users.profile', $user->id) }}"
    class="flex max-w-xl items-center justify-between bg-white rounded-xl px-3 py-2 shadow-sm hover:shadow-md transition cursor-pointer"
>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-linear-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
            {{ strtoupper($user->username[0]) }}
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-gray-900 hover:text-blue-600">{{ $user->username }}</span>
            @if(!empty($user->name))
                <span class="text-xs text-gray-500">{{ $user->name }}</span>
            @endif
        </div>
    </div>

    <div>

        @if($user->posts()->count())
            <span class="text-[11px] text-gray-400 mr-2">
                {{ $user->posts()->count() }} posts
            </span>
        @endif

        @if(auth()->check() && auth()->user()->id !== $user->id)
            @php
                $isFollowing = auth()->user()->following()->where('followed_id', $user->id)->exists();
            @endphp
            <form method="POST" action="{{ $isFollowing ? route('users.unfollow', $user->id) : route('users.follow', $user->id) }}"
                data-follow-form-mini-list data-follow-user-id="{{ $user->id }}" class="inline-block">
                @csrf
                @if($isFollowing)
                    @method('DELETE')
                @endif
                <button type="submit"
                        class="ml-2 px-3 py-1 text-xs rounded-full {{ $isFollowing ? 'border border-gray-300 hover:bg-gray-50' : 'bg-blue-600 text-white hover:bg-blue-700' }} font-semibold">
                    {{ $isFollowing ? 'Seguindo' : 'Seguir' }}
                </button>
            </form>
        @endif

    </div>

</a>
