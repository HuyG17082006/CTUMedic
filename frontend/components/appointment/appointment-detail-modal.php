<?php
$modalId = $modalId ?? 'appointment-detail-modal';

$currentRole = strtolower($_SESSION['role'] ?? '');
$currentStatus = strtoupper($appointmentStatus ?? '');

$canEdit = false;
$canCancel = false;
$canConfirm = false;
$canReject = false;

switch ($currentRole) {

    case 'patient':

        if (in_array($currentStatus, ['PENDING', 'CONFIRMED'], true)) {
            $canEdit = true;
            $canCancel = true;
        }

        break;

    case 'doctor':

        if ($currentStatus === 'PENDING') {
            $canConfirm = true;
            $canReject = true;
        }

        if ($currentStatus === 'CONFIRMED') {
            $canCancel = true;
        }

        break;

    case 'manager':

        if (in_array($currentStatus, ['PENDING', 'CONFIRMED'], true)) {
            $canEdit = true;
            $canCancel = true;
        }

        if ($currentStatus === 'PENDING') {
            $canConfirm = true;
            $canReject = true;
        }

        break;
}
?>

<div
    class="appointment-modal"
    id="<?= htmlspecialchars($modalId) ?>"
    aria-hidden="true"
    hidden
    >

    <div
        class="appointment-modal__backdrop"
        data-modal-close>
    </div>

    <section
        class="appointment-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="<?= htmlspecialchars($modalId) ?>-title">

        <header class="appointment-modal__header">

            <div>

                <span
                    class="appointment-code"
                    data-detail-appointment-id>
                    LK0001
                </span>

                <h2 id="<?= htmlspecialchars($modalId) ?>-title">
                    Chi tiết lịch khám
                </h2>

            </div>

            <button
                type="button"
                class="appointment-modal__close"
                data-modal-close>

                <i class="fa-solid fa-xmark"></i>

            </button>

        </header>

        <div class="appointment-modal__body">

            <section class="appointment-summary">

                <div class="appointment-summary__top">

                    <div>
                        <h3>
                            Trạng thái lịch khám
                        </h3>
                    </div>

                    <span
                        class="appointment-status"
                        data-detail-status>
                        Đã xác nhận
                    </span>

                </div>

                <div class="appointment-summary__grid">

                    <div class="appointment-summary__item">
                        <i class="fa-regular fa-calendar"></i>
                        <div>
                            <span>Ngày khám</span>
                            <strong data-detail-date>—</strong>
                        </div>
                    </div>

                    <div class="appointment-summary__item">
                        <i class="fa-regular fa-clock"></i>
                        <div>
                            <span>Giờ khám</span>
                            <strong data-detail-time>—</strong>
                        </div>
                    </div>

                    <div class="appointment-summary__item">
                        <i class="fa-solid fa-user-doctor"></i>
                        <div>
                            <span>Bác sĩ</span>
                            <strong data-detail-doctor-name>—</strong>
                        </div>
                    </div>

                    <div class="appointment-summary__item">
                        <i class="fa-solid fa-stethoscope"></i>
                        <div>
                            <span>Chuyên khoa</span>
                            <strong data-detail-doctor-specialty>—</strong>
                        </div>
                    </div>

                </div>

                <div class="appointment-reason">

                    <h4>Lý do khám</h4>

                    <p data-detail-reason>
                        —
                    </p>

                </div>

            </section>

            <div class="appointment-related">

                <section class="appointment-detail-card">

                    <h3>Thông tin bệnh nhân</h3>

                    <dl>

                        <dt>Họ và tên</dt>
                        <dd data-detail-patient-name>—</dd>

                        <dt>Số điện thoại</dt>
                        <dd data-detail-patient-phone>—</dd>

                        <dt>Ngày sinh</dt>
                        <dd data-detail-patient-birth>—</dd>

                    </dl>

                </section>

                <section class="appointment-detail-card">

                    <h3>Thông tin bác sĩ</h3>

                    <dl>

                        <dt>Họ và tên</dt>
                        <dd data-detail-doctor-name>—</dd>

                        <dt>Chuyên khoa</dt>
                        <dd data-detail-doctor-specialty>—</dd>

                    </dl>

                </section>

            </div>

        </div>

        <footer
            class="appointment-modal__actions"
            data-detail-actions>

            <button
                type="button"
                class="btn-secondary"
                data-action="edit">
                Sửa lịch
            </button>

            <button
                type="button"
                class="btn-primary"
                data-action="confirm">
                Xác nhận
            </button>

            <button
                type="button"
                class="btn-danger"
                data-action="reject">
                Từ chối
            </button>

            <button
                type="button"
                class="btn-danger"
                data-action="cancel">
                Hủy lịch
            </button>

        </footer>

    </section>

</div>