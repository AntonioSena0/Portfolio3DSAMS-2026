package br.edu.etec.biblioteca.ui;

import br.edu.etec.biblioteca.models.Livro;

import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;
import javax.swing.table.DefaultTableModel;
import java.awt.BorderLayout;
import java.awt.FlowLayout;
import java.awt.GridBagConstraints;
import java.awt.GridBagLayout;
import java.awt.GridLayout;
import java.awt.Insets;

/**
 * Painel de Livros.
 * ATENÇÃO: falhas lógicas intencionais mantidas (quantidade negativa aceita,
 * busca com SQL injection, exclusão não implementada).
 */
public class LivroPanel extends JPanel {

    private DefaultTableModel modelo;

    public LivroPanel() {
        JPanel conteudo = Tema.painelConteudo();
        conteudo.setLayout(new GridBagLayout());
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.gridx = 0;
        gbc.weightx = 1.0;
        gbc.fill = GridBagConstraints.HORIZONTAL;

        // ---- Busca (card pequeno) ----
        gbc.gridy = 0;
        gbc.weighty = 0;
        gbc.insets = new Insets(0, 0, Tema.PAD, 0);
        conteudo.add(montarBusca(), gbc);

        // ---- Cadastro ----
        gbc.gridy = 1;
        gbc.insets = new Insets(0, 0, Tema.PAD, 0);
        conteudo.add(montarFormularioCadastro(), gbc);

        // ---- Tabela ----
        gbc.gridy = 2;
        gbc.weighty = 1.0;
        gbc.fill = GridBagConstraints.BOTH;
        gbc.insets = new Insets(0, 0, 0, 0);
        conteudo.add(montarTabela(), gbc);

        setLayout(new BorderLayout());
        add(conteudo, BorderLayout.CENTER);
    }

    private JPanel montarBusca() {
        JPanel card = Tema.card();
        card.setLayout(new BorderLayout(0, 8));

        card.add(Tema.rotulo("Buscar por título ou autor"), BorderLayout.NORTH);

        JPanel linha = new JPanel(new BorderLayout(12, 0));
        linha.setOpaque(false);
        JTextField tfBusca = campo();
        javax.swing.JButton btnBuscar = Tema.botaoSecundario("Buscar");
        linha.add(tfBusca, BorderLayout.CENTER);
        linha.add(btnBuscar, BorderLayout.EAST);
        card.add(linha, BorderLayout.CENTER);

        btnBuscar.addActionListener(e -> {
            String termo = tfBusca.getText().trim();
            // BUG: passar termo diretamente para SQL (injeção e curingas).
            Livro livro = new Livro();
            modelo.setRowCount(0);
            for (String[] linha2 : livro.buscar(termo)) {
                modelo.addRow(linha(linha2));
            }
        });

        card.setPreferredSize(new java.awt.Dimension(0, 84));
        return card;
    }

