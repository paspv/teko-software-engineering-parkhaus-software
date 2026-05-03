<?php

namespace App\Models;

class FloorModel extends BaseModel
{
    public int $id;
    public int $parkingGarageId;
    public string $name;
}