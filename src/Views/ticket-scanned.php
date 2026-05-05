<?php include __DIR__ . '/layout/html_head.php'; ?>

<?php include __DIR__ . '/layout/header.php'; ?>


<?php
/** @var TicketModel $ticket */
/** @var ParkingGarageModel $garage */
?>

<div class="content-center-outer">
    <div class="content-center ticket-data">
        <?php if (!is_null($ticket)) : ?>
            <span class="title">Ihr Ticket:</span>
            <grid class="ticket-data-detail">
                <p>Code:</p>
                <p><?= $ticket->identifier ?></p>
                <p>Ankunft:</p>
                <p><?= $ticket->arrival->format('d M y H:i') ?></p>
                <p>Abfahrt:</p>
                <p><?= $ticket->departure->format('d M y H:i') ?></p>
            </grid>
            <hr>
            <a href="/<?= $garage->id ?>/exit" class="btn">Ausfahren</a>

        <?php else: ?>
            <p>Ein Fehler ist aufgetreten</p>
        <?php endif ?>
    </div>
</div>


<?php include __DIR__ . '/layout/html_end.php'; ?>



