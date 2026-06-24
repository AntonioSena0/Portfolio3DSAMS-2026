<x-app-layout>
    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase text-[#B20000]">Administrativo</p>
            <h1 class="mt-2 text-4xl font-bold text-[#313131]">Gerenciar eventos</h1>
            <p class="mt-4 max-w-2xl text-[#5A5A5A]">Crie, edite e remova eventos do sistema.</p>
        </div>
    </section>

    <section class="py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 border-l-4 border-green-600 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('admin.events.create') }}" class="inline-block bg-[#B20000] px-5 py-3 text-sm font-bold uppercase text-white transition hover:bg-[#8F0000]">+ Novo evento</a>
            </div>

            <div class="overflow-x-auto bg-white shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-[#DDD] bg-[#F5F5F5]">
                            <th class="px-4 py-3 font-bold text-[#313131]">Título</th>
                            <th class="px-4 py-3 font-bold text-[#313131]">Data</th>
                            <th class="px-4 py-3 font-bold text-[#313131]">Categoria</th>
                            <th class="px-4 py-3 font-bold text-[#313131]">Criação</th>
                            <th class="px-4 py-3 font-bold text-[#313131]">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            <tr class="border-b border-[#DDD]">
                                <td class="px-4 py-3 font-medium text-[#313131]">{{ $event->title }}</td>
                                <td class="px-4 py-3 text-[#5A5A5A]">{{ $event->event_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-[#5A5A5A]">{{ $event->category ?? '—' }}</td>
                                <td class="px-4 py-3 text-[#5A5A5A]">{{ $event->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="font-bold text-[#B20000] hover:underline">Editar</a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este evento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-3 font-bold text-[#5A5A5A] hover:text-[#B20000]">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-[#5A5A5A]">Nenhum evento cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $events->links() }}
            </div>
        </div>
    </section>
</x-app-layout>
