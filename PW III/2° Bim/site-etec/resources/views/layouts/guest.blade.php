<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ETEC Zona Leste</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-[#313131]">
    @include('components.navbar')
    <main class="min-h-screen bg-[#F5F5F5]">
        <div class="mx-auto max-w-md px-4 py-12 sm:px-6">
            <div class="border-t-4 border-[#B20000] bg-white p-8 shadow-md">
                <div class="mb-6">
                    <p class="text-sm font-bold uppercase text-[#B20000]">Área restrita</p>
                    <h1 class="mt-2 text-2xl font-bold text-[#313131]">ETEC Zona Leste</h1>
                </div>
                {{ $slot }}
            </div>
        </div>
    </main>
    @include('components.footer')
</body>
</html>
