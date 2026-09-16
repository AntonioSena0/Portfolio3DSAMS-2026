<?php
// Front Controller - Sistema de Biblioteca Escolar
// ATENÇÃO: aplicação intencionalmente com falhas para fins de estudo/teste.
//
// Para rodar:
//   cd app-para-teste-web
//   php -S localhost:8000 -t public
//   acesse http://localhost:8000
//
// O banco é criado automaticamente na primeira execução (veja boot()).

session_start();

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/models/Aluno.php';
require_once __DIR__ . '/../app/models/Livro.php';
require_once __DIR__ . '/../app/models/Emprestimo.php';

// Cria o banco se não existir
function boot()
{
    $arquivo = __DIR__ . '/../data/biblioteca.db';
    if (!file_exists($arquivo)) {
        // BUG DE CONFIGURAÇÃO: usar dados de produção fixos, sem checar se
        // é ambiente de desenvolvimento. Além disso, dispara o seed via shell.
        $db = Database::getInstance();
        $db->getPdo()->exec("
            CREATE TABLE IF NOT EXISTS alunos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                turma TEXT NOT NULL,
                telefone TEXT
            );
            CREATE TABLE IF NOT EXISTS livros (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                titulo TEXT NOT NULL,
                autor TEXT NOT NULL,
                ano INTEGER,
                categoria TEXT,
                quantidade INTEGER DEFAULT 1,
                isbn TEXT
            );
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
        // Seed
        $db->getPdo()->exec("INSERT INTO alunos (nome, email, turma, telefone) VALUES
            ('Ana Souza', 'ana@escola.edu', '2A', '11999990001'),
            ('Bruno Lima', 'bruno@escola.edu', '2A', '11999990002'),
            ('Carla Dias', 'carla@escola.edu', '2B', '11999990003'),
            ('Diego Rocha', 'diego@escola.edu', '2B', '11999990004');
        ");
        $db->getPdo()->exec("INSERT INTO livros (titulo, autor, ano, categoria, quantidade, isbn) VALUES
            ('Dom Casmurro', 'Machado de Assis', 1899, 'Romance', 3, '9788535908611'),
            ('Memorias Postumas', 'Machado de Assis', 1881, 'Romance', 2, '9788535870772'),
            ('O Cortico', 'Aluisio Azevedo', 1890, 'Romance', 1, '9788572324894'),
            ('Vidas Secas', 'Graciliano Ramos', 1938, 'Regional', 4, '9788503011782'),
            ('Capitães da Areia', 'Jorge Amado', 1937, 'Romance', 2, '9788542214978');
        ");
        $db->getPdo()->exec("INSERT INTO emprestimos (aluno_id, livro_id, data_emprestimo, status) VALUES
            (1, 1, '" . date('Y-m-d', strtotime('-20 days')) . "', 'emprestado'),
            (2, 3, '" . date('Y-m-d', strtotime('-7 days')) . "', 'emprestado');
        ");
    }
}

boot();

// ===== ROTEAMENTO SIMPLES =====
$acao = $_GET['acao'] ?? 'dashboard';
$rota = explode('/', $acao);

switch ($rota[0]) {
    case 'dashboard':
        $livro = new Livro();
        $emprestimo = new Emprestimo();
        $aluno = new Aluno();
        $totalLivros = count($livro->todos());
        $totalAlunos = count($aluno->todos());
        $totalEmprestimos = count($emprestimo->todos());
        $atrasados = $emprestimo->atrasados();
        include __DIR__ . '/../app/views/dashboard.php';
        break;

    case 'alunos':
        $aluno = new Aluno();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $turma = $_POST['turma'] ?? '';
            $telefone = $_POST['telefone'] ?? '';
            $resultado = $aluno->criar($nome, $email, $turma, $telefone);
            // BUG: redireciona sem considerar mensagens de erro
            header('Location: /index.php?acao=alunos');
            exit;
        }
        $alunos = $aluno->todos();
        include __DIR__ . '/../app/views/alunos.php';
        break;

    case 'livros':
        $livro = new Livro();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'] ?? '';
            $autor = $_POST['autor'] ?? '';
            $ano = $_POST['ano'] ?? '';
            $categoria = $_POST['categoria'] ?? '';
            $quantidade = $_POST['quantidade'] ?? 1;
            $isbn = $_POST['isbn'] ?? '';
            $livro->criar($titulo, $autor, $ano, $categoria, $quantidade, $isbn);
            header('Location: /index.php?acao=livros');
            exit;
        }
        $termo = $_GET['q'] ?? '';
        if ($termo !== '') {
            $livros = $livro->buscar($termo);
        } else {
            $livros = $livro->todos();
        }
        include __DIR__ . '/../app/views/livros.php';
        break;

    case 'emprestimos':
        $emprestimo = new Emprestimo();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['devolver'])) {
                $emprestimo->devolver($_POST['id']);
                header('Location: /index.php?acao=emprestimos');
                exit;
            }
            $resultado = $emprestimo->emprestar($_POST['aluno_id'], $_POST['livro_id']);
            // BUG: erro retornado é ignorado -> aluno não fica sabendo
            header('Location: /index.php?acao=emprestimos');
            exit;
        }
        $emprestimos = $emprestimo->todos();
        $aluno = new Aluno();
        $alunos = $aluno->todos();
        $livro = new Livro();
        $livros = $livro->todos();
        include __DIR__ . '/../app/views/emprestimos.php';
        break;

    default:
        http_response_code(404);
        echo 'Página não encontrada';
        break;
}
