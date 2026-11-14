<?php

require PATH_COMPONENTS . '/appointment/appointment_filter.php';

?>

<div
    class="appointment-page"
    data-page="<?= htmlspecialchars($pageType) ?>">

    <section class="appointment-page__section">

        <?php require __DIR__ . '/appointment_table.php'; ?>

        <?php require __DIR__ . '/../pagination.php'; ?>

    </section>

    <?php require __DIR__ . '/appointment_detail_modal.php'; ?>

    <?php require __DIR__ . '/appointment_cancel_modal.php'; ?>

    <?php require __DIR__ . '/appointment_complete_modal.php'; ?>

    <?php require __DIR__ . '/reschedule_modal.php'; ?>

</div>