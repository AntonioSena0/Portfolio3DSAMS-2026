# MeetFlow

Aplicativo Android desenvolvido em Kotlin com SQLite para gerenciamento de reuniões e participantes, utilizando relacionamento N:M (muitos para muitos).

O projeto foi construído com foco em boas práticas de desenvolvimento Android nativo, organização de código e experiência do usuário.

---

# Tecnologias Utilizadas

- Kotlin
- SQLite
- XML Layouts

---

# Funcionalidades

## Usuários
- Cadastro de usuários
- Listagem de usuários
- Atualização de Usuários
- Exclusão de usuários

## Reuniões
- Cadastro de reuniões
- Listagem de reuniões
- Exclusão de reuniões
- Tela de detalhes da reunião

## Relacionamento N:M
- Uma reunião pode possuir vários participantes
- Um usuário pode participar de várias reuniões

## Interface
- Dashboard principal
- Toolbar personalizada
- Navegação entre telas
- Feedback visual com Snackbar
- Empty States
- Material Design

---

# Estrutura do Projeto

```text
com.example.meetflow
│
├── activities
├── adapters
├── database
├── model
├── res
│   ├── drawable
│   ├── layout
│   └── values
```

---

# Banco de Dados

O projeto utiliza SQLite com as seguintes tabelas:

## usuario
Armazena os usuários do sistema.

## reuniao
Armazena as reuniões cadastradas.

## usuario_reuniao
Tabela intermediária responsável pelo relacionamento N:M entre usuários e reuniões.

---

# Conceitos Aplicados

- CRUD completo
- RecyclerView
- Adapters
- SQLite Relacional
- Relacionamento Muitos para Muitos (N:M)
- Integridade Relacional
- Lifecycle Android
- Material Design
- Navegação entre Activities

---

# Capturas de Tela

## Dashboard

<img src="./app/assets/Dashboard.png" alt="Dashboard" />

---

## Cadastro de Usuário

<img src="./app/assets/Cadastrar Usuário.png" alt="Cadastrar Usuário" />

---

## Cadastro de Reunião

<img src="./app/assets/Cadastrar Reuniões.png" alt="Cadastrar Reuniões" />

---

## Lista de Usuários

<img src="./app/assets/Usuários.png" alt="Usuários" />

---

## Atualizar Usuário

<img src="./app/assets/Atualizar Usuário.png" alt="Atualizar Usuário" />

---

## Lista de Reuniões

<img src="./app/assets/Reuniões.png" alt="Reuniões" />

---

## Detalhes da Reunião

<img src="./app/assets/Detalhes (Reunião).png" alt="Detalhes Reunião" />

---

# Autor

Antonio Bernardino de Sena Neto

Projeto desenvolvido para fins acadêmicos e de aprendizado em desenvolvimento Android nativo com Kotlin.