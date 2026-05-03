<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Models\ParkingSpaceModel;
use App\Services\FloorService;


class ParkingSpaceService extends BaseService 
{
    public function __construct()
    {
        parent::__construct(ParkingSpaceModel::class, "ParkingSpace");
    }

    public function chooseParkingSpace(int $garageId): ?ParkingSpaceModel
    {
        $floorService = new FloorService();

        $floors = $floorService->getByParkingGarageId($garageId);

        $floorIds = implode(',', array_column($floors, 'id'));

        return $this->fetchOne($this->db->query("SELECT t.* from {$this->tableName} as t join ParkingSpaceState as s on s.Id = t.ParkingSpaceState_Id where t.Floor_Id in ({$floorIds}) AND s.Status == LIMIT 1;"));
    }

    public function updateState() 
    {
        
    }
}