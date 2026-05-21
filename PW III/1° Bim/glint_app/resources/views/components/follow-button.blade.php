@props(['user', 'size' => 'small'])

@if(auth()->check() && auth()->user()->id !== $user->id)
    @php
        $isFollowing = auth()->user()->following()->where('followed_id', $user->id)->exists();
    @endphp
    <form method="POST" action="{{ $isFollowing ? route('users.unfollow', $user) : route('users.follow', $user) }}"
          data-follow-form class="inline-block"
          data-follow-user-id="{{ $user->id }}">
        @csrf
        @if($isFollowing)
            @method('DELETE')
            <button type="submit"
                    class="{{ $size === 'small' ? 'px-3 py-1 text-xs' : 'px-4 py-1.5 text-sm' }}
                           rounded-full border border-gray-300 hover:bg-gray-50 font-semibold">
                Seguindo
            </button>
        @else
            <button type="submit"
                    class="{{ $size === 'small' ? 'px-3 py-1 text-xs' : 'px-4 py-1.5 text-sm' }}
                           rounded-full bg-blue-600 text-white hover:bg-blue-700 font-semibold">
                Seguir
            </button>
        @endif
    </form>
@endif