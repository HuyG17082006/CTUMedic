
<section class="work-schedule-page">

    <!-- Lịch nghỉ làm -->
    <div class="work-schedule-calendar-section">

        <div class="work-schedule-calendar-header">
            <div>
                <h3>Lịch nghỉ làm</h3>
                <p>Theo dõi các ngày nghỉ đã đăng ký</p>
            </div>

            <div class="work-schedule-calendar-navigation">
                <button
                    type="button"
                    id="prev-month"
                    aria-label="Tháng trước"
                >
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <span id="current-month"></span>

                <button
                    type="button"
                    id="next-month"
                    aria-label="Tháng sau"
                >
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Calendar được render bằng JavaScript -->
        <div id="work-schedule-calendar"></div>

    </div>

    <!-- Form đăng ký nghỉ -->
    <div class="work-schedule-form-section">

        <?php
        require __DIR__ . '/../../components/work_calender_form.php';
        ?>

    </div>

</section>

<script type="module" src="/dashboard/CTUMedic/frontend/assets/js/shared/work_calender/work_calender.js"></script>
