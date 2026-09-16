<?php
// Model Aluno
// ATENÇÃO: código intencionalmente com falhas para fins de estudo.

require_once __DIR__ . '/../core/Database.php';

class Aluno
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Obtém todos os alunos
    public function todos()
    {
        $resultado = $this->db->query("SELECT * FROM alunos");
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtém um aluno pelo id
    public function buscar($id)
    {
        // BUG DE SEGURANÇA: SQL Injection - id não é parametrizado
        $sql = "SELECT * FROM alunos WHERE id = " . $id;
        $resultado = $this->db->query($sql);
        if ($resultado === false) {
            return null;
        }
        return $resultado->fetch(PDO::FETCH_ASSOC);
    }

    // Cria um novo aluno
    public function criar($nome, $email, $turma, $telefone)
    {
        // BUG: SQL Injection (email não sanitizado)
        // BUG LÓGICO: validar se os campos não são vazios está aqui, fora da camada de validação
        if ($nome == '' || $email == '') {
            return ['erro' => 'Nome e email são obrigatórios.'];
        }

        $sql = "INSERT INTO alunos (nome, email, turma, telefone)
                VALUES ('$nome', '$email', '$turma', '$telefone')";
        $this->db->exec($sql);

        // BUG: verifica se houve erro comparando com true (deveria ser !== false)
        // como exec retorna number of rows ou false, se inserir 1 linha, 1 == true é true
        $id = $this->db->getPdo()->lastInsertId();
        return ['id' => $id];
    }

    // Atualiza um aluno
    public function atualizar($id, $nome, $email, $turma, $telefone)
    {
        $sql = "UPDATE alunos SET
                    nome = '$nome',
                    email = '$email',
                    turma = '$turma',
                    telefone = '$telefone'
                WHERE id = $id";
        return $this->db->exec($sql);
    }

    // Remove um aluno
    public function remover($id)
    {
        $sql = "DELETE FROM alunos WHERE id = " . $id;
        // NÃO verifica se o aluno tem empréstimos pendentes -> perde integridade
        return $this->db->exec($sql);
    }

    // Validação de e-mail - reimplementação desnecessária e incompleta
    public function emailValido($email)
    {
        // BUG: nunca retorna true para emails comuns pois não considera domínio completo.
        // Ex.: ana@escola.edu não tem ponto no domínio -> filter_var retorna true, ok.
        if (!preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/', $email)) {
            // BUG: mensagem de erro não informa qual campo falhou
            return 'E-mail inválido.';
        }
        return true;
    }
}
