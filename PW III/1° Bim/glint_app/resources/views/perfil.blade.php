<x-layout>
  <div class="max-w-4xl mx-auto py-8 px-4 space-y-6">
    <div class="flex items-center mb-2">
      <a href="{{ route('home.posts') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800">
        <i class="bi bi-arrow-left mr-1"></i>
        Voltar
      </a>
    </div>

    <section class="bg-white rounded-2xl shadow-sm p-6">
      <div class="flex items-start space-x-6">
        <div class="w-24 h-24 rounded-full bg-linear-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold">
          {{ $user->username[0] }}
        </div>

        <div class="flex-1 space-y-3">
          <div class="flex items-center flex-wrap gap-3">
            <h1 class="text-xl font-bold text-gray-900">{{ $user->username }}</h1>

            @if($isOwnProfile)
            <button type="button" class="js-open-modal cursor-pointer px-4 py-1.5 rounded-full border border-gray-300 text-sm font-semibold hover:bg-gray-50" data-modal-target="editProfileModal">
              Editar perfil
            </button>

            <form method="POST" action="{{ route('auth.logout') }}">
              @csrf
              <button type="submit" class="cursor-pointer px-4 py-1.5 rounded-full border border-red-500 text-sm font-semibold text-red-600 hover:bg-red-50">
                Sair
              </button>
            </form>

            <button type="button" class="cursor-pointer js-open-modal px-4 py-1.5 rounded-full text-sm font-semibold bg-red-600 text-white hover:bg-red-700" data-modal-target="deleteAccountModal">
              Excluir Conta
            </button>

            <form id="delete-account-form" method="POST" action="{{ route('users.delete') }}" style="display: none;">
              @csrf
              @method('DELETE')
            </form>
            @elseif(auth()->check())
            <form method="POST" action="{{ $isFollowing ? route('users.unfollow', $user) : route('users.follow', $user) }}" data-follow-form data-follow-user-id="{{ $user->id }}" class="inline-block">
              @csrf
              @if($isFollowing)
              @method('DELETE')
              @endif
              <button type="submit" class="px-4 py-1.5 rounded-full {{ $isFollowing ? 'border border-gray-300 hover:bg-gray-50' : 'bg-blue-600 text-white hover:bg-blue-700' }} text-sm font-semibold">
                {{ $isFollowing ? 'Seguindo' : 'Seguir' }}
              </button>
            </form>
            @endif
          </div>

          <div class="flex items-center space-x-6 text-sm">
            <span><span class="font-semibold">{{ $user->posts->count() }}</span> publicações</span>
            <span><span class="font-semibold">{{ $user->followers->count() }}</span> seguidores</span>
            <span><span class="font-semibold">{{ $user->following->count() }}</span> seguindo</span>
          </div>

          <p class="text-sm text-gray-800">{{ $user->bio }}</p>
        </div>
      </div>
    </section>

    <section class="bg-white rounded-2xl shadow-sm">
      <div id="profileTabs" class="border-b border-gray-200 flex items-center justify-center space-x-10 text-xs font-semibold text-gray-500">
        <button class="tab-btn py-3 flex items-center space-x-1 border-b-2 border-blue-600 text-blue-600" data-tab-target="tab-publicacoes">
          <i class="bi bi-grid-3x3-gap"></i>
          <span>PUBLICAÇÕES</span>
        </button>
      </div>

      <div id="tab-publicacoes" class="tab-panel">
        <div class="grid grid-cols-5 gap-1 md:gap-2 p-2 md:p-3">
          @foreach ($user->posts as $post)
          <div class="h-40 w-40 flex border-2 border-blue-900 text-center rounded-xl hover:shadow-2xl hover:-translate-y-0.5 cursor-pointer transition-all">
            @if($post->image != null)
            <img class="object-cover rounded-xl" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->subject }}">
            @else
            @php
              $limit = 100;
              $res = (strlen($post->subject) > $limit) ? substr($post->subject, 0, $limit) . '...' : $post->subject
            @endphp
            <h1 class="p-2 text-xm opacity-80 font-semibold">{{ $res }}</h1>
            @endif
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <x-modal id="editProfileModal" title="Editar perfil">
      <form method="POST" action="{{ route('users.update') }}" class="space-y-4">
        @csrf
        @method('PATCH')

        <div class="space-y-1">
          <label class="text-xs font-semibold text-gray-700" for="username">Username</label>
          <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <x-form-error field="username" />
        </div>

        <div class="space-y-1">
          <label class="text-xs font-semibold text-gray-700" for="bio">Bio</label>
          <textarea id="bio" name="bio" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio', $user->bio) }}</textarea>
          <x-form-error field="bio" />
        </div>

        <div class="flex flex-1 items-center justify-end gap-5 space-y-2 pt-2">
          <x-form-error />
          <button type="button" class="js-close-modal px-4 py-2 cursor-pointer rounded-lg border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded-lg cursor-pointer bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700">Salvar</button>
        </div>
      </form>
    </x-modal>

    <x-modal id="deleteAccountModal" title="Confirmar Exclusão de Conta">
      <div class="text-center py-4">
        <div class="mb-4">
          <i class="bi bi-exclamation-triangle text-4xl text-red-600"></i>
        </div>
        <p class="text-gray-700 mb-6">Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita e todos os seus dados serão permanentemente removidos.</p>
        <div class="flex justify-center space-x-4">
          <button type="button" class="js-close-modal px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">Cancelar</button>
          <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold" onclick="document.getElementById('delete-account-form').submit();">
            Confirmar Exclusão
          </button>
        </div>
      </div>
    </x-modal>
  </div>

  <script src="{{ asset('js/follow.js') }}"></script>
  <script src="{{ asset('js/modal.js') }}"></script>
</x-layout>
