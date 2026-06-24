<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ETEC Zona Leste — Página não encontrada</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-[#313131]">
    <section class="flex min-h-screen flex-col items-center justify-center bg-[#F5F5F5] px-4">
        <div class="max-w-lg text-center">
            <p class="text-sm font-bold uppercase text-[#B20000]">Erro 404</p>
            <h1 class="mt-4 text-6xl font-bold text-[#313131]">Página não encontrada</h1>
            <p class="mt-6 text-lg leading-8 text-[#5A5A5A]">O conteúdo que você procura não está disponível ou foi removido. Verifique o endereço ou volte para a página inicial.</p>
            <div class="mt-10">
                <a href="{{ url('/') }}" class="bg-[#B20000] px-6 py-3 text-sm font-bold uppercase text-white transition hover:bg-[#8F0000]">Voltar ao início</a>
            </div>
        </div>
    </section>
</body>
</html>
