<?php

namespace App\Models;

class PricingDayMappingModel extends BaseModel
{
    public int $id;
    public int $pricingTypeId;
    public int $dayIndex;
}