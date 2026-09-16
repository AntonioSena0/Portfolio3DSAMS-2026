# Relatório de Testes — Biblioteca Escolar Desktop

**Aluno(s):** Antonio Bernardino de Sena Neto, Beatriz Galdino Torres, João Pedro Rodrigues
**Turma:** 3° DS AMS
**Data:** 10/09/2026

---

## 1. Plano de Testes

| ID | Módulo | Tipo | Pré-condição | Ação | Resultado esperado | Resultado obtido | Status (P/F) |
|----|--------|------|--------------|------|--------------------|------------------|--------------|
| CT-01 | Empréstimos | Funcional / Caixa preta | Livro com disponível > 0, aluno com < 3 livros | Emprestar pela UI | Registra o empréstimo | Empréstimo registrado | P |
| CT-02 | Empréstimos | Funcional / Caixa preta | Livro com disponível = 0 (ex.: "O Cortiço", qtd 1, já emprestado) | Emprestar o mesmo livro p/ outro aluno | Sistema recusa | Sistema permite; disponível fica negativo | F (D01) |
| CT-03 | Empréstimos | Funcional / Caixa preta | Aluno com 3 empréstimos ativos | Tentar o 4º empréstimo | Sistema recusa | Sistema recusa ("atingiu o limite") | P |
| CT-04 | Empréstimos | Funcional / Caixa preta | Empréstimo já devolvido | Devolver o mesmo empréstimo 2x | 2ª devolução falha | 2ª devolução "funciona" e sobrescreve a data | F (D02) |
| CT-05 | Alunos | Funcional / Caixa preta | E-mail já cadastrado | Cadastrar aluno com e-mail duplicado | Mensagem "e-mail já cadastrado" | Falha com mensagem genérica ("Não foi possível cadastrar") | F (D03) |
| CT-06 | Alunos | Funcional / Caixa preta | Aluno selecionado na tabela | Clicar "Excluir selecionado" e confirmar | Aluno excluído da lista | Linha continua lá (nada é excluído) | F (D08) |
| CT-07 | Livros | Funcional / Caixa preta | Tela de cadastro de livro | Cadastrar livro com quantidade -5 | Sistema recusa (qtd < 0) | Livro cadastrado com qtd negativa | F (D05) |
| CT-08 | Livros | Funcional / Caixa preta | Acervo com livros | Buscar por `%` | Nenhum resultado (ou só literais) | Retorna todo o acervo (curinga vaza) | F (D06) |
| CT-09 | Livros | Funcional / Caixa preta | Livro selecionado na tabela | Clicar "Excluir selecionado" | Livro excluído | Aviso "ainda não implementada" | F (D09) |
| CT-10 | Início | Funcional / Caixa cinza | Seed padrão (empréstimo de 20 dias atrás ativo) | Abrir aba Início | 1 atraso listado; totais corretos | Totais e atraso exibidos corretamente | P |
| CT-11 | Livros/Alunos | Segurança / Caixa branca | Ler `Livro.buscar/criar`, `Aluno.criar` | Inserir `' OR '1'='1` na busca | Entrada tratada como texto | String concatenada no SQL (injeção) | F (D06) |
| CT-12 | Alunos | Integridade / Caixa branca | Aluno com empréstimo ativo | Chamar `Aluno.remover(id)` | Bloqueia ou trata pendência | Remove mesmo com pendência | F (D07) |
| CT-13 | Alunos | Validação / Caixa branca | — | Chamar `emailValido("teste@@escola..com")` | Retorna false | Retorna true | F (D04) |
| CT-14 | Livros/Empréstimos | Manutenibilidade / Caixa branca | Ler o código | Inspecionar `disponibilidade()` e variável `disponivel` | Código usado ou removido | Método morto; variável calculada e ignorada | F (D10) |
| CT-15 | Geral | Não funcional (confiabilidade/usabilidade) / Caixa preta | Forçar erro (duplicado, limite, falha) | Observar mensagens | Mensagem explica a causa | Mensagens genéricas ("Não foi possível...") | F (D11) |
| CT-16 | Banco | Não funcional (portabilidade/confiabilidade) / Caixa branca | Ler `core/Database.java` | Mudar pasta de execução / simular falha de conexão | Caminho configurável; erro avisado | `data/biblioteca.db` fixo; exceção engolida; singleton sem sincronização | F (D12) |

