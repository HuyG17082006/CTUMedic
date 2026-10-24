
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

$filterId = $filterId ?? 'appointment-filter';
$showStatus = $showStatus ?? true;
?>

<section
    class="appointment-filter"
    id="<?= htmlspecialchars($filterId) ?>"
>
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
            data-filter-reset
        >
            Xóa bộ lọc
        </button>
    </div>

    <div class="appointment-filter__fields">

        <!-- Chuyên khoa: JavaScript sẽ tải dữ liệu từ API -->
        <div class="appointment-filter__field">
            <label for="<?= htmlspecialchars($filterId) ?>-specialty">
                Chuyên khoa
            </label>

            <select
                id="<?= htmlspecialchars($filterId) ?>-specialty"
                name="specialty"
                data-filter="specialty"
            >
                <option value="">Tất cả chuyên khoa</option>
            </select>
        </div>

        <!-- Bác sĩ: mặc định bị khóa cho đến khi chọn chuyên khoa -->
        <div class="appointment-filter__field">
            <label for="<?= htmlspecialchars($filterId) ?>-doctor">
                Bác sĩ
            </label>

            <select
                id="<?= htmlspecialchars($filterId) ?>-doctor"
                name="doctor"
                data-filter="doctor"
                disabled
            >
                <option value="">Chọn chuyên khoa trước</option>
            </select>
        </div>

        <!-- Ngày khám -->
        <div class="appointment-filter__field">
            <label for="<?= htmlspecialchars($filterId) ?>-date">
                Ngày khám
            </label>

            <input
                type="date"
                id="<?= htmlspecialchars($filterId) ?>-date"
                name="date"
                data-filter="date"
            >
        </div>

        <!-- Trạng thái -->
        <?php if ($showStatus): ?>
            <div class="appointment-filter__field">
                <label for="<?= htmlspecialchars($filterId) ?>-status">
                    Trạng thái
                </label>

                <select
                    id="<?= htmlspecialchars($filterId) ?>-status"
                    name="status"
                    data-filter="status"
                >
                    <option value="">Tất cả trạng thái</option>
                    <option value="PENDING">Chờ xác nhận</option>
                    <option value="CONFIRMED">Đã xác nhận</option>
                    <option value="REJECTED">Bị từ chối</option>
                    <option value="CANCELLED">Đã hủy</option>
                    <option value="COMPLETED">Đã hoàn thành</option>
                </select>
            </div>
        <?php endif; ?>

    </div>

    <div class="appointment-filter__footer">
        <span class="appointment-filter__hint">
            Chọn tiêu chí để lọc lịch khám.
        </span>

        <button
            type="button"
            class="appointment-filter__submit"
            data-filter-apply
        >
            <span aria-hidden="true">⌕</span>
            Lọc lịch khám
        </button>
    </div>
</section>
