<?php

namespace App\Services;

use App\Core\Security;
use App\Enums\ParkingSpaceStateEnum;
use App\Models\ParkingSpaceModel;
use App\Models\TicketModel;
use DateTime;
use Exception;

class TicketService extends BaseService
{
    public function __construct()
    {
        parent::__construct(TicketModel::class, "Ticket");
    }

    public function createTicket(ParkingSpaceModel $parkingSpace): ?TicketModel
    {
        $parkingSpaceService = new ParkingSpaceService();
        $security = new Security();

        $data = [
            'Floor_Id'        => $parkingSpace->floorId,
            'ParkingSpace_Id' => $parkingSpace->id,
            'Arrival'        => (new DateTime())->format('Y-m-d H:i:s'),
            'Departure'      => null,
            'Identifier'     => $security->getGuid()
        ];

        $columnNames = array_keys($data);
        $columns = implode(', ', $columnNames);        
        $placeholders = implode(', ', array_map(fn($col) => ":$col", $columnNames));
        $sql = "INSERT INTO {$this->tableName} ($columns) VALUES ($placeholders)";

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute($data);

        if (!$success) {
            return null;
        }

        $id = $this->db->lastInsertId();

        /** @var TicketModel $ticket */
        $ticket = $this->getById($id);

        $parkingSpaceStateService = new ParkingSpaceStateService();
        $newState = $parkingSpaceStateService->getStateByName(ParkingSpaceStateEnum::OCCUPIED->getDbName());

        if ($parkingSpaceService->updateState($ticket->parkingSpaceId, $newState->id)) {
            return $ticket;
        }

    }

    public function getByIdentifier(string $identifier): ?TicketModel
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE identifier = :identifier");
        $stmt->execute([
            'identifier' => $identifier
        ]);

        return $this->fetchOne($stmt);
    }

    public function setDepartureTime(int $ticketId, DateTime $departureDateTime = new DateTime()): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->tableName} SET Departure = :departure WHERE Id = :id");
        return $stmt->execute([
            'id' => $ticketId,
            'departure' => $departureDateTime->format('Y-m-d H:i:s')
        ]);
    }
}
