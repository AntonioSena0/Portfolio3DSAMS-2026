<x-layout>
    <div class="min-h-screen bg-gray-50 text-gray-900">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-600 tracking-tight">CleanTasks</h1>

                <div class="flex items-center gap-4">

                    @auth

                        <form method="POST" action="{{ route('users.logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="text-sm cursor-pointer font-medium text-red-600 hover:bg-red-700 hover:text-white border border-red-200 hover:border-red-300 px-4 py-2 rounded-full transition"
                            >
                                Sair
                            </button>
                        </form>

                    @endauth

                </div>
            </div>
        </nav>

        <main class="mx-auto px-6 mt-20 text-center">
            <h2 class="text-5xl md:text-6xl font-extrabold text-gray-900 tracking-tighter leading-tight">
                Organize suas tarefas
            </h2>

            @auth
                <div class="mt-8 inline-block bg-white shadow-md rounded-xl px-10 py-8 text-left">
                    <p class="text-2xl text-gray-500 ">Você está logado como:</p>
                    <div class="w-full h-full flex flex-col justify-center items-center">
                        <p class="mt-1 text-4xl font-semibold text-gray-900">
                            {{ Auth::user()->name }}
                        </p>
                    </div>
                </div>

                <section class="flex flex-1 justify-around items-start">

                    <div class="w-100 h-100 mt-16 bg-white shadow-md rounded-xl px-8 py-8 max-w-xl mx-auto text-left">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            Criar nova tarefa
                        </h3>

                        <form method="POST" action="{{ route('tasks.create') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">
                                    Título da tarefa
                                </label>
                                <input
                                    id="title"
                                    type="text"
                                    name="title"
                                    maxlength="100"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ex: Finalizar relatório semanal"
                                >
                            </div>

                            <div class="mb-4">
                                <label for="end_at" class="block text-sm font-medium text-gray-700">
                                    Data de término
                                </label>
                                <input
                                    id="end_at"
                                    type="date"
                                    name="end_at"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>

                            <button
                                type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition"
                            >
                                Criar tarefa
                            </button>
                        </form>
                    </div>

                    <div class="w-2xl mx-auto px-6 mt-16 pb-20">
                        <h3 class="text-3xl font-bold mb-8 text-gray-900">Suas Tarefas</h3>
                        @forelse ($tasks as $task)
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="mb-4">
                                @method('PATCH')
                                @csrf
                                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 {{ $task->is_complete ? 'border-green-500 bg-green-50' : 'border-blue-500 bg-blue-50' }}">
                                    <div class="flex justify-between items-center gap-4">
                                        <div class="flex-1">
                                            <h4 class="text-xl font-semibold text-gray-900 {{ $task->is_complete ? 'line-through text-gray-500' : '' }}">
                                                {{ $task->title }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                Vence: <span class="{{ $task->end_at < now() ? 'text-red-500 font-medium' : 'text-green-500 font-medium' }}">
                                                    {{ $task->end_at->format('d/m/Y') }}
                                                </span>
                                            </p>
                                        </div>
                                        <label class="flex items-center cursor-pointer space-x-2">
                                            <input type="checkbox"
                                                   name="is_complete"
                                                   {{ $task->is_complete ? 'checked' : '' }}
                                                   onchange="this.form.submit()"
                                                   class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm font-medium {{ $task->is_complete ? 'text-green-700' : 'text-gray-700' }}">
                                                {{ $task->is_complete ? 'Concluída' : 'Pendente' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        @empty
                            <div class="text-center py-16">
                                <p class="text-xl text-gray-500 mb-4">Ainda não possui tarefas criadas</p>
                                <p class="text-gray-400">Crie sua primeira tarefa acima!</p>
                            </div>
                        @endforelse
                    </div>

                </section>

            @endauth

        </main>
    </div>
</x-layout>
