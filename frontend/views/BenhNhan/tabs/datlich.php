<?php

$selectedDate = $_GET['date'] ?? date('Y-m-d');

$timeSlots = WORKING_TIME_SLOTS;

$selectedDoctor = $_GET['doctorId'] ?? '';

?>

<section class="patient-booking-page">

    <!-- Thông tin đặt lịch -->
    <section class="booking-info-card">

        <div class="booking-info-card__heading">
            <i class="fa-regular fa-calendar-check"></i>

            <div>
                <h3>Thông tin khám</h3>
                <p>
                    Chọn bác sĩ và ngày khám phù hợp với bạn.
                </p>
            </div>
        </div>

        <form method="GET" class="booking-filter-form">

            <input
                type="hidden"
                name="page"
                value="patient">

            <input
                type="hidden"
                name="tab"
                value="datlich">

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

                    <option value="noikhoa">
                        Nội khoa
                    </option>

                    <option value="ngoai khoa">
                        Ngoại khoa
                    </option>

                    <option value="tim mach">
                        Tim mạch
                    </option>

                    <option value="da lieu">
                        Da liễu
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
                    required>

                    <option value="">
                        Chọn bác sĩ
                    </option>

                    <!-- Dữ liệu bác sĩ sẽ được render từ backend -->

                </select>

            </div>

            <div class="booking-form-field">

                <label for="booking-date">
                    Ngày khám
                </label>

                <input
                    type="date"
                    id="booking-date"
                    name="date"
                    value="<?= htmlspecialchars($selectedDate) ?>"
                    min="<?= date('Y-m-d') ?>"
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

    <!-- Chú thích trạng thái -->
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

    <!-- Chọn khung giờ -->
    <form method="POST" class="booking-slot-form">

        <input
            type="hidden"
            name="doctorId"
            value="<?= htmlspecialchars($selectedDoctor) ?>">

        <input
            type="hidden"
            name="date"
            value="<?= htmlspecialchars($selectedDate) ?>">

        <div class="booking-slot-form__heading">

            <div>
                <h3>Chọn khung giờ khám</h3>

                <p>
                    <?= htmlspecialchars($selectedDate) ?>
                    · 40 phút / lượt
                </p>
            </div>

        </div>

        <div class="booking-periods" id="booking-periods">
            <!-- Các khung giờ sẽ được render bằng JavaScript -->
        </div>

        <!-- Lý do khám -->
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

                <span class="booking-form-field__hint">
                    Tối đa 500 ký tự.
                </span>

            </div>

        </section>

        <!-- Xác nhận đặt lịch -->
        <div class="booking-submit">

            <div class="booking-submit__note">
                <i class="fa-solid fa-circle-info"></i>

                <p>
                    Vui lòng kiểm tra thông tin trước khi xác nhận.
                    Lịch khám sẽ được xác nhận sau khi đặt thành công.
                </p>
            </div>

            <button
                type="submit"
                class="booking-submit__button">

                <i class="fa-solid fa-calendar-check"></i>
                Xác nhận đặt lịch

            </button>

        </div>

    </form>

</section>

<script>
    function getEarliestBookingDateTime() {
        const now = new Date();

        const currentHour = now.getHours();

        // Buổi chiều đã bắt đầu: chỉ được đặt từ ngày mai.
        if (currentHour >= 13) {
            now.setDate(now.getDate() + 1);
            now.setHours(7, 0, 0, 0);

            return now;
        }

        // Buổi sáng: được đặt từ 13:00 cùng ngày.
        if (currentHour >= 7) {
            now.setHours(13, 0, 0, 0);

            return now;
        }

        // Trước 07:00: có thể đặt từ 07:00 cùng ngày.
        now.setHours(7, 0, 0, 0);

        return now;
    }

    function isBookingSlotAllowed(date, startTime) {
        const slotDateTime = new Date(`${date}T${startTime}:00`);
        const earliest = getEarliestBookingDateTime();

        return slotDateTime >= earliest;
    }
</script>