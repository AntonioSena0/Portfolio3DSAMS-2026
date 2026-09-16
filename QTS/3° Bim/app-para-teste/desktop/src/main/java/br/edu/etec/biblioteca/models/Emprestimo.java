package br.edu.etec.biblioteca.models;

import br.edu.etec.biblioteca.core.Database;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

/**
 * Model Emprestimo.
 * ATENÇÃO: código intencionalmente com FALHAS (regras de negócio quebradas).
 */
public class Emprestimo {

    // Limite de livros por aluno (constante no código).
    private static final int LIMITE_LIVROS = 3;

    private final Connection conn;

    public Emprestimo() {
        this.conn = Database.getConnection();
    }

    public List<String[]> todos() {
        List<String[]> lista = new ArrayList<>();
        String sql = "SELECT e.id, a.nome AS aluno_nome, l.titulo AS livro_titulo, "
            + "e.data_emprestimo, e.data_devolucao, e.status "
            + "FROM emprestimos e "
            + "JOIN alunos a ON a.id = e.aluno_id "
            + "JOIN livros l ON l.id = e.livro_id "
            + "ORDER BY e.data_emprestimo DESC";
        try (Statement st = conn.createStatement();
             ResultSet rs = st.executeQuery(sql)) {
            while (rs.next()) {
                lista.add(new String[]{
                    String.valueOf(rs.getInt("id")),
                    rs.getString("aluno_nome"),
                    rs.getString("livro_titulo"),
                    rs.getString("data_emprestimo"),
                    rs.getString("data_devolucao") == null ? "" : rs.getString("data_devolucao"),
                    rs.getString("status")
                });
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return lista;
    }

    /**
     * Registra um empréstimo.
     * BUG DE NEGÓCIO (crítico): a disponibilidade é calculada mas NUNCA checada
     * antes de inserir — é possível emprestar além do estoque.
     * BUG DE NEGÓCIO: o limite de livros por aluno é checado ANTES do insert e
     * usa ">= LIMITE", o que permite ao aluno pegar o 4º livro (ver docs).
     */
    public boolean emprestar(int alunoId, int livroId) {
        Livro livro = new Livro();
        Aluno aluno = new Aluno();

        if (livro.porId(livroId) == null) {
            System.out.println("[BUG] livro não encontrado (ignorado pela UI)");
            return false;
        }
        if (aluno.buscar(alunoId) == null) {
            System.out.println("[BUG] aluno não encontrado (ignorado pela UI)");
            return false;
        }

        int disponivel = livro.quantidadeDisponivel(livroId);
        // NOTA: variável calculada mas NUNCA usada -> o empréstimo segue mesmo com 0.

        // Contagem de empréstimos ativos do aluno
        int emprestados = 0;
        String countSql = "SELECT COUNT(*) AS t FROM emprestimos "
            + "WHERE aluno_id = " + alunoId + " AND status = 'emprestado'";
        try (Statement st = conn.createStatement();
             ResultSet rs = st.executeQuery(countSql)) {
            rs.next();
            emprestados = rs.getInt("t");
        } catch (SQLException e) {
            e.printStackTrace();
        }

        if (emprestados >= LIMITE_LIVROS) {
            System.out.println("[INFO] aluno já atingiu o limite (" + LIMITE_LIVROS + ")");
            return false;
        }

        String data = LocalDate.now().toString();
        String sql = "INSERT INTO emprestimos (aluno_id, livro_id, data_emprestimo, status) VALUES ("
            + alunoId + ", " + livroId + ", '" + data + "', 'emprestado')";
        try (Statement st = conn.createStatement()) {
            st.executeUpdate(sql);
            return true;
        } catch (SQLException e) {
            return false;
        }
    }

    /**
     * Devolve um empréstimo.
     * BUG DE NEGÓCIO: não verifica se o empréstimo já foi devolvido, permitindo
     * devolução dupla que sobrescreve a data.
     */
    public boolean devolver(int id) {
        String data = LocalDate.now().toString();
        String sql = "UPDATE emprestimos SET data_devolucao = '" + data
            + "', status = 'devolvido' WHERE id = " + id;
        try (Statement st = conn.createStatement()) {
            st.executeUpdate(sql);
            return true;
        } catch (SQLException e) {
            return false;
        }
    }

    /** Empréstimos ativos com mais de 14 dias (em atraso). */
    public List<String[]> atrasados() {
        List<String[]> lista = new ArrayList<>();
        String sql = "SELECT e.id, a.nome AS aluno_nome, l.titulo AS livro_titulo, e.data_emprestimo "
            + "FROM emprestimos e "
            + "JOIN alunos a ON a.id = e.aluno_id "
            + "JOIN livros l ON l.id = e.livro_id "
            + "WHERE e.status = 'emprestado' "
            + "  AND date(e.data_emprestimo) < date('now','-14 day')";
        try (Statement st = conn.createStatement();
             ResultSet rs = st.executeQuery(sql)) {
            while (rs.next()) {
                lista.add(new String[]{
                    rs.getString("aluno_nome"),
                    rs.getString("livro_titulo"),
                    rs.getString("data_emprestimo")
                });
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return lista;
    }
}
