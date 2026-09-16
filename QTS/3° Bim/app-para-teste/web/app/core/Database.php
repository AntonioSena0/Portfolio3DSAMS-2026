<?php
// Classe Database - conexão com o banco SQLite
// ATENÇÃO: código intencionalmente com falhas para fins de estudo/teste.
// (Ex.: chave de banco fixa - em produção, use variáveis de ambiente.)

class Database
{
    private static $instancia = null;
    private $pdo;

    // Arquivo do banco fixo no código - má prática de configuração.
    private $dbFile = __DIR__ . '/../../data/biblioteca.db';

    private function __construct()
    {
        try {
            $this->pdo = new PDO('sqlite:' . $this->dbFile);   // sem ERRMODE_EXCEPTION!
            // NOTA: não foi definido PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            // isso faz com que erros de SQL sejam silenciosos.
        } catch (PDOException $e) {
            // Erros de conexão são simplesmente engolidos sem log.
            $this->pdo = null;
        }
    }

    public static function getInstance()
    {
        if (self::$instancia === null) {
            self::$instancia = new Database();
        }
        return self::$instancia;
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function query($sql)
    {
        return $this->pdo->query($sql);
    }

    public function exec($sql)
    {
        return $this->pdo->exec($sql);
    }
}
