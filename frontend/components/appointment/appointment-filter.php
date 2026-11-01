<?php

/**
 * Component: Appointment Filter
 *
 * Dùng chung cho Bệnh nhân, Bác sĩ và Quản lý.
 *
 * TODO: JavaScript sẽ gọi API để tải chuyên khoa, bác sĩ
 * và áp dụng bộ lọc. Component này chỉ dựng giao diện.
 *
 * TODO: XÓA DỮ LIỆU DEMO nếu bổ sung dữ liệu mẫu vào file này.
 */
// Phân loại trang : lịch sử khám - lịch khám
$role = $role ?? '';
$tableType = $tableType ?? 'appointments';

$isDoctor = $role === 'doctor';
?>

<form
    class="appointment-filter">
    <div class="appointment-filter__header">
        <div>
            <h3 class="appointment-filter__title">Bộ lọc lịch khám</h3>
            <p class="appointment-filter__description">
                Lọc lịch khám theo các tiêu chí bên dưới.
            </p>
        </div>

        <button
            type="button"
            class="appointment-filter__reset"
            data-filter-reset>
            Xóa bộ lọc
        </button>
    </div>

    <div class="appointment-filter__fields">

        <!-- Chuyên khoa: JavaScript sẽ tải dữ liệu từ API -->
        <div class="appointment-filter__field">
            <label for="specialty">
                Chuyên khoa
            </label>

            <select
                id="specialty"
                name="specialty"
                data-filter="specialty"
                class="specialty-select-box"
                <?= $isDoctor ? 'disabled' : '' ?>>
                <option value="">
                    <?= $isDoctor ? 'Không áp dụng' : 'Tất cả chuyên khoa' ?>
                </option>
            </select>
        </div>

        <!-- Bác sĩ: mặc định bị khóa cho đến khi chọn chuyên khoa -->
        <div class="appointment-filter__field">
            <label for="doctor">
                Bác sĩ
            </label>

            <select
                id="doctor"
                name="doctor"
                data-filter="doctor"
                class="doctor-select-box"
                disabled>

                <option value="">
                    <?= $isDoctor ? 'Không áp dụng' : 'Chọn chuyên khoa trước' ?>
                </option>
            </select>
        </div>

        <!-- Ngày khám -->
        <div class="appointment-filter__field">
            <label for="date">
                Ngày khám
            </label>

            <input
                type="date"
                id="date"
                name="date"
                data-filter="date"
                class="date-input-box">
        </div>

        <!-- Trạng thái -->
        <div class="appointment-filter__field" hidden>
            <label for="status">
                Trạng thái
            </label>

            <select
                id="status"
                name="status"
                data-filter="status"
                class="status-select-box">
                <option value="">Tất cả trạng thái</option>
                <option value="CONFIRMED">Sắp tới</option>
                <option value="CANCELLED">Đã hủy</option>
                <option value="COMPLETED">Đã hoàn thành</option>
            </select>
        </div>

    </div>

    <div class="appointment-filter__footer">
        <span class="appointment-filter__hint">
            Chọn tiêu chí để lọc lịch khám.
        </span>

        <button
            type="button"
            class="appointment-filter__submit"
            data-filter-apply>
            <span aria-hidden="true">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            Lọc lịch khám
        </button>
    </div>
</form>
