<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use PDOStatement;

class BaseService 
{
    private PDO $db;
    private $modelClass = "";
    private $tableName = "";

    public function __construct(string $modelClass, string $tableName)
    {
        $this->db = Database::getConnection();
        $this->modelClass = $modelClass;
        $this->tableName = $tableName;
    }

    public function getAll(): ?array
    {
        return $this->fetchAll(
            $this->db
            ->query("SELECT * FROM " . $this->tableName )
        );
    }

    public function getById(int $id): ?object
    {
        return $this->fetchOne(
            $this->db->query("SELECT * FROM {$this->tableName} WHERE ID = {$id}")
        );
    }

    private function fetchAll(PDOStatement $query): ?array
    {
        return $query->fetchAll(PDO::FETCH_CLASS, $this->modelClass);
    }

    private function fetchOne(PDOStatement $query): ?object
    {
        return $query->fetchObject($this->modelClass);
    }
}