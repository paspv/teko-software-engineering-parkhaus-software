<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Enums\ParkingSpaceStateEnum;
use App\Models\ParkingSpaceModel;
use App\Services\FloorService;
use App\Services\ParkingSpaceStateService;


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
        $floorIds = array_column($floors, 'id');

        if (empty($floorIds)) {
            return null;
        }

        $parkingSpaceStateService = new ParkingSpaceStateService();
        $openState = $parkingSpaceStateService->getStateByName(ParkingSpaceStateEnum::AVAILABLE->getDbName());

        $placeholders = implode(',', array_fill(0, count($floorIds), '?'));

        $sql = "SELECT t.* FROM {$this->tableName} AS t 
                    JOIN ParkingSpaceState AS s ON s.Id = t.ParkingSpaceState_Id 
                    WHERE t.Floor_Id IN ($placeholders) 
                    AND s.Id = ? 
                    LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $params = array_merge($floorIds, [$openState->id]);
        $stmt->execute($params);
        
        return $this->fetchOne($stmt);
    }

    public function updateState(int $spaceId, int $stateId): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->tableName} SET ParkingSpaceState_Id = :stateId WHERE Id = :spaceId");
        return $stmt->execute(['stateId' => $stateId, 'spaceId' => $spaceId]);
    }
}