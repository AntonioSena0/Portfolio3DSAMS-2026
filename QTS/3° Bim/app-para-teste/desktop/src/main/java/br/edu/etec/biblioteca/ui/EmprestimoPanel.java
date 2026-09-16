package br.edu.etec.biblioteca.ui;

import br.edu.etec.biblioteca.models.Aluno;
import br.edu.etec.biblioteca.models.Emprestimo;
import br.edu.etec.biblioteca.models.Livro;

import javax.swing.BorderFactory;
import javax.swing.JComboBox;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.table.DefaultTableModel;
import java.awt.BorderLayout;
import java.awt.FlowLayout;
import java.awt.GridBagConstraints;
import java.awt.GridBagLayout;
import java.awt.GridLayout;
import java.awt.Insets;
import java.util.List;

/**
 * Painel de Empréstimos.
 * ATENÇÃO: falhas lógicas intencionais mantidas (disponibilidade não checada,
 * devolução dupla, mensagens genéricas de erro).
 */
public class EmprestimoPanel extends JPanel {

    private final DefaultTableModel modelo;
    private final JComboBox<String> cbAlunos;
    private final JComboBox<String> cbLivros;

    public EmprestimoPanel() {
        modelo = new DefaultTableModel(
            new String[]{"ID", "Aluno", "Livro", "Empréstimo", "Devolução", "Status"}, 0) {
            @Override
            public boolean isCellEditable(int row, int column) {
                return false;
            }
        };
        cbAlunos = new JComboBox<>(listaAlunos());
        cbLivros = new JComboBox<>(listaLivros());

        JPanel conteudo = Tema.painelConteudo();
        conteudo.setLayout(new GridBagLayout());
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.gridx = 0;
        gbc.weightx = 1.0;
        gbc.fill = GridBagConstraints.HORIZONTAL;

        gbc.gridy = 0;
        gbc.weighty = 0;
        gbc.insets = new Insets(0, 0, Tema.PAD, 0);
        conteudo.add(montarNovoEmprestimo(), gbc);

        gbc.gridy = 1;
        gbc.weighty = 1.0;
        gbc.fill = GridBagConstraints.BOTH;
        gbc.insets = new Insets(0, 0, 0, 0);
        conteudo.add(montarTabela(), gbc);

        setLayout(new BorderLayout());
        add(conteudo, BorderLayout.CENTER);
    }

    private JPanel montarNovoEmprestimo() {
        JPanel card = Tema.card();
        card.setLayout(new BorderLayout(0, 16));

        JLabel titulo = Tema.titulo("Novo Empréstimo");
        titulo.setBorder(BorderFactory.createEmptyBorder(0, 0, 4, 0));

        JPanel grid = new JPanel(new GridLayout(0, 2, 18, 12));
        grid.setOpaque(false);
        grid.add(Tema.rotulo("Aluno"));
        grid.add(cbAlunos);
        grid.add(Tema.rotulo("Livro"));
        grid.add(cbLivros);

        JPanel corpo = new JPanel(new BorderLayout(0, 12));
        corpo.setOpaque(false);
        corpo.add(titulo, BorderLayout.NORTH);
        corpo.add(grid, BorderLayout.CENTER);

        JPanel botoes = new JPanel(new FlowLayout(FlowLayout.RIGHT, 0, 0));
        botoes.setOpaque(false);
        javax.swing.JButton btnEmprestar = Tema.botaoPrimario("Emprestar");
        botoes.add(btnEmprestar);

        card.add(corpo, BorderLayout.CENTER);
        card.add(botoes, BorderLayout.SOUTH);

        btnEmprestar.addActionListener(e -> {
            int alunoId = idSelecionado(cbAlunos);
            int livroId = idSelecionado(cbLivros);
            if (alunoId < 0 || livroId < 0) {
                JOptionPane.showMessageDialog(this, "Selecione aluno e livro.");
                return;
            }
            Emprestimo emprestimo = new Emprestimo();
            boolean ok = emprestimo.emprestar(alunoId, livroId);
            if (ok) {
                JOptionPane.showMessageDialog(this, "Empréstimo realizado!");
            } else {
                // BUG: a causa real (disponibilidade zero, limite, etc.) não é informada.
                JOptionPane.showMessageDialog(this,
                    "Não foi possível realizar o empréstimo.",
                    "Erro", JOptionPane.ERROR_MESSAGE);
            }
            recarregar(emprestimo);
        });

        return card;
    }

    private JPanel montarTabela() {
        JPanel card = Tema.card();
        card.setLayout(new BorderLayout(0, 12));

        JLabel titulo = Tema.titulo("Empréstimos");
        card.add(titulo, BorderLayout.NORTH);

        Emprestimo emp = new Emprestimo();
        for (String[] e : emp.todos()) {
            modelo.addRow(e);
        }

        JTable tabela = new JTable(modelo);
        tabela.setRowHeight(30);
        tabela.setShowVerticalLines(false);
        tabela.setIntercellSpacing(new java.awt.Dimension(14, 0));
        tabela.getTableHeader().setReorderingAllowed(false);
        card.add(new JScrollPane(tabela), BorderLayout.CENTER);

        JPanel botoes = new JPanel(new FlowLayout(FlowLayout.RIGHT, 0, 0));
        botoes.setOpaque(false);
        javax.swing.JButton btnDevolver = Tema.botaoSecundario("Devolver selecionado");
        botoes.add(btnDevolver);
        card.add(botoes, BorderLayout.SOUTH);

        btnDevolver.addActionListener(e -> {
            int linha = tabela.getSelectedRow();
            if (linha < 0) {
                JOptionPane.showMessageDialog(this, "Selecione um empréstimo.");
                return;
            }
            String id = modelo.getValueAt(linha, 0).toString();
            Emprestimo emprestimo = new Emprestimo();
            // BUG: devolver() não verifica se já devolvido (devolução dupla).
            emprestimo.devolver(Integer.parseInt(id));
            recarregar(emprestimo);
        });

        return card;
    }

    private void recarregar(Emprestimo emprestimo) {
        modelo.setRowCount(0);
        for (String[] l : emprestimo.todos()) {
            modelo.addRow(l);
        }
    }

    private String[] listaAlunos() {
        Aluno aluno = new Aluno();
        List<String[]> todos = aluno.todos();
        String[] itens = new String[todos.size()];
        for (int i = 0; i < todos.size(); i++) {
            String[] l = todos.get(i);
            itens[i] = l[0] + " - " + l[1];
        }
        return itens;
    }

    private String[] listaLivros() {
        Livro livro = new Livro();
        List<String[]> todos = livro.todos();
        String[] itens = new String[todos.size()];
        for (int i = 0; i < todos.size(); i++) {
            String[] l = todos.get(i);
            itens[i] = l[0] + " - " + l[1] + " (disp: "
                + livro.quantidadeDisponivel(Integer.parseInt(l[0])) + ")";
        }
        return itens;
    }

    private int idSelecionado(JComboBox<String> combo) {
        Object sel = combo.getSelectedItem();
        if (sel == null) {
            return -1;
        }
        String s = sel.toString();
        int idx = s.indexOf(" - ");
        if (idx < 0) {
            return -1;
        }
        try {
            return Integer.parseInt(s.substring(0, idx).trim());
        } catch (NumberFormatException e) {
            return -1;
        }
    }
}
