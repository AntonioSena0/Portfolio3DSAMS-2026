# Middleware com Laravel

Projeto Laravel 12 criado para demonstrar o uso de middleware entre uma requisição HTTP, um controller e uma visualização Blade.

## Fluxo da aplicação

- A rota recebe a requisição de acesso ao portal.
- O `PortalController` aciona o middleware `SharePortalAccessMessage`.
- O middleware compartilha as mensagens de acesso com a visualização.
- A view `portal.blade.php` apresenta as mensagens ao usuário.

## Mensagens do middleware

- Bem vindo ao portal
- Seu acesso não foi autorizado.
- Entrar em contato com o administrador.

## Rotas

| Método | Endereço | Descrição |
| --- | --- | --- |
| `GET` | `/` | Abre a página principal do portal |
| `GET` | `/portal` | Abre a página do portal pelo endereço nomeado |

## Preparação

Requisitos: PHP 8.2 ou superior, Composer e MySQL 8.

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Crie um banco de dados chamado `middleware_app` e configure somente as credenciais locais no `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=middleware_app
DB_USERNAME=root
DB_PASSWORD=
```

## Executando o projeto

Inicie o servidor local:

```bash
composer run dev
```

Acesse no navegador:

```text
http://localhost:8000
```

## Testes

Para validar a resposta das rotas e as mensagens enviadas pelo middleware, execute:

```bash
php artisan test
```
