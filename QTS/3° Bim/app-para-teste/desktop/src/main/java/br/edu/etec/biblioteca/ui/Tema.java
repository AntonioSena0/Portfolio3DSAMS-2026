package br.edu.etec.biblioteca.ui;

import javax.swing.BorderFactory;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.UIManager;
import javax.swing.border.Border;
import java.awt.Color;
import java.awt.Cursor;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.Insets;
import java.awt.RenderingHints;

/**
 * Tema visual da aplicação (minimalista, poucas cores, nativo do Swing).
 *
 * Centraliza a paleta e os componentes reutilizáveis para dar um visual
 * "quase moderno" usando apenas o Swing (UIManager + bordas/cores).
 */
public final class Tema {

    // ---- Paleta (poucas cores, tom suave) ----
    public static final Color PRIMARIO   = new Color(0x3B82F6);
    public static final Color PRIMARIO_H = new Color(0x2563EB);
    public static final Color FUNDO      = new Color(0xF7F8FA);
    public static final Color SUPERFICIE = new Color(0xFFFFFF);
    public static final Color TEXTO      = new Color(0x1F2937);
    public static final Color TEXTO_SUAVE = new Color(0x6B7280);
    public static final Color BORDA      = new Color(0xE5E7EB);
    public static final Color HOVER_FILA = new Color(0xEFF4FF);

    public static final int PAD = 16;

    private Tema() {
    }

    /** Aplica o tema global via UIManager. */
    public static void aplicar() {
        try {
            UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
        } catch (Exception e) {
            // se não conseguir o L&F do sistema, segue com o padrão.
        }

        // ----- Componentes de formulário -----
        UIManager.put("Button.font", UIManager.getFont("Button.font").deriveFont(Font.PLAIN, 13f));
        UIManager.put("Button.foreground", Color.WHITE);
        UIManager.put("Button.background", PRIMARIO);
        UIManager.put("Button.focus", new Color(0, 0, 0, 0));
        UIManager.put("Button.select", PRIMARIO_H);
        UIManager.put("Button.margin", new javax.swing.plaf.InsetsUIResource(8, 16, 8, 16));

        UIManager.put("TextField.font", UIManager.getFont("TextField.font").deriveFont(13f));
        UIManager.put("TextField.background", Color.WHITE);
        UIManager.put("TextField.caretForeground", TEXTO);

        UIManager.put("ComboBox.font", UIManager.getFont("ComboBox.font").deriveFont(13f));
        UIManager.put("ComboBox.background", Color.WHITE);

        // ----- Tabela (visual limpo, listrado) -----
        UIManager.put("Table.font", UIManager.getFont("Table.font").deriveFont(13f));
        UIManager.put("Table.background", Color.WHITE);
        UIManager.put("Table.foreground", TEXTO);
        UIManager.put("Table.gridColor", BORDA);
        UIManager.put("Table.selectionBackground", HOVER_FILA);
        UIManager.put("Table.selectionForeground", TEXTO);
        UIManager.put("TableHeader.font", UIManager.getFont("TableHeader.font").deriveFont(Font.BOLD, 12f));
        UIManager.put("TableHeader.background", new Color(0xF3F4F6));
        UIManager.put("TableHeader.foreground", TEXTO_SUAVE);

        // ----- Abas -----
        UIManager.put("TabbedPane.font", UIManager.getFont("TabbedPane.font").deriveFont(Font.PLAIN, 13f));
        UIManager.put("TabbedPane.selected", Color.WHITE);
        UIManager.put("TabbedPane.focus", new Color(0, 0, 0, 0));

        // ----- Diálogos (JOptionPane) -----
        UIManager.put("OptionPane.background", Color.WHITE);
        UIManager.put("OptionPane.messageFont", UIManager.getFont("OptionPane.messageFont").deriveFont(13f));
        UIManager.put("Panel.background", Color.WHITE);
    }

    // ======================= Componentes reutilizáveis =======================

    /** Botão primário (azul) com cantos arredondados e padding. */
    public static JButton botaoPrimario(String texto) {
        return botaoPlano(texto, PRIMARIO, PRIMARIO_H, Color.WHITE);
    }

