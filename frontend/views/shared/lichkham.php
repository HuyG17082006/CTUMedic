
<?php
$pageTitle = 'Lịch khám';
$pageDescription = 'Theo dõi và quản lý các lịch khám.';

$filterId = 'appointment-filter';
$showStatus = true;

require PATH_COMPONENTS . '/appointment-filter.php';

$tableId = 'appointment-table';
$tableType = 'appointments';

$columns = [
    'code'      => 'Mã lịch',
    'patient'   => 'Bệnh nhân',
    'doctor'    => 'Bác sĩ',
    'specialty' => 'Chuyên khoa',
    'date'      => 'Ngày khám',
    'time'      => 'Giờ khám',
    'reason'    => 'Lý do khám',
    'status'    => 'Trạng thái',
    'actions'   => 'Thao tác',
];
?>

<div class="appointment-page" data-page="appointments">
    <section class="appointment-page__section">

        <?php require PATH_COMPONENTS . '/appointment-table.php'; ?>

        <div class="appointment-pagination" data-pagination>
            <!-- TODO: JavaScript render phân trang -->
        </div>
    </section>

    <?php
    $modalId = 'appointment-detail-modal';
    require PATH_COMPONENTS . '/appointment-detail-modal.php';
    ?>
</div>
