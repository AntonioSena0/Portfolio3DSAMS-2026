<x-app-layout>
    <section class="bg-[#B20000] text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-20">
            <div>
                <p class="text-sm font-bold uppercase text-white/75">Centro Paula Souza</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-bold leading-tight md:text-5xl">ETEC Zona Leste</h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-white/85">Formação técnica pública com base prática, conexão com o território e foco em empregabilidade.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('cursos') }}" class="bg-white px-5 py-3 text-sm font-bold uppercase text-[#B20000] transition hover:bg-[#F5F5F5]">Conhecer cursos</a>
                    <a href="{{ route('eventos') }}" class="border border-white px-5 py-3 text-sm font-bold uppercase text-white transition hover:bg-white hover:text-[#B20000]">Ver eventos</a>
                </div>
            </div>
            <div class="grid content-end border-l border-white/30 pl-6">
                <p class="text-6xl font-bold text-white/20">ZL</p>
                <p class="mt-4 max-w-sm text-sm leading-6 text-white/80">Uma escola técnica feita para transformar repertório em projeto, projeto em portfólio e portfólio em oportunidade.</p>
            </div>
        </div>
    </section>

    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-sm font-bold uppercase text-[#B20000]">Infraestrutura</p>
                <h2 class="mt-2 text-3xl font-bold text-[#313131]">Ambientes para aprender fazendo</h2>
            </div>
            <div class="mt-9 grid gap-5 md:grid-cols-4">
                @foreach (['Laboratórios técnicos', 'Biblioteca e estudo', 'Auditório', 'Ambientes colaborativos'] as $item)
                    <div class="border-l-4 border-[#B20000] bg-white p-5 shadow-sm">
                        <h3 class="font-bold text-[#313131]">{{ $item }}</h3>
                        <p class="mt-3 text-sm leading-6 text-[#5A5A5A]">Estrutura pensada para aulas práticas, projetos integradores e vivência profissional.</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-14">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div>
                <p class="text-sm font-bold uppercase text-[#B20000]">Sala Maker</p>
                <h2 class="mt-2 text-3xl font-bold text-[#313131]">Prototipagem como método de aprendizagem</h2>
            </div>
            <div class="border-t-4 border-[#00C1CF] bg-[#313131] p-8 text-white">
                <p class="text-lg leading-8">A Sala Maker concentra recursos para robótica, fabricação digital, eletrônica e experimentação, aproximando disciplinas técnicas de problemas reais da comunidade.</p>
            </div>
        </div>
    </section>

    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-bold uppercase text-[#B20000]">Destaques</p>
                    <h2 class="mt-2 text-3xl font-bold text-[#313131]">Notícias e eventos recentes</h2>
                </div>
                <a href="{{ route('eventos') }}" class="text-sm font-bold uppercase text-[#B20000] hover:underline">Ver todos</a>
            </div>
            <div class="mt-8 bg-white px-6 shadow-sm">
                @foreach ($highlights as $event)
                    <x-event-item :event="$event" />
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
