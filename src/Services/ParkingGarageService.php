<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Models\ParkingGarageModel;

class ParkingGarageService extends BaseService 
{
    public function __construct()
    {
        parent::__construct(ParkingGarageModel::class, "ParkingGarage");
    }
}