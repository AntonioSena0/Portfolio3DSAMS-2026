package br.edu.etec.biblioteca.core;

import java.io.File;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

/**
 * Conexão com o banco SQLite.
 *
 * ATENÇÃO: código intencionalmente com FALHAS para fins de estudo.
 * - Não configura {@code FK} nem {@code Statement} com timeouts;
 * - Erros de conexão são engolidos sem log;
 * - Caminho do banco fixo no código (má prática de configuração).
 */
public class Database {

    // Caminho fixo (relativo ao diretório onde o programa for executado).
    // BUG DE CONFIGURAÇÃO: usar caminho/AbsPath fixo impede portabilidade.
    private static final String DB_PADRAO = "data" + File.separator + "biblioteca.db";

    private static String dbPath = System.getProperty("biblioteca.db", DB_PADRAO);

    private static Connection conexao;

    private Database() {
    }

    public static synchronized void definirBanco(String caminho) {
        if (caminho.equals(dbPath) && conexao != null) {
            return;
        }
        fechar();
        dbPath = caminho;
    }

    public static synchronized void usarBancoPadrao() {
        definirBanco(System.getProperty("biblioteca.db", DB_PADRAO));
    }

    private static synchronized void fechar() {
        if (conexao != null) {
            try {
                conexao.close();
            } catch (Exception ignored) {
            }
            conexao = null;
        }
    }

    /**
     * Obtém a conexão única (singleton sincronizado).
     */
    public static synchronized Connection getConnection() {
        if (conexao == null) {
            try {
                Class.forName("org.sqlite.JDBC");
                // BUG: sem PDO/JDBC transacional; autocommit ativo.
                // Também não configura PK/foreign keys enforcement.
                conexao = DriverManager.getConnection("jdbc:sqlite:" + dbPath);
            } catch (Exception e) {
                // BUG DE CONFIABILIDADE: exceção engolida (nothing logged),
                // e o programa segue com conexao == null -> NullPointer a seguir.
                e.printStackTrace();
            }
        }
        return conexao;
    }

    /**
     * Cria as tabelas e insere dados de exemplo caso o banco não exista.
     * BUG: "criarBancoSeNecessario" usa DDL via Statement com constante SQL
     * inline e não valida retorno (código frágil).
     */
    public static void criarBancoSeNecessario() {
        File arquivo = new File(dbPath);
        File pasta = arquivo.getParentFile();
        if (pasta != null && !pasta.exists()) {
            pasta.mkdirs();
        }

        Connection conn = getConnection();
        if (conn == null) {
            // BUG: se a conexão falhou, aqui daria NPE; retorna sem aviso ao usuário.
            return;
        }

        try (Statement st = conn.createStatement()) {
            st.executeUpdate(
                "CREATE TABLE IF NOT EXISTS alunos ("
                + " id INTEGER PRIMARY KEY AUTOINCREMENT,"
                + " nome TEXT NOT NULL,"
                + " email TEXT NOT NULL UNIQUE,"
                + " turma TEXT NOT NULL,"
                + " telefone TEXT)"
            );
            st.executeUpdate(
                "CREATE TABLE IF NOT EXISTS livros ("
                + " id INTEGER PRIMARY KEY AUTOINCREMENT,"
                + " titulo TEXT NOT NULL,"
                + " autor TEXT NOT NULL,"
                + " ano INTEGER,"
                + " categoria TEXT,"
                + " quantidade INTEGER DEFAULT 1,"
                + " isbn TEXT)"
            );
            st.executeUpdate(
                "CREATE TABLE IF NOT EXISTS emprestimos ("
                + " id INTEGER PRIMARY KEY AUTOINCREMENT,"
                + " aluno_id INTEGER NOT NULL,"
                + " livro_id INTEGER NOT NULL,"
                + " data_emprestimo TEXT NOT NULL,"
                + " data_devolucao TEXT,"
                + " status TEXT DEFAULT 'emprestado')"
            );

            // Semear dados se a tabela de alunos estiver vazia
            java.sql.ResultSet rs = st.executeQuery("SELECT COUNT(*) AS c FROM alunos");
            boolean vazio = rs.next() && rs.getInt("c") == 0;
            if (vazio) {
                st.executeUpdate("INSERT INTO alunos (nome, email, turma, telefone) VALUES "
                    + "('Ana Souza','ana@escola.edu','2A','11999990001'),"
                    + "('Bruno Lima','bruno@escola.edu','2A','11999990002'),"
                    + "('Carla Dias','carla@escola.edu','2B','11999990003'),"
                    + "('Diego Rocha','diego@escola.edu','2B','11999990004')");
                st.executeUpdate("INSERT INTO livros (titulo, autor, ano, categoria, quantidade, isbn) VALUES "
                    + "('Dom Casmurro','Machado de Assis',1899,'Romance',3,'9788535908611'),"
                    + "('Memorias Postumas','Machado de Assis',1881,'Romance',2,'9788535870772'),"
                    + "('O Cortico','Aluisio Azevedo',1890,'Romance',1,'9788572324894'),"
                    + "('Vidas Secas','Graciliano Ramos',1938,'Regional',4,'9788503011782'),"
                    + "('Capitães da Areia','Jorge Amado',1937,'Romance',2,'9788542214978')");
                st.executeUpdate("INSERT INTO emprestimos "
                    + "(aluno_id, livro_id, data_emprestimo, status) VALUES "
                    + "(1,1,date('now','-20 days'),'emprestado'),"
                    + "(2,3,date('now','-7 days'),'emprestado')");
            }
        } catch (SQLException e) {
            // BUG: erro de DDL silencioso (apenas printa, sem UX para o usuário).
            e.printStackTrace();
        }
    }
}
