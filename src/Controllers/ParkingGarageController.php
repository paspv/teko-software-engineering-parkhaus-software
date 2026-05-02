<?php
namespace App\Controllers;

use App\Services\ParkingGarageService;
use Exception;

class ParkingGarageController extends BaseController 
{    
    public function entrance($vars) 
    {
        if (
            !array_key_exists('garageId', $vars)
            || !is_int($vars['garageId'])
            ) {
                header("HTTP/1.0 400 Bad Request");
                echo "400 - Bad Request";
                return;
        }

        $garageId = $vars["garageId"];

        $service = new ParkingGarageService();
        $garage = $service->getById();
        
        $this->render('entrance', [
            parking
        ]);
    }
    
    public function exit($vars) 
    {
        echo "Exiting Garage: " . htmlspecialchars($vars['garageId']);
    }
}