<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\ParkingGarageService;

class HomeController extends BaseController 
{
    public function index($vars) 
    {
        $service = new ParkingGarageService();
        $garages = $service->getAll();

        $this->render('garage-list', [
            'garages' => $garages
        ]);
    }
}