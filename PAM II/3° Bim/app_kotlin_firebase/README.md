# Povos Originários

Aplicativo Android desenvolvido em Kotlin com Firebase Authentication para apresentar um acervo fotográfico de artefatos, vestimentas e cultura material de populações indígenas.

O projeto foi construído com foco em boas práticas de desenvolvimento Android nativo, organização de código, identidade visual e experiência do usuário.

## Tecnologias Utilizadas

- Kotlin
- Jetpack Compose
- Material Design 3
- Firebase Authentication
- StateFlow
- ViewModel
- Gradle Kotlin DSL

## Funcionalidades

### Usuários

- Cadastro de usuários com e-mail e senha
- Login de usuários
- Persistência da sessão autenticada
- Logout de usuários
- Validação de e-mail e senha
- Feedback visual de carregamento e erros

### Acervo Povos Originários

- Catálogo fotográfico de cultura material indígena
- Apresentação de cerâmica Marajoara
- Apresentação de cestaria Haliti-Paresi
- Contexto sobre povo, território e técnica
- Créditos e atribuição das fotografias
- Conteúdo protegido por autenticação

### Interface

- Interface 100% construída com Jetpack Compose
- Identidade visual inspirada em materiais naturais, com tons de urucum, argila, mata e palha
- Tela de login
- Tela de cadastro
- Tela principal do acervo
- Navegação orientada pelo estado de autenticação
- Feedback visual com Snackbar
- Indicador de carregamento
- Suporte à rolagem e diferentes alturas de tela

## Estrutura do Projeto

```text
com.example.app_kotlin_firebase
│
├── auth
│   ├── AuthState.kt
│   ├── AuthViewModel.kt
│   ├── CredentialsValidator.kt
│   └── FirebaseAuthRepository.kt
│
├── ui
│   ├── PovosOriginariosApp.kt
│   └── theme
│       ├── Color.kt
│       ├── Theme.kt
│       └── Type.kt
│
├── MainActivity.kt
└── res
    ├── drawable
    ├── mipmap
    └── values
```

## Backend

O projeto utiliza o Firebase Authentication como backend de identidade. O cadastro e o login são realizados pelo provedor E-mail/senha, e a sessão autenticada é restaurada automaticamente pelo SDK do Firebase.

O acervo temático só é exibido depois da autenticação. Sem o arquivo de configuração do Firebase, o aplicativo continua abrindo e orienta o desenvolvedor a concluir a integração.

## Configuração do Firebase

### 1. Criar o projeto

1. Acesse [Firebase Console](https://console.firebase.google.com/).
2. Clique em **Criar um projeto**.
3. Informe um nome, como `Povos Originarios`.
4. Conclua a criação. O Google Analytics é opcional para esta atividade.

### 2. Cadastrar o aplicativo Android

1. Na visão geral do projeto, clique no ícone do Android.
2. Use exatamente este nome de pacote:

```text
com.example.app_kotlin_firebase
```

3. Informe o apelido `Povos Originários`.
4. Para autenticação por e-mail e senha, o SHA-1 não é necessário.
5. Clique em **Registrar app**.

### 3. Adicionar o arquivo de configuração

1. Baixe o arquivo `google-services.json` fornecido pelo Firebase.
2. Coloque o arquivo dentro da pasta `app`:

```text
app_kotlin_firebase/app/google-services.json
```

3. No Android Studio, clique em **Sync Project with Gradle Files**.

O projeto aplica o plugin Google Services automaticamente quando encontra esse arquivo.

### 4. Ativar autenticação por e-mail e senha

1. No Firebase Console, abra **Criação > Authentication**.
2. Clique em **Vamos começar**.
3. Abra a aba **Sign-in method**.
4. Selecione **E-mail/senha**.
5. Ative a primeira opção **E-mail/senha**.
6. Clique em **Salvar**.

### 5. Executar e validar

1. Abra o projeto no Android Studio.
2. Aguarde a sincronização do Gradle.
3. Inicie um emulador ou conecte um smartphone.
4. Execute o módulo `app`.
5. Na tela inicial, toque em **Cadastre-se**.
6. Use um e-mail válido e uma senha com pelo menos 6 caracteres.
7. Após o cadastro, confirme que o acervo foi aberto.
8. No Firebase Console, abra **Authentication > Users** e confirme que o usuário foi registrado.
9. Use **Sair** no aplicativo e faça login novamente com a mesma conta.

## Conceitos Aplicados

- Autenticação com backend Firebase
- Arquitetura orientada a estado
- Repository Pattern
- ViewModel
- StateFlow
- Componentização com Composables
- Validação de formulário
- Tratamento de erros de rede e autenticação
- Persistência de sessão
- Material Design 3
- Design responsivo
- Acessibilidade com descrições de conteúdo

## Créditos das Imagens

- Vaso globular Marajoara, fotografia de Dornicke, acervo do Museu Nacional, licença CC BY-SA 4.0, via [Wikimedia Commons](https://commons.wikimedia.org/wiki/File:Cultura_Marajoara_-_Vaso_Globular_MN_01_(cropped).jpg).
- Cestaria Paresi, fotografia de Daderot no Memorial dos Povos Indígenas, licença CC0, via [Wikimedia Commons](https://commons.wikimedia.org/wiki/File:Paresi_baskets_-_Memorial_dos_Povos_Ind%C3%ADgenas_-_Brasilia_-_DSC00502.JPG).

## Autor

Antonio Bernardino de Sena Neto

Projeto desenvolvido para fins acadêmicos e de aprendizado em desenvolvimento Android nativo com Kotlin, Jetpack Compose e Firebase.
