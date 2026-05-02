<?php include __DIR__ . '/layout/html_head.php'; ?>

<?php include __DIR__ . '/layout/header.php'; ?>


<?php
/** @var TicketModel $ticket */
/** @var ParkingGarageModel $garage */
?>

<div class="content-center-outer">
    <div class="content-center entrance">
        <form method="post" action="/<?= $garage->id ?>/printTicket">
            <input name="action" value="getTicket" type="hidden" />
            <button class="big-red">Ticket</button>
        </form>
    </div>
</div>


<?php include __DIR__ . '/layout/html_end.php'; ?>



