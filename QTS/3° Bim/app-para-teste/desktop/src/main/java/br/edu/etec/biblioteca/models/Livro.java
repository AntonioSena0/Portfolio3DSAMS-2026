package br.edu.etec.biblioteca.models;

import br.edu.etec.biblioteca.core.Database;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

/**
 * Model Livro.
 * ATENÇÃO: código intencionalmente com FALHAS (SQL injection, regra de negócio).
 */
public class Livro {

    private final Connection conn;

    public Livro() {
        this.conn = Database.getConnection();
    }

    public List<String[]> todos() {
        List<String[]> lista = new ArrayList<>();
        String sql = "SELECT id, titulo, autor, ano, categoria, quantidade, isbn FROM livros";
        try (Statement st = conn.createStatement();
             ResultSet rs = st.executeQuery(sql)) {
            while (rs.next()) {
                lista.add(new String[]{
                    String.valueOf(rs.getInt("id")),
                    rs.getString("titulo"),
                    rs.getString("autor"),
                    String.valueOf(rs.getInt("ano")),
                    rs.getString("categoria"),
                    String.valueOf(rs.getInt("quantidade")),
                    rs.getString("isbn")
                });
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return lista;
    }

    /**
     * Busca livros por termo (título ou autor).
     * BUG DE SEGURANÇA + PERFORMANCE: SQL injection e LIKE sem escape;
     * uso de '%' e '_' do usuário viram curingas (falsos positivos).
     */
    public List<String[]> buscar(String termo) {
        List<String[]> lista = new ArrayList<>();
        String sql = "SELECT id, titulo, autor, ano, categoria, quantidade, isbn FROM livros "
            + "WHERE titulo LIKE '%" + termo + "%' OR autor LIKE '%" + termo + "%'";
        try (Statement st = conn.createStatement();
             ResultSet rs = st.executeQuery(sql)) {
            while (rs.next()) {
                lista.add(new String[]{
                    String.valueOf(rs.getInt("id")),
                    rs.getString("titulo"),
                    rs.getString("autor"),
                    String.valueOf(rs.getInt("ano")),
                    rs.getString("categoria"),
                    String.valueOf(rs.getInt("quantidade")),
                    rs.getString("isbn")
                });
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return lista;
    }

    public String[] porId(int id) {
        String sql = "SELECT id, titulo, autor, ano, categoria, quantidade, isbn FROM livros WHERE id = " + id;
        try (Statement st = conn.createStatement();
             ResultSet rs = st.executeQuery(sql)) {
            if (rs.next()) {
                return new String[]{
                    String.valueOf(rs.getInt("id")),
                    rs.getString("titulo"),
                    rs.getString("autor"),
                    String.valueOf(rs.getInt("ano")),
                    rs.getString("categoria"),
                    String.valueOf(rs.getInt("quantidade")),
                    rs.getString("isbn")
                };
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    /**
     * Cria livro.
     * BUG DE VALIDAÇÃO: não valida quantidade negativa.
     * BUG DE SEGURANÇA: SQL injection.
     */
    public boolean criar(String titulo, String autor, int ano, String categoria, int quantidade, String isbn) {
        String sql = "INSERT INTO livros (titulo, autor, ano, categoria, quantidade, isbn) VALUES ("
            + "'" + titulo + "', '" + autor + "', " + ano + ", '" + categoria + "', "
            + quantidade + ", '" + isbn + "')";
        try (Statement st = conn.createStatement()) {
            st.executeUpdate(sql);
            return true;
        } catch (SQLException e) {
            return false;
        }
    }

    /**
     * Remove livro.
     * BUG DE INTEGRIDADE: remove mesmo se houver empréstimos ativos.
     */
    public boolean remover(int id) {
        String sql = "DELETE FROM livros WHERE id = " + id;
        try (Statement st = conn.createStatement()) {
            st.executeUpdate(sql);
            return true;
        } catch (SQLException e) {
            return false;
        }
    }

    /**
     * Quantidade disponível de um livro.
     * BUG LÓGICO: conta apenas empréstimos com status 'emprestado' e subtrai da
     * quantidade total. Se houver mais empréstimos ativos que o estoque, retorna
     * valor NEGATIVO (possível porque o emprestar() não checa disponibilidade).
     */
    public int quantidadeDisponivel(int id) {
        String[] livro = porId(id);
        if (livro == null) {
            return 0;
        }
        int total = Integer.parseInt(livro[5]);
        String sql = "SELECT COUNT(*) AS t FROM emprestimos "
            + "WHERE livro_id = " + id + " AND status = 'emprestado'";
        try (Statement st = conn.createStatement();
             ResultSet rs = st.executeQuery(sql)) {
            rs.next();
            int emprestados = rs.getInt("t");
            return total - emprestados;
        } catch (SQLException e) {
            e.printStackTrace();
            return total;
        }
    }

    /**
     * BUG: método morto (código morto) — não é chamado em nenhum lugar da UI.
     */
    public String disponibilidade(int id) {
        int qtd = quantidadeDisponivel(id);
        if (qtd <= 0) {
            return "indisponivel";
        } else if (qtd == 1) {
            return "ultima-unidade";
        }
        return "disponivel";
    }
}
