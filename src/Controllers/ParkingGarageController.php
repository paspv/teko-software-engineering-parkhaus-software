<?php

namespace App\Controllers;

use App\Enums\ParkingSpaceStateEnum;
use App\Models\ParkingGarageModel;
use App\Models\ParkingSpaceStateModel;
use App\Services\ParkingGarageService;
use App\Services\TicketService;
use App\Services\FloorService;
use App\Services\ParkingSpaceService;
use App\Services\ParkingSpaceStateService;
use DateTime;

class ParkingGarageController extends BaseController
{
    public function entrance(array $urlVariables)
    {
        if (!$this->ensureRequiredIntParam('garageId', $urlVariables)) {
            return;
        }

        $garageId = $urlVariables["garageId"];

        $garageService = new ParkingGarageService();
        $garage = $garageService->getById($garageId);

        $this->render('entrance-screen', [
            "garage" => $garage,
        ]);
    }

    public function printTicket(array $urlVariables)
    {
        if (!$this->ensureRequiredIntParam('garageId', $urlVariables)) {
            return;
        }

        $ticketService = new TicketService();
        $parkingSpaceService = new ParkingSpaceService();
        $parkingGarageService = new ParkingGarageService();

        /** @var ParkingGarageModel $garage */
        $garage = $parkingGarageService->getById($urlVariables['garageId']);

        // If user already has a ticket, load it. Otherwise create a new one.
        if ($_COOKIE["ticket_identifier"] ?? false) {
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

    public function exit($urlVariables)
    {
        if (!$this->ensureRequiredIntParam('garageId', $urlVariables)) {
            return;
        }

        $ticketService = new TicketService();
        $parkingGarageService = new ParkingGarageService();

        /** @var ParkingGarageModel $garage */
        $garage = $parkingGarageService->getById($urlVariables['garageId']);

        $this->render('exit-screen', [
            "garage" => $garage,
        ]);
    }

    public function scanTicket($urlVariables)
    {
        if (!$this->ensureRequiredIntParam('garageId', $urlVariables)) {
            return;
        }

        if (!$ticketIdentifier = $_POST['ticketIdentifier'] ?? null) {
            header('Location: /'. $urlVariables['garageId'] . '/entrance');
            die();
        }

        $ticketService = new TicketService();
        $parkingGarageService = new ParkingGarageService();
        $parkingSpaceService = new ParkingSpaceService();
        $parkingSpaceStateService = new ParkingSpaceStateService();

        /** @var ParkingGarageModel $garage */
        $garage = $parkingGarageService->getById($urlVariables['garageId']);
        $ticket = $ticketService->getByIdentifier($ticketIdentifier);
        $ticket->departure = new DateTime();


        /** TODO Payment step */

        $this->render('ticket-scanned', [
            "garage" => $garage,
            "ticket" => $ticket
        ]);
    }

    public function payTicket($urlVariables)
    {
        if (!$this->ensureRequiredIntParam('garageId', $urlVariables)) {
            return;
        }

        if (!$ticketIdentifier = $_POST['ticketIdentifier'] ?? null) {
            header('Location: /'. $urlVariables['garageId'] . '/entrance');
            die();
        }

        $ticketService = new TicketService();
        $parkingGarageService = new ParkingGarageService();
        $parkingSpaceService = new ParkingSpaceService();
        $parkingSpaceStateService = new ParkingSpaceStateService();

        /** @var ParkingGarageModel $garage */
        $garage = $parkingGarageService->getById($urlVariables['garageId']);
        $ticket = $ticketService->getByIdentifier($ticketIdentifier);
        $ticket->departure = new DateTime();

        if (!$ticketService->setDepartureTime($ticket->id, $ticket->departure)) {
            return;
        }

        $openState = $parkingSpaceStateService->getStateByName(
            ParkingSpaceStateEnum::AVAILABLE->getDbName()
        );

        if (!$parkingSpaceService->updateState(
            $ticket->parkingSpaceId,
            $openState->id
        )) {
            return;
        }

        // delete cookie by setting the expiration date to the past.
        setcookie('ticket_identifier', $ticket->identifier, time() - 3600);

        $this->render('ticket-scanned', [
            "garage" => $garage,
            "ticket" => $ticket
        ]);
    }
}
