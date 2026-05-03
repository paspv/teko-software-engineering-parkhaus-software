<?php

namespace App\Services;

use PDO;
use App\Core\Database;
use App\Core\Security;
use App\Models\ParkingSpaceModel;
use App\Models\TicketModel;
use App\Services\FloorService;
use DateTime;
use DateTimeImmutable;
use stdClass;

class TicketService extends BaseService
{
    public function __construct()
    {
        parent::__construct(TicketModel::class, "Ticket");
    }

    public function createTicket(ParkingSpaceModel $parkingSpace): ?TicketModel
    {
        $parkingSpaceService = new ParkingGarageService();
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

        $parkingSpaceService->updateState($ticket->parkingSpaceId, );

        return $ticket;
    }
}
