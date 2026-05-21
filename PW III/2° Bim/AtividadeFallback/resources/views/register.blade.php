<x-layout>

    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center">Criar Conta</h2>
            <form action="{{ route('users.register') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium">Nome</label>
                        <input type="text" name="name" id="name" required class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium">E-mail</label>
                        <input type="email" name="email" id="email" required class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium">Senha</label>
                        <input type="password" name="password" id="password" required class="w-full p-2 border rounded">
                    </div>
                </div>
                <button type="submit" class="w-full py-2 mt-6 text-white bg-green-600 rounded hover:bg-green-700">Registrar</button>
            </form>
            <p class="text-center text-sm text-gray-600">
                Já possui conta? <a href="{{ route('login') }}" class="text-green-600 hover:underline">Faça login</a>
            </p>
        </div>
    </div>

</x-layout>