> Observação de caixa cinza: o comentário em `Emprestimo.emprestar()` sugere que o limite
> (`>= LIMITE`) permitiria o 4º livro, mas o teste real (CT-03, JUnit
> `RegraDeNegocioTest#alunoNaoDeveriaPassarDoLimiteDeLivros`) mostra que o
> 4º é **bloqueado corretamente** — o comentário está enganoso, o comportamento está conforme a spec.

---

## 2. Defeitos Encontrados

### Defeito #D01 — Empréstimo além do estoque (disponibilidade negativa)

- **Módulo/Classe:** Empréstimos / `models/Emprestimo.java` (`emprestar`, linhas ~61-103)
- **Categoria:** funcional (regra de negócio)
- **Tipo de teste que detectou:** caixa preta (UI) + caixa branca (variável ignorada) + JUnit
- **Como reproduzir:**
  1. Use um livro com qtd 1 (ex.: "O Cortiço").
  2. Empreste para o aluno A.
  3. Empreste o mesmo livro para o aluno B.
- **Resultado esperado:** 2º empréstimo recusado ("sem exemplares disponíveis").
- **Resultado obtido:** 2º empréstimo permitido; `quantidadeDisponivel()` retorna valor NEGATIVO.
- **Gravidade:** crítica
- **Prioridade:** 1
- **Causa provável:** `int disponivel = livro.quantidadeDisponivel(livroId);` é calculado mas NUNCA checado antes do INSERT (`Emprestimo.java:74-76`).
- **Proposta de solução:** bloquear quando `disponivel <= 0`:
  ```java
  if (disponivel <= 0) { return false; } // + mensagem "Sem exemplares disponíveis"
  ```
  (ideal: usar `PreparedStatement` e transação.)
- **Teste de regressão:** `RegraDeNegocioTest#naoDeveriaEmprestarAlemDoEstoque`

### Defeito #D02 — Devolução dupla permitida

- **Módulo/Classe:** Empréstimos / `models/Emprestimo.java` (`devolver`, linhas ~110-120)
- **Categoria:** funcional (regra de negócio)
- **Tipo de teste que detectou:** caixa preta + JUnit
- **Como reproduzir:**
  1. Devolva um empréstimo.
  2. Selecione-o de novo e devolva outra vez.
- **Resultado esperado:** 2ª devolução recusada/ignorada ("já devolvido").
- **Resultado obtido:** retorna `true` e sobrescreve `data_devolucao`.
- **Gravidade:** alta
- **Prioridade:** 2
- **Causa provável:** UPDATE incondicional, sem checar o status atual.
- **Proposta de solução:** só devolver se `status = 'emprestado'`:
  ```sql
  UPDATE emprestimos SET data_devolucao = ?, status = 'devolvido'
  WHERE id = ? AND status = 'emprestado'  -- e retornar (linhasAfetadas > 0)
  ```
- **Teste de regressão:** `RegraDeNegocioTest#naoDeveriaPermitirDevolucaoDupla`

### Defeito #D03 — E-mail duplicado sem mensagem útil

- **Módulo/Classe:** Alunos / `models/Aluno.java` (`criar`) + `ui/AlunoPanel.java`
- **Categoria:** validação / usabilidade
- **Tipo de teste que detectou:** caixa preta + JUnit
- **Como reproduzir:** cadastre dois alunos com o mesmo e-mail.
- **Resultado esperado:** "E-mail já cadastrado".
- **Resultado obtido:** falha com erro genérico ("Não foi possível cadastrar o aluno.").
- **Gravidade:** média
- **Prioridade:** 3
- **Causa provável:** UNIQUE estoura `SQLException` que vira `false` mudo (`Aluno.java:84-87`); a UI não diferencia a causa (`AlunoPanel.java:114-117`).
- **Proposta de solução:** checar e-mail existente antes do INSERT e/ou capturar a mensagem de constraint e exibir "E-mail já cadastrado".
- **Teste de regressão:** `CadastroAlunoEmailDuplicadoTest#emailDuplicadoDeveSerRecusado`