    /** Botão secundário (neutro, mínimo) para ações periféricas. */
    public static JButton botaoSecundario(String texto) {
        return botaoPlano(texto, new Color(0xF3F4F6), new Color(0xE5E7EB), TEXTO);
    }

    /**
     * Botão de aparência plana. Usa uma Border que pinta o fundo de forma
     * confiável em qualquer Look &amp; Feel nativo (o L&amp;F do Windows ignora
     * setBackground em botões, causando fundo claro + texto claro).
     */
    private static JButton botaoPlano(String texto, Color base, Color hover, Color fg) {
        JButton b = new JButton(texto);
        b.setForeground(fg);
        b.setFont(b.getFont().deriveFont(Font.BOLD, 13f));
        b.setFocusPainted(false);
        // Não deixa o L&F pintar por cima do nosso fundo.
        b.setContentAreaFilled(false);
        b.setBorderPainted(false);
        b.setOpaque(false);
        b.setMargin(new java.awt.Insets(8, 18, 8, 18));
        b.setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));

        Color[] estado = {base, base, hover};
        b.setBorder(new BotaoPlanoBorder(estado));
        b.addMouseListener(new java.awt.event.MouseAdapter() {
            @Override
            public void mouseEntered(java.awt.event.MouseEvent e) {
                estado[0] = hover;
                b.repaint();
            }

            @Override
            public void mouseExited(java.awt.event.MouseEvent e) {
                estado[0] = base;
                b.repaint();
            }
        });
        return b;
    }

    /**
     * Border que pinta o fundo arredondado do botão e uma borda fina,
     * respeitando a cor corrente (para hover).
     */
    private static final class BotaoPlanoBorder implements Border {
        private final Color[] cor;

        BotaoPlanoBorder(Color[] cor) {
            this.cor = cor;
        }

        @Override
        public void paintBorder(java.awt.Component c, java.awt.Graphics g,
                                int x, int y, int w, int h) {
            Graphics2D g2 = (Graphics2D) g.create();
            g2.setRenderingHint(RenderingHints.KEY_ANTIALIASING,
                RenderingHints.VALUE_ANTIALIAS_ON);
            int r = 6;
            g2.setColor(cor[0]);
            g2.fillRoundRect(x, y, w - 1, h - 1, r, r);
            g2.setColor(cor[2]);
            g2.drawRoundRect(x, y, w - 1, h - 1, r, r);
            g2.dispose();
        }

        @Override
        public Insets getBorderInsets(java.awt.Component c) {
            return new Insets(8, 16, 8, 16);
        }

        @Override
        public boolean isBorderOpaque() {
            return true;
        }
    }


    /** Título de seção dentro de um painel. */
    public static JLabel titulo(String texto) {
        JLabel l = new JLabel(texto);
        l.setFont(l.getFont().deriveFont(Font.BOLD, 15f));
        l.setForeground(TEXTO);
        return l;
    }

    /** Rótulo de campo de formulário. */
    public static JLabel rotulo(String texto) {
        JLabel l = new JLabel(texto);
        l.setFont(l.getFont().deriveFont(Font.PLAIN, 13f));
        l.setForeground(TEXTO_SUAVE);
        return l;
    }

    /** Card branco com borda suave e arredondada. */
    public static JPanel card() {
        JPanel p = new JPanel();
        p.setBackground(SUPERFICIE);
        p.setBorder(cardBorder());
        return p;
    }

    /** Borda padrão de card: arredondada com linha clara + padding. */
    public static Border cardBorder() {
        return BorderFactory.createCompoundBorder(
            BorderFactory.createLineBorder(BORDA, 1, true),
            BorderFactory.createEmptyBorder(PAD, PAD, PAD, PAD));
    }

    /** Painel de fundo padrão da área de conteúdo. */
    public static JPanel painelConteudo() {
        JPanel p = new JPanel();
        p.setBackground(FUNDO);
        p.setBorder(BorderFactory.createEmptyBorder(PAD, PAD, PAD, PAD));
        return p;
    }

    /** Altura mínima para campos de texto, de modo a ficarem confortáveis. */
    public static void dimensaoCampo(javax.swing.JComponent campo, int largura) {
        Dimension d = campo.getPreferredSize();
        d.height = 34;
        d.width = largura;
        campo.setPreferredSize(d);
        campo.setMinimumSize(d);
    }
}
