<?php
namespace App\Infra;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost'; 
    private $dbName = 'nome_do_banco'; 
    private $username = 'usuario'; 
    private $password = 'senha'; 
    private $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO("pgsql:host={$this->host};dbname={$this->dbName}", $this->username, $this->password);
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
