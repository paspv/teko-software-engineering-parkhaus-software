<?php include __DIR__ . '/layout/html_head.php'; ?>
<?php  $barrierOpen = true ?>

<?php include __DIR__ . '/layout/header.php'; ?>

<?php
/** @var App\Models\TicketModel $ticket */
/** @var App\Models\FloorModel $floor */
/** @var App\Models\ParkingGarageModel $garage */
/** @var App\Models\ParkingSpaceModel $parkingSpace */
?>

<div class="content-center-outer">
    <div class="content-center ticket-data">
        <?php if (!is_null($ticket)) : ?>
            <span class="title">Ihr Ticket:</span>
            <grid class="ticket-data-detail">
                <p>Code:</p>
                <p><?= $ticket->identifier ?></p>
                <p>Ankunft:</p>
                <p><?= $ticket->arrival->format('d M Y, H:i') ?></p>
                <p>Stockwerk:</p>

                <p><?= $floor->name ?></p>                
                <p>Parkplatz:</p>
                <p><?= $parkingSpace->name ?></p>

            </grid>
            <hr>
            <a href="/<?= $garage->id ?>/exit" class="btn">Einfahren</a>

        <?php else: ?>
            <p>Ein Fehler ist aufgetreten</p>
        <?php endif ?>
    </div>
</div>



<?php include __DIR__ . '/layout/html_end.php'; ?>