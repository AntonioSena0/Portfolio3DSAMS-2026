<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            background: #f1eee8;
            color: #191919;
            font-family: Arial, Helvetica, sans-serif;
        }

        main {
            width: min(90%, 680px);
            padding: 48px 0;
            border-top: 8px solid #191919;
            border-bottom: 1px solid #191919;
        }

        h1 {
            margin: 0 0 32px;
            font-size: clamp(2.5rem, 8vw, 5.5rem);
            line-height: 0.9;
            letter-spacing: -0.06em;
        }

        p {
            margin: 8px 0;
            font-size: 1.15rem;
            line-height: 1.5;
        }

        p:first-of-type {
            font-weight: 700;
        }
    </style>
</head>
<body>
    <main>
        <h1>Portal</h1>
        @foreach ($portalMessages as $message)
            <p>{{ $message }}</p>
        @endforeach
    </main>
</body>
</html>
