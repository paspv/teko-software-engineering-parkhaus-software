<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Models\ParkingGarage;

class ParkingGarageService extends BaseService 
{
    public function __construct()
    {
        parent::__construct(ParkingGarage::class, "ParkingGarage");
    }
}