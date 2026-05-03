<?php

namespace App\Models;

class ParkingSpaceModel extends BaseModel
{
    public int $id;
    public int $floorId;
    public int $parkingSpaceStateId;
    public string $name;
}