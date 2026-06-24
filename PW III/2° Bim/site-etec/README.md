# Site-Etec

## Descrição

Este repositório contém a implementação do site institucional da ETEC Zona Leste utilizando Laravel 11 com o starter kit Breeze e Tailwind CSS, seguindo o design system da instituição.

## Funcionalidades

| Funcionalidade | Descrição |
|----------------|-----------|
| Autenticação | Login, registro a partir do admin, recuperação e confirmação de senha (Breeze) com o visual da ETEC. |
| Agenda de Eventos | Exibição de eventos/notícias. |
| Camada de serviços | Lógica de negócios encapsulada em `app/Services/*`. |
| Camada de repositório | Abstração de acesso a dados (`app/Repositories/*`). |
| DTOs | Validação e transporte de dados entre camadas (`app/Http/Requests/*`). |
| Migrações & Seeders | Estrutura de tabelas `courses` pronta para ser populada e admin padrão. |
| Pronto para testes | Estrutura preparada para unit e feature tests (PHPUnit). |

## Como rodar o projeto

> Requisitos: PHP >= 8.2.12, Composer 2.9.5, Node >= 24.9.0, MySQL, Git.

```bash
# Terminal

git clone https://github.com/AntonioSena0/Portfolio3DSAMS-2026.git
cd Portfolio3DSAMS-2026/PW III/2° Bim/site-etec

composer install

npm install

copy .env.example .env

php artisan key:generate

php artisan migrate:fresh --seed

composer run dev
```

## Desenvolvido por

**Antonio Sena** – Desenvolvedor Back-End
