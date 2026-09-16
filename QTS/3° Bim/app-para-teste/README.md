# App para Testes de Software — Web + Desktop

Repositório educacional da disciplina **Qualidade e Teste de Software**. Contém
**duas versões** da mesma aplicação (Sistema de Biblioteca Escolar),
**propositalmente com bugs**, para que os alunos pratiquem testes de
**caixa branca, preta e cinza**, testes **funcionais** e **não funcionais**,
encontrem **problemas no código**, proponham **soluções** e **documentem tudo**.

## Estrutura

```
app-para-teste-web/
├── web/        # Versão WEB  (HTML + CSS + JS + PHP + SQLite)
└── desktop/    # Versão DESKTOP (Java Swing + SQLite)
```

Cada versão tem seus próprios:
- **README** com instruções de execução;
- **docs/ATIVIDADE_ALUNO.md** (guia da atividade);
- **docs/MODELO_RELATORIO.md** (template de entrega);

## Como rodar

### Web (`web/`)

```bash
cd web
php -S localhost:8000 -t public
# acesse http://localhost:8000
```

### Desktop (`desktop/`)

Requisições: JDK 17+ e Maven.

```bash
cd desktop
mvn package
java -jar target/biblioteca-desktop.jar
# ou (Windows)  run.bat   |   (Linux/macOS)  ./run.sh
```

## Bugs plantados (resumo)

Percente as mesmas categorias nas duas versões:

- **Injeção de SQL** (busca/consulta por concatenação)
- **XSS** / falta de saída escapada (web)
- **Regra de negócio quebrada**: empréstimo sem checar disponibilidade (pode
  gerar disponibilidade **negativa**)
- **Devolução dupla** não bloqueada
- **Exclusão falsa** (botão confirma mas não exclui)
- **Erros silenciosos** / mensagens genéricas
- **Código morto** e validações fracas
- **Não funcionais**: acessibilidade, contraste, configuração fixa

> Confira os gabaritos em `web/docs/BUGS_E_SOLUCOES.md` e
> `desktop/docs/BUGS_E_SOLUCOES.md`.

---

Projeto educacional. **Use com responsabilidade.**
