# Biblioteca Escolar — Desktop (Java Swing + SQLite)

Aplicação **desktop** propositalmente com **bugs** para a disciplina de
**Qualidade e Teste de Software**. É a versão nativa (Java Swing) do mesmo
Sistema de Biblioteca Escolar que também existe na versão **web** (pasta `../web`).

Objetivo: praticar **caixa branca, preta e cinza**, testes **funcionais** e
**não funcionais**, encontrar **problemas** no código, propor **soluções** e
**documentar** tudo.

## Tecnologias

- **Java 17** + **Swing** (interface gráfica)
- **SQLite** via JDBC (driver `org.xerial:sqlite-jdbc`)
- **Maven** para build
- **JUnit 5** (já configurado para os testes que os alunos vão escrever)

## Como rodar

Pré-requisitos: **JDK 17+** e **Maven 3.8+** instalados e no `PATH`.

### Windows

```bat
build.bat   :: compila e gera target\biblioteca-desktop.jar
run.bat     :: inicia a aplicação (compila antes se preciso)
```

### Linux / macOS

```bash
chmod +x build.sh run.sh
./build.sh
./run.sh
```

### Ou manualmente (igual em qualquer SO)

```bash
mvn package                          # gera o jar executável
java -jar target/biblioteca-desktop.jar
```

> O banco `data/biblioteca.db` é criado e populado automaticamente na primeira
> execução. Para recomeçar do zero, apague o arquivo e execute novamente.

## Estrutura do projeto

```
desktop/
├── pom.xml
├── build.bat / build.sh      # build
├── run.bat / run.sh          # execução
├── src/
│   └── main/java/br/edu/etec/biblioteca/
│       ├── Main.java                 # ponto de entrada
│       ├── core/Database.java        # conexão SQLite (com falhas)
│       ├── models/
│       │   ├── Aluno.java            # (SQL injection, validação fraca)
│       │   ├── Livro.java            # (SQL injection, regra de negócio)
│       │   └── Emprestimo.java       # (disponibilidade não checada, devolução dupla)
│       └── ui/
│           ├── JanelaPrincipal.java
│           ├── DashboardPanel.java
│           ├── AlunoPanel.java
│           ├── LivroPanel.java
│           └── EmprestimoPanel.java
├── src/test/java/...         # aqui vão os JUnit tests dos alunos
├── data/                     # banco SQLite (gerado)
├── docs/                     # materiais de apoio
└── README.md
```

## Funcionalidades e regras de negócio (spec de referência)

Mesmas do app web:

1. **Início**: totais de livros/alunos/empréstimos e empréstimos atrasados (>14 dias).
2. **Alunos**: cadastrar, listar e excluir.
3. **Livros**: cadastrar, buscar por título/autor, listar e mostrar a quantidade
   disponível.
4. **Empréstimos**: emprestar, devolver e listar.
5. **Regras**:
   - Mín./máx.: aluno não pode ter mais que **3** livros emprestados por vez.
   - **Não** emprestar livro com disponível **0**.
   - **Não** devolver o mesmo livro duas vezes.
   - Ao excluir aluno/livro, tratar empréstimos pendentes.

> ⚠️ Várias dessas regras estão **propositalmente quebradas**. Descubra-as!

## Sobre as dependências

- O driver **sqlite-jdbc** e o **JUnit** são baixados automaticamente pelo Maven
  na primeira execução (arquivos ficam na pasta local `.m2`, sem necessidade de
  commits). O `pom.xml` já declara tudo.

---

Projeto educacional. **Use com responsabilidade.**
