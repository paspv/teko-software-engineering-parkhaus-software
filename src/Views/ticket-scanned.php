<?php include __DIR__ . '/layout/html_head.php'; ?>

<?php include __DIR__ . '/layout/header.php'; ?>


<?php
/** @var TicketModel $ticket */
/** @var ParkingGarageModel $garage */
/** @var float $price */
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
                <p>Preis:</p>
                <p><?= sprintf("%.2f", $price) ?></p>
            </grid>
            <hr>

            <form method="post" action="/<?= $garage->id ?>/payTicket">
                <input type="hidden" name="price" value="<?= sprintf("%.2f", $price) ?>">
                <input type="hidden" name="ticketIdentifier" value="<?= $_COOKIE['ticket_identifier'] ?>">
                <input type="hidden" name="departure" value="<?= $ticket->departure->format('Y-m-d H:i:s') ?>">
                <button class="btn">Bezahlen</button>
            </form>

        <?php else: ?>
            <p>Ein Fehler ist aufgetreten</p>
        <?php endif ?>
    </div>
</div>


<?php include __DIR__ . '/layout/html_end.php'; ?>



