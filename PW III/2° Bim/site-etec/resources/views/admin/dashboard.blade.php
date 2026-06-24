<x-app-layout>
    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase text-[#B20000]">Administrativo</p>
            <h1 class="mt-2 text-4xl font-bold text-[#313131]">Painel de controle</h1>
            <p class="mt-4 max-w-2xl text-[#5A5A5A]">Bem-vindo ao ambiente administrativo da ETEC Zona Leste.</p>
        </div>
    </section>

    <section class="py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 border-l-4 border-green-600 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="border-l-4 border-[#B20000] bg-white p-6 shadow-sm">
                    <p class="text-sm font-bold uppercase text-[#5A5A5A]">Alunos</p>
                    <p class="mt-2 text-4xl font-bold text-[#313131]">{{ $studentsCount }}</p>
                    <a href="{{ route('admin.students') }}" class="mt-3 inline-block text-sm font-bold text-[#B20000] hover:underline">Gerenciar alunos →</a>
                </div>
                <div class="border-l-4 border-[#00C1CF] bg-white p-6 shadow-sm">
                    <p class="text-sm font-bold uppercase text-[#5A5A5A]">Eventos</p>
                    <p class="mt-2 text-4xl font-bold text-[#313131]">{{ $eventsCount }}</p>
                    <a href="{{ route('admin.events') }}" class="mt-3 inline-block text-sm font-bold text-[#B20000] hover:underline">Gerenciar eventos →</a>
                </div>
                <div class="border-l-4 border-[#313131] bg-white p-6 shadow-sm">
                    <p class="text-sm font-bold uppercase text-[#5A5A5A]">Cursos</p>
                    <p class="mt-2 text-4xl font-bold text-[#313131]">{{ $coursesCount }}</p>
                </div>
            </div>

            <div class="mt-10 grid gap-8 md:grid-cols-2">
                <div>
                    <h2 class="text-xl font-bold text-[#313131]">Últimos alunos cadastrados</h2>
                    <div class="mt-4 bg-white shadow-sm">
                        @forelse ($recentStudents as $student)
                            <div class="border-b border-[#DDD] px-4 py-3">
                                <p class="font-medium text-[#313131]">{{ $student->name }}</p>
                                <p class="text-sm text-[#5A5A5A]">{{ $student->email }} — {{ $student->course?->name ?? 'Sem curso' }}</p>
                            </div>
                        @empty
                            <p class="px-4 py-6 text-sm text-[#5A5A5A]">Nenhum aluno cadastrado ainda.</p>
                        @endforelse
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-[#313131]">Últimos eventos</h2>
                    <div class="mt-4 bg-white shadow-sm">
                        @forelse ($recentEvents as $event)
                            <div class="border-b border-[#DDD] px-4 py-3">
                                <p class="font-medium text-[#313131]">{{ $event->title }}</p>
                                <p class="text-sm text-[#5A5A5A]">{{ $event->event_date->format('d/m/Y') }}</p>
                            </div>
                        @empty
                            <p class="px-4 py-6 text-sm text-[#5A5A5A]">Nenhum evento criado ainda.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
