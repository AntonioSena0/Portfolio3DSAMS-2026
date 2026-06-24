<x-app-layout>
    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase text-[#B20000]">Administrativo</p>
            <h1 class="mt-2 text-4xl font-bold text-[#313131]">Gerenciar alunos</h1>
            <p class="mt-4 max-w-2xl text-[#5A5A5A]">Cadastre, edite e remova alunos do sistema.</p>
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
                <a href="{{ route('admin.students.create') }}" class="inline-block bg-[#B20000] px-5 py-3 text-sm font-bold uppercase text-white transition hover:bg-[#8F0000]">+ Novo aluno</a>
            </div>

            <div class="overflow-x-auto bg-white shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-[#DDD] bg-[#F5F5F5]">
                            <th class="px-4 py-3 font-bold text-[#313131]">Nome</th>
                            <th class="px-4 py-3 font-bold text-[#313131]">E-mail</th>
                            <th class="px-4 py-3 font-bold text-[#313131]">Curso</th>
                            <th class="px-4 py-3 font-bold text-[#313131]">Cadastro</th>
                            <th class="px-4 py-3 font-bold text-[#313131]">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr class="border-b border-[#DDD]">
                                <td class="px-4 py-3 font-medium text-[#313131]">{{ $student->name }}</td>
                                <td class="px-4 py-3 text-[#5A5A5A]">{{ $student->email }}</td>
                                <td class="px-4 py-3 text-[#5A5A5A]">{{ $student->course?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-[#5A5A5A]">{{ $student->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="font-bold text-[#B20000] hover:underline">Editar</a>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este aluno?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-3 font-bold text-[#5A5A5A] hover:text-[#B20000]">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-[#5A5A5A]">Nenhum aluno cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $students->links() }}
            </div>
        </div>
    </section>
</x-app-layout>
