<?php
// Model Emprestimo
// ATENÇÃO: código intencionalmente com falhas para fins de estudo.

require_once __DIR__ . '/../core/Database.php';

class Emprestimo
{
    private $db;

    // Limite de livros por aluno (constante mal utilizada - ver bugs no emprestar())
    const LIMITE_LIVROS = 3;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function todos()
    {
        $sql = "SELECT e.*, a.nome AS aluno_nome, l.titulo AS livro_titulo
                FROM emprestimos e
                JOIN alunos a ON a.id = e.aluno_id
                JOIN livros l ON l.id = e.livro_id
                ORDER BY e.data_emprestimo DESC";
        $resultado = $this->db->query($sql);
        if ($resultado === false) {
            return [];
        }
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registra um novo empréstimo
    public function emprestar($alunoId, $livroId)
    {
        $livro = new Livro();
        $aluno = new Aluno();
        $livroExiste = $livro->porId($livroId);
        $alunoExiste = $aluno->buscar($alunoId);

        if (!$livroExiste) {
            return ['erro' => 'Livro não encontrado.'];
        }
        if (!$alunoExiste) {
            return ['erro' => 'Aluno não encontrado.'];
        }

        // BUG LÓGICO (regra de negócio): deveria impedir o empréstimo quando
        // a quantidade disponível é 0, mas a variável $disponivel é calculada
        // e NUNCA checada antes de inserir.
        $disponivel = $livro->quantidadeDisponivel($livroId);

        // BUG DE NEGÓCIO: limite de livros por aluno é checado contra o LIMITE
        // com uma condição invertida (>= em vez de >), permitindo empréstimos a mais.
        $sql = "SELECT COUNT(*) AS t FROM emprestimos
                WHERE aluno_id = $alunoId AND status = 'emprestado'";
        $r = $this->db->query($sql);
        $emprestados = (int)$r->fetch(PDO::FETCH_ASSOC)['t'];

        if ($emprestados >= self::LIMITE_LIVROS) {
            return ['erro' => 'Aluno já atingiu o limite de empréstimos.'];
        }

        // BUG LÓGICO: insere mesmo se não houver disponibilidade
        $data = date('Y-m-d');
        $sql = "INSERT INTO emprestimos (aluno_id, livro_id, data_emprestimo, status)
                VALUES ($alunoId, $livroId, '$data', 'emprestado')";
        $this->db->exec($sql);
        return ['id' => $this->db->getPdo()->lastInsertId()];
    }

    // Registra devolução de um empréstimo
    public function devolver($id)
    {
        // BUG: não verifica se o empréstimo já foi devolvido -> pode "devolver" duas vezes
        $data = date('Y-m-d');
        $sql = "UPDATE emprestimos
                SET data_devolucao = '$data', status = 'devolvido'
                WHERE id = $id";
        return $this->db->exec($sql);
    }

    // Lista empréstimos em atraso (data_devolucao nula e mais de 14 dias)
    public function atrasados()
    {
        $sql = "SELECT e.*, a.nome AS aluno_nome, l.titulo AS livro_titulo
                FROM emprestimos e
                JOIN alunos a ON a.id = e.aluno_id
                JOIN livros l ON l.id = e.livro_id
                WHERE e.status = 'emprestado'
                  AND date(e.data_emprestimo) < date('now', '-14 day')";
        $resultado = $this->db->query($sql);
        if ($resultado === false) {
            return [];
        }
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public function doAluno($alunoId)
    {
        $sql = "SELECT e.*, l.titulo AS livro_titulo
                FROM emprestimos e
                JOIN livros l ON l.id = e.livro_id
                WHERE e.aluno_id = $alunoId";
        $resultado = $this->db->query($sql);
        if ($resultado === false) {
            return [];
        }
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }
}
