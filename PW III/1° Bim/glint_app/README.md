# 🌟 Glint App

Rede social simples com Laravel 11 onde usuários criam posts, curtem, comentam e seguem outros usuários.

## Funcionalidades

- **Posts**: Criar posts com texto e imagens
- **Timeline**: Feed de posts cronológico
- **Likes**: Curtir/descurtir posts
- **Comentários**: Adicionar comentários
- **Seguir**: Seguir/deixar de seguir usuários
- **Perfil**: Gerenciar perfil pessoal
- **Exclusão**: Excluir posts, comentários e conta (com modais de confirmação)

## Requisitos

- PHP >= 8.3
- Composer
- Node.js & npm (para assets)
- Banco de dados (MySQL)

## Instalação

```bash
# Clonar repositório
git clone <url-do-projeto>
cd glint_app

# Instalar dependências PHP
composer install

# Instalar dependências npm
npm install

# Configurar chave
cp .env.example .env
php artisan key:generate

# Configurar banco de dados
# Editar .env com suas credenciais DB

# Migrar e popular database
php artisan migrate
php artisan db:seed

# Linkar storage
php artisan storage:link

# Build assets
npm run build

# Iniciar projeto
php artisan serve
```

Acesse: `http://localhost:8000`

## Configuração Básica

Edite `.env`:

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=glint
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

## Segurança

- Autenticação via Laravel Auth
- Validação de formulários
- Modais de confirçaão para exclusões
- Proteção de rotas por middleware

## Stack

- **Backend**: Laravel 13, PHP 8.3, Eloquent ORM
- **Frontend**: Blade Templates, Bootstrap Icons
- **Assets**: Laravel Vite, JavaScript
- **Database**: MySQL
- **Ferramentas**: Docker

## Licença

MIT
