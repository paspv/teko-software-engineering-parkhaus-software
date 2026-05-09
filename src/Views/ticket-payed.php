<?php include __DIR__ . '/layout/html_head.php'; ?>
<?php  $barrierOpen = true ?>

<?php include __DIR__ . '/layout/header.php'; ?>


<?php
/** @var ParkingGarageModel $garage */
?>

<div class="content-center-outer">
    <div class="content-center ticket-data">
        <span class="title">Zahlung verarbeitet</span>

        <a href="/<?= $garage->id ?>/entrance" class="btn">Ausfahrt</a>
    </div>
</div>

<?php include __DIR__ . '/layout/html_end.php'; ?>



