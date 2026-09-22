# EscapeEDU - Escola Misteriosa

Aplicativo Android para gerenciamento de roteiros de Escape Room Educacional. Permite cadastrar estacoes de desafio, pistas, equipes inscritas e pontuacoes, com ranking por equipe. Dados persistidos no Firebase Firestore em tempo real.

## Funcionalidades

- Estacoes de desafio: nome, descricao do enigma, disciplina, dificuldade, tempo limite, ordem e status ativa/inativa, com busca por nome ou disciplina
- Pistas: vinculadas a uma estacao, com titulo, texto, custo em pontos e ordem, com busca por pista ou estacao
- Equipes inscritas: nome, turma/escola, membros e lider, com busca por equipe ou turma
- Pontuacoes: vinculo equipe x estacao, pontos, tempo de resolucao, pistas usadas e status concluida/pendente, com ranking Top 5 agregado por equipe
- CRUD completo: criar, listar em tempo real, editar e excluir, com dialogo de confirmacao e mensagens de retorno

## Tecnologias

- Kotlin + Jetpack Compose
- Firebase Firestore

## Estrutura do Firestore

- estacoes: nome, descricao, disciplina, dificuldade, tempoLimiteMin, ordem, ativa, criadoEm
- pistas: estacaoId, titulo, texto, custoPontos, ordem, criadoEm
- equipes: nome, turma, membros, lider, criadoEm
- pontuacoes: equipeId, equipeNome, estacaoId, estacaoNome, pontos, tempoResolucaoMin, pistasUsadas, concluida, criadoEm

## Pre-requisitos

- Android Studio Ladybug ou superior
- JDK 11
- Conta no Firebase

## Configuracao do Firebase

1. Crie um projeto em console.firebase.google.com
2. Adicione um app Android com o pacote `com.example.escape_room_app`
3. Baixe o `google-services.json` e coloque em `app/google-services.json`
4. Ative o Firestore Database em modo de teste, regiao `southamerica-east1`
5. Em Regras, publique para uso:
```
rules_version = '2';
service cloud.firestore {
  match /databases/{db}/documents {
    match /{document=**} {
      allow read, write: if true;
    }
  }
}
```

## Como executar

1. Abra a pasta no Android Studio
2. Aguarde o Sync do Gradle
3. Selecione um emulador ou dispositivo fisico
4. Clique em Run
5. O app abre com o nome EscapeEDU. Use o botao + para adicionar em cada aba

## Estrutura de pastas

- app/src/main/java/com/example/escape_room_app/MainActivity.kt
- data/model/Models.kt
- data/repo/Repositories.kt
- ui/viewmodel/EscapeViewModels.kt
- ui/screens/AppNav.kt, StationsScreen.kt, HintsScreen.kt, TeamsScreen.kt, ScoresScreen.kt
- ui/components/Common.kt
- ui/theme/Color.kt, Theme.kt, Type.kt
- FIRESTORE_SETUP.md com passo a passo detalhado do Firebase
