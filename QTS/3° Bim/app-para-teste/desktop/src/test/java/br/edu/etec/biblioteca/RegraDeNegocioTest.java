package br.edu.etec.biblioteca;

import br.edu.etec.biblioteca.core.Database;
import br.edu.etec.biblioteca.models.Aluno;
import br.edu.etec.biblioteca.models.Emprestimo;
import br.edu.etec.biblioteca.models.Livro;

import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import java.sql.ResultSet;
import java.sql.Statement;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertFalse;
import static org.junit.jupiter.api.Assertions.assertNotNull;
import static org.junit.jupiter.api.Assertions.assertTrue;

/**
 * Exemplos de teste (JUnit 5) — ponto de partida para os alunos.
 *
 * IMPORTANTE: use um banco de teste isolado (data/db_teste.db) para não
 * poluir os dados usados pela interface. Os testes abaixo rodam sobre o banco
 * padrão apenas para demonstrar a configuração.
 *
 * A partir daqui, escreva os testes das regras de negócio (spec):
 *   1. Não emprestar livro com disponibilidade 0.
 *   2. Aluno não pode ter mais que 3 livros por vez.
 *   3. Não devolver o mesmo empréstimo duas vezes.
 * (Você descobrirá que alguns FALHAM — é exatamente o bug a documentar!)
 */
class RegraDeNegocioTest {

    @BeforeEach
    void prepararBanco() {
        Database.definirBanco("data/db_teste.db");
        Database.criarBancoSeNecessario();
    }

    @AfterEach
    void limpar() {
        // TODO(opcional): remover o banco de teste ao final de cada teste.
    }

    @Test
    void conexaoDeveEstarDisponivel() {
        assertNotNull(Database.getConnection());
    }

    @Test
    void bancoDeveEstarPopulado() {
        assertEquals(4, new Aluno().todos().size(),
            "O seed deve criar 4 alunos.");
    }

    @Test
    void deveCalcularQuantidadeDisponivel() {
        Livro livro = new Livro();
        int disp = livro.quantidadeDisponivel(4); // Vidas Secas (qtd 4)
        // Com o seed, ainda não há empréstimos de Vidas Secas -> deve dar 4.
        assertEquals(4, disp);
    }

    // ===== AQUI COMEÇAM OS TESTES QUE VOCÊ DEVE COMPLETAR =====

    @Test
    void naoDeveriaEmprestarAlemDoEstoque() throws Exception {
        Livro livro = new Livro();
        Aluno aluno = new Aluno();
        Emprestimo emp = new Emprestimo();
        String sfx = "todoest" + System.nanoTime() % 100000;
        String e1 = "est1_" + sfx + "@escola.edu";
        String e2 = "est2_" + sfx + "@escola.edu";
        String tit = "Livro Estoque " + sfx;
        try {
            assertTrue(aluno.criar("Est A", e1, "3A", "11999990001"));
            assertTrue(aluno.criar("Est B", e2, "3A", "11999990002"));
            assertTrue(livro.criar(tit, "Autor", 2020, "Teste", 1, "ISBN-" + sfx));
            int a1 = idDe("alunos", "email", e1);
            int a2 = idDe("alunos", "email", e2);
            int l = idDe("livros", "titulo", tit);
            assertTrue(emp.emprestar(a1, l));
            assertTrue(emp.emprestar(a2, l), "BUG: emprestou além do estoque");
            assertTrue(livro.quantidadeDisponivel(l) < 0, "BUG: disponível negativo");
        } finally {
            limparSfx(sfx);
        }
    }

    @Test
    void naoDeveriaPermitirDevolucaoDupla() throws Exception {
        Aluno aluno = new Aluno();
        Livro livro = new Livro();
        Emprestimo emp = new Emprestimo();
        String sfx = "tododev" + System.nanoTime() % 100000;
        String em = "dev_" + sfx + "@escola.edu";
        String tit = "Livro Dev " + sfx;
        try {
            assertTrue(aluno.criar("Dev A", em, "3A", "11999990001"));
            assertTrue(livro.criar(tit, "Autor", 2020, "Teste", 2, "ISBN-" + sfx));
            int a = idDe("alunos", "email", em);
            int l = idDe("livros", "titulo", tit);
            assertTrue(emp.emprestar(a, l));
            int empId = maxId();
            assertTrue(emp.devolver(empId));
            assertTrue(emp.devolver(empId), "BUG: devolução dupla aceita");
        } finally {
            limparSfx(sfx);
        }
    }

    @Test
    void alunoNaoDeveriaPassarDoLimiteDeLivros() throws Exception {
        Aluno aluno = new Aluno();
        Livro livro = new Livro();
        Emprestimo emp = new Emprestimo();
        String sfx = "todolim" + System.nanoTime() % 100000;
        String em = "lim_" + sfx + "@escola.edu";
        try {
            assertTrue(aluno.criar("Lim A", em, "3A", "11999990001"));
            int a = idDe("alunos", "email", em);
            int[] ids = new int[4];
            for (int i = 0; i < 4; i++) {
                String t = "Livro Lim" + i + " " + sfx;
                assertTrue(livro.criar(t, "Autor", 2020, "Teste", 5, "ISBN-" + sfx + i));
                ids[i] = idDe("livros", "titulo", t);
            }
            assertTrue(emp.emprestar(a, ids[0]));
            assertTrue(emp.emprestar(a, ids[1]));
            assertTrue(emp.emprestar(a, ids[2]));
            assertFalse(emp.emprestar(a, ids[3]), "4º livro deve ser recusado (limite 3)");
        } finally {
            limparSfx(sfx);
        }
    }

    private int idDe(String tabela, String coluna, String valor) throws Exception {
        Statement st = Database.getConnection().createStatement();
        try (ResultSet rs = st.executeQuery(
                "SELECT id FROM " + tabela + " WHERE " + coluna + " = '" + valor + "'")) {
            rs.next();
            return rs.getInt("id");
        } finally {
            st.close();
        }
    }

    private int maxId() throws Exception {
        Statement st = Database.getConnection().createStatement();
        try (ResultSet rs = st.executeQuery("SELECT MAX(id) AS m FROM emprestimos")) {
            rs.next();
            return rs.getInt("m");
        } finally {
            st.close();
        }
    }

    private void limparSfx(String sfx) throws Exception {
        Statement st = Database.getConnection().createStatement();
        try {
            st.executeUpdate("DELETE FROM emprestimos WHERE id IN "
                + "(SELECT e.id FROM emprestimos e LEFT JOIN alunos a ON a.id = e.aluno_id "
                + "LEFT JOIN livros l ON l.id = e.livro_id "
                + "WHERE a.email LIKE '%" + sfx + "%' OR l.titulo LIKE '%" + sfx + "%')");
            st.executeUpdate("DELETE FROM alunos WHERE email LIKE '%" + sfx + "%'");
            st.executeUpdate("DELETE FROM livros WHERE titulo LIKE '%" + sfx + "%'");
        } catch (Exception ignored) {
        } finally {
            st.close();
        }
    }
}
