package br.edu.etec.biblioteca;

import br.edu.etec.biblioteca.core.Database;
import br.edu.etec.biblioteca.models.Livro;

import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import java.sql.Connection;
import java.sql.Statement;
import java.util.UUID;

import static org.junit.jupiter.api.Assertions.assertTrue;

class CadastroLivroQuantidadeTest {

    private String sufixo;
    private Livro livro;

    @BeforeEach
    void preparar() {
        Database.definirBanco("data/db_teste.db");
        Database.criarBancoSeNecessario();
        sufixo = UUID.randomUUID().toString().substring(0, 8);
        livro = new Livro();
    }

    @AfterEach
    void limpar() throws Exception {
        Connection c = Database.getConnection();
        try (Statement st = c.createStatement()) {
            st.executeUpdate("DELETE FROM livros WHERE titulo LIKE '%" + sufixo + "%'");
        } catch (Exception ignored) {
        }
    }

    @Test
    void livroComQuantidadeNegativaEAceitoAtualmente() {
        String titulo = "Livro Qtd " + sufixo;
        assertTrue(livro.criar(titulo, "Autor Qtd", 2020, "Teste", -5, "ISBN-" + sufixo));
    }
}
