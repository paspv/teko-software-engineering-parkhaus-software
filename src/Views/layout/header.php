
<?php
/** @var App\Models\ParkingGarageModel $garage */
/** @var ?TicketModel $ticket */
?>

<header>

    <a href="/">Parkhausauswahl</a>

    <span class="center"><?= $garage->name ?></span>

    <nav class="right">
        <ul>
            <span>Eingang</span>
            <span>Ausgang</span>
        </ul>
    </nav>

</header>

<div class="status-bar">
    <div class="barrier-wrapper">
        <div class="barrier-state <?= ($barrierOpen ?? false) ? 'barrier-open':'' ?>"></div>
        <span>Schranke</span>
    </div>

    <?php if ($_COOKIE["ticket_identifier"] ?? false): ?>
        <div>Ticket: <?= $_COOKIE["ticket_identifier"] ?></div>
    <?php endif ?>

</div>