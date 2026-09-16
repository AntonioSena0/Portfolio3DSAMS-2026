package br.edu.etec.biblioteca.ui;

import javax.swing.BorderFactory;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JTabbedPane;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Font;

/**
 * Janela principal com abas (Swing).
 * Visual minimalista: barra de título clara, abas e rodapé discretos.
 */
public class JanelaPrincipal extends JFrame {

    public JanelaPrincipal() {
        super("Sistema de Biblioteca Escolar");
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setSize(980, 640);
        setLocationRelativeTo(null);
        getContentPane().setBackground(Tema.FUNDO);

        add(criarTopo(), BorderLayout.NORTH);

        JTabbedPane abas = new JTabbedPane();
        abas.setBackground(Tema.FUNDO);
        abas.addTab("Início", new DashboardPanel());
        abas.addTab("Alunos", new AlunoPanel());
        abas.addTab("Livros", new LivroPanel());
        abas.addTab("Empréstimos", new EmprestimoPanel());
        add(abas, BorderLayout.CENTER);

        add(criarRodape(), BorderLayout.SOUTH);
    }

    private JPanel criarTopo() {
        JPanel topo = new JPanel(new java.awt.BorderLayout());
        topo.setBackground(Tema.SUPERFICIE);
        topo.setBorder(BorderFactory.createCompoundBorder(
            BorderFactory.createMatteBorder(0, 0, 1, 0, Tema.BORDA),
            BorderFactory.createEmptyBorder(16, 20, 16, 20)));

        JLabel icone = new JLabel("B");
        icone.setOpaque(true);
        icone.setBackground(Tema.PRIMARIO);
        icone.setForeground(Color.WHITE);
        icone.setHorizontalAlignment(JLabel.CENTER);
        icone.setFont(icone.getFont().deriveFont(Font.BOLD, 18f));
        icone.setBorder(BorderFactory.createEmptyBorder(4, 12, 4, 12));

        JLabel titulo = new JLabel("Biblioteca Escolar");
        titulo.setFont(titulo.getFont().deriveFont(Font.BOLD, 18f));
        titulo.setForeground(Tema.TEXTO);
        titulo.setBorder(BorderFactory.createEmptyBorder(0, 12, 0, 0));

        JPanel esq = new JPanel(new java.awt.FlowLayout(java.awt.FlowLayout.LEFT, 0, 0));
        esq.setOpaque(false);
        esq.add(icone);
        esq.add(titulo);

        JLabel sub = new JLabel("App para Testes de Software");
        sub.setFont(sub.getFont().deriveFont(Font.PLAIN, 12f));
        sub.setForeground(Tema.TEXTO_SUAVE);

        topo.add(esq, java.awt.BorderLayout.WEST);
        topo.add(sub, java.awt.BorderLayout.EAST);
        return topo;
    }

    private JPanel criarRodape() {
        JLabel rodape = new JLabel("Swing + SQLite  ·  disciplina de Qualidade e Teste de Software");
        rodape.setHorizontalAlignment(JLabel.CENTER);
        rodape.setForeground(Tema.TEXTO_SUAVE);
        rodape.setBackground(Tema.SUPERFICIE);
        rodape.setOpaque(true);
        rodape.setBorder(BorderFactory.createCompoundBorder(
            BorderFactory.createMatteBorder(1, 0, 0, 0, Tema.BORDA),
            BorderFactory.createEmptyBorder(10, 8, 10, 8)));

        JPanel sul = new JPanel(new BorderLayout());
        sul.setBackground(Tema.SUPERFICIE);
        sul.add(rodape, BorderLayout.CENTER);
        return sul;
    }
}
