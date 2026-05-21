<form method="post" action="{{ $slot == 'Cadastrar-se' ? route('auth.register') : route('auth.login') }}" class="flex flex-col items-center justify-center min-h-screen bg-blue-500 p-8">

    @csrf

    <div class="mb-12 text-center">
        @if($slot == 'Cadastrar-se')
            <h1 class="text-5xl md:text-6xl font-bold text-white drop-shadow-2xl bg-linear-to-r from-white to-gray-200 bg-clip-text">Seja Bem-vindo à Glint</h1>
            <p class="text-xl text-blue-100 mt-4">Crie sua conta e comece agora</p>
        @else
            <h1 class="text-5xl md:text-6xl font-bold text-white drop-shadow-2xl bg-linear-to-r from-white to-gray-200 bg-clip-text">Bem-vindo de volta à Glint</h1>
            <p class="text-xl text-blue-100 mt-4">Faça login para continuar</p>
        @endif
    </div>

    <div class="w-full max-w-md bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl rounded-3xl p-8 md:p-10 space-y-8">

        <div class="flex justify-center">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm border-2 border-white/30">
                <i class="bi bi-person-circle text-3xl text-white"></i>
            </div>
        </div>

        <div class="space-y-6">
            @if($slot == 'Cadastrar-se')
                <div>
                    <label class="block text-lg font-semibold text-white mb-3" for="username">Nome de usuário</label>
                    <input
                        class="w-full h-12 px-4 rounded-2xl bg-white/90 border-2 border-white/30 text-gray-900 font-medium focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all duration-300 hover:border-white/50 shadow-lg"
                        type="text"
                        name="username"
                        id="username"
                        placeholder="Seu nome de usuário"
                        required
                    >
                </div>
            @endif

            <div>
                <label class="block text-lg font-semibold text-white mb-3" for="email">Email</label>
                <input
                    class="w-full h-12 px-4 rounded-2xl bg-white/90 border-2 border-white/30 text-gray-900 font-medium focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all duration-300 hover:border-white/50 shadow-lg"
                    type="email"
                    name="email"
                    id="email"
                    placeholder="seu@email.com"
                    required
                >
            </div>

            <div>
                <label class="block text-lg font-semibold text-white mb-3" for="password">Senha</label>
                <input
                    class="w-full h-12 px-4 rounded-2xl bg-white/90 border-2 border-white/30 text-gray-900 font-medium focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all duration-300 hover:border-white/50 shadow-lg pr-12"
                    type="password"
                    name="password"
                    id="password"
                    placeholder="••••••••"
                    required
                >
            </div>
        </div>

        <x-form-error field="__all__" />

        <input
            class="w-full h-14 bg-blue-800 cursor-pointer text-white font-bold text-lg rounded-2xl shadow-xl hover:bg-blue-900 focus:ring-4 focus:ring-blue-500/50 active:from-blue-800 active:scale-95 transition-all duration-300 flex items-center justify-center group"
            type="submit" value="{{ $slot }}"
        >
        </input>

        <div class="text-center pt-4">
            @if($slot == 'Cadastrar-se')
                <p class="text-blue-100 text-sm">Já tem conta? <a href="{{ route('login') }}" class="text-white font-semibold hover:underline">Entrar</a></p>
            @else
                <p class="text-blue-100 text-sm">Não tem conta? <a href="{{ route('register') }}" class="text-white font-semibold hover:underline">Cadastrar-se</a></p>
            @endif
        </div>
    </div>
</form>
