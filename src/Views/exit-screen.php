<?php include __DIR__ . '/layout/html_head.php'; ?>

<?php include __DIR__ . '/layout/header.php'; ?>


<?php
/** @var TicketModel $ticket */
/** @var ParkingGarageModel $garage */
?>

<div class="content-center-outer">
    <div class="content-center ticket-data">
            <span class="title">Zur Ausfahrt Ticket scannen</span>
        <form method="post" action="/<?= $garage->id ?>/scanTicket">
            <input type="hidden" name="ticketIdentifier" value="<?= $_COOKIE['ticket_identifier'] ?>">
            <button class="btn">Ticket Scannen</button>
        </form>
    </div>
</div>


<?php include __DIR__ . '/layout/html_end.php'; ?>



