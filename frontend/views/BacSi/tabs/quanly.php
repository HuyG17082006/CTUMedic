<?php

$selectedDate = $_GET['date'] ?? date('Y-m-d');

?>

<section class="doctor-schedule-page">

    <!-- Chọn ngày -->
    <section class="schedule-date-card">

        <div class="schedule-date-card__heading">
            <div>
                <h3>Khung giờ làm việc</h3>
                <p>
                    Chọn ngày để xem trạng thái và quản lý
                    các khung giờ khám.
                </p>
            </div>

            <span class="schedule-date-card__badge">
                <i class="fa-regular fa-clock"></i>
                40 phút / lượt
            </span>
        </div>

        <form method="GET" class="schedule-date-form">

            <input type="hidden" name="page" value="doctor">
            <input type="hidden" name="tab" value="lichlamviec">

            <div class="schedule-date-form__field">
                <label for="schedule-date">Ngày làm việc</label>

                <input
                    type="date"
                    id="schedule-date"
                    name="date"
                    value="<?= htmlspecialchars($selectedDate) ?>"
                    min="<?= date('Y-m-d') ?>"
                    required>
            </div>

            <button type="submit" class="schedule-date-form__submit">
                <i class="fa-solid fa-magnifying-glass"></i>
                Xem lịch
            </button>

        </form>

    </section>

    <!-- Chú thích trạng thái -->
    <section class="schedule-legend">

        <span class="schedule-legend__item">
            <span class="schedule-legend__dot schedule-legend__dot--available"></span>
            Có thể đặt
        </span>

        <span class="schedule-legend__item">
            <span class="schedule-legend__dot schedule-legend__dot--booked"></span>
            Đã có lịch khám
        </span>

        <span class="schedule-legend__item">
            <span class="schedule-legend__dot schedule-legend__dot--blocked"></span>
            Đã khóa
        </span>

        <span class="schedule-legend__item">
            <span class="schedule-legend__dot schedule-legend__dot--completed"></span>
            Đã qua
        </span>

    </section>

    <!-- JavaScript sẽ render các buổi và khung giờ tại đây -->
    <div class="schedule-periods" id="schedule-periods">

        <!-- Nội dung được render bằng JavaScript -->

    </div>

    <!-- Ghi chú -->
    <aside class="schedule-note">
        <i class="fa-solid fa-circle-info"></i>

        <p>
            Khóa khung giờ sẽ ngăn bệnh nhân đặt lịch mới
            trong khoảng thời gian đó. Các khung giờ đã có
            lịch khám cần được xử lý riêng để đảm bảo bệnh nhân
            được thông báo khi lịch hẹn bị hủy.
        </p>
    </aside>

</section>