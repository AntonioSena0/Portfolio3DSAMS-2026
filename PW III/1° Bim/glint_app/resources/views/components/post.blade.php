<section class="space-y-4 w-full max-w-xl ">
  <article class="bg-white w-full rounded-2xl shadow-sm overflow-hidden">
    <header class="flex items-center justify-between px-4 py-3">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-full bg-linear-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
          {{ $post->user->username[0] }}
        </div>
        <div>
          <a href="{{ route('users.profile', $post->user->id) }}" class="text-sm font-semibold text-gray-900 hover:text-blue-600">
            {{ $post->user->username }}
          </a>
          <p class="text-xs text-gray-500">{{ $post->created_at->format('d/m/y H:i') }}</p>
        </div>
      </div>

      <div class="flex items-center space-x-2">
        @if(auth()->check() && auth()->user()->id !== $post->user_id)
        @php
          $isFollowing = auth()->user()->following()->where('followed_id', $post->user_id)->exists();
        @endphp
        <form method="POST" action="{{ $isFollowing ? route('users.unfollow', $post->user_id) : route('users.follow', $post->user_id) }}" data-follow-form-mini data-follow-user-id="{{ $post->user_id }}" class="inline-block">
          @csrf
          @if($isFollowing)
          @method('DELETE')
          @endif
          <button type="submit" class="px-3 py-1 text-xs rounded-full {{ $isFollowing ? 'cursor-pointer border border-gray-300 hover:bg-gray-50' : 'cursor-pointer bg-blue-600 text-white hover:bg-blue-700' }} font-semibold">
            {{ $isFollowing ? 'Seguindo' : 'Seguir' }}
          </button>
        </form>
        @endif

        @if(auth()->check() && auth()->user()->id === $post->user_id)
        <button type="button" class="cursor-pointer px-3 py-1 text-xs rounded-full border border-red-300 text-red-600 hover:bg-red-50 font-semibold js-open-modal" data-modal-target="deletePostModal-{{ $post->id }}">Excluir</button>
        @endif
      </div>
    </header>

    <main class="p-5 flex flex-col gap-4">
      <div class="w-full">
        <p class="text-xl text-gray-900 wrap-break-words">{{ $post->subject }}</p>
      </div>

      @if($post->image != null)
      <div class="w-full h-72 overflow-hidden rounded-xl border-4 border-blue-800 bg-gray-100">
        <img class="h-full w-full object-cover" src="{{ asset('storage/' . $post->image) }}" alt="">
      </div>
      @endif
    </main>

    <hr>

    @php
      $liked = auth()->user()->likedPosts->contains($post->id)
    @endphp
    <form data-post-id="{{ $post->id }}" class="like-form px-4 py-3 space-y-2">
      @csrf
      <div class="flex items-center space-x-4 text-xl">
        <button type="submit" class="like-btn {{ $liked ? 'text-red-500' : ''}} cursor-pointer transition" data-liked="{{ $liked ? 'true' : 'false'}}">
          <i class="bi {{ $liked ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        </button>
        <button type="button" class="js-open-modal text-gray-700 cursor-pointer transition" data-modal-target="commentModal-{{ $post->id }}">
          <i class="bi bi-chat"></i>
        </button>
      </div>
      <p class="text-xs text-gray-500">
        <span class="like-count" data-likes="{{ $post->likedBy()->count() }}">
          {{ $post->likedBy()->count() }}
        </span> curtidas •
        <span class="comment-count" data-post-id="{{ $post->id }}" data-comments="{{ $post->comments->count() }}">
          {{ $post->comments->count() }}
        </span> comentários
      </p>
    </form>

    <form id="delete-post-form-{{ $post->id }}" method="POST" action="{{ route('posts.delete', $post) }}" style="display: none;">
      @csrf
      @method('DELETE')
    </form>

    <x-modal id="commentModal-{{ $post->id }}" title="Comentários">
      <form action="{{ route('comments.create', $post) }}" method="POST" class="pt-3 comment-form" data-post-id="{{ $post->id }}">
        @csrf
        <div class="flex flex-col gap-4 mb-4">
          <textarea name="body" placeholder="Escreva seu comentário..." class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2"></textarea>
          <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 cursor-pointer bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">Comentar</button>
          </div>
        </div>
      </form>

      <div class="max-h-[300px] overflow-y-auto space-y-3">
        @forelse($post->comments as $comment)
        <x-comment :comment="$comment" />
        @empty
        <p class="text-sm text-gray-500 no-comments-msg">Nenhum comentário ainda.</p>
        @endforelse
      </div>
    </x-modal>

    <x-modal id="deletePostModal-{{ $post->id }}" title="Confirmar Exclusão">
      <div class="text-center py-4">
        <div class="mb-4">
          <i class="bi bi-exclamation-triangle text-4xl text-red-600"></i>
        </div>
        <p class="text-gray-700 mb-6">Tem certeza que deseja excluir este post?</p>
        <div class="flex justify-center space-x-4">
          <button type="button" class="js-close-modal px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">Cancelar</button>
          <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold" onclick="document.getElementById('delete-post-form-{{ $post->id }}').submit();">Confirmar Exclusão</button>
        </div>
      </div>
    </x-modal>
  </article>
</section>

<script src="{{ asset('js/follow.js') }}"></script>
