package br.edu.etec.biblioteca;

import br.edu.etec.biblioteca.core.Database;
import br.edu.etec.biblioteca.models.Livro;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertEquals;

class BuscaLivroTest {

    private Livro livro;

    @BeforeEach
    void preparar() {
        Database.definirBanco("data/db_teste.db");
        Database.criarBancoSeNecessario();
        livro = new Livro();
    }

    @Test
    void buscaPorTituloExistenteRetornaOLivro() {
        assertEquals(1, livro.buscar("Dom Casmurro").size());
    }

    @Test
    void buscaPorCuringaRetornaTodoOAcervoAtualmente() {
        assertEquals(livro.todos().size(), livro.buscar("%").size());
    }
}
