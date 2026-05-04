<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Models\ParkingSpaceStateModel;

class ParkingSpaceStateService extends BaseService 
{
    public function __construct()
    {
        parent::__construct(ParkingSpaceStateModel::class, "ParkingSpaceState");
    }

    public function getStateByName($name): ParkingSpaceStateModel
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE Name = :name");
        $stmt->execute(['name' => $name]);

        return $this->fetchOne($stmt);
    }
}