    private JPanel montarFormularioCadastro() {
        JPanel card = Tema.card();
        card.setLayout(new BorderLayout(0, 16));

        JLabel titulo = Tema.titulo("Adicionar Livro");
        titulo.setBorder(new javax.swing.border.EmptyBorder(0, 0, 4, 0));

        // Grid 3 colunas de campos para aproveitar espaço
        JPanel grid = new JPanel(new GridLayout(0, 3, 18, 14));
        grid.setOpaque(false);

        JTextField tfTitulo = campo();
        JTextField tfAutor = campo();
        JTextField tfAno = campo();
        JTextField tfCategoria = campo();
        JTextField tfQtd = campo("1");
        JTextField tfIsbn = campo();

        grid.add(Tema.rotulo("Título"));
        grid.add(Tema.rotulo("Autor"));
        grid.add(Tema.rotulo("Ano"));
        grid.add(tfTitulo);
        grid.add(tfAutor);
        grid.add(tfAno);
        grid.add(Tema.rotulo("Categoria"));
        grid.add(Tema.rotulo("Quantidade"));
        grid.add(Tema.rotulo("ISBN"));
        grid.add(tfCategoria);
        grid.add(tfQtd);
        grid.add(tfIsbn);

        JPanel corpo = new JPanel(new BorderLayout(0, 12));
        corpo.setOpaque(false);
        corpo.add(titulo, BorderLayout.NORTH);
        corpo.add(grid, BorderLayout.CENTER);

        JPanel botoes = new JPanel(new FlowLayout(FlowLayout.RIGHT, 0, 0));
        botoes.setOpaque(false);
        javax.swing.JButton btnCadastrar = Tema.botaoPrimario("Adicionar Livro");
        botoes.add(btnCadastrar);

        card.add(corpo, BorderLayout.CENTER);
        card.add(botoes, BorderLayout.SOUTH);

        btnCadastrar.addActionListener(e -> {
            String titulo2 = tfTitulo.getText().trim();
            String autor = tfAutor.getText().trim();
            int ano = 0;
            int qtd = 1;
            try {
                ano = tfAno.getText().isEmpty() ? 0 : Integer.parseInt(tfAno.getText().trim());
            } catch (NumberFormatException ex) {
                JOptionPane.showMessageDialog(this, "Ano inválido.");
                return;
            }
            try {
                qtd = Integer.parseInt(tfQtd.getText().trim());
            } catch (NumberFormatException ex) {
                JOptionPane.showMessageDialog(this, "Quantidade inválida.");
                return;
            }
            // BUG: aceita quantidade negativa sem validação.
            Livro livro = new Livro();
            boolean ok = livro.criar(titulo2, autor, ano, tfCategoria.getText().trim(), qtd, tfIsbn.getText().trim());
            if (ok) {
                JOptionPane.showMessageDialog(this, "Livro adicionado!");
                modelo.setRowCount(0);
                for (String[] l : livro.todos()) {
                    modelo.addRow(linha(l));
                }
            } else {
                JOptionPane.showMessageDialog(this, "Falha ao adicionar livro.",
                    "Erro", JOptionPane.ERROR_MESSAGE);
            }
        });

        return card;
    }

    private JPanel montarTabela() {
        JPanel card = Tema.card();
        card.setLayout(new BorderLayout(0, 12));

        JLabel titulo = Tema.titulo("Acervo de Livros");
        card.add(titulo, BorderLayout.NORTH);

        modelo = new DefaultTableModel(
            new String[]{"ID", "Título", "Autor", "Ano", "Categoria", "Qtd", "Disponíveis", "ISBN"}, 0) {
            @Override
            public boolean isCellEditable(int row, int column) {
                return false;
            }
        };
        Livro livro = new Livro();
        for (String[] l : livro.todos()) {
            modelo.addRow(linha(l));
        }

        JTable tabela = new JTable(modelo);
        tabela.setRowHeight(30);
        tabela.setShowVerticalLines(false);
        tabela.setIntercellSpacing(new java.awt.Dimension(14, 0));
        tabela.getTableHeader().setReorderingAllowed(false);
        card.add(new JScrollPane(tabela), BorderLayout.CENTER);

        JPanel botoes = new JPanel(new FlowLayout(FlowLayout.RIGHT, 0, 0));
        botoes.setOpaque(false);
        javax.swing.JButton btnExcluir = Tema.botaoSecundario("Excluir selecionado");
        botoes.add(btnExcluir);
        card.add(botoes, BorderLayout.SOUTH);

        btnExcluir.addActionListener(e -> {
            int linha2 = tabela.getSelectedRow();
            if (linha2 < 0) {
                JOptionPane.showMessageDialog(this, "Selecione um livro primeiro.");
                return;
            }
            JOptionPane.showMessageDialog(this,
                "Funcionalidade de exclusão de livro ainda não implementada.",
                "Aviso", JOptionPane.WARNING_MESSAGE);
        });

        return card;
    }

    /** Monta a linha da tabela com a coluna de "Disponíveis" calculada. */
    private String[] linha(String[] l) {
        String[] r = new String[8];
        System.arraycopy(l, 0, r, 0, 7);
        r[6] = String.valueOf(new Livro().quantidadeDisponivel(Integer.parseInt(l[0])));
        r[7] = l[6];
        return r;
    }

    private JTextField campo() {
        return campo("");
    }

    private JTextField campo(String valor) {
        JTextField tf = new JTextField(valor);
        Tema.dimensaoCampo(tf, 150);
        return tf;
    }
}
