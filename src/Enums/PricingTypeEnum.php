<?php 

namespace App\Enums;

enum PricingTypeEnum
{
    case WEEKDAYS;
    case WEEKENDS_AND_HOLIDAYS;

    public function getDbName(): string 
    {
        return match($this) {
            PricingTypeEnum::WEEKDAYS => 'Wochentage',
            PricingTypeEnum::WEEKENDS_AND_HOLIDAYS => 'Wochenende und Feiertage',
        };
    }
}