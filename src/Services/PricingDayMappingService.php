<?php

namespace App\Services;

use PDO;
use DateTime;
use App\Core\Database;
use App\Models\PricingDayMappingModel;
use DateError;

class PricingDayMappingService extends BaseService 
{
    public function __construct()
    {
        parent::__construct(PricingDayMappingModel::class, "PricingDayMapping");
    }

    public function getPricingTypeIdByDay(DateTime $date): int
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE DayIndex = :dayIndex");
        $stmt->execute(['dayIndex' => $date->format('N')]);

        return $this->fetchOne($stmt)->pricingTypeId;
    }
}