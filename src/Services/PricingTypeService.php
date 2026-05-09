<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Models\PricingTypeModel;
use DateTime;
use DateTimeImmutable;

class PricingTypeService extends BaseService 
{
    public function __construct()
    {
        parent::__construct(PricingTypeModel::class, "PricingType");
    }

    public function getTypeByName(string $name): PricingTypeModel
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE Name = :name");
        $stmt->execute(['name' => $name]);

        return $this->fetchOne($stmt);
    }
}