<?php
namespace App\Controllers;

use App\Services\ParkingGarageService;
use Exception;

class ParkingGarageController extends BaseController 
{    
    public function entrance($vars) 
    {
        if (!$this->ensureRequiredParam('garageId', $vars)) {
            return;
        }

        $garageId = $vars["garageId"];

        $garageService = new ParkingGarageService();
        $garage = $garageService->getById($garageId);

        // var_dump($_COOKIE);
        // $ticketService = new TicketService();
        // $ticket = $ticketService->getById($garageId);
        
        $this->render('entrance', [
            "garage" => $garage,
            // "ticket" => $ticket
        ]);
    }

    public function printTicket() 
    {
        //create ticket

        // print 
    }
    
    public function exit($vars) 
    {
        echo "Exiting Garage: " . htmlspecialchars($vars['garageId']);
    }
}