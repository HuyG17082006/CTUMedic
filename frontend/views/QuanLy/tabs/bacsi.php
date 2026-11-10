<div class="appointment-container">
    <!-- Thanh tìm kiếm và Nút Thêm Bác sĩ -->
    <div class="appointment-filter-card">
        <div class="filter-wrapper">
            <div class="filter-item-search">
                <input type="text" id="searchDoctorInput" class="form-input" placeholder="Tìm kiếm theo họ tên, chuyên khoa, email, tên đăng nhập...">
            </div>
            <div class="filter-buttons">
                <button type="button" class="btn-search" id="btnSearchDoctor">Tìm kiếm</button>
                <button type="button" class="btn-reset" id="btnResetDoctorFilter">Đặt lại</button>
                <button type="button" class="btn-add-doctor" onclick="openAddDoctorModal()">+ Thêm bác sĩ</button>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách bác sĩ -->
    <div class="appointment-table-card">
        <div class="table-header">
            <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0;">Danh sách bác sĩ trong hệ thống</h3>
        </div>
        
        <div class="table-responsive">
            <table class="doctor-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Họ và tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Chuyên khoa</th>
                        <th>Email</th>
                        <th style="text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="doctorTableBody">
                    <tr>
                        <td colspan="6" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Thanh phân trang -->
        <div class="pagination-container" id="paginationWrapper"></div>
    </div>
</div>

<!-- Modal Thêm Bác Sĩ -->
<div id="addDoctorModal" class="modal-overlay">
    <div class="modal-content">
        <h3 style="margin-top: 0; font-size: 18px; color: #111827;">Thêm bác sĩ mới</h3>
        <form id="addDoctorForm">
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Tên đăng nhập (*)</label>
                <input type="text" id="addUsername" required class="form-input">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Mật khẩu (*)</label>
                <input type="password" id="addPassword" required class="form-input">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Họ và tên bác sĩ (*)</label>
                <input type="text" id="addHoTen" required class="form-input">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Chuyên khoa</label>
                <input type="text" id="addChuyenKhoa" class="form-input">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Email</label>
                <input type="email" id="addEmail" class="form-input">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeAddDoctorModal()" class="btn-reset">Hủy</button>
                <button type="submit" class="btn-add-doctor">Tạo mới</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sửa Bác Sĩ -->
<div id="editDoctorModal" class="modal-overlay">
    <div class="modal-content">
        <h3 style="margin-top: 0; font-size: 18px; color: #111827;">Chỉnh sửa thông tin bác sĩ</h3>
        <form id="editDoctorForm">
            <input type="hidden" id="editDoctorId">
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Họ và tên (*)</label>
                <input type="text" id="editHoTen" required class="form-input">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Chuyên khoa</label>
                <input type="text" id="editChuyenKhoa" class="form-input">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Email</label>
                <input type="email" id="editEmail" class="form-input">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeEditDoctorModal()" class="btn-reset">Hủy</button>
                <button type="submit" class="btn-search">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="/dashboard/CTUMedic/frontend/assets/js/QuanLy/bacsi.js"></script>