<?php
// Model Livro
// ATENÇÃO: código intencionalmente com falhas para fins de estudo.

require_once __DIR__ . '/../core/Database.php';

class Livro
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function todos()
    {
        $resultado = $this->db->query("SELECT * FROM livros");
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca livros por termo (pesquisa)
    public function buscar($termo)
    {
        // BUG DE SEGURANÇA + PERFORMANCE: SQL injection e LIKE sem escape, varredura total.
        $sql = "SELECT * FROM livros WHERE titulo LIKE '%$termo%' OR autor LIKE '%$termo%'";
        $resultado = $this->db->query($sql);
        if ($resultado === false) {
            return [];
        }
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function porId($id)
    {
        $sql = "SELECT * FROM livros WHERE id = " . $id;
        $resultado = $this->db->query($sql);
        if ($resultado === false) {
            return null;
        }
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($titulo, $autor, $ano, $categoria, $quantidade, $isbn)
    {
        // BUG: não valida se quantidade é negativa antes do insert
        $sql = "INSERT INTO livros (titulo, autor, ano, categoria, quantidade, isbn)
                VALUES ('$titulo', '$autor', '$ano', '$categoria', $quantidade, '$isbn')";
        $this->db->exec($sql);
        return $this->db->getPdo()->lastInsertId();
    }

    public function atualizar($id, $titulo, $autor, $ano, $categoria, $quantidade, $isbn)
    {
        $sql = "UPDATE livros SET
                    titulo = '$titulo',
                    autor = '$autor',
                    ano = '$ano',
                    categoria = '$categoria',
                    quantidade = $quantidade,
                    isbn = '$isbn'
                WHERE id = $id";
        return $this->db->exec($sql);
    }

    public function remover($id)
    {
        // BUG: remove livro mesmo se estiver emprestado -> inconsistência de dados
        $sql = "DELETE FROM livros WHERE id = " . $id;
        return $this->db->exec($sql);
    }

    // Retorna a quantidade disponível de um livro
    public function quantidadeDisponivel($id)
    {
        // BUG LÓGICO: considera apenas empréstimos com status 'emprestado',
        // porém não subtrai da quantidade total corretamente se houver devoluções parciais.
        $livro = $this->porId($id);
        if (!$livro) {
            return 0;
        }
        $total = (int)$livro['quantidade'];
        $sql = "SELECT COUNT(*) AS t FROM emprestimos
                WHERE livro_id = $id AND status = 'emprestado'";
        $r = $this->db->query($sql);
        if ($r === false) {
            return $total;
        }
        $emprestados = (int)$r->fetch(PDO::FETCH_ASSOC)['t'];
        return $total - $emprestados;
    }

    // BUG: método "disponibilidade" não usado em lugar algum - código morto.
    public function disponibilidade($id)
    {
        $qtd = $this->quantidadeDisponivel($id);
        if ($qtd <= 0) {
            return 'indisponivel';
        } elseif ($qtd == 1) {
            return 'ultima-unidade';
        }
        return 'disponivel';
    }
}
