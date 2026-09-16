# Guia de Atividade — Testes de Software (App Desktop)

> Material para o aluno. Aplicação: **Biblioteca Escolar Desktop (Java Swing + SQLite)**.

## Objetivo

Aplicar os conceitos de **Qualidade e Teste de Software** em um sistema real e
propositalmente "bugado". Você vai praticar **caixa branca, preta e cinza**,
testes **funcionais** e **não funcionais**, encontrar **problemas**, propor
**soluções** e **documentar** tudo.

## Passo 1 — Rodar a aplicação

```bash
# na pasta desktop
mvn package
java -jar target/biblioteca-desktop.jar
# ou (Windows):  run.bat    |   (Linux/macOS):  ./run.sh
```

A janela abre com abas: **Início, Alunos, Livros, Empréstimos**.

## Passo 2 — Ler a "spec" (especificação de referência)

O sistema deveria:

1. **Início**: mostrar totais e empréstimos atrasados (mais de 14 dias).
2. **Alunos**: cadastrar, listar e excluir.
3. **Livros**: cadastrar, buscar por título/autor, listar e mostrar qtd. disponível.
4. **Empréstimos**: emprestar, devolver e listar.
5. **Regras de negócio**:
   - Aluno não pode ter mais que **3** livros por vez.
   - **Não** emprestar livro com disponível **0**.
   - **Não** devolver o mesmo livro duas vezes.
   - Ao excluir aluno/livro, tratar empréstimos pendentes.

> Sua missão: **descobrir** o que funciona e o que **não** funciona.

## Passo 3 — Planejar e executar testes

Registre os casos numa tabela:

| ID | Módulo | Tipo | Pré-condição | Ação | Esperado | Obtido | P/F |
|----|--------|------|--------------|------|----------|--------|-----|
| CT-01 | Empréstimos | Funcional | Livro disponível | Emprestar | Registra | ? | ? |

Dicas (mas explore além delas):

**Caixa preta** (use a UI, sem olhar o código):
- Emprestar vários livros para um mesmo aluno até tentar o 4º e o 5º.
- Devolver o mesmo empréstimo duas vezes.
- Cadastrar aluno com e-mail duplicado.
- Testar o botão "Excluir" de aluno.

**Caixa branca** (leia `src/main/java/.../models/` e `core/Database.java`):
- Procure SQL montado por concatenação de strings (injeção).
- Encontre código morto / variáveis calculadas e não usadas.
- Verifique tratamento (ou falta) de erros de banco.

**Caixa cinza**:
- Compare a spec com o que o código realmente faz (ex.: disponibilidade e limite).

**Não funcionais**:
- Usabilidade/acessibilidade da UI (contraste, foco de teclado).
- Confiabilidade (o que acontece quando algo falha? há mensagens úteis?).
- Compatibilidade/portabilidade do caminho do banco.

## Passo 4 — Escreva também testes automatizados (JUnit)

Crie testes em `src/test/java/br/edu/etec/biblioteca/` e rode:

```bash
mvn test
```

Exemplo de estrutura de um teste (a partir daqui, você expande):

```java
package br.edu.etec.biblioteca;

import br.edu.etec.biblioteca.models.Emprestimo;
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class RegraDeNegocioTest {
    @Test
    void naoDeveriaEmprestarAlemDoEstoque() {
        Emprestimo emp = new Emprestimo();
        // TODO: preencher para reproduzir o cenário e verificar o comportamento
        // (você deve descobrir que o teste FALHA — e documentar o defeito).
    }
}
```

## Passo 5 — Documentar cada defeito

Para cada problema, preencha:

```
Defeito #N
-----------
Módulo/Classe:
Categoria: (funcional / não funcional / segurança / usabilidade ...)
Tipo de teste que detectou: (caixa preta / branca / cinza)
Como reproduzir:
Resultado esperado:
Resultado obtido:
Gravidade: (baixa / média / alta / crítica)
Prioridade: (1-5)
Causa provável: (análise do código)
Proposta de solução: (descrição + código)
Teste de regressão:
```

## Passo 6 — Entregar

Relatório com: plano de testes, defeitos encontrados, classificação/estatísticas,
conclusão e sugestões. Use o modelo em `docs/MODELO_RELATORIO.md`.
