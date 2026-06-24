<footer class="bg-[#313131] text-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 md:grid-cols-[1.2fr_0.8fr_1fr] lg:px-8">
        <div>
            <h2 class="text-2xl font-bold">ETEC Zona Leste</h2>
            <p class="mt-3 max-w-md text-sm leading-6 text-white/70">Educação técnica pública, prática e conectada aos caminhos profissionais da Zona Leste de São Paulo.</p>
        </div>

        <div>
            <h3 class="text-sm font-bold uppercase text-white">Navegação</h3>
            <nav class="mt-4 grid gap-2 text-sm text-white/70">
                <a href="{{ route('home') }}" class="transition hover:text-[#00C1CF]">Início</a>
                <a href="{{ route('cursos') }}" class="transition hover:text-[#00C1CF]">Cursos</a>
                <a href="{{ route('eventos') }}" class="transition hover:text-[#00C1CF]">Eventos</a>
            </nav>
        </div>

        <div>
            <h3 class="text-sm font-bold uppercase text-white">Contato</h3>
            <div class="mt-4 space-y-2 text-sm text-white/70">
                <p>Av. Águia de Haia, 2633 - Cidade A.E. Carvalho</p>
                <p>São Paulo - SP</p>
                <p>secretaria@eteczonaleste.com.br</p>
            </div>
            <div class="mt-5 flex gap-4 text-sm font-bold text-white">
                <a target="_blank" href="https://www.instagram.com/eteczonalesteoficial/" class="transition hover:text-[#00C1CF]">Instagram</a>
                <a target="_blank" href="https://www.youtube.com/@etecdazonaleste2949" class="transition hover:text-[#00C1CF]">YouTube</a>
            </div>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-4 text-xs text-white/60 sm:px-6 lg:px-8">© {{ date('Y') }} ETEC Zona Leste. Todos os direitos reservados.</div>
    </div>
</footer>
