
<?php
$pageTitle = 'Lịch sử khám';
$pageDescription = 'Tra cứu các lịch khám đã hoàn thành.';

$filterId = 'history-filter';

require PATH_COMPONENTS . '/appointment-filter.php';

$tableId = 'history-table';
$tableType = 'history';

?>

<div class="appointment-page" data-page="history">
    <section class="appointment-page__section">

        <?php require PATH_COMPONENTS . '/appointment-table.php'; ?>

        <div class="appointment-pagination" data-pagination>
            <!-- TODO: JavaScript render phân trang -->
        </div>
    </section>

    <?php
    $modalId = 'history-detail-modal';
    require PATH_COMPONENTS . '/appointment-detail-modal.php';
    ?>
</div>
