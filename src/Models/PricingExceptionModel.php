<?php

namespace App\Models;

use DateTime;

class PricingExceptionModel extends BaseModel
{
    public int $id;
    public int $pricingTypeId;
    public DateTime $date;
    public string $name;
}
