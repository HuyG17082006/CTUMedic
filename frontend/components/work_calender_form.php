
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? '';

if (!in_array($role, ['doctor', 'manager'], true)) {
    return;
}

$isManager = $role === 'manager';

?>

<div class="leave-form-card">

    <!-- Tiêu đề -->
    <div class="leave-form-header">

        <div>
            <h3>Đăng ký nghỉ làm</h3>

            <p>
                <?= $isManager
                    ? 'Đăng ký nghỉ và tạm dừng hoạt động khám trên toàn hệ thống.'
                    : 'Đăng ký nghỉ làm theo buổi của bạn.' ?>
            </p>
        </div>

        <span class="leave-scope-badge <?= $isManager ? 'system' : 'doctor' ?>">
            <?= $isManager ? 'Toàn hệ thống' : 'Cá nhân' ?>
        </span>

    </div>

    <!-- Form -->
    <form id="leave-form">

        <div class="leave-form-body">

            <!-- Ngày nghỉ -->
            <div class="form-group">

                <label for="leave-date">
                    Ngày nghỉ
                </label>

                <input
                    type="date"
                    id="leave-date"
                    name="ngayNghi"
                    min="<?= date('Y-m-d') ?>"
                    required
                >

            </div>

            <!-- Buổi nghỉ -->
            <div class="form-group">

                <label for="leave-session">
                    Buổi nghỉ
                </label>

                <select
                    id="leave-session"
                    name="buoi"
                    required
                >
                    <option value="">-- Chọn buổi nghỉ --</option>

                    <option value="MORNING">
                        Buổi sáng (07:00 - 11:00)
                    </option>

                    <option value="AFTERNOON">
                        Buổi chiều (13:00 - 17:00)
                    </option>

                </select>

            </div>

            <!-- Lý do nghỉ -->
            <div class="form-group">

                <label for="leave-reason">
                    Lý do nghỉ
                </label>

                <textarea
                    id="leave-reason"
                    name="lyDo"
                    rows="4"
                    maxlength="500"
                    placeholder="Nhập lý do nghỉ làm..."
                    required
                ></textarea>

            </div>

            <!-- Thông báo -->
            <div class="leave-scope-notice">

                <i class="fa-solid fa-circle-info"></i>

                <span>
                    <?= $isManager
                        ? 'Các lịch khám đã xác nhận trong buổi này của toàn bộ bác sĩ sẽ được chuyển sang trạng thái đã hủy.'
                        : 'Các lịch khám đã xác nhận trong buổi này của bạn sẽ được chuyển sang trạng thái đã hủy.' ?>
                </span>

            </div>

            <!-- Nút thao tác -->
            <div class="leave-form-actions">

                <button
                    type="reset"
                    class="btn btn-secondary"
                >
                    Làm mới
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fa-solid fa-calendar-xmark"></i>
                    Xác nhận nghỉ
                </button>

            </div>

        </div>

    </form>

</div>
