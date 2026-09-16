package br.edu.etec.biblioteca.models;

import br.edu.etec.biblioteca.core.Database;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

/**
 * Model Aluno.
 * ATENÇÃO: código intencionalmente com FALHAS (SQL injection, validação frágil).
 */
public class Aluno {

    private final Connection conn;

    public Aluno() {
        this.conn = Database.getConnection();
    }

    /** Lista todos os alunos. */
    public List<String[]> todos() {
        List<String[]> lista = new ArrayList<>();
        try (Statement st = conn.createStatement();
             ResultSet rs = st.executeQuery("SELECT id, nome, email, turma, telefone FROM alunos")) {
            while (rs.next()) {
                lista.add(new String[]{
                    String.valueOf(rs.getInt("id")),
                    rs.getString("nome"),
                    rs.getString("email"),
                    rs.getString("turma"),
                    rs.getString("telefone")
                });
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return lista;
    }

    /**
     * Busca aluno por id.
     * BUG DE SEGURANÇA: SQL injection (id concatenado, não preparado).
     */
    public String[] buscar(int id) {
        String sql = "SELECT id, nome, email, turma, telefone FROM alunos WHERE id = " + id;
        try (Statement st = conn.createStatement();
             ResultSet rs = st.executeQuery(sql)) {
            if (rs.next()) {
                return new String[]{
                    String.valueOf(rs.getInt("id")),
                    rs.getString("nome"),
                    rs.getString("email"),
                    rs.getString("turma"),
                    rs.getString("telefone")
                };
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    /**
     * Cria um aluno.
     * BUG DE SEGURANÇA: SQL injection (campos concatenados).
     * BUG DE VALIDAÇÃO: apenas verifica nome/email vazios; telefone/turma sem validar.
     */
    public boolean criar(String nome, String email, String turma, String telefone) {
        if (nome == null || nome.trim().isEmpty() || email == null || email.trim().isEmpty()) {
            // BUG: retorna false sem informar o usuário qual campo está errado.
            return false;
        }
        String sql = "INSERT INTO alunos (nome, email, turma, telefone) VALUES ("
            + "'" + nome + "', '" + email + "', '" + turma + "', '" + telefone + "')";
        try (Statement st = conn.createStatement()) {
            int linhas = st.executeUpdate(sql);
            // BUG LÓGICO: o resultado real (linhas) é ignorado; retorna true sempre
            // se não houve exceção, mesmo que nada tenha sido inserido.
            return true;
        } catch (SQLException e) {
            // BUG: exceção (ex.: email duplicado) vira apenas "false" sem mensagem.
            return false;
        }
    }

    /**
     * Remove um aluno pelo id.
     * BUG DE INTEGRIDADE: não verifica empréstimos pendentes antes de excluir.
     */
    public boolean remover(int id) {
        String sql = "DELETE FROM alunos WHERE id = " + id;
        try (Statement st = conn.createStatement()) {
            st.executeUpdate(sql);
            return true;
        } catch (SQLException e) {
            return false;
        }
    }

    /**
     * Valida e-mail.
     * BUG: retorna true mesmo para e-mails obviamente inválidos (validação fraca),
     * e não trata nulo (NullPointerException em chamadas com null).
     */
    public boolean emailValido(String email) {
        // Regex incompleta: "a@b" passaria sem ponto? Não — mas "a@@b.com.br" passa.
        if (email == null) {
            return false;
        }
        return email.contains("@") && email.contains(".");
    }
}
