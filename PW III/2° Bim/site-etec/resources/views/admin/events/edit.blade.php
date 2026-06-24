    <x-app-layout>
    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase text-[#B20000]">Administrativo</p>
            <h1 class="mt-2 text-4xl font-bold text-[#313131]">Editar evento</h1>
        </div>
    </section>

    <section class="py-14">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.events.update', $event) }}" method="POST" class="bg-white p-8 shadow-sm">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-bold text-[#313131]">Título</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                        @error('title') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-bold text-[#313131]">Slug (URL)</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $event->slug) }}" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                        @error('slug') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="event_date" class="block text-sm font-bold text-[#313131]">Data do evento</label>
                        <input type="date" name="event_date" id="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                        @error('event_date') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-bold text-[#313131]">Categoria</label>
                        <select name="category" id="category"
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                            <option value="">Selecione uma categoria</option>
                            <option value="destaque" @selected(old('category', $event->category) == 'destaque')>Destaque</option>
                            <option value="noticia" @selected(old('category', $event->category) == 'noticia')>Notícia</option>
                            <option value="evento" @selected(old('category', $event->category) == 'evento')>Evento</option>
                            <option value="comunicado" @selected(old('category', $event->category) == 'comunicado')>Comunicado</option>
                        </select>
                        @error('category') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="target_grade" class="block text-sm font-bold text-[#313131]">Série destino <span class="font-normal text-[#5A5A5A]">(opcional)</span></label>
                        <select name="target_grade" id="target_grade"
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                            <option value="">Todas as séries</option>
                            <option value="1" @selected(old('target_grade', $event->target_grade) == '1')>1º Ano</option>
                            <option value="2" @selected(old('target_grade', $event->target_grade) == '2')>2º Ano</option>
                            <option value="3" @selected(old('target_grade', $event->target_grade) == '3')>3º Ano</option>
                        </select>
                        @error('target_grade') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="image_url" class="block text-sm font-bold text-[#313131]">URL da imagem</label>
                        <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $event->image_url) }}"
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                        @error('image_url') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="excerpt" class="block text-sm font-bold text-[#313131]">Resumo</label>
                        <textarea name="excerpt" id="excerpt" rows="3"
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">{{ old('excerpt', $event->excerpt) }}</textarea>
                        @error('excerpt') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-bold text-[#313131]">Conteúdo</label>
                        <textarea name="content" id="content" rows="10" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">{{ old('content', $event->content) }}</textarea>
                        @error('content') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-4">
                    <button type="submit" class="bg-[#B20000] px-5 py-3 text-sm font-bold uppercase text-white transition hover:bg-[#8F0000]">Atualizar</button>
                    <a href="{{ route('admin.events') }}" class="text-sm font-bold text-[#5A5A5A] hover:text-[#B20000]">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
