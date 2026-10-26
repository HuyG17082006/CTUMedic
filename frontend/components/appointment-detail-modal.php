<?php
$modalId = $modalId ?? 'appointment-detail-modal';

// Role của người đang đăng nhập
$currentRole = strtoupper($_SESSION['role'] ?? '');

// Trạng thái lịch khám đang xem
$appointmentStatus = strtoupper($appointmentStatus ?? '');

// Xác định các quyền thao tác
$canEdit = true;
$canCancel = true;
$canConfirm = true;
$canReject = true;

if (in_array($appointmentStatus, ['PENDING', 'CONFIRMED'], true)) {
    switch ($currentRole) {
        case 'patient':
            $canEdit = true;
            $canCancel = true;
            break;

        case 'doctor':
            if ($appointmentStatus === 'PENDING') {
                $canConfirm = true;
                $canReject = true;
            } else {
                $canCancel = true;
            }
            break;

        case 'manager':
            $canEdit = true;
            $canCancel = true;

            if ($appointmentStatus === 'PENDING') {
                $canConfirm = true;
                $canReject = true;
            }
            break;
    }
}
?>

<div
    class="appointment-modal"
    id="<?= htmlspecialchars($modalId) ?>"
    aria-hidden="true"
    hidden
    >
    <div class="appointment-modal__backdrop" data-modal-close></div>

    <section
        class="appointment-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="<?= htmlspecialchars($modalId) ?>-title">
        <header class="appointment-modal__header">
            <div>
                <h2 id="<?= htmlspecialchars($modalId) ?>-title">
                    Chi tiết lịch khám
                </h2>
                <p data-detail-appointment-code>Chưa chọn lịch khám</p>
            </div>

            <button
                type="button"
                class="appointment-modal__close"
                data-modal-close
                aria-label="Đóng hộp thoại">
                &times;
            </button>
        </header>

        <div class="appointment-modal__body">

            <!-- Bác sĩ: bên trái -->
            <section class="appointment-detail-card">
                <h3>Thông tin bác sĩ</h3>

                <dl>
                    <dt>Mã bác sĩ</dt>
                    <dd data-detail-doctor-code>—</dd>

                    <dt>Họ và tên</dt>
                    <dd data-detail-doctor-name>—</dd>

                    <dt>Chuyên khoa</dt>
                    <dd data-detail-doctor-specialty>—</dd>

                    <dt>Email</dt>
                    <dd data-detail-doctor-email>—</dd>
                </dl>
            </section>

            <!-- Lịch khám: ở giữa -->
            <section class="appointment-detail-card appointment-detail-card--main">
                <h3>Thông tin lịch khám</h3>

                <dl>
                    <dt>Mã lịch khám</dt>
                    <dd data-detail-appointment-id>—</dd>

                    <dt>Ngày khám</dt>
                    <dd data-detail-date>—</dd>

                    <dt>Giờ khám</dt>
                    <dd data-detail-time>—</dd>

                    <dt>Trạng thái</dt>
                    <dd data-detail-status>—</dd>

                    <dt>Lý do khám</dt>
                    <dd data-detail-reason>—</dd>

                    <dt>Thời điểm đặt lịch</dt>
                    <dd data-detail-created-at>—</dd>
                </dl>


                <div
                    class="appointment-modal__actions"
                    data-detail-actions>
                    <?php if ($canEdit): ?>
                        <button
                            type="button"
                            class="btn-secondary"
                            data-action="edit">
                            Sửa lịch
                        </button>
                    <?php endif; ?>

                    <?php if ($canConfirm): ?>
                        <button
                            type="button"
                            class="btn-primary"
                            data-action="confirm">
                            Xác nhận
                        </button>
                    <?php endif; ?>

                    <?php if ($canReject): ?>
                        <button
                            type="button"
                            class="btn-danger"
                            data-action="reject">
                            Từ chối
                        </button>
                    <?php endif; ?>

                    <?php if ($canCancel): ?>
                        <button
                            type="button"
                            class="btn-danger"
                            data-action="cancel">
                            Hủy lịch
                        </button>
                    <?php endif; ?>
                </div>

            </section>

            <!-- Bệnh nhân: bên phải -->
            <section class="appointment-detail-card">
                <h3>Thông tin bệnh nhân</h3>

                <dl>
                    <dt>Mã bệnh nhân</dt>
                    <dd data-detail-patient-code>—</dd>

                    <dt>Họ và tên</dt>
                    <dd data-detail-patient-name>—</dd>

                    <dt>Ngày sinh</dt>
                    <dd data-detail-patient-birth>—</dd>

                    <dt>Số điện thoại</dt>
                    <dd data-detail-patient-phone>—</dd>

                    <dt>Email</dt>
                    <dd data-detail-patient-email>—</dd>

                    <dt>CCCD</dt>
                    <dd data-detail-patient-cccd>—</dd>
                </dl>
            </section>

        </div>
    </section>
</div>