<?php

namespace App;

use App\Core\Router;
use App\Controllers\ParkingGarageController;
use App\Controllers\HomeController;
use App\Controllers\ReportController;
use Dotenv\Dotenv;

class App 
{
    public function init() 
    {
        $this->initEnvVariables();

        $this->configureRouter()->dispatch($_SERVER['REQUEST_URI']);
    }

    private function initEnvVariables() 
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    private function configureRouter(): Router 
    {
        $router = new Router();

        // Static Home
        $router->add('/', [HomeController::class, 'index']);

        // Garage Routes
        $router->add('/{garageId}/entry', [ParkingGarageController::class, 'entrance']);
        $router->add('/{garageId}/exit', [ParkingGarageController::class, 'exit']);

        // Report Routes
        $router->add('/report', [ReportController::class, 'index']);
        $router->add('/report/{reportId}', [ReportController::class, 'show']);

        return $router;
    }
}