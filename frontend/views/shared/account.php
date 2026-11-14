<div class="appointment-container">
    <!-- Card 1: Thông tin tài khoản hệ thống -->
    <div class="appointment-table-card account-card--spaced">
        <div class="table-header">
            <h3 class="table-title">Thông tin tài khoản đăng nhập</h3>
        </div>

        <div class="account-card__body">
            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" id="profileUsername" class="form-input account-input--username" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Vai trò hệ thống</label>
                    <input type="text" id="profileRole" class="form-input account-input--disabled" disabled>
                </div>
            </div>

            <div class="form-group account-status">
                <label class="form-label">Trạng thái hoạt động</label>
                <div id="profileStatusBadge"></div>
            </div>
        </div>
    </div>

    <!-- Card 2: Thông tin hồ sơ cá nhân (Tự động hiện nếu là Bệnh nhân hoặc Bác sĩ) -->
    <div id="personalProfileCard" class="appointment-table-card account-card--spaced">
        <div class="table-header">
            <h3 class="table-title">Thông tin hồ sơ cá nhân</h3>
        </div>

        <form id="updateProfileForm" class="account-form">
            <!-- Form dành cho Bệnh nhân -->
            <div id="patientProfileFields">
                <div class="form-group">
                    <label for="pfPatientHoTen" class="form-label">Họ và tên <span class="required">*</span></label>
                    <input type="text" id="pfPatientHoTen" class="form-input" placeholder="Nhập họ và tên">
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="pfPatientNgaySinh" class="form-label">Ngày sinh</label>
                        <input type="date" id="pfPatientNgaySinh" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="pfPatientSDT" class="form-label">Số điện thoại</label>
                        <input type="text" id="pfPatientSDT" class="form-input" placeholder="09xxxxxxxx">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="pfPatientEmail" class="form-label">Email</label>
                        <input type="email" id="pfPatientEmail" class="form-input" placeholder="example@email.com">
                    </div>

                    <div class="form-group">
                        <label for="pfPatientCCCD" class="form-label">CCCD</label>
                        <input type="text" id="pfPatientCCCD" class="form-input" placeholder="Nhập số CCCD">
                    </div>
                </div>
            </div>

            <!-- Form dành cho Bác sĩ -->
            <div id="doctorProfileFields">
                <div class="form-group">
                    <label for="pfDoctorHoTen" class="form-label">Họ và tên <span class="required">*</span></label>
                    <input type="text" id="pfDoctorHoTen" class="form-input" placeholder="Nhập họ và tên">
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="pfDoctorEmail" class="form-label">Email <span class="required">*</span></label>
                        <input type="email" id="pfDoctorEmail" class="form-input" placeholder="doctor@ctumedic.vn">
                    </div>

                    <div class="form-group">
                        <label for="pfDoctorChuyenKhoa" class="form-label">Chuyên khoa <span class="required">*</span></label>
                        <select id="pfDoctorChuyenKhoa" class="form-select">
                            <option value="Nội tổng quát">Nội tổng quát</option>
                            <option value="Nội khoa">Nội khoa</option>
                            <option value="Nhi khoa">Nhi khoa</option>
                            <option value="Tim mạch">Tim mạch</option>
                            <option value="Da liễu">Da liễu</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="account-form__actions">
                <button type="submit" class="btn-primary-filter">Lưu thông tin cá nhân</button>
            </div>
        </form>
    </div>

    <!-- Card 3: Đổi mật khẩu -->
    <div class="appointment-table-card">
        <div class="table-header">
            <h3 class="table-title">Đổi mật khẩu</h3>
        </div>

        <form id="changePasswordForm" class="account-form">
            <div class="form-group">
                <label for="oldPassword" class="form-label">Mật khẩu hiện tại <span class="required">*</span></label>
                <input type="password" id="oldPassword" required class="form-input" placeholder="Nhập mật khẩu đang sử dụng">
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="newPassword" class="form-label">Mật khẩu mới <span class="required">*</span></label>
                    <input type="password" id="newPassword" required minlength="6" class="form-input" placeholder="Tối thiểu 6 ký tự">
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới <span class="required">*</span></label>
                    <input type="password" id="confirmPassword" required minlength="6" class="form-input" placeholder="Nhập lại mật khẩu mới">
                </div>
            </div>

            <div class="account-form__actions">
                <button type="submit" class="btn-primary-filter">Cập nhật mật khẩu</button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="/dashboard/CTUMedic/frontend/assets/js/shared/profile.js"></script>