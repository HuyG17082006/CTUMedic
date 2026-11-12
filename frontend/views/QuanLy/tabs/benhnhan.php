<div class="appointment-container">
    <!-- Card Bộ lọc -->
    <div class="appointment-filter-card">
        <div class="filter-wrapper">
            <div class="filter-item filter-item-search">
                <label for="searchPatientInput" class="filter-label">Từ khóa tìm kiếm</label>
                <input type="text" id="searchPatientInput" class="form-input" placeholder="Tìm theo họ tên, SĐT, CCCD, email...">
            </div>

            <div class="filter-item">
                <label for="filterStatus" class="filter-label">Trạng thái tài khoản</label>
                <select id="filterStatus" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1">Đang hoạt động</option>
                    <option value="0">Đã vô hiệu hóa</option>
                </select>
            </div>

            <div class="filter-item">
                <label for="filterAgeGroup" class="filter-label">Độ tuổi</label>
                <select id="filterAgeGroup" class="form-select">
                    <option value="">Tất cả độ tuổi</option>
                    <option value="under_18">Trẻ em (&lt; 18 tuổi)</option>
                    <option value="18_59">Người lớn (18 - 59 tuổi)</option>
                    <option value="over_60">Người cao tuổi (&ge; 60 tuổi)</option>
                </select>
            </div>

            <div class="filter-item">
                <label for="sortBy" class="filter-label">Sắp xếp theo</label>
                <select id="sortBy" class="form-select">
                    <option value="id_desc">Mới nhất</option>
                    <option value="id_asc">Cũ nhất</option>
                    <option value="name_asc">Tên (A &rarr; Z)</option>
                    <option value="name_desc">Tên (Z &rarr; A)</option>
                </select>
            </div>

            <div class="filter-buttons">
                <button type="button" class="btn-primary-filter" id="btnSearchPatient">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    <span>Tìm kiếm</span>
                </button>
                <button type="button" class="btn-secondary-reset" id="btnResetFilter">Đặt lại</button>
            </div>
        </div>
    </div>

    <!-- Card Bảng dữ liệu -->
    <div class="appointment-table-card">
        <div class="table-header">
            <h3 class="table-title">Danh sách bệnh nhân trong hệ thống</h3>
        </div>
        
        <div class="table-responsive">
            <table class="patient-table">
                <thead>
                    <tr>
                        <th style="width: 55px;">STT</th>
                        <th>Họ và tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Ngày sinh</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>CCCD</th>
                        <th>Trạng thái</th>
                        <th style="text-align: center; width: 210px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="patientTableBody">
                    <tr>
                        <td colspan="9" class="table-empty-state">Đang tải dữ liệu...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination-container" id="paginationWrapper"></div>
    </div>
</div>

<!-- Modal Sửa Thông Tin Bệnh Nhân -->
<div id="editPatientModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Chỉnh sửa thông tin bệnh nhân</h3>
            <button type="button" class="modal-close-btn" onclick="closeEditModal()">&times;</button>
        </div>

        <form id="editPatientForm">
            <input type="hidden" id="editPatientId">
            
            <div class="form-group">
                <label for="editHoTen" class="form-label">Họ và tên <span class="required">*</span></label>
                <input type="text" id="editHoTen" required class="form-input" placeholder="Nhập họ và tên">
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="editNgaySinh" class="form-label">Ngày sinh</label>
                    <input type="date" id="editNgaySinh" class="form-input">
                </div>

                <div class="form-group">
                    <label for="editSDT" class="form-label">Số điện thoại</label>
                    <input type="text" id="editSDT" class="form-input" placeholder="09xxxxxxxx">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="editEmail" class="form-label">Email</label>
                    <input type="email" id="editEmail" class="form-input" placeholder="example@email.com">
                </div>

                <div class="form-group">
                    <label for="editCCCD" class="form-label">CCCD</label>
                    <input type="text" id="editCCCD" class="form-input" placeholder="Nhập số CCCD">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn-secondary-reset">Hủy</button>
                <button type="submit" class="btn-primary-filter">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="/dashboard/CTUMedic/frontend/assets/js/QuanLy/benhnhan.js"></script>