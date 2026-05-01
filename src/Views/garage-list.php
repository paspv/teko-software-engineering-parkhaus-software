<?php include __DIR__ . '/layout/header.php'; ?>

<?php
/** @var string $name */
/** @var array $garages */
?>

<h3>Alle Parkhäuser:</h3>
<ul>
    <?php if (empty($garages)): ?>
        <li>Es wurden keine Parkhäuser gefunden.</li>
    <?php else: ?>

        <?php foreach ($garages as $garage): ?>
            <li>
                <strong><?= $garage->Name ?></strong> 
                — <a href="/<?= $garage->Id ?>/entry">Go to Entry</a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

<?php include __DIR__ . '/layout/footer.php'; ?>



