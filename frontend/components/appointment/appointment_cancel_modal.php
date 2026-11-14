<div class="cancel-modal" id="cancel-appointment-modal" hidden>
    <div class="cancel-modal__backdrop" data-cancel-close></div>

    <div class="cancel-modal__dialog">
        <div class="cancel-modal__header">
            <div class="cancel-modal__icon">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>

            <button type="button" class="cancel-modal__close" data-cancel-close>
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="cancel-modal__body">
            <h3>Xác nhận hủy lịch khám</h3>
            <p>Bạn có chắc chắn muốn hủy lịch khám này không?</p>

            <div class="cancel-modal__appointment">
                <div>
                    <span>Mã lịch khám</span>
                    <strong id="cancel-appointment-code">---</strong>
                </div>

                <div>
                    <span>Bệnh nhân</span>
                    <strong id="cancel-appointment-patient">---</strong>
                </div>

                <div>
                    <span>Ngày khám</span>
                    <strong id="cancel-appointment-date">---</strong>
                </div>

                <div>
                    <span>Giờ khám</span>
                    <strong id="cancel-appointment-time">---</strong>
                </div>
            </div>

            <div class="cancel-modal__reason">
                <label for="cancel-appointment-reason">Lý do hủy lịch</label>
                <textarea
                    id="cancel-appointment-reason"
                    rows="3"
                    maxlength="500"
                    placeholder="Nhập lý do hủy lịch khám..."
                    required
                ></textarea>
            </div>
        </div>

        <div class="cancel-modal__footer">
            <button type="button" class="cancel-modal__button cancel-modal__button--back" data-cancel-close>
                Quay lại
            </button>

            <button type="button" class="cancel-modal__button cancel-modal__button--confirm" id="confirm-cancel-appointment">
                <i class="fa-solid fa-ban"></i>
                Xác nhận hủy
            </button>
        </div>
    </div>
</div>