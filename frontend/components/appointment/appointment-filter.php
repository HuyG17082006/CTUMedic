<?php

$role = $role ?? '';
$tableType = $tableType ?? 'appointments';

$isDoctor = $role === 'doctor';
$isHistory = $tableType === 'history';

?>

<form class="appointment-filter">

    <div class="appointment-filter__fields">

        <div class="appointment-filter__field">

            <label for="specialty">
                Chuyên khoa
            </label>

            <select
                id="specialty"
                name="specialty"
                class="specialty-select-box"
                <?= $isDoctor ? 'disabled' : '' ?>>

                <option value="">
                    <?= $isDoctor
                        ? 'Không áp dụng'
                        : 'Tất cả chuyên khoa'
                    ?>
                </option>

            </select>

        </div>

        <div class="appointment-filter__field">

            <label for="doctor">
                Bác sĩ
            </label>

            <select
                id="doctor"
                name="doctor"
                class="doctor-select-box"
                disabled>

                <option value="">
                    <?= $isDoctor
                        ? 'Không áp dụng'
                        : 'Chọn chuyên khoa trước'
                    ?>
                </option>

            </select>

        </div>

        <div class="appointment-filter__field">

            <label for="date">
                Ngày khám
            </label>

            <input
                type="date"
                id="date"
                name="date"
                class="date-input-box">

        </div>

        <?php if ($isHistory): ?>

            <div class="appointment-filter__field">

                <label for="status">
                    Trạng thái
                </label>

                <select
                    id="status"
                    name="status"
                    class="status-select-box">

                    <option value="">
                        Tất cả trạng thái
                    </option>

                    <option value="COMPLETED">
                        Đã khám
                    </option>

                    <option value="CANCELLED">
                        Đã hủy
                    </option>

                </select>

            </div>

        <?php else: ?>

            <!-- JS vẫn cần phần tử này -->

            <select
                class="status-select-box"
                hidden>

                <option value=""></option>

            </select>

        <?php endif; ?>

    </div>

    <div class="appointment-filter__footer">

        <div class="appointment-filter__summary">

            <?php if ($isHistory): ?>
                <span>
                    Lọc lịch đã khám hoặc đã hủy
                </span>
            <?php else: ?>
                <span>
                    Lọc các lịch khám sắp tới
                </span>
            <?php endif; ?>

        </div>

        <div class="appointment-filter__actions">

            <button
                type="button"
                class="appointment-filter__reset">

                <i class="fa-solid fa-rotate-left"></i>

                Đặt lại

            </button>

            <button
                type="button"
                class="appointment-filter__submit">

                <i class="fa-solid fa-magnifying-glass"></i>

                Lọc dữ liệu

            </button>

        </div>

    </div>

</form>