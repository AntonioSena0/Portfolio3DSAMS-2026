package br.edu.etec.biblioteca;

import br.edu.etec.biblioteca.core.Database;
import br.edu.etec.biblioteca.models.Aluno;
import br.edu.etec.biblioteca.models.Emprestimo;
import br.edu.etec.biblioteca.models.Livro;

import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.UUID;

import static org.junit.jupiter.api.Assertions.assertFalse;
import static org.junit.jupiter.api.Assertions.assertNotNull;
import static org.junit.jupiter.api.Assertions.assertTrue;

class ExclusaoAlunoComPendenciaTest {

    private String sufixo;
    private Aluno aluno;
    private Livro livro;
    private Emprestimo emprestimo;

    @BeforeEach
    void preparar() {
        Database.definirBanco("data/db_teste.db");
        Database.criarBancoSeNecessario();
        sufixo = UUID.randomUUID().toString().substring(0, 8);
        aluno = new Aluno();
        livro = new Livro();
        emprestimo = new Emprestimo();
    }

    @AfterEach
    void limpar() throws Exception {
        Connection c = Database.getConnection();
        try (Statement st = c.createStatement()) {
            st.executeUpdate("DELETE FROM emprestimos WHERE id IN "
                + "(SELECT e.id FROM emprestimos e LEFT JOIN alunos a ON a.id = e.aluno_id "
                + "LEFT JOIN livros l ON l.id = e.livro_id "
                + "WHERE a.email LIKE '%" + sufixo + "%' OR l.titulo LIKE '%" + sufixo + "%')");
            st.executeUpdate("DELETE FROM alunos WHERE email LIKE '%" + sufixo + "%'");
            st.executeUpdate("DELETE FROM livros WHERE titulo LIKE '%" + sufixo + "%'");
        } catch (Exception ignored) {
        }
    }

    @Test
    void alunoComEmprestimoAtivoNaoDeveSerRemovido() throws Exception {
        String email = "pend_" + sufixo + "@escola.edu";
        String titulo = "Livro Pend " + sufixo;
        assertTrue(aluno.criar("Aluno Pend", email, "3A", "11999990001"));
        assertTrue(livro.criar(titulo, "Autor Pend", 2020, "Teste", 2, "ISBN-" + sufixo));
        int alunoId = buscarId("alunos", "email", email);
        int livroId = buscarId("livros", "titulo", titulo);
        assertTrue(emprestimo.emprestar(alunoId, livroId));
        assertFalse(aluno.remover(alunoId),
            "Sistema deve bloquear exclusão de aluno com empréstimo ativo");
        assertNotNull(aluno.buscar(alunoId),
            "Aluno com pendência deve continuar cadastrado");
    }

    private int buscarId(String tabela, String coluna, String valor) throws Exception {
        Statement st = Database.getConnection().createStatement();
        try (ResultSet rs = st.executeQuery(
                "SELECT id FROM " + tabela + " WHERE " + coluna + " = '" + valor + "'")) {
            rs.next();
            return rs.getInt("id");
        } finally {
            st.close();
        }
    }
}
