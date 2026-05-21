@props(['searchPosts' => collect(), 'searchUsers' => collect(), 'term' => null])

<section id="section-search" class="space-y-6 w-full max-w-5xl mx-auto">
    <div class="w-full max-w-2xl mx-auto space-y-6">
        <header class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-blue-900">Buscar</h1>
            <span class="text-xs text-gray-500">Explore pessoas e posts</span>
        </header>

        <form
            method="GET"
            action="{{ route('home.search') }}"
            class="flex items-center bg-white rounded-full shadow-sm px-4 py-2 space-x-3 max-w-xl"
        >
            <i class="bi bi-search text-gray-400"></i>
            <input
                id="search-input"
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Pesquisar"
                class="flex-1 border-0 focus:ring-0 focus:outline-none text-sm text-gray-700"
            >
        </form>

        @if(!empty($term))
            <p class="text-xs text-gray-500">
                Resultados para: <span class="font-semibold">{{ $term }}</span>
            </p>
        @endif

        @if($searchUsers->isNotEmpty())
            <section class="space-y-2 mt-4">
                <h2 class="text-sm font-semibold text-gray-700">Contas</h2>

                @foreach($searchUsers as $user)
                    <x-account-mini :user="$user" />
                @endforeach
            </section>
        @endif

        <section class="space-y-3 mt-4">
            @forelse($searchPosts as $post)
                <h2 class="text-sm font-semibold text-gray-700">Posts</h2>
                <x-post :post="$post" :user="$post->user" />
            @empty
                @if($term)
                    <p class="text-sm text-gray-500">Nenhum post encontrado.</p>
                @endif
            @endforelse
        </section>
    </div>
</section>
