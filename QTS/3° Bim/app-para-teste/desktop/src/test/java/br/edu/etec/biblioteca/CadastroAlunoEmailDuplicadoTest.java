package br.edu.etec.biblioteca;

import br.edu.etec.biblioteca.core.Database;
import br.edu.etec.biblioteca.models.Aluno;

import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import java.sql.Connection;
import java.sql.Statement;
import java.util.UUID;

import static org.junit.jupiter.api.Assertions.assertFalse;
import static org.junit.jupiter.api.Assertions.assertTrue;

class CadastroAlunoEmailDuplicadoTest {

    private String sufixo;
    private Aluno aluno;

    @BeforeEach
    void preparar() {
        Database.definirBanco("data/db_teste.db");
        Database.criarBancoSeNecessario();
        sufixo = UUID.randomUUID().toString().substring(0, 8);
        aluno = new Aluno();
    }

    @AfterEach
    void limpar() throws Exception {
        Connection c = Database.getConnection();
        try (Statement st = c.createStatement()) {
            st.executeUpdate("DELETE FROM alunos WHERE email LIKE '%" + sufixo + "%'");
        } catch (Exception ignored) {
        }
    }

    @Test
    void emailDuplicadoDeveSerRecusado() {
        String email = "dup_" + sufixo + "@escola.edu";
        assertTrue(aluno.criar("Aluno Original", email, "3A", "11999990001"));
        assertFalse(aluno.criar("Aluno Duplicado", email, "3B", "11999990002"));
    }
}
