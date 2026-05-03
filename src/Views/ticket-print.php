<?php include __DIR__ . '/layout/html_head.php'; ?>

<?php
/** @var App\Models\TicketModel $ticket */
?>

<div class="content-center-outer">
    <div class="content-center garage-list">
        <?php if (!is_null($ticket)) : ?>
            <span class="title">Ihr Ticket:</span>
            <ul class="parking-garage-seleciton-list">
               <?php var_dump($ticket); ?>
                <?php foreach (get_object_vars($ticket) as $ticket): ?>
                    <li>
                        <a href="/<?= "" ?>/entrance"><?= $ticket ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Ein Fehler ist aufgetreten</p>
        <?php endif ?>
    </div>
</div>



<?php include __DIR__ . '/layout/html_end.php'; ?>