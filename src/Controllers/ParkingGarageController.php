<?php

namespace App\Controllers;

use App\Enums\ParkingSpaceStateEnum;
use App\Models\ParkingGarageModel;
use App\Models\ParkingSpaceStateModel;
use App\Services\ParkingGarageService;
use App\Services\PricingService;
use App\Services\TicketService;
use App\Services\FloorService;
use App\Services\ParkingSpaceService;
use App\Services\ParkingSpaceStateService;
use App\Services\PaymentService;
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
        $pricingService = new PricingService();

        /** @var ParkingGarageModel $garage */
        $garage = $parkingGarageService->getById($urlVariables['garageId']);
        $ticket = $ticketService->getByIdentifier($ticketIdentifier);
        $ticket->departure = new DateTime();

        $price = $pricingService->calculatePrice($garage->id, $ticket->arrival, $ticket->departure);

        $this->render('ticket-scanned', [
            "garage" => $garage,
            "ticket" => $ticket,
            "price" => $price
        ]);
    }

    public function payTicket($urlVariables)
    {
        if (!$this->ensureRequiredIntParam('garageId', $urlVariables)) {
            return;
        }

        $ticketIdentifier = $this->ensureRequiredFormParam($urlVariables, 'ticketIdentifier');
        $price = (float)$this->ensureRequiredFormParam($urlVariables, 'price');
        $departure = DateTime::createFromFormat(
            'Y-m-d H:i:s', 
            $this->ensureRequiredFormParam($urlVariables, 'departure')
        );

        $ticketService = new TicketService();
        $parkingGarageService = new ParkingGarageService();
        $parkingSpaceService = new ParkingSpaceService();
        $parkingSpaceStateService = new ParkingSpaceStateService();
        $paymentService = new PaymentService();

        if (!$paymentService->pay($price)) {
            echo "Zahlung fehlgeschlagen";
            die();
        }

        /** @var ParkingGarageModel $garage */
        $garage = $parkingGarageService->getById($urlVariables['garageId']);
        $ticket = $ticketService->getByIdentifier($ticketIdentifier);
        $ticket->departure = $departure;

        if (!$ticketService->setDepartureTime($ticket->id, $ticket->departure)) {
            header("HTTP/1.0 500 Internal Server Error");
            echo "500 - Internal Server Error";
            die();
        }

        $openState = $parkingSpaceStateService->getStateByName(
            ParkingSpaceStateEnum::AVAILABLE->getDbName()
        );

        if (!$parkingSpaceService->updateState(
            $ticket->parkingSpaceId,
            $openState->id
        )) {
            header("HTTP/1.0 500 Internal Server Error");
            echo "500 - Internal Server Error";
            die();
        }

        // delete cookie by setting the expiration date to the past.
        setcookie('ticket_identifier', $ticket->identifier, time() - 3600);

        $this->render('ticket-payed', [
            "garage" => $garage,
        ]);
    }
}
