@include('partials.header')

<x-layout>

    <div class="flex flex-1 flex-col items-center bg-blue-500 px-6 py-12 md:p-20 gap-10 md:gap-20">

        <h1 class="text-3xl sm:text-4xl md:text-5xl text-white font-bold text-center px-2">Seja bem vindo à Glint</h1>

        <div class="flex flex-col lg:flex-row flex-1 justify-center items-center gap-10 lg:gap-16 w-full max-w-6xl">

            <div class="text-lg md:text-xl text-white w-full max-w-xl font-semibold text-center lg:text-left">

                <h4 class="leading-relaxed">Glint é a rede social que brilha conexões reais! Conecte-se com amigos,
                    compartilhe momentos autênticos em fotos e vídeos, e descubra comunidades
                    vibrantes baseadas em interesses como música, games e criatividade. Simples,
                    intuitiva e focada em interações genuínas, cadastre-se agora e deixe sua luz brilhar!</h4>

            </div>

            <div class="shrink-0">

                <img src="{{ asset('img/logo.png') }}" alt="Logo Glint" class="w-48 h-48 md:w-64 md:h-64 object-contain bg-white rounded-full p-4">

            </div>

        </div>


    </div>

    <div class="flex flex-col md:flex-row flex-1 justify-center items-stretch md:items-start gap-8 md:gap-6 px-6 py-12 md:p-20 max-w-6xl mx-auto w-full">
        <x-card>

            <x-slot:icon><i class="bi bi-file-post"></i></x-slot:icon>

            <x-slot:description>
                <p>Compartilhe suas conquistas e seus melhores momentos.</p>
            </x-slot:description>

        </x-card>
        <x-card>

            <x-slot:icon><i class="bi bi-person-circle"></i></x-slot:icon>

            <x-slot:description>
                <p>Acompanhe pessoas do mundo todo a qualquer instante.</p>
            </x-slot:description>

        </x-card>
        <x-card>

            <x-slot:icon><i class="bi bi-heart"></i></x-slot:icon>

            <x-slot:description>
                <p>Curta os seus posts favoritos e interaja com a comunidade</p>
            </x-slot:description>

        </x-card>
    </div>

    <div class="flex flex-1 flex-col items-center bg-blue-500 p-20 gap-20">

        <div class="w-[60%] text-center">

            <h1 class="text-5xl text-white font-bold">Faça amizades em tempo real, e juntos, <span class="text-blue-800/80">explorem</span> novos horizontes, embarque nessa <span class="text-blue-800/80">aventura</span></h1>

        </div>

        <div>

            <a href="{{ route('register') }}"><x-button class="text-xl md:text-3xl font-bold min-w-56 px-8 py-5 bg-white text-blue-600 border-2 border-white hover:text-white hover:bg-blue-600">Cadastrar-se</x-button></a>

        </div>

    </div>

</x-layout>

@include('partials.footer')
