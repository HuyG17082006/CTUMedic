<div class="complete-modal" id="complete-appointment-modal" hidden>
    <div class="complete-modal__backdrop" data-complete-close></div>

    <div class="complete-modal__dialog">
        <div class="complete-modal__header">
            <div class="complete-modal__icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>

            <button type="button" class="complete-modal__close" data-complete-close>
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="complete-modal__body">
            <h3>Hoàn thành lịch khám</h3>
            <p>Bạn có chắc chắn muốn đánh dấu lịch khám này là đã hoàn thành không?</p>

            <div class="complete-modal__appointment">
                <div>
                    <span>Mã lịch khám</span>
                    <strong id="complete-appointment-code">---</strong>
                </div>

                <div>
                    <span>Bệnh nhân</span>
                    <strong id="complete-appointment-patient">---</strong>
                </div>

                <div>
                    <span>Ngày khám</span>
                    <strong id="complete-appointment-date">---</strong>
                </div>

                <div>
                    <span>Giờ khám</span>
                    <strong id="complete-appointment-time">---</strong>
                </div>
            </div>
        </div>

        <div class="complete-modal__footer">
            <button type="button" class="complete-modal__button complete-modal__button--back" data-complete-close>
                Quay lại
            </button>

            <button type="button" class="complete-modal__button complete-modal__button--confirm" id="confirm-complete-appointment">
                <i class="fa-solid fa-check"></i>
                Hoàn thành
            </button>
        </div>
    </div>
</div>