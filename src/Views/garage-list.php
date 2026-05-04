<?php include __DIR__ . '/layout/html_head.php'; ?>

<?php
/** @var string $name */
/** @var array $garages */
?>

<div class="content-center-outer">
    <div class="content-center garage-list">
        <span class="title">Alle Parkhäuser:</span>
        <ul class="parking-garage-seleciton-list">
            <?php if (empty($garages)): ?>
                <li>Es wurden keine Parkhäuser gefunden.</li>
            <?php else: ?>

                <?php foreach ($garages as $garage): ?>
                    <li>
                        <a class="btn" href="/<?= $garage->id ?>/entrance"><?= $garage->name ?></a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>


<?php include __DIR__ . '/layout/html_end.php'; ?>



