package br.edu.etec.biblioteca.ui;

import br.edu.etec.biblioteca.models.Aluno;

import javax.swing.BorderFactory;
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
 * Painel de Alunos.
 *
 * ATENÇÃO: UI com falhas lógicas intencionais mantidas:
 * - O botão "Excluir" pede confirmação mas NÃO exclui de verdade.
 * - Erros de cadastro (ex.: email duplicado) não são mostrados ao usuário.
 */
public class AlunoPanel extends JPanel {

    private DefaultTableModel modelo;

    public AlunoPanel() {
        // Conteúdo central com margens generosas em volta de tudo.
        JPanel conteudo = Tema.painelConteudo();
        conteudo.setLayout(new java.awt.GridBagLayout());
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.gridx = 0;
        gbc.weightx = 1.0;
        gbc.fill = GridBagConstraints.HORIZONTAL;

        // ---- Card do formulário ----
        gbc.gridy = 0;
        gbc.insets = new Insets(0, 0, Tema.PAD, 0);
        conteudo.add(montarFormularioCadastro(), gbc);

        // ---- Card da tabela ----
        gbc.gridy = 1;
        gbc.weighty = 1.0;
        gbc.fill = GridBagConstraints.BOTH;
        gbc.insets = new Insets(0, 0, 0, 0);
        conteudo.add(montarTabela(), gbc);

        setLayout(new BorderLayout());
        add(conteudo, BorderLayout.CENTER);
    }

    /** Formulário dentro de um card branco com borda, botão à direita. */
    private JPanel montarFormularioCadastro() {
        JPanel card = Tema.card();
        card.setLayout(new BorderLayout(0, 0));

        JLabel titulo = Tema.titulo("Cadastro de Aluno");
        titulo.setBorder(BorderFactory.createEmptyBorder(0, 0, 12, 0));

        // Grid de rótulos + campos (2 colunas)
        JPanel grid = new JPanel(new GridLayout(0, 2, 18, 12));
        grid.setOpaque(false);

        JTextField tfNome = campo();
        JTextField tfEmail = campo();
        JTextField tfTurma = campo();
        JTextField tfTelefone = campo();

        grid.add(Tema.rotulo("Nome"));
        grid.add(tfNome);
        grid.add(Tema.rotulo("E-mail"));
        grid.add(tfEmail);
        grid.add(Tema.rotulo("Turma"));
        grid.add(tfTurma);
        grid.add(Tema.rotulo("Telefone"));
        grid.add(tfTelefone);

        JPanel corpo = new JPanel(new BorderLayout(0, 16));
        corpo.setOpaque(false);
        corpo.add(titulo, BorderLayout.NORTH);
        corpo.add(grid, BorderLayout.CENTER);

        JPanel botoes = new JPanel(new FlowLayout(FlowLayout.RIGHT, 0, 0));
        botoes.setOpaque(false);
        javax.swing.JButton btnCadastrar = Tema.botaoPrimario("Cadastrar");
        botoes.add(btnCadastrar);

        card.add(corpo, BorderLayout.CENTER);
        card.add(botoes, BorderLayout.SOUTH);

        btnCadastrar.addActionListener(e -> {
            String nome = tfNome.getText().trim();
            String email = tfEmail.getText().trim();
            String turma = tfTurma.getText().trim();
            String telefone = tfTelefone.getText().trim();

            Aluno aluno = new Aluno();
            boolean ok = aluno.criar(nome, email, turma, telefone);
            if (ok) {
                // BUG: sempre mostra sucesso, mesmo que o aluno não tenha sido inserido.
                JOptionPane.showMessageDialog(this, "Aluno cadastrado com sucesso!");
                modelo.setRowCount(0);
                for (String[] l : aluno.todos()) {
                    modelo.addRow(l);
                }
                tfNome.setText("");
                tfEmail.setText("");
                tfTurma.setText("");
                tfTelefone.setText("");
            } else {
                // BUG DE UX: mensagem genérica, sem dizer o que deu errado.
                JOptionPane.showMessageDialog(this, "Não foi possível cadastrar o aluno.",
                    "Erro", JOptionPane.ERROR_MESSAGE);
            }
        });

        return card;
    }

    /** Tabela dentro de um card, com botão de excluir no rodapé do card. */
    private JPanel montarTabela() {
        JPanel card = Tema.card();
        card.setLayout(new BorderLayout(0, 12));

        JLabel titulo = Tema.titulo("Lista de Alunos");
        card.add(titulo, BorderLayout.NORTH);

        modelo = new DefaultTableModel(
            new String[]{"ID", "Nome", "E-mail", "Turma", "Telefone"}, 0) {
            @Override
            public boolean isCellEditable(int row, int column) {
                return false;
            }
        };
        Aluno aluno = new Aluno();
        for (String[] l : aluno.todos()) {
            modelo.addRow(l);
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
            int linha = tabela.getSelectedRow();
            if (linha < 0) {
                JOptionPane.showMessageDialog(this, "Selecione um aluno primeiro.");
                return;
            }
            String id = modelo.getValueAt(linha, 0).toString();
            int resposta = JOptionPane.showConfirmDialog(this,
                "Excluir o aluno ID " + id + "? Esta ação não pode ser desfeita.",
                "Confirmar exclusão", JOptionPane.YES_NO_OPTION);

            if (resposta == JOptionPane.YES_OPTION) {
                // BUG LÓGICO (importante): a exclusão é "confirmada" mas NADA é
                // feito de verdade. o Aluno.remover() NUNCA é chamado.
                // atualizarTabela(); // a linha continua lá (recarregar da base)
                Aluno a = new Aluno();
                modelo.setRowCount(0);
                for (String[] l : a.todos()) {
                    modelo.addRow(l);
                }
            }
        });

        return card;
    }

    private javax.swing.JTextField campo() {
        JTextField tf = new JTextField();
        Tema.dimensaoCampo(tf, 200);
        return tf;
    }
}
