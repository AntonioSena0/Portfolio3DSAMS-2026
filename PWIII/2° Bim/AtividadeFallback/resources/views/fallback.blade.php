<x-layout>

    <div class="flex min-h-screen flex-col items-center justify-center bg-gray-50 text-center px-4">
        <h1 class="text-9xl font-extrabold text-blue-600">404</h1>
        <h2 class="text-3xl font-bold mt-4 text-gray-800">Página não encontrada</h2>
        <p class="mt-4 text-gray-600 max-w-sm">Desculpe, a página que você está procurando não existe ou foi movida.</p>
        <a href="{{ route("home") }}" class="mt-8 px-6 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
            Voltar para a Home
        </a>
    </div>

</x-layout>
