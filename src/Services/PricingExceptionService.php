<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Models\PricingExceptionModel;
use DateTime;

class PricingExceptionService extends BaseService 
{
    public function __construct()
    {
        parent::__construct(PricingExceptionModel::class, "PricingException");
    }

    /**
     * @return array<PricingExceptionModel>
     */
    public function getByDateRange(DateTime $arrival, DateTime $departure): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE Date >= :arrival AND Date <= :departure");
        $stmt->execute([
            'arrival' => $arrival->format('Y-m-d H:i:s'),
            'departure' => $departure->format('Y-m-d H:i:s')
        ]);

        return $this->fetchAll($stmt);
    }
}