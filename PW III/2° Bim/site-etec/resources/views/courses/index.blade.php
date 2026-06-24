<x-app-layout>
    <section class="bg-[#F5F5F5] py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase text-[#B20000]">Cursos</p>
            <h1 class="mt-2 text-4xl font-bold text-[#313131]">Formações técnicas</h1>
            <p class="mt-4 max-w-2xl leading-7 text-[#5A5A5A]">Escolha um percurso de formação alinhado ao mercado, com base prática e acompanhamento docente.</p>
        </div>
    </section>

    <section class="p-10">
        <div class="flex justify-center items-center w-full gap-2 sm:px-6 lg:px-2">
            @foreach ($courses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>
    </section>
</x-app-layout>
