<?php

namespace App\Models;

use DateTime;

class PricingModel extends BaseModel
{
    public int $id;
    public int $parkingGarageId;
    public int $pricingTypeId;
    public float $price;
    public DateTime $applicableFrom;

    public function __set($name, $value) {
        if (($name === 'ApplicableFrom') && is_string($value)) {
            $camelName = lcfirst(str_replace('_', '', $name));
            $this->$camelName = new \DateTime($value);
            return;
        }

        parent::__set($name, $value);
    }
}