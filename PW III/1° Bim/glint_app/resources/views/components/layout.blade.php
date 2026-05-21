<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @auth
  <meta name="user-id" content="{{ auth()->user()->id }}">
  @endauth
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Glint</title>
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="m-0 p-0 w-full overflow-x-hidden bg-blue-100">
  <main>
    {{ $slot ?? '' }}
  </main>
</body>
</html>