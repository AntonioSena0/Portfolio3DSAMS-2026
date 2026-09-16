# Chat WebSocket

Chat em tempo real com Laravel Breeze (auth) + Chatify + Pusher WebSocket + MySQL em Docker.

## Stack

- Laravel 12 + Breeze (Blade)
- Chatify (`munafio/chatify`)
- Pusher Channels (cluster `us2`, canal `chat3AMS`)
- MySQL 8.0 em Docker
- Vite

## Requisitos

- PHP 8.2, Composer, Node 24+

## Setup

1. Suba o MySQL e crie o banco (seja por xampp ou docker)

2. Configure o `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatweb3ams
DB_USERNAME=root
DB_PASSWORD=rootroot

PUSHER_APP_ID=[seu_id]
PUSHER_APP_KEY=[sua_key]
PUSHER_APP_SECRET=[seu_secret]
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=us2
```

3. Instale:
```bash
composer install
npm install
php artisan migrate
php artisan storage:link
```

## Rodar

Um comando só sobe server + fila + vite:
```bash
composer run dev
```

Acesse:
- `http://127.0.0.1:8000/` - home
- `http://127.0.0.1:8000/register` - crie 2 contas para testar
- `http://127.0.0.1:8000/chatify` - chat

## Notas

- O aviso `No internet access` no Chatify significa que o Pusher não conectou (key/cluster inválidos), não falta de internet.
- Config do Chatify em `config/chatify.php`, views em `resources/views/vendor/Chatify`, JS em `public/js/chatify/code.js`.
