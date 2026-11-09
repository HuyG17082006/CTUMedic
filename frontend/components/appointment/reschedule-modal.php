<div class="reschedule-modal" id="reschedule-modal" hidden>

    <div class="reschedule-modal__backdrop" data-reschedule-close></div>

    <div class="reschedule-modal__dialog">

        <!-- Header -->
        <div class="reschedule-modal__header">

            <div class="reschedule-modal__heading">
                <span class="reschedule-modal__icon">
                    <i class="fa-regular fa-clock"></i>
                </span>

                <div>
                    <h3>Thay đổi giờ khám</h3>
                    <p>Chọn khung giờ mới phù hợp với bạn.</p>
                </div>
            </div>

            <button
                type="button"
                class="reschedule-modal__close"
                data-reschedule-close>
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

        <!-- Thông tin lịch hiện tại -->
        <div class="reschedule-current">

            <div class="reschedule-current__heading">
                <i class="fa-solid fa-calendar-check"></i>
                Lịch khám hiện tại
            </div>

            <div class="reschedule-current__info">
                <div>
                    <span>Ngày khám</span>
                    <strong id="reschedule-current-date">---</strong>
                </div>

                <div>
                    <span>Giờ khám</span>
                    <strong id="reschedule-current-time">---</strong>
                </div>
            </div>

        </div>

        <!-- Chọn giờ mới -->
        <div class="reschedule-slots">

            <div class="reschedule-slots__heading">
                <h4>Chọn khung giờ mới</h4>
                <p>Các khung giờ không khả dụng sẽ không thể lựa chọn.</p>
            </div>

            <?php
            require __DIR__ . '/../../components/booking-periods.php'
            ?>

        </div>

        <!-- Footer -->
        <div class="reschedule-modal__footer">

            <div class="reschedule-selected">
                <span>Giờ khám mới</span>
                <strong id="reschedule-selected-time">Chưa chọn</strong>
            </div>

            <div class="reschedule-modal__actions">

                <button
                    type="button"
                    class="reschedule-button reschedule-button--cancel"
                    data-reschedule-close>
                    Quay lại
                </button>

                <button
                    type="button"
                    class="reschedule-button reschedule-button--confirm"
                    id="confirm-reschedule"
                    disabled>
                    <i class="fa-solid fa-check"></i>
                    Xác nhận đổi giờ
                </button>

            </div>

        </div>

    </div>

</div>