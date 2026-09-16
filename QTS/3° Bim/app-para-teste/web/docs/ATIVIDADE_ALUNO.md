# Guia de Atividade — Testes de Software

> Material para o aluno. Leia atentamente e siga os passos.

## Objetivo

Aplicar os conceitos da disciplina **Qualidade e Teste de Software** em um
sistema real e propositalmente "bugado": o **Sistema de Biblioteca Escolar**.

Você vai praticar **testes de caixa branca, preta e cinza**, testes
**funcionais** e **não funcionais**, encontrará **problemas no código**, proporá
**soluções** e documentará **tudo**.

## Passo 1 — Rodar a aplicação

```bash
cd app-para-teste-web
php -S localhost:8000 -t public
```

Abra <http://localhost:8000>. Navegue pelas telas: Painel, Alunos, Livros e
Empréstimos.

## Passo 2 — Ler a "spec" (especificação de referência)

O sistema deveria (segundo a especificação):

1. **Painel**: mostrar totais de livros, alunos, empréstimos e os empréstimos
   atrasados (mais de 14 dias).
2. **Alunos**: cadastrar, listar e excluir.
3. **Livros**: cadastrar, buscar por título/autor, listar e mostrar quantidade
   disponível.
4. **Empréstimos**: emprestar, devolver, listar.
5. **Regras de negócio**:
   - Aluno não pode ter mais que **3** livros emprestados por vez.
   - **Não** emprestar livro com quantidade disponível **0**.
   - Não devolver livro duas vezes.
   - Ao excluir aluno/livro, tratar empréstimos pendentes.

> Sua tarefa: **descobrir** o que está funcionando e o que **não** está.

## Passo 3 — Planejar e executar os testes

Crie uma tabela de casos de teste. Exemplo de modelo:

| ID | Módulo | Tipo | Pré-condição | Ação | Resultado esperado | Resultado obtido | Status |
|----|--------|------|--------------|------|--------------------|------------------|--------|
| CT-01 | Empréstimos | Funcional | Livro disponível, aluno sem limite | Emprestar livro | Empréstimo registrado | ? | ? |

Dicas de casos para explorar (mas **não se limite** a eles):

**Caixa preta** (não olhe o código):
- Emprestar um livro com disponível 0 (cadastre/use "Quarto de Despejo").
- Tentar pegar o 4º e o 5º livro para o mesmo aluno.
- Devolver o mesmo livro duas vezes.
- Cadastrar aluno com nome `<script>alert(1)</script>` e abrir a lista.

**Caixa branca** (agora sim, leia `app/models/`, `app/core/`, `public/index.php`):
- Procure por concatenação de SQL (`"SELECT ... $var"`).
- Encontre ramos/métodos que não são alcançados (código morto).
- Verifique se erros de banco não estão sendo silenciados.

**Caixa cinza**:
- Compare a "spec" com o que o código realmente faz.
- Ex.: a spec diz que devolução dupla deve ser tratada — o código trata?

**Não funcionais**:
- Segurança: tente inputs maliciosos (SQL injection na busca de livros:
  `' OR '1'='1`).
- Usabilidade/acessibilidade: contraste, foco de teclado, responsividade.
- Confiabilidade: comportamento em telas pequenas.

## Passo 4 — Documentar cada defeito

Para cada problema encontrado, preencha:

```
Defeito #N
-----------
Módulo/Arquivo: ...
Categoria: (funcional / não funcional / segurança / usabilidade ...)
Tipo de teste que detectou: (caixa preta / branca / cinza)
Como reproduzir: (passo a passo)
Resultado esperado:
Resultado obtido:
Gravidade: (baixa / média / alta / crítica)
Prioridade: (1-5)
Causa provável: (análise, olhando o código)
Proposta de solução: (descrição + código corrigido)
Teste de regressão: (como confirmar que o bug foi corrigido sem quebrar nada)
```

## Passo 5 — Entregar

Entregue um **relatório** contendo:
1. Plano de testes (a tabela de casos).
2. Os defeitos encontrados (no formato acima).
3. Classificação e estatísticas (quantos funcionais/não funcionais, por gravidade).
4. Conclusão e sugestões de melhoria.