### Defeito #D04 — Validação de e-mail fraca

- **Módulo/Classe:** Alunos / `models/Aluno.java` (`emailValido`, linhas ~109-115)
- **Categoria:** validação
- **Tipo de teste que detectou:** caixa branca + JUnit
- **Como reproduzir:** chamar `emailValido("teste@@escola..com")`.
- **Resultado esperado:** `false`.
- **Resultado obtido:** `true` (só verifica `contains("@")` e `contains(".")`).
- **Gravidade:** média
- **Prioridade:** 4
- **Causa provável:** regex/validação incompleta; método nem é usado no cadastro.
- **Proposta de solução:** validar com regex (ex.: `^[^@\s]+@[^@\s]+\.[^@\s]+$`) e usar no fluxo de cadastro.
- **Teste de regressão:** `ValidacaoEmailTest#emailInvalidoComArrobaDuploPassaNaValidacaoAtual`

### Defeito #D05 — Livro com quantidade negativa aceito

- **Módulo/Classe:** Livros / `models/Livro.java` (`criar`, linhas ~100-110)
- **Categoria:** funcional (validação)
- **Tipo de teste que detectou:** caixa preta + JUnit
- **Como reproduzir:** cadastrar livro com quantidade -5.
- **Resultado esperado:** recusa ("quantidade inválida").
- **Resultado obtido:** livro criado com qtd -5.
- **Gravidade:** média
- **Prioridade:** 3
- **Causa provável:** nenhuma validação de quantidade (comentário "BUG DE VALIDAÇÃO" no código).
- **Proposta de solução:**
  ```java
  if (quantidade < 0) return false;
  ```
  + `CHECK(quantidade >= 0)` no DDL.
- **Teste de regressão:** `CadastroLivroQuantidadeTest#livroComQuantidadeNegativaEAceitoAtualmente`

### Defeito #D06 — SQL injection + curingas na busca/cadastro (SEGURANÇA)

- **Módulo/Classe:** Livros/Alunos/Empréstimos / `models/*.java` + core
- **Categoria:** segurança
- **Tipo de teste que detectou:** caixa branca + caixa preta + JUnit
- **Como reproduzir:**
  1. Buscar por `%` retorna todo o acervo.
  2. Entradas com aspas (ex.: `' OR '1'='1`) são concatenadas no SQL (`Livro.buscar`, `Livro.criar`/`porId`, `Aluno.criar`/`buscar`/`remover`, `Emprestimo.emprestar`/`devolver`).
- **Resultado esperado:** entrada tratada como texto literal (placeholder).
- **Resultado obtido:** concatenação direta → injeção; `%` e `_` viram curingas.
- **Gravidade:** crítica
- **Prioridade:** 1
- **Causa provável:** uso de `Statement` + concatenação em vez de `PreparedStatement`.
- **Proposta de solução:** trocar tudo por `PreparedStatement`:
  ```java
  try (PreparedStatement ps = conn.prepareStatement(
      "SELECT ... WHERE titulo LIKE ? ESCAPE '\\' OR autor LIKE ? ESCAPE '\\'")) {
      String t = termo.replace("\\", "\\\\").replace("%", "\\%").replace("_", "\\_");
      ps.setString(1, "%" + t + "%"); ps.setString(2, "%" + t + "%"); ...
  }
  ```
- **Teste de regressão:** `BuscaLivroTest#buscaPorCuringaRetornaTodoOAcervoAtualmente`

### Defeito #D07 — Excluir aluno com empréstimo pendente (model permite)

- **Módulo/Classe:** Alunos / `models/Aluno.java` (`remover`, linhas ~94-102)
- **Categoria:** funcional (integridade)
- **Tipo de teste que detectou:** caixa branca/cinza + JUnit
- **Como reproduzir:** criar aluno, emprestar um livro, chamar `remover(id)`.
- **Resultado esperado:** bloqueio ou tratamento da pendência (spec).
- **Resultado obtido:** retorna `true` e apaga (empréstimos ficam órfãos).
- **Gravidade:** alta
- **Prioridade:** 2
- **Causa provável:** DELETE sem checar empréstimos ativos; sem FK enforcement (`Database.java` não ativa `foreign_keys`).
- **Proposta de solução:** antes do DELETE, contar pendências e recusar com mensagem; + `PRAGMA foreign_keys=ON` e FK (`aluno_id REFERENCES alunos(id)`).
- **Teste de regressão:** `ExclusaoAlunoComPendenciaTest#alunoComEmprestimoAtivoERemovidoAtualmente`

### Defeito #D08 — Botão "Excluir" de aluno não exclui (exclusão falsa)

- **Módulo/Classe:** Alunos / `ui/AlunoPanel.java` (linhas ~168-177)
- **Categoria:** funcional (UI)
- **Tipo de teste que detectou:** caixa preta + caixa branca
- **Como reproduzir:** selecione um aluno, clique "Excluir selecionado", confirme YES.
- **Resultado esperado:** aluno removido da tabela/banco.
- **Resultado obtido:** confirmação acontece mas `Aluno.remover()` NUNCA é chamado; a tabela só é recarregada (linha continua).
- **Gravidade:** alta
- **Prioridade:** 2
- **Causa provável:** chamada a `remover()` ausente/comentada no listener.
- **Proposta de solução:** chamar `new Aluno().remover(id)` após confirmação (respeitando D07: bloquear se houver pendência e informar), depois recarregar.
- **Teste de regressão:** manual (UI) — repetir os passos e conferir que a linha some.

### Defeito #D09 — Exclusão de livro não implementada

- **Módulo/Classe:** Livros / `ui/LivroPanel.java` (linhas ~197-206)
- **Categoria:** funcional (UI)
- **Tipo de teste que detectou:** caixa preta
- **Como reproduzir:** selecione um livro e clique "Excluir selecionado".
- **Resultado esperado:** exclusão (tratando pendências, cf. spec).
- **Resultado obtido:** aviso "Funcionalidade ... ainda não implementada."
- **Gravidade:** média
- **Prioridade:** 4
- **Causa provável:** funcionalidade não implementada (e `Livro.remover()` também não trata empréstimos ativos — mesmo problema de D07).
- **Proposta de solução:** implementar exclusão com checagem de pendências + mensagem.
- **Teste de regressão:** manual (UI).

### Defeito #D10 — Código morto / variável ignorada

- **Módulo/Classe:** Livros/Empréstimos / `models/Livro.java` (`disponibilidade`, ~154-162) e `models/Emprestimo.java` (variável `disponivel`, ~74-76)
- **Categoria:** manutenibilidade (código morto)
- **Tipo de teste que detectou:** caixa branca
- **Como reproduzir:** inspeção — `disponibilidade()` não é chamado em nenhuma UI; `disponivel` é calculado e nunca usado.
- **Resultado esperado:** código usado ou removido.
- **Resultado obtido:** método morto + variável ignorada (que esconde o D01).
- **Gravidade:** baixa
- **Prioridade:** 5
- **Causa provável:** refatoração incompleta.
- **Proposta de solução:** usar `disponibilidade()` na UI (selo Disponível/Última unidade/Indisponível) e a variável no bloqueio do D01; ou remover.
- **Teste de regressão:** compilação + revisão (grep por chamadas).

### Defeito #D11 — Mensagens de erro genéricas (confiabilidade/usabilidade)

- **Módulo/Classe:** UI + models (`AlunoPanel`, `LivroPanel`, `EmprestimoPanel`, `Database`)
- **Categoria:** não funcional (confiabilidade/usabilidade)
- **Tipo de teste que detectou:** caixa preta + caixa branca
- **Como reproduzir:** force qualquer erro (duplicado, limite, estoque, falha de banco).
- **Resultado esperado:** mensagem que explica a causa e o que fazer.
- **Resultado obtido:** "Não foi possível..." genérico; erros de banco só vão ao console (`printStackTrace`), sem feedback na UI.
- **Gravidade:** média
- **Prioridade:** 3
- **Causa provável:** exceções engolidas/convertidas em boolean sem motivo.
- **Proposta de solução:** retornar motivos (enum/exceção de negócio) e exibir na UI, ex.: "Limite de 3 livros atingido", "Sem exemplares disponíveis", "E-mail já cadastrado"; logar erros técnicos.
- **Teste de regressão:** manual (repetir CT-02/CT-04/CT-05 e ler as mensagens).

### Defeito #D12 — Caminho do banco fixo + falhas silenciosas (portabilidade)

- **Módulo/Classe:** `core/Database.java` (linhas ~21, 31-45, 60-63, 114-117)
- **Categoria:** não funcional (portabilidade/confiabilidade)
- **Tipo de teste que detectou:** caixa branca
- **Como reproduzir:** rodar o app de pastas diferentes; simular falha de conexão.
- **Resultado esperado:** caminho configurável; falha de banco avisa o usuário.
- **Resultado obtido:** `data/biblioteca.db` fixo relativo ao CWD; exceção engolida → `conexao` null → NPE adiante; sem FK/timeouts; DDL silencioso.
- **Gravidade:** média
- **Prioridade:** 4
- **Causa provável:** configuração hard-coded + catch que só printa.
- **Proposta de solução (PARCIALMENTE APLICADA p/ os testes):** caminho configurável via propriedade (`-Dbiblioteca.db=...`) + `Database.definirBanco(caminho)` / `usarBancoPadrao()`, com `getConnection()` sincronizado; os testes JUnit usam `data/db_teste.db` isolado. Falta ainda: validar a conexão na abertura com diálogo de erro, ativar `PRAGMA foreign_keys=ON` e tratar DDL silencioso.
- **Teste de regressão:** rodar de outra pasta; teste de conexão (`RegraDeNegocioTest`); hash do `biblioteca.db` inalterado após o `mvn test`.

---

## 3. Testes automatizados (JUnit)

- Lista de classes de teste criadas/alteradas:
  - `br.edu.etec.biblioteca.RegraDeNegocioTest` (COMPLETADA: os 3 TODOs implementados — `naoDeveriaEmprestarAlemDoEstoque`, `naoDeveriaPermitirDevolucaoDupla`, `alunoNaoDeveriaPassarDoLimiteDeLivros` — + 3 testes de exemplo já existentes)
  - `br.edu.etec.biblioteca.BuscaLivroTest` (NOVA, 2 testes: busca normal + curinga `%`)
  - `br.edu.etec.biblioteca.CadastroAlunoEmailDuplicadoTest` (NOVA, 1 teste)
  - `br.edu.etec.biblioteca.CadastroLivroQuantidadeTest` (NOVA, 1 teste: quantidade negativa)
  - `br.edu.etec.biblioteca.ExclusaoAlunoComPendenciaTest` (NOVA, 1 teste)
  - `br.edu.etec.biblioteca.ValidacaoEmailTest` (NOVA, 3 testes)
  - Sobre o isolamento: cada teste cria seus próprios dados (com um código único no e-mail/título) e apaga tudo no final, e todos rodam num banco separado (`data/db_teste.db`), então o banco de verdade da interface nunca é alterado (a gente conferiu pelo hash do arquivo antes e depois).

--- 

#### Resultado do `mvn test` (com o banco de teste resetado antes da execução):

```
[INFO] Running br.edu.etec.biblioteca.BuscaLivroTest
[INFO] Tests run: 2, Failures: 0, Errors: 0, Skipped: 0 -- in br.edu.etec.biblioteca.BuscaLivroTest
[INFO] Running br.edu.etec.biblioteca.CadastroAlunoEmailDuplicadoTest
[INFO] Tests run: 1, Failures: 0, Errors: 0, Skipped: 0 -- in br.edu.etec.biblioteca.CadastroAlunoEmailDuplicadoTest
[INFO] Running br.edu.etec.biblioteca.CadastroLivroQuantidadeTest
[INFO] Tests run: 1, Failures: 0, Errors: 0, Skipped: 0 -- in br.edu.etec.biblioteca.CadastroLivroQuantidadeTest
[INFO] Running br.edu.etec.biblioteca.ExclusaoAlunoComPendenciaTest
[INFO] Tests run: 1, Failures: 0, Errors: 0, Skipped: 0 -- in br.edu.etec.biblioteca.ExclusaoAlunoComPendenciaTest
[INFO] Running br.edu.etec.biblioteca.RegraDeNegocioTest
[INFO] aluno já atingiu o limite (3)
[INFO] Tests run: 6, Failures: 0, Errors: 0, Skipped: 0 -- in br.edu.etec.biblioteca.RegraDeNegocioTest
[INFO] Running br.edu.etec.biblioteca.ValidacaoEmailTest
[INFO] Tests run: 3, Failures: 0, Errors: 0, Skipped: 0 -- in br.edu.etec.biblioteca.ValidacaoEmailTest
[INFO] Results:
[INFO] Tests run: 14, Failures: 0, Errors: 0, Skipped: 0
[INFO] BUILD SUCCESS
```

> Um detalhe importante: os testes foram escritos para confirmar o comportamento real do sistema (que está bugado), então eles passam mesmo quando o defeito existe — por exemplo, o teste afirma que o empréstimo além do estoque *acontece*. A ideia é que, quando cada defeito for corrigido, o teste correspondente seja ajustado para o comportamento esperado e continue passando, servindo como teste de regressão.

## 4. Classificação e Estatísticas

- Total de casos de teste executados (manuais, plano): **16**
- Total aprovados / reprovados (manuais): **3 P / 13 F**
- Testes automatizados JUnit: **14 (14 aprovados, 0 reprovados, 0 erros)**
- Nº de defeitos por categoria:
  - Funcional (regra de negócio/integridade/UI): **6** (D01, D02, D05, D07, D08, D09)
  - Segurança: **1** (D06)
  - Validação: **2** (D03, D04)
  - Manutenibilidade (código morto): **1** (D10)
  - Não funcional (usabilidade/confiabilidade): **1** (D11)
  - Não funcional (portabilidade/confiabilidade): **1** (D12)
- Nº de defeitos por gravidade:
  - Crítica: **2** (D01, D06)
  - Alta: **3** (D02, D07, D08)
  - Média: **6** (D03, D04, D05, D09, D11, D12)
  - Baixa: **1** (D10)
- **Total de defeitos documentados: 12**

---

## 5. Conclusão

Na avaliação do grupo, o sistema **não está pronto para uso real**. O que mais chamou a nossa atenção foi que justo as regras principais estão quebradas: dá para emprestar livro sem estoque (e o disponível fica negativo — D01) e dá para devolver o mesmo empréstimo duas vezes (D02). Além disso, achamos uma falha grave de segurança (SQL injection em quase todos os models — D06) e descobrimos que o botão de excluir aluno finge que exclui, mas não exclui nada (D08), enquanto o de livro nem chegou a ser implementado (D09). Completam a lista as validações fracas (D03–D05), a perda de integridade ao excluir com pendências (D07), as mensagens de erro genéricas (D11) e o banco com caminho fixo e sem proteções (D12).

Por outro lado, nem tudo está errado: o **limite de 3 livros funciona** (o CT-03 passou, e o comentário no código que dizia o contrário estava enganado — foi um achado legal de caixa cinza), os atrasados (>14 dias) e os totais da tela inicial estão certos (CT-10), e a bateria de 14 testes JUnit que criamos fica como **rede de regressão** para quem for corrigir os defeitos.

Como sugestão de ordem de correção: 1) D01 + D06 (checar o estoque em transação e usar PreparedStatement); 2) D02 (só devolver se `status='emprestado'`); 3) D07/D08/D09 (exclusão de verdade, com checagem de pendências e chave estrangeira); 4) D03–D05/D11 (validações e mensagens que expliquem o erro); 5) D12 (configurar o caminho do banco, ativar `PRAGMA foreign_keys=ON`, mostrar os erros na tela); 6) D10 (remover ou aproveitar o código morto). A cada correção, o teste JUnit correspondente deve ser atualizado para o comportamento esperado, mantendo o `mvn test` verde.
