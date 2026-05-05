<?php 

namespace App\Enums;

enum ParkingSpaceStateEnum
{
    case AVAILABLE;
    case OCCUPIED;
    case RENTED;

    public function getDbName(): string 
    {
        return match($this) {
            ParkingSpaceStateEnum::AVAILABLE => 'Available',
            ParkingSpaceStateEnum::OCCUPIED => 'Occupied',
            ParkingSpaceStateEnum::RENTED => 'Rented'
        };
    }
}