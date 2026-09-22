# Seeder App — Povoamento de Banco de Dados (Laravel)

Atividade de **Seeders** em Laravel: criação de seeders via Artisan, povoamento com
inserção massiva respeitando regras relacionais e exportação do dump `.sql`
(estrutura + dados) para versionamento/entrega.

## Domínio e modelagem

Loja simples com 3 entidades:

| Tabela | Colunas principais | Regra relacional |
|---|---|---|
| `users` | `name`, `email` (unique), `password`, `email_verified_at` | — (padrão Laravel) |
| `categories` | `name` (unique), `slug` (unique), `description`, `active` | — (lado 1 do 1:N) |
| `products` | `category_id` (FK), `name`, `slug` (unique), `price`, `stock`, `active` | `products.category_id → categories.id` (`restrictOnDelete`, `cascadeOnUpdate`) |

- `Category hasMany Product` / `Product belongsTo Category` (`app/Models/`).
- Volume final: **11 usuários, 8 categorias, 50 produtos**.

## Pré-requisitos

- PHP 8.3+, Composer, MySQL 8.x acessível em `127.0.0.1:3306`.
- Neste ambiente o MySQL roda em Docker (`mysql:8.4`, container `devup-mysql`).
- Configure o `.env` a partir do `.env.example`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seeder_app
DB_USERNAME=root
DB_PASSWORD=devup_database_pass
```

## Etapa 1 — Criação e configuração dos Seeders (CLI + `run()`)

Comandos executados (geração via Artisan):

```bash
php artisan make:model Category -m -f
php artisan make:model Product -m -f
php artisan make:seeder CategorySeeder
php artisan make:seeder ProductSeeder
php artisan make:seeder UserSeeder
```

Lógica implementada no `run()` de cada seeder (inserção massiva):

- `database/seeders/CategorySeeder.php` — 8 categorias curadas em **1 único `upsert`**
  (deduplicação por `slug`, idempotente).
- `database/seeders/ProductSeeder.php` — 25 produtos curados via `upsert` por `slug`
  (resolve `category_id` pelos `slug`s reais das categorias, com fallback que chama
  `CategorySeeder` se a ordem for invertida) **+ 25 produtos via `Product::factory(25)`**
  (total ~50). Demonstra FK válida + volume.
- `database/seeders/UserSeeder.php` — 1 admin fixo via `updateOrCreate`
  (`admin@seederapp.local` / `password123`) **+ 10 usuários via `User::factory(10)`**.
- `database/seeders/DatabaseSeeder.php` — orquestra na ordem que respeita a FK:

```php
$this->call([UserSeeder::class, CategorySeeder::class, ProductSeeder::class]);
```

## Etapa 2 — Execução do povoamento + verificação

```bash
php artisan migrate:fresh --seed
# ou, separadamente:
php artisan migrate
php artisan db:seed
# apenas um seeder:
php artisan db:seed --class=ProductSeeder
```

Verificação da integridade (No SGBD):

```bash
SELECT * FROM users;
SELECT * FROM categories;
SELECT * FROM products;
```

Resultado obtido:

| users | categories | products |
|---|---|---|
| 11 | 8 | 50 |

Verificação também possível via phpMyAdmin / Workbench: abrir `seeder_app`,
conferir `products.category_id` apontando para `categories.id` e o `JOIN`
`products ⨝ categories` retornando as 50 linhas.

## Etapa 3 — Exportação do banco (dump `.sql`)

Arquivo entregue: **`database/dump/seeder_app.sql`** (estrutura + dados, ~51 KB).

## Estrutura dos arquivos relevantes

```
app/Models/Category.php
app/Models/Product.php
database/migrations/2026_09_22_015447_create_categories_table.php
database/migrations/2026_09_22_015448_create_products_table.php
database/factories/CategoryFactory.php
database/factories/ProductFactory.php
database/seeders/DatabaseSeeder.php
database/seeders/CategorySeeder.php
database/seeders/ProductSeeder.php
database/seeders/UserSeeder.php
database/dump/seeder_app.sql
```
