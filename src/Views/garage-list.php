<?php include __DIR__ . '/layout/header.php'; ?>

<?php
/** @var string $name */
/** @var array $garages */
?>

<div class="parking-garage-selection">
    <h3>Alle Parkhäuser:</h3>
    <ul class="parking-garage-seleciton-list">
        <?php if (empty($garages)): ?>
            <li>Es wurden keine Parkhäuser gefunden.</li>
        <?php else: ?>

            <?php foreach ($garages as $garage): ?>
                <li>
                    <a href="/<?= $garage->Id ?>/entry"><?= $garage->Name ?></a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>


<?php include __DIR__ . '/layout/footer.php'; ?>



