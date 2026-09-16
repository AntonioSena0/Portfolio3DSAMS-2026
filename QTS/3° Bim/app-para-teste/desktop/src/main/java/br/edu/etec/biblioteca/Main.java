package br.edu.etec.biblioteca;

import br.edu.etec.biblioteca.core.Database;
import br.edu.etec.biblioteca.ui.JanelaPrincipal;
import br.edu.etec.biblioteca.ui.Tema;

import javax.swing.SwingUtilities;

/**
 * Ponto de entrada da aplicação desktop "Biblioteca Escolar".
 *
 * ATENÇÃO: aplicação propositalmente com BUGS para fins de estudo/teste
 * na disciplina de Qualidade e Teste de Software.
 */
public class Main {

    public static void main(String[] args) {
        // BUG DE CONFIGURAÇÃO: força a criação do banco com dados de exemplo
        // toda vez que a aplicação roda, sem checar o ambiente (dev/prod).
        Database.criarBancoSeNecessario();

        // Aplica o tema visual antes de criar a janela.
        Tema.aplicar();

        SwingUtilities.invokeLater(() -> {
            JanelaPrincipal janela = new JanelaPrincipal();
            janela.setVisible(true);
        });
    }
}
