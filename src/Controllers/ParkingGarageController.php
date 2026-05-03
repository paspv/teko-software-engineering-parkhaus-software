<?php
namespace App\Controllers;

use App\Models\FloorModel;
use App\Services\ParkingGarageService;
use App\Services\TicketService;
use App\Services\FloorService;
use App\Services\ParkingSpaceService;
use App\Models\TicketModel;
use Exception;

class ParkingGarageController extends BaseController 
{    
    public function entrance(array $urlVariables) 
    {
        if (!$this->ensureRequiredParam('garageId', $urlVariables)) {
            return;
        }

        $garageId = $urlVariables["garageId"];

        $garageService = new ParkingGarageService();
        $garage = $garageService->getById($garageId);

        $this->render('entrance', [
            "garage" => $garage,
        ]);
    }

    public function printTicket(array $urlVariables) 
    {
        if (!$this->ensureRequiredParam('garageId', $urlVariables)) {
            return;
        }
        
        $garageId = $urlVariables['garageId'];

        $ticketService = new TicketService();
        $parkingSpaceService = new ParkingSpaceService();

        $parkingSpace = $parkingSpaceService->chooseParkingSpace($garageId);
        
        if (is_null($parkingSpace)) {
            echo "Kein Parkplatz verfügbar.";
            return false;
        }

        $ticket = $ticketService->createTicket($parkingSpace);

        if (is_null($ticket)) {
            header("HTTP/1.0 500 Internal Server Error");
            echo "500 - Internal Server Error ";
            return;
        }

        setcookie('ticket_identifier', $ticket->identifier);

        $this->render('ticket-print', [
            "ticket" => $ticket,
        ]);
    }
    
    public function exit($vars) 
    {
        echo "Exiting Garage: " . htmlspecialchars($vars['garageId']);
    }
}