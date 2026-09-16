package br.edu.etec.biblioteca.ui;

import br.edu.etec.biblioteca.models.Aluno;
import br.edu.etec.biblioteca.models.Emprestimo;
import br.edu.etec.biblioteca.models.Livro;

import javax.swing.BorderFactory;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.table.DefaultTableModel;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.FlowLayout;
import java.awt.Font;

/**
 * Painel "Início" (dashboard) com estatísticas e empréstimos atrasados.
 */
public class DashboardPanel extends JPanel {

    public DashboardPanel() {
        setLayout(new BorderLayout());
        setBackground(Tema.FUNDO);
        setBorder(BorderFactory.createEmptyBorder(Tema.PAD, Tema.PAD, Tema.PAD, Tema.PAD));

        Livro livro = new Livro();
        Aluno aluno = new Aluno();
        Emprestimo emprestimo = new Emprestimo();

        // ---- Cards de estatísticas ----
        JPanel cards = new JPanel(new FlowLayout(FlowLayout.LEFT, 16, 0));
        cards.setOpaque(false);
        cards.add(card("Total de Livros", String.valueOf(livro.todos().size()), new Color(0x3B82F6)));
        cards.add(card("Total de Alunos", String.valueOf(aluno.todos().size()), new Color(0x10B981)));
        cards.add(card("Total de Empréstimos", String.valueOf(emprestimo.todos().size()), new Color(0x8B5CF6)));

        // ---- Título + tabela de atrasados ----
        JPanel atrasados = Tema.card();
        atrasados.setLayout(new java.awt.GridBagLayout());
        java.awt.GridBagConstraints gbc = new java.awt.GridBagConstraints();
        gbc.gridx = 0;
        gbc.gridy = 0;
        gbc.anchor = java.awt.GridBagConstraints.WEST;
        gbc.insets = new java.awt.Insets(0, 0, 10, 0);
        atrasados.add(Tema.titulo("Empréstimos em atraso (mais de 14 dias)"), gbc);

        DefaultTableModel modelo = new DefaultTableModel(new String[]{"Aluno", "Livro", "Data"}, 0) {
            @Override
            public boolean isCellEditable(int row, int column) {
                return false;
            }
        };
        for (String[] a : emprestimo.atrasados()) {
            modelo.addRow(a);
        }
        JTable tabela = new JTable(modelo);
        tabela.setRowHeight(30);
        tabela.setShowVerticalLines(false);
        tabela.setIntercellSpacing(new java.awt.Dimension(12, 0));

        // Cabeçalho um pouco mais escuro + alinhamento
        tabela.getTableHeader().setReorderingAllowed(false);

        gbc.gridy = 1;
        gbc.weightx = 1.0;
        gbc.weighty = 1.0;
        gbc.fill = java.awt.GridBagConstraints.BOTH;
        atrasados.add(new JScrollPane(tabela), gbc);

        JPanel corpo = new JPanel(new java.awt.GridBagLayout());
        corpo.setOpaque(false);
        java.awt.GridBagConstraints c2 = new java.awt.GridBagConstraints();
        c2.gridx = 0;
        c2.gridy = 0;
        c2.weightx = 1.0;
        c2.fill = java.awt.GridBagConstraints.HORIZONTAL;
        c2.insets = new java.awt.Insets(0, 0, 16, 0);
        corpo.add(cards, c2);
        c2.gridy = 1;
        c2.weighty = 1.0;
        c2.fill = java.awt.GridBagConstraints.BOTH;
        corpo.add(atrasados, c2);

        add(corpo, BorderLayout.CENTER);

        JLabel dica = new JLabel(
            "<html><font color='#6B7280'>Dica para o tester: confira se a lista de atrasados está "
            + "correta, considerando as datas e o status. Existe bug no cálculo?</font></html>");
        dica.setBorder(BorderFactory.createEmptyBorder(12, 0, 0, 0));
        add(dica, BorderLayout.SOUTH);
    }

    private JPanel card(String titulo, String valor, Color cor) {
        JPanel card = Tema.card();
        card.setLayout(new java.awt.BorderLayout(0, 6));

        // Barra de acento lateral sutis
        card.setBorder(BorderFactory.createCompoundBorder(
            BorderFactory.createLineBorder(Tema.BORDA, 1, true),
            BorderFactory.createEmptyBorder(18, 22, 18, 22)));

        JLabel lblTitulo = new JLabel(titulo);
        lblTitulo.setFont(lblTitulo.getFont().deriveFont(Font.PLAIN, 13f));
        lblTitulo.setForeground(Tema.TEXTO_SUAVE);

        JLabel lblValor = new JLabel(valor);
        lblValor.setFont(lblValor.getFont().deriveFont(Font.BOLD, 30f));
        lblValor.setForeground(cor);

        card.add(lblTitulo, BorderLayout.NORTH);
        card.add(lblValor, BorderLayout.CENTER);

        // preferência de largura mínima por card
        card.setPreferredSize(new java.awt.Dimension(190, 96));
        return card;
    }
}
