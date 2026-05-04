<?php
namespace App\Controllers;

use App\Models\ParkingGarageModel;
use App\Services\ParkingGarageService;
use App\Services\TicketService;
use App\Services\FloorService;
use App\Services\ParkingSpaceService;

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

        $ticketService = new TicketService();
        $parkingSpaceService = new ParkingSpaceService();
        $parkingGarageService = new ParkingGarageService();

        /** @var ParkingGarageModel $garage */
        $garage = $parkingGarageService->getById($urlVariables['garageId']);

        // If user already has a ticket, load it. Otherwise create a new one.
        if ($_COOKIE["ticket_identifier"]) {
            $ticket = $ticketService->getByIdentifier($_COOKIE["ticket_identifier"]);
            $parkingSpace = $parkingSpaceService->getById($ticket->parkingSpaceId);
        } else {
            $parkingSpace = $parkingSpaceService->chooseParkingSpace($garage->id);
            $ticket = $ticketService->createTicket($parkingSpace);

            setcookie('ticket_identifier', $ticket->identifier);
            
            if (is_null($parkingSpace)) {
                echo "Kein Parkplatz verfügbar.";
                return false;
            }
        }

        if (is_null($ticket)) {
            header("HTTP/1.0 500 Internal Server Error");
            echo "500 - Internal Server Error ";
            return;
        }

        $floorService = new FloorService();
        $floor = $floorService->getById($parkingSpace->floorId);
   
        $this->render('ticket-print', [
            "ticket" => $ticket,
            "garage" => $garage,
            "floor" => $floor,
            "parkingSpace" => $parkingSpace
        ]);
    }
    
    public function exit($vars) 
    {
        echo "Exiting Garage: " . htmlspecialchars($vars['garageId']);
    }
}