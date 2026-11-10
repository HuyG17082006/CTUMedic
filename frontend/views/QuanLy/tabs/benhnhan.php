<div class="appointment-container">
    <!-- Bộ lọc -->
    <div class="appointment-filter-card">
        <div class="filter-wrapper">
            <div class="filter-item-search">
                <input type="text" id="searchPatientInput" class="form-input" placeholder="Tìm kiếm theo họ tên, SĐT, CCCD, email...">
            </div>

            <div class="filter-item-select">
                <select id="filterAgeGroup" class="form-select">
                    <option value="">-- Độ tuổi --</option>
                    <option value="all">Tất cả độ tuổi</option>
                    <option value="under_18">Trẻ em (&lt; 18 tuổi)</option>
                    <option value="18_59">Người lớn (18 - 59 tuổi)</option>
                    <option value="over_60">Người cao tuổi (&ge; 60 tuổi)</option>
                </select>
            </div>

            <div class="filter-item-select">
                <select id="sortBy" class="form-select">
                    <option value="id_desc">Mới nhất</option>
                    <option value="id_asc">Cũ nhất</option>
                    <option value="name_asc">Tên (A -> Z)</option>
                    <option value="name_desc">Tên (Z -> A)</option>
                </select>
            </div>

            <div class="filter-buttons">
                <button type="button" class="btn-edit" style="background:#2563eb; color:#fff; border:none; padding:10px 18px;" id="btnSearchPatient">Tìm kiếm</button>
                <button type="button" class="form-select" style="width:auto; cursor:pointer;" id="btnResetFilter">Đặt lại</button>
            </div>
        </div>
    </div>

    <!-- Bảng dữ liệu -->
    <div class="appointment-table-card">
        <div class="table-header">
            <h3 style="margin:0; font-size:18px; color:#111827;">Danh sách bệnh nhân trong hệ thống</h3>
        </div>
        
        <div class="table-responsive">
            <table class="patient-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Họ và tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Ngày sinh</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>CCCD</th>
                        <th style="text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="patientTableBody">
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 24px;">Đang tải dữ liệu...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Thanh Phân Trang -->
        <div class="pagination-container" id="paginationWrapper"></div>
    </div>
</div>

<!-- Modal Sửa Thông Tin Bệnh Nhân -->
<div id="editPatientModal" class="modal-overlay">
    <div class="modal-content">
        <h3 style="margin-top: 0; font-size: 18px; color: #111827;">Chỉnh sửa thông tin bệnh nhân</h3>
        <form id="editPatientForm">
            <input type="hidden" id="editPatientId">
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Họ và tên</label>
                <input type="text" id="editHoTen" required class="form-input">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Ngày sinh</label>
                <input type="date" id="editNgaySinh" class="form-input">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Số điện thoại</label>
                <input type="text" id="editSDT" class="form-input">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Email</label>
                <input type="email" id="editEmail" class="form-input">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">CCCD</label>
                <input type="text" id="editCCCD" class="form-input">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeEditModal()" class="form-select" style="width:auto; cursor:pointer;">Hủy</button>
                <button type="submit" class="btn-edit" style="background:#2563eb; color:#fff; border:none; padding:8px 16px;">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="/dashboard/CTUMedic/frontend/assets/js/QuanLy/benhnhan.js"></script>