<section id="section-create" class="space-y-6 w-full max-w-5xl mx-auto">
    <div class="w-full max-w-xl mx-auto space-y-6">
        <header class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-blue-900">Criar postagem</h1>
        </header>

        <form method="post" enctype="multipart/form-data" action="{{ route('posts.create') }}" class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
            @csrf
            <div class="flex items-center space-x-3">
                {{ $slot }}
            </div>

            <div>
                <textarea
                    name="subject"
                    id="publish-textarea"
                    rows="4"
                    class="w-full p-2 border rounded-xl focus:ring-0 text-sm text-gray-900 placeholder-gray-400 resize-none"
                    placeholder="O que você está pensando hoje?"
                ></textarea>
                <x-form-error field="subject" />
            </div>

            <div id="file-cont" class="border border-dashed border-gray-300 rounded-2xl p-4 flex flex-col items-center justify-center text-center space-y-2 bg-gray-50">
                <i class="bi bi-image text-4xl text-blue-500"></i>
                <p class="text-xs text-gray-600">
                    Escolha uma imagem para acompanhar suas ideias
                </p>

                <label class="inline-flex items-center px-3 py-2 rounded-full bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 cursor-pointer transition">
                    Escolher arquivo
                    <input
                        id="publish-image"
                        type="file"
                        name="image"
                        accept="image/jpeg,image/jpg,image/png"
                        class="hidden"
                    >
                </label>
                <x-form-error field="image" />
            </div>

            <div class="mt-2 flex flex-col items-center gap-3 justify-center">
                <button
                    type="button"
                    id="publish-image-remove"
                    class="cursor-pointer px-3 py-1.5 rounded-full bg-blue-500 hover:bg-blue-800 text-white text-xs font-semibold hidden"
                >
                    Remover
                </button>
                <div id="publish-image-preview-wrap" class="hidden w-full h-72 overflow-hidden rounded-xl border-4 border-blue-800 bg-gray-100">
                    <img
                        id="publish-image-preview"
                        src=""
                        alt=""
                        class="h-full w-full object-cover"
                    >
                </div>
            </div>

            <div class="flex flex-col items-end pt-2 space-y-2">
                <x-form-error />
                <input
                    type="submit"
                    value="Publicar"
                    id="publish-button"
                    class="px-5 py-2 rounded-full bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 cursor-pointer transition"
                >
            </div>
        </form>
    </div>
</section>
