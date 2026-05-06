<x-layout>

    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center">Login</h2>
            <form action="{{ route('users.login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium">E-mail</label>
                        <input type="email" name="email" id="email" required class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium">Senha</label>
                        <input type="password" name="password" id="password" required class="w-full p-2 border rounded">
                    </div>
                </div>
                <button type="submit" class="w-full py-2 mt-6 text-white bg-blue-600 rounded hover:bg-blue-700">Entrar</button>
            </form>
            <p class="text-center text-sm text-gray-600">
                Ainda não tem conta? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Cadastre-se</a>
            </p>
        </div>
    </div>

</x-layout>
