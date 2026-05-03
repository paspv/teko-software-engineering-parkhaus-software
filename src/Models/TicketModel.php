<?php

namespace App\Models;

use DateTime;

class TicketModel extends BaseModel
{
    public int $id;
    public int $floorId;
    public int $parkingSpaceId;
    public DateTime $arrival;
    public ?DateTime $departure;
    public string $identifier;

    public function __set($name, $value) {
        if (($name === 'Arrival' || $name === 'Departure') && is_string($value)) {
            $camelName = lcfirst(str_replace('_', '', $name));
            $this->$camelName = new \DateTime($value);
            return;
        }

        parent::__set($name, $value);
    }
}
