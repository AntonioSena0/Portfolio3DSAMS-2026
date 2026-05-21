# CleanTask - Aplicação Laravel

---

## Aplicação de gestão de tarefas com fallback

Essa aplicação foi feita utilizando php, laravel, blade e docker para virtualização do banco de dados

---

Objetivo:

Essa aplicação foi realizada com o intuito de aprimorar minhas habilidades com o framework laravel e a linguagem php
Explorando:

- Rotas
- Validações
- Manipulação de dados

---

## Requisitos

PHP: >= 8.5.3
Composer: >= 2.9.5

---

## Funcionalidades

**Algumas funcionalidades básicas desse website são:**

- **Cadastro e login de usuário**
- **logout**
- **validações**
- **criação e conclusão de tarefas**

---

## Rotas

```java
//TELAS
(GET)'/' -> Rota para tela de login
(GET)'/register' -> Rota para tela de registro
(GET)'/home' -> Rota para home

//USERS
(POST)"/register" -> Rota que realiza o cadastro
(POST)"/login" -> Rota que realiza o login
(POST)"/logout" -> Rota que realiza o logout

//TASKS
(POST)"/create-task" -> Rota para criação de tarefas
(PATCH)"/toggle/{task}" -> Rota para concluir tarefas
```

---

## Como Rodar?

```bash
#Instalando dependências
composer install
npm install
npm run build

#Definindo variáveis de ambiente
Verifique o .env.example na raiz do projeto e crie um .env baseado nele e preencha os dados

#Gerar chave para aplicação
php artisan key:generate

#Iniciar Banco de Dados
docker compose up -d

#Executar as migrações
php artisan migrate

#Inicializar
composer run dev

```

---

#### ☕Desenvolvido por Antonio Bernardino de Sena Neto
