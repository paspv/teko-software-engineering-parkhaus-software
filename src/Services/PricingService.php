<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Enums\PricingTypeEnum;
use App\Models\ParkingGarageModel;
use App\Models\PricingExceptionsModel;
use App\Services\PricingExceptionService;
use App\Models\PricingModel;
use DateTime;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;

class PricingService extends BaseService 
{
    const float FULL_DAY_FLAT_RATE = 35.00;
    private array $config = [];

    public function __construct()
    {
        parent::__construct(PricingModel::class, "Pricing");
    }

    public function calculatePrice(int $parkingGarageId, DateTime $arrival, DateTime $departure): float
    {
        $pricingExceptionService = new PricingExceptionService();
        $pricingTypeService = new PricingTypeService();

        $stayPeriod = new DatePeriod(
            (clone $arrival)->setTime(0,0,0),
            new DateInterval('P1D'),
            (clone $departure)->setTime(0,0,0),
            DatePeriod::INCLUDE_END_DATE
        );

        $secondsPassed = $departure->getTimestamp() - $arrival->getTimestamp();
        $hoursPassed = floor(abs($secondsPassed / 3600));

        if ($hoursPassed >= 24) {
            // Calculate using the daily flat rate
            return iterator_count($stayPeriod) * $this::FULL_DAY_FLAT_RATE;
        }
        
        // Calculate the individual hours
        $exceptions = $pricingExceptionService->getByDateRange($arrival, $departure);

        $dateConfig = [];

        foreach ($stayPeriod as $date) {
            $this->config[$date->format('Y-m-d')] = null;
        }

        // assemble dataConfig with exception data and then fill in normal data
        foreach ($exceptions as $exception) {
            $this->config[$exception->date->format('Y-m-d')] = [
                'typeId' => $exception->pricingTypeId
            ];
        }

        // check for empty spots in config array
        if (count($this->config) !== count(array_filter($this->config))) {
            $pricingDayMappingService = new PricingDayMappingService();
            foreach ($this->config as $key => $conf) {
                if (!$conf) {
                    $this->config[$key] = $pricingDayMappingService->getPricingTypeIdByDay(new DateTime($key));
                }
            }
        }

        foreach ($this->config as $key => $typeId) { 
            $pricings = $this->getByTypeId($parkingGarageId, $typeId);

            $formatedArray = [];
            foreach ($pricings as $pricing) {
                $time = $pricing->applicableFrom->format('H:i');
                $formatedArray[$time] = [
                    'price' => $pricing->price
                ];
            }
            $this->config[$key] = $formatedArray;
        }

        $calculatedPrice = round($this->calculateQuarterlyRates($arrival, $departure), 2);

        // Do not charge more than the daily flat rate.
        return min($calculatedPrice, $this::FULL_DAY_FLAT_RATE);
    }

    /**
     * @source function generated using Gemini, modified by hand to fit my exact needs and ensure full understanding of the code 
     */
    private function calculateQuarterlyRates(DateTime $arrival, DateTime $departure): float {
        $arrivalTimeStamp = $arrival->getTimestamp();
        $departureTimeStamp = $departure->getTimestamp();
        $totalPrice = 0.0;

        for ($currentTs = $arrivalTimeStamp; $currentTs < $departureTimeStamp; $currentTs += 900) {
            $dateStr = date('Y-m-d', $currentTs);
            $timeStr = date('H:i', $currentTs);

            $hourlyRate = $this->getTariff($dateStr, $timeStr);
            
            // Convert hourly rate to 15 min rate
            $totalPrice += ($hourlyRate / 4);
        }

        return $totalPrice;
    }

    /**
     * @source function generated using Gemini, modified by hand to fit my exact needs and ensure full understanding of the code 
     */
    private function getTariff(string $date, string $time): float {
        $dayConfig = $this->config[$date];
        $currentTariff = 0.0;
        $latestTime = "00:00";

        foreach ($dayConfig as $configTime => $data) {
            if ($time >= $configTime && $configTime >= $latestTime) {
                $currentTariff = $data['price'];
                $latestTime = $configTime;
            }
        }

        return $currentTariff;
    }


    /**
     * @return ?array<PricingModel>
     */
    private function getByTypeId(int $garageId, int $typeId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE ParkingGarage_Id = :garageId AND PricingType_Id = :pricingTypeId");
        $stmt->execute([
            'garageId' => $garageId,
            'pricingTypeId' => $typeId
        ]);

        return $this->fetchAll($stmt);  
    }
}