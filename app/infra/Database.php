<?php
namespace App\Infra;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost'; 
    private $port = '5433';
    private $dbName = 'gerenciador-de-metas'; 
    private $username = 'postgres'; 
    private $password = 'batata';
    private $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO(
                "pgsql:host={$this->host};port={$this->port};dbname={$this->dbName}",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
