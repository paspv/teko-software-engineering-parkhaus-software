
<?php
/** @var App\Models\ParkingGarageModel $garage */
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