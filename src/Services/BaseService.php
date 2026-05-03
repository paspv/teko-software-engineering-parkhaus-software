<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use PDOStatement;

class BaseService 
{
    protected PDO $db;
    protected $modelClass = "";
    protected $tableName = "";

    public function __construct(string $modelClass, string $tableName)
    {
        $this->db = Database::getConnection();
        $this->modelClass = $modelClass;
        $this->tableName = $tableName;
    }

    public function getAll(): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->tableName );
        $stmt->execute();
        
        return $this->fetchAll($stmt);
    }

    public function getById(int $id): ?object
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE id = :id");
        $stmt->execute(['id' => $id]);

        return $this->fetchOne($stmt);
    }

    protected function fetchAll(PDOStatement $query): ?array
    {
        return $query->fetchAll(PDO::FETCH_CLASS, $this->modelClass);
    }

    protected function fetchOne(PDOStatement $query): ?object
    {
        return $query->fetchObject($this->modelClass);
    }

    
}