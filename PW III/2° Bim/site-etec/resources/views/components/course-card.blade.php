@props(['course'])

<article class="border border-[#DDD] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
    <div class="flex items-start justify-between gap-4">
        <h3 class="text-xl font-bold text-[#B20000]">{{ $course->name }}</h3>
        <span class="border border-[#00C1CF] px-2 py-1 text-xs font-bold uppercase text-[#007C85]">{{ $course->type }}</span>
    </div>
    <p class="mt-4 text-sm leading-6 text-[#313131]">{{ $course->description }}</p>
    <dl class="mt-6 grid grid-cols-2 gap-4 border-t border-[#DDD] pt-4 text-sm">
        <div>
            <dt class="font-bold text-[#313131]">Duração</dt>
            <dd class="mt-1 text-[#5A5A5A]">{{ $course->duration ?? 'A definir' }}</dd>
        </div>
        <div>
            <dt class="font-bold text-[#313131]">Período</dt>
            <dd class="mt-1 text-[#5A5A5A]">{{ $course->period ?? 'A definir' }}</dd>
        </div>
    </dl>
</article>
