<?php
// Script de inicialização do banco de dados
// Rode uma única vez: php data/import_bd.php
// Esse script cria a tabela e insere alguns dados de exemplo.

require __DIR__ . '/../app/core/Database.php';

$db = Database::getInstance();

$db->exec("
CREATE TABLE IF NOT EXISTS alunos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    turma TEXT NOT NULL,
    telefone TEXT
);
");

$db->exec("
CREATE TABLE IF NOT EXISTS livros (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    autor TEXT NOT NULL,
    ano INTEGER,
    categoria TEXT,
    quantidade INTEGER DEFAULT 1,
    isbn TEXT
);
");

$db->exec("
CREATE TABLE IF NOT EXISTS emprestimos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    aluno_id INTEGER NOT NULL,
    livro_id INTEGER NOT NULL,
    data_emprestimo TEXT NOT NULL,
    data_devolucao TEXT,
    status TEXT DEFAULT 'emprestado',
    FOREIGN KEY (aluno_id) REFERENCES alunos(id),
    FOREIGN KEY (livro_id) REFERENCES livros(id)
);
");

// Seed - apenas se as tabelas estiverem vazias
$count = $db->query("SELECT COUNT(*) AS t FROM alunos")->fetch(PDO::FETCH_ASSOC)['t'];
if ($count == 0) {
    $db->exec("INSERT INTO alunos (nome, email, turma, telefone) VALUES
        ('Ana Souza', 'ana@escola.edu', '2A', '11999990001'),
        ('Bruno Lima', 'bruno@escola.edu', '2A', '11999990002'),
        ('Carla Dias', 'carla@escola.edu', '2B', '11999990003'),
        ('Diego Rocha', 'diego@escola.edu', '2B', '11999990004'),
        ('Eva Martins', 'eva@escola.edu', '3A', '11999990005');
    ");
    $db->exec("INSERT INTO livros (titulo, autor, ano, categoria, quantidade, isbn) VALUES
        ('Dom Casmurro', 'Machado de Assis', 1899, 'Romance', 3, '9788535908611'),
        ('Memorias Postumas', 'Machado de Assis', 1881, 'Romance', 2, '9788535870772'),
        ('O Cortico', 'Aluisio Azevedo', 1890, 'Romance', 1, '9788572324894'),
        ('Vidas Secas', 'Graciliano Ramos', 1938, 'Regional', 4, '9788503011782'),
        ('Capitães da Areia', 'Jorge Amado', 1937, 'Romance', 2, '9788542214978'),
        ('Quarto de Despejo', 'Carolina Maria de Jesus', 1960, 'Memórias', 0, '9788594540000'),
        ('Iracema', 'José de Alencar', 1865, 'Romance', 2, '9788516126100'),
        ('O Mulato', 'Aluisio Azevedo', 1881, 'Romance', 1, '9788572324528');
    ");
    $db->exec("INSERT INTO emprestimos (aluno_id, livro_id, data_emprestimo, data_devolucao, status) VALUES
        (1, 1, '2026-08-01', '2026-08-15', 'devolvido'),
        (2, 3, '2026-08-10', '2026-08-24', 'devolvido'),
        (3, 5, '2026-08-12', NULL, 'emprestado'),
        (4, 2, '2026-08-14', NULL, 'emprestado');
    ");
    echo "Dados de exemplo inseridos." . PHP_EOL;
} else {
    echo "Banco já populado. Nada a fazer." . PHP_EOL;
}
echo "OK" . PHP_EOL;
