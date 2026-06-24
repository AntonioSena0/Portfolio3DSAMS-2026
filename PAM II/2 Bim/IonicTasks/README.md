# IonicTasks – Gerenciador de Tarefas Offline

Aplicativo mobile desenvolvido com **Ionic + Angular + Capacitor**, com suporte a:

- Cadastro e login de usuários
- CRUD de tarefas por usuário
- Armazenamento local com **SQLite** via `@capacitor-community/sqlite` (offline-first)

> Observação: o SQLite nativo só funciona em dispositivos/emuladores. No navegador, o plugin usa fallback web ou pode não funcionar totalmente.

---

## Tecnologias

- **Ionic Framework 8** (Angular standalone)
- **Angular 18** (standalone components, rotas lazy)
- **Capacitor 7**
- **@capacitor-community/sqlite** para banco local
- TypeScript

---

## Estrutura de telas

- **Login**
  - Formulário com validação (email, senha).
  - Redireciona para tarefas ao logar.
- **Registro**
  - Formulário com nome, email, senha e confirmação de senha.
  - Validações: campos obrigatórios, formato de email, tamanho mínimo, senhas iguais.
  - Ao registrar, cria o usuário no SQLite e já faz login.
- **Tarefas**
  - Lista de tarefas do usuário logado.
  - Formulário compacto para criar novas tarefas (título, descrição, datas, prioridade).
  - Armazenamento e leitura das tarefas via `DatabaseService` / SQLite.

---

## Pré-requisitos

- Node.js e npm instalados.
- **Ionic CLI** global:
  ```bash
  npm install -g @ionic/cli
  ```
- **Android Studio** com SDK e platform-tools (adb).
- Java JDK (17 ou 21).
- Dispositivo Android ou emulador para testar o SQLite nativo.

---

## Instalação do projeto

1. Clone o repositório (ou copie o projeto para um caminho sem acentos/espaços especiais, por exemplo `C:\dev\IonicTasks` no Windows):
   ```bash
   # CMD
   git clone https://github.com/AntonioSena0/Portfolio3DSAMS-2026.git
   cd PAM II/2 Bim/IonicTasks
   ```

2. Instale as dependências:
   ```bash
   npm install
   ```

---

## Rodando no navegador (apenas UI)

Use o navegador apenas para desenvolver a interface e validar navegação/validações de formulário. O SQLite nativo **não funciona** como no Android/iOS.

```bash
ionic serve
```

- App disponível em: `http://localhost:8100`
- Fluxo esperado:
  - `/register` → criar usuário.
  - `/login` → login.
  - `/tasks` → lista e criação de tarefas.

---

## Setup do Capacitor + Android

1. Inicialize o Capacitor (se ainda não foi feito):
   ```bash
   npx cap init IonicTasks com.example.ionictasks
   ```

2. Adicione a plataforma Android:
   ```bash
   npx cap add android
   ```

3. Instale o plugin SQLite:
   ```bash
   npm install @capacitor-community/sqlite
   npx cap sync
   ```

---

## Rodando no dispositivo Android (recomendado)

Para testar login/registro/tarefas com **SQLite nativo**, use um dispositivo Android ou emulador.

### 1. Ativar modo desenvolvedor no celular

1. Em **Configurações > Sobre o telefone**, toque 7x em **Número da versão**.
2. Em **Opções do desenvolvedor**, ative:
   - Depuração USB.
3. Conecte o celular ao PC via USB e aceite a permissão de depuração.

### 2. Verificar se o dispositivo está visível

No terminal:

```bash
adb devices
```

Deve aparecer algo como:

```text
List of devices attached
5A484JD5B    device
```

Se aparecer `unauthorized`, aceite a permissão no celular.

### 3. Build + sync do app

Na pasta do projeto:

```bash
ionic build
npx cap sync android
```

### 4. Rodar com live reload no dispositivo

```bash
ionic capacitor run android -l --external
```

- Escolha o dispositivo quando for perguntado.
- O Ionic vai:
  - Subir o servidor em `http://<IP_DO_PC>:8100`,
  - Abrir o app no dispositivo apontando para essa URL.

Certifique-se de que o PC e o celular estão na mesma rede.

### 5. Rodar via Android Studio (sem live reload)

Se preferir um APK “normal” (sem live reload):

```bash
ionic build
npx cap sync android
npx cap open android
```

No Android Studio:

1. Selecione o dispositivo (ou emulador) no topo.
2. Clique no botão **Run**.

O app será instalado e aberto no aparelho.

---

## Fluxo de uso no app

1. Abra o app.
2. Vá para a tela de **Registro**:
   - Preencha nome, email, senha e confirmação.
   - Envie o formulário.
3. Você será redirecionado para **Tarefas**:
   - Veja a lista de tarefas do usuário (vazia no começo).
   - Use o botão de adicionar para criar novas tarefas.
4. Feche o app e abra de novo:
   - Vá em **Login**, informe o mesmo email/senha.
   - As tarefas do usuário devem aparecer novamente (persistidas em SQLite).

---

## Problemas comuns

- **Erro de path não ASCII no Windows**  
  Caminhos com acentos/símbolos (ex.: `2° Bim`) podem quebrar o build Android.  
  Solução: mover o projeto para um caminho simples, ex.: `C:\dev\IonicTasks`.

- **No navegador, login/SQLite não funcionam direito**  
  Isso é esperado: o plugin usa fallback web ou fica limitado.  
  Solução: testar login/registro/tarefas em dispositivo/emulador, como descrito acima.

---

## Scripts úteis

- Rodar no navegador:
  ```bash
  ionic serve
  ```

- Build web:
  ```bash
  ionic build
  ```

- Sync com Capacitor:
  ```bash
  npx cap sync
  ```

- Rodar no Android com live reload:
  ```bash
  ionic capacitor run android -l --external
  ```

- Abrir projeto nativo no Android Studio:
  ```bash
  npx cap open android
  ```
