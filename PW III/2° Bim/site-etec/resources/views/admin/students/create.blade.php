<x-app-layout>
    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase text-[#B20000]">Administrativo</p>
            <h1 class="mt-2 text-4xl font-bold text-[#313131]">Cadastrar aluno</h1>
        </div>
    </section>

    <section class="py-14">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.students.store') }}" method="POST" class="bg-white p-8 shadow-sm">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-[#313131]">Nome completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                        @error('name') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-[#313131]">E-mail</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                        @error('email') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="course_id" class="block text-sm font-bold text-[#313131]">Curso</label>
                        <select name="course_id" id="course_id" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                            <option value="">Selecione um curso</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->name }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="grade" class="block text-sm font-bold text-[#313131]">Série</label>
                        <select name="grade" id="grade" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                            <option value="">Selecione a série</option>
                            <option value="1" @selected(old('grade') == '1')>1º Ano</option>
                            <option value="2" @selected(old('grade') == '2')>2º Ano</option>
                            <option value="3" @selected(old('grade') == '3')>3º Ano</option>
                        </select>
                        @error('grade') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-[#313131]">Senha</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                        @error('password') <p class="mt-1 text-sm text-[#B20000]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-[#313131]">Confirmar senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full border border-[#DDD] px-4 py-3 text-sm text-[#313131] focus:border-[#B20000] focus:outline-none">
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-4">
                    <button type="submit" class="bg-[#B20000] px-5 py-3 text-sm font-bold uppercase text-white transition hover:bg-[#8F0000]">Salvar</button>
                    <a href="{{ route('admin.students') }}" class="text-sm font-bold text-[#5A5A5A] hover:text-[#B20000]">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
