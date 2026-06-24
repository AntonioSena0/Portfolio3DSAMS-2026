<x-app-layout>
    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto mb-10 max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase text-[#B20000]">Área interna</p>
            <h1 class="mt-2 text-4xl font-bold text-[#313131]">Painel administrativo</h1>
            <p class="mt-4 max-w-2xl text-[#5A5A5A]">Você está autenticado no ambiente da ETEC Zona Leste.</p>
        </div>
        <div>
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-6 border-l-4 border-green-600 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white px-6 shadow-sm">
                    @forelse ($events as $event)
                        <x-event-item :event="$event" />
                    @empty
                        <p class="py-8 text-center text-sm text-[#5A5A5A]">Nenhum evento cadastrado no momento.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
