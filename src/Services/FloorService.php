<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Models\FloorModel;

class FloorService extends BaseService 
{
    public function __construct()
    {
        parent::__construct(FloorModel::class, "Floor");
    }

    /**
     * @return ?array<FloorModel>
     */
    public function getByParkingGarageId(int $garageId): ?array
    {
        return $this->fetchAll(
            $this->db->query("SELECT * FROM {$this->tableName} WHERE ParkingGarage_Id = {$garageId}")
        );    
    }
}