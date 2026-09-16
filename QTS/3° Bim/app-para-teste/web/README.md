# Sistema de Biblioteca Escolar — App para Testes de Software

Aplicação web **propositalmente com bugs** para a disciplina de **Qualidade e
Teste de Software**. O objetivo é que os alunos pratiquem os conceitos de:

- Testes de **caixa branca**, **caixa preta** e **caixa cinza**
- Testes **funcionais** e **não funcionais**
- Identificação de problemas no código e propostas de **solução**
- **Documentação** de todos os testes realizados

## Tecnologias

- Front-end: HTML, CSS e JavaScript (vanilla)
- Back-end: PHP (sem frameworks)
- Banco de dados: SQLite (arquivo local `data/biblioteca.db`)

## Como rodar

Pré-requisito: PHP 7.4+ (com extensão `pdo_sqlite`).

```bash
# entre na pasta do projeto
cd app-para-teste-web

# inicie o servidor embutido
php -S localhost:8000 -t public
```

Acesse: <http://localhost:8000>

> O banco de dados é criado e populado automaticamente na **primeira** execução
> (veja a função `boot()` em `public/index.php`). Para recriar o banco do zero,
> basta apagar o arquivo `data/biblioteca.db` e recarregar a página.

## Estrutura do projeto

```
app-para-teste-web/
├── public/
│   ├── index.php          # Front controller (rotas + boot)
│   ├── css/estilos.css    # Estilos (com bugs de acessibilidade/UX)
│   └── js/app.js          # JavaScript (com bugs de lógica)
├── app/
│   ├── core/
│   │   └── Database.php   # Conexão SQLite (com falhas)
│   ├── models/
│   │   ├── Aluno.php      # (com bugs: SQL injection, validação)
│   │   ├── Livro.php      # (com bugs: sql injection, regra de negócio)
│   │   └── Emprestimo.php # (com bugs: regra de negócio/limite)
│   └── views/             # Telas (dashboard, alunos, livros, emprestimos)
├── data/
│   ├── biblioteca.db      # Banco SQLite (gerado automaticamente)
│   └── import_bd.php      # Script alternativo de seed
├── docs/
│   └── ...                # Materiais de apoio para o aluno
└── README.md
```

## Funcionalidades (visão geral — spec de referência)

1. **Painel**: exibe totais de livros, alunos e empréstimos, além de empréstimos
   em atraso (mais de 14 dias).
2. **Alunos**: cadastrar, listar e excluir alunos.
3. **Livros**: cadastrar, buscar (por título/autor) e listar livros, exibindo a
   quantidade disponível.
4. **Empréstimos**: registrar empréstimo de um livro a um aluno, devolver livro
   e listar todos os empréstimos.

### Regras de negócio previstas (spec)

- Um aluno não pode pegar mais de **3** livros emprestados ao mesmo tempo.
- Não é permitido emprestar um livro com **quantidade disponível 0**.
- Devolução de um livro que já foi devolvido deve ser impedida (ou ao menos
  tratada).
- Ao excluir um aluno/livro, deve-se considerar empréstimos pendentes.

> ⚠️ Várias dessas regras estão **propositalmente quebradas** no código. Sua
> missão como tester é **descobri-las** e **documentá-las**.

## Proposta de atividade (sugestão)

Cada grupo/dupla deve produzir um **relatório de testes** contendo:

1. **Plano de testes** com os casos derivados da spec acima.
2. Testes de **caixa preta** (entradas/saídas, sem olhar o código).
3. Testes de **caixa branca** (caminhos, ramos, condições — precisa ler o código).
4. Testes de **caixa cinza** (conhecimento parcial da estrutura).
5. Classificação em **funcionais** e **não funcionais** (usabilidade, segurança,
   desempenho, compatibilidade).
6. Registro de cada **defeito encontrado** com: passo a passo, resultado esperado
   vs. obtido, gravidade/prioridade.
7. Para cada bug: **proposta de correção** (código) e evidência de teste de
   regressão.

---

Feito para fins educacionais. **Use com responsabilidade.**
