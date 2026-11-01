<section class="patient-booking-page">

    <section class="booking-info-card">

        <div class="booking-info-card__heading">
            <i class="fa-regular fa-calendar-check"></i>

            <div>
                <h3>Thông tin khám</h3>
                <p>Chọn bác sĩ và ngày khám phù hợp với bạn.</p>
            </div>
        </div>

        <form class="booking-filter-form">

            <div class="booking-form-field">
                <label for="booking-specialty">
                    Chuyên khoa
                </label>

                <select
                    id="booking-specialty"
                    name="specialty"
                    required>
                    <option value="">
                        Chọn chuyên khoa
                    </option>
                </select>

            </div>

            <div class="booking-form-field">
                <label for="booking-doctor">
                    Bác sĩ
                </label>

                <select
                    id="booking-doctor"
                    name="doctorId"
                    disabled
                    required>
                    <option value="">
                        Chọn chuyên khoa trước
                    </option>
                </select>

            </div>

            <div class="booking-form-field">
                <label for="booking-date">
                    Ngày khám
                </label>

                <input
                    value="<?= date('Y-m-d') ?>"
                    min="<?= date('Y-m-d') ?>"
                    type="date"
                    id="booking-date"
                    name="date"
                    required>

            </div>

            <button
                type="submit"
                class="booking-filter-form__submit">
                <i class="fa-solid fa-magnifying-glass"></i>
                Tìm khung giờ
            </button>

        </form>

    </section>

    <section class="booking-legend">

        <span class="booking-legend__item">
            <span class="booking-legend__dot booking-legend__dot--available"></span>
            Còn trống
        </span>

        <span class="booking-legend__item">
            <span class="booking-legend__dot booking-legend__dot--booked"></span>
            Đã có người đặt
        </span>

        <span class="booking-legend__item">
            <span class="booking-legend__dot booking-legend__dot--blocked"></span>
            Bác sĩ đã khóa
        </span>

        <span class="booking-legend__item">
            <span class="booking-legend__dot booking-legend__dot--completed"></span>
            Đã qua
        </span>

    </section>

    <div class="booking-slot-form">

        <div class="booking-slot-form__heading">
            <div>
                <h3>Chọn khung giờ khám</h3>
                <p id="booking-selected-date"></p>
            </div>
        </div>

        <div
            class="booking-periods"
            id="booking-periods">

            <div class="booking-period morning">

                <div class="booking-period__header">
                    <div class="booking-period__heading">
                        <span class="booking-period__icon booking-period__icon--morning">
                            <i class="fa-solid fa-sun"></i>
                        </span>

                        <div>
                            <h4>Buổi sáng</h4>
                            <p>07:00 – 11:00</p>
                        </div>
                    </div>
                </div>

                <div
                    class="booking-slots"
                    id="morning-slots"></div>

            </div>

            <div class="booking-period afternoon">

                <div class="booking-period__header">
                    <div class="booking-period__heading">
                        <span class="booking-period__icon booking-period__icon--afternoon">
                            <i class="fa-solid fa-cloud-sun"></i>
                        </span>

                        <div>
                            <h4>Buổi chiều</h4>
                            <p>13:00 – 17:00</p>
                        </div>
                    </div>
                </div>

                <div
                    class="booking-slots"
                    id="afternoon-slots"></div>

            </div>

        </div>

        <section class="booking-reason-card">

            <div class="booking-form-field">

                <label for="booking-reason">
                    Lý do khám
                </label>

                <textarea
                    id="booking-reason"
                    name="liDoKham"
                    rows="4"
                    maxlength="500"
                    placeholder="Mô tả triệu chứng hoặc lý do bạn muốn khám..."
                    required></textarea>

            </div>

        </section>

        <div class="booking-submit">

            <div class="booking-submit__note">
                <i class="fa-solid fa-circle-info"></i>

                <p>
                    Vui lòng kiểm tra thông tin trước khi xác nhận.
                </p>
            </div>

            <button
                type="button"
                class="booking-submit__button">
                <i class="fa-solid fa-calendar-check"></i>
                Xác nhận đặt lịch
            </button>

        </div>

    </div>

</section>

<script
    type="module"
    src="/dashboard/CTUMedic/frontend/assets/js/BenhNhan/datlich.js">
</script>