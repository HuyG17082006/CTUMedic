
<?php
$pageTitle = 'Lịch sử khám';
$pageDescription = 'Tra cứu các lịch khám đã hoàn thành.';

$filterId = 'history-filter';
$showStatus = false;

require PATH_COMPONENTS . '/appointment-filter.php';

$tableId = 'history-table';
$tableType = 'history';

$columns = [
    'code'      => 'Mã lịch',
    'patient'   => 'Bệnh nhân',
    'doctor'    => 'Bác sĩ',
    'specialty' => 'Chuyên khoa',
    'date'      => 'Ngày khám',
    'time'      => 'Giờ khám',
    'reason'    => 'Lý do khám',
    'status'    => 'Trạng thái',
];
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
