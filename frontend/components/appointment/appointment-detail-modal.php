
<?php

$modalId = $modalId ?? 'appointment-detail-modal';

?>

<div class="appointment-modal"
     id="<?= htmlspecialchars($modalId) ?>"
     aria-hidden="true"
     hidden>

    <div class="appointment-modal__backdrop" data-modal-close></div>

    <section class="appointment-modal__dialog"
             role="dialog"
             aria-modal="true"
             aria-labelledby="<?= htmlspecialchars($modalId) ?>-title">

        <!-- HEADER -->
        <header class="appointment-modal__header">

            <div>
                <span class="appointment-code" data-detail-appointment-id>—</span>

                <h2 id="<?= htmlspecialchars($modalId) ?>-title">
                    Chi tiết lịch khám
                </h2>
            </div>

            <button type="button"
                    class="appointment-modal__close"
                    data-modal-close
                    aria-label="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </header>

        <!-- BODY -->
        <div class="appointment-modal__body">

            <!-- THÔNG TIN LỊCH KHÁM -->
            <section class="appointment-summary">

                <div class="appointment-summary__header">
                    <h3>Thông tin lịch khám</h3>
                    <span class="appointment-status" data-detail-status>—</span>
                </div>

                <div class="appointment-summary__grid">

                    <div>
                        <span>Ngày khám</span>
                        <strong data-detail-date>—</strong>
                    </div>

                    <div>
                        <span>Giờ khám</span>
                        <strong data-detail-time>—</strong>
                    </div>

                    <div>
                        <span>Bác sĩ</span>
                        <strong data-detail-doctor-name>—</strong>
                    </div>

                    <div>
                        <span>Chuyên khoa</span>
                        <strong data-detail-doctor-specialty>—</strong>
                    </div>

                </div>

                <div class="appointment-reason">
                    <h4>Lý do khám</h4>
                    <p data-detail-reason>—</p>
                </div>

            </section>

            <!-- THÔNG TIN BỆNH NHÂN -->
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

            <!-- THÔNG TIN HỦY -->
            <section class="appointment-detail-card"
                     data-detail-cancellation
                     hidden>

                <h3>Thông tin hủy lịch</h3>

                <dl>
                    <dt>Người hủy</dt>
                    <dd data-detail-cancelled-by>—</dd>

                    <dt>Thời gian hủy</dt>
                    <dd data-detail-cancelled-at>—</dd>

                    <dt>Lý do hủy</dt>
                    <dd data-detail-cancellation-reason>—</dd>
                </dl>

            </section>

        </div>

        <!-- FOOTER -->
        <footer class="appointment-modal__actions">

            <button type="button"
                    class="btn-secondary"
                    data-action-reschedule
                    hidden>
                <i class="fa-regular fa-clock"></i>
                Đổi khung giờ
            </button>

            <button type="button"
                    class="btn-danger"
                    data-action-cancel
                    hidden>
                <i class="fa-solid fa-ban"></i>
                Hủy lịch khám
            </button>

            <button type="button"
                    class="btn-third"
                    data-action-complete
                    hidden>
                <i class="fa-solid fa-check"></i>
                Hoàn thành
            </button>

        </footer>

    </section>

</div>
