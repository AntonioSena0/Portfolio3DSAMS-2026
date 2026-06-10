# EduLibre

Plataforma de ensino gratuito desenvolvida com Laravel 12, PHP 8.2, Blade, Tailwind CSS, Alpine.js e GSAP.

## Sobre o Projeto

O EduLibre é uma plataforma web de educação gratuita criada para democratizar o acesso ao conhecimento, conectando professores voluntários e estudantes sem qualquer barreira financeira.

A plataforma permite que professores criem e publiquem matérias compostas por vídeo-aulas, enquanto alunos podem acessar todo o conteúdo gratuitamente, acompanhar seu progresso, avaliar aulas e participar da comunidade através de comentários.

## Objetivo

Oferecer ensino gratuito e de qualidade para qualquer pessoa com acesso à internet.

### Objetivos Secundários

- Permitir que professores publiquem conteúdo sem complexidade técnica
- Garantir uma experiência moderna e intuitiva para estudantes
- Manter qualidade através de um fluxo de aprovação editorial
- Escalar o catálogo de conteúdos sem perda de performance
- Construir uma comunidade educacional colaborativa

## Principais Funcionalidades

### Alunos

- Cadastro e autenticação
- Catálogo público de matérias
- Assistir vídeo-aulas gratuitamente
- Continuação de estudos
- Avaliação de vídeos
- Comentários em aulas
- Dashboard pessoal

### Professores

- Cadastro como professor
- Processo de aprovação administrativa
- Criação de matérias
- Gerenciamento de vídeo-aulas
- Ordenação de vídeos por youtube embed code
- Dashboard exclusivo

### Administradores

- Aprovação de professores
- Aprovação de matérias
- Gerenciamento de usuários
- Moderação de conteúdo
- Controle de categorias
- Relatórios e indicadores
- Bloqueio de usuários

## Arquitetura

O projeto segue arquitetura em camadas para manter separação de responsabilidades e facilitar manutenção.

```text
HTTP Request
    ↓
Routes
    ↓
Middlewares
    ↓
Controllers
    ↓
Form Requests
    ↓
Services
    ↓
Repositories
    ↓
Models (Eloquent)
    ↓
MySQL
```

## Stack Tecnológica

| Camada | Tecnologia |
|----------|------------|
| Backend | PHP 8.2.12 |
| Framework | Laravel 12.x |
| ORM | Eloquent |
| Banco de Dados | MySQL 8.x |
| Frontend | Blade |
| CSS | Tailwind CSS 3.x |
| Interatividade | Alpine.js 3.x |
| Animações | GSAP 3.x |
| Build Tool | Vite |
| Ambiente Local | XAMPP |
| Versionamento | Git + GitHub |

## Estrutura do Projeto

```text
app/
├── Events/
├── Http/
├── Jobs/
├── Listeners/
├── Mail/
├── Models/
├── Observers/
├── Policies/
├── Providers/
├── Repositories/
└── Services/

database/
├── migrations/
├── factories/
└── seeders/

resources/
├── css/
├── js/
└── views/

routes/
├── web.php
├── auth.php
├── student.php
├── professor.php
└── admin.php

tests/
├── Feature/
└── Unit/
```

## Banco de Dados

### Principais Entidades

- Users
- Categories
- Subjects
- Videos
- Enrollments
- VideoProgress
- Comments
- Ratings
- Attachments
- Notifications

## Controle de Acesso

### Roles

| Role | Permissões |
|--------|------------|
| Student | Assistir, comentar e avaliar |
| Professor | Criar e gerenciar conteúdos |
| Admin | Controle total do sistema |

### Status de Usuário

| Status | Descrição |
|----------|------------|
| active | Conta ativa |
| pending | Aguardando aprovação |
| blocked | Conta bloqueada |

## Fluxo de Publicação

```text
Draft
   ↓
Under Review
   ↓
Published
```

Caso rejeitado:

```text
Under Review
       ↓
Rejected
       ↓
Draft
```

Somente conteúdos com status `published` ficam disponíveis publicamente.

## Requisitos

- PHP 8.2.12 ou superior
- Composer
- Node.js
- NPM
- MySQL 8.x
- XAMPP (ambiente recomendado)
- Git

## Instalação

### Clonar o repositório

```bash
git clone https://github.com/AntonioSena0/portfolio3DSAMS-2026.git

cd PW III/2° Bim/EduLibre
```

### Instalar dependências PHP

```bash
composer install
```

### Instalar dependências Frontend

```bash
npm install
```

### Configurar ambiente

```bash
cp .env.example .env
```

Gerar chave da aplicação:

```bash
php artisan key:generate
```

### Configurar banco de dados

Criar banco (ou ligar o xampp):

```sql
CREATE DATABASE edulibre;
```

Configurar no arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edulibre
DB_USERNAME=root
DB_PASSWORD=
```

### Executar migrations

```bash
php artisan migrate
```

### Popular banco de dados

```bash
php artisan db:seed
```

### Executar ambiente de desenvolvimento

```bash
composer run dev
```

## Fluxo Rápido de Instalação

```bash
git clone https://github.com/AntonioSena0/edulibre.git

cd edulibre

composer install

npm install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan db:seed

composer run dev
```

## Testes

Executar todos os testes:

```bash
php artisan test
```

Executar cobertura:

```bash
php artisan test --coverage
```

## Indicadores de Negócio

O sistema acompanha métricas como:

- Total de alunos cadastrados
- Total de professores ativos
- Total de matérias publicadas
- Vídeos assistidos por dia
- Taxa de conclusão de matérias
- Tempo médio de sessão
- Avaliações realizadas
- Engajamento em comentários

## Segurança

- Senhas criptografadas com Hash do Laravel
- Proteção CSRF
- Rate Limiting
- Policies e Gates
- Middleware de autorização
- Soft Deletes para auditoria
- Validação centralizada via Form Requests

### Limites

| Ação | Limite |
|--------|--------|
| Login | 5 tentativas por minuto |
| Cadastro | 3 tentativas a cada 5 minutos |
| Comentários | 10 por hora |
| Avaliações | 1 por vídeo |

## Licença

Este projeto é distribuído sob a licença MIT.

## Autor

Desenvolvido por **Antonio Sena**

GitHub: https://github.com/AntonioSena0

---

EduLibre — Educação gratuita para todos.