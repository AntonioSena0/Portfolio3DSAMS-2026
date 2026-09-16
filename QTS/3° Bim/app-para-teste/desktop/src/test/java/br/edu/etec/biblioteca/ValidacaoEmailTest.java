package br.edu.etec.biblioteca;

import br.edu.etec.biblioteca.core.Database;
import br.edu.etec.biblioteca.models.Aluno;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertFalse;
import static org.junit.jupiter.api.Assertions.assertTrue;

class ValidacaoEmailTest {

    private Aluno aluno;

    @BeforeEach
    void preparar() {
        Database.definirBanco("data/db_teste.db");
        Database.criarBancoSeNecessario();
        aluno = new Aluno();
    }

    @Test
    void emailValidoDeveSerAceito() {
        assertTrue(aluno.emailValido("ana@escola.edu"));
    }

    @Test
    void emailSemArrobaOuPontoDeveSerRejeitado() {
        assertFalse(aluno.emailValido("anaescolaedu"));
    }

    @Test
    void emailInvalidoComArrobaDuploPassaNaValidacaoAtual() {
        assertTrue(aluno.emailValido("teste@@escola..com"));
    }
}
