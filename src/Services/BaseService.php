<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Models\ParkingGarages;

class BaseService {
    private PDO $db;
    private $modelClass = "";
    private $tableName = "";

    public function __construct(string $modelClass, string $tableName)
    {
        $this->db = Database::getConnection();
        $this->modelClass = $modelClass;
        $this->tableName = $tableName;
    }

    function getAll() {
        return $this->db
            ->query("SELECT * FROM " . $this->tableName )
            ->fetchAll(PDO::FETCH_CLASS, $this->modelClass);
    }
}