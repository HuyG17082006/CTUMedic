<?php

require PATH_COMPONENTS . '/appointment/appointment-filter.php';

?>

<div
    class="appointment-page"
    data-page="<?= htmlspecialchars($pageType) ?>">

    <section class="appointment-page__section">

        <?php require __DIR__ . '/appointment-table.php'; ?>

        <?php require __DIR__ . '/../pagination.php'; ?>

    </section>

    <?php require __DIR__ . '/appointment-detail-modal.php'; ?>

</div>