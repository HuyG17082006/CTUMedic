<div class="appointment-container">
    <!-- Card Bộ lọc -->
    <div class="appointment-filter-card">
        <div class="filter-wrapper">
            <div class="filter-item filter-item-search">
                <label for="searchDoctorInput" class="filter-label">Từ khóa tìm kiếm</label>
                <input type="text" id="searchDoctorInput" class="form-input" placeholder="Tìm theo họ tên, email, tên đăng nhập...">
            </div>

            <div class="filter-item">
                <label for="filterSpecialty" class="filter-label">Chuyên khoa</label>
                <select id="filterSpecialty" class="form-select">
                    <option value="">Tất cả chuyên khoa</option>
                    <option value="Nội tổng quát">Nội tổng quát</option>
                    <option value="Nội khoa">Nội khoa</option>
                    <option value="Nhi khoa">Nhi khoa</option>
                    <option value="Tim mạch">Tim mạch</option>
                    <option value="Da liễu">Da liễu</option>
                </select>
            </div>

            <div class="filter-item">
                <label for="filterDoctorStatus" class="filter-label">Trạng thái tài khoản</label>
                <select id="filterDoctorStatus" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1">Đang hoạt động</option>
                    <option value="0">Đã vô hiệu hóa</option>
                </select>
            </div>

            <div class="filter-item">
                <label for="sortDoctorBy" class="filter-label">Sắp xếp theo</label>
                <select id="sortDoctorBy" class="form-select">
                    <option value="id_desc">Mới nhất</option>
                    <option value="id_asc">Cũ nhất</option>
                    <option value="name_asc">Tên (A &rarr; Z)</option>
                    <option value="name_desc">Tên (Z &rarr; A)</option>
                </select>
            </div>

            <div class="filter-buttons">
                <button type="button" class="btn-primary-filter" id="btnSearchDoctor">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    <span>Tìm kiếm</span>
                </button>
                <button type="button" class="btn-secondary-reset" id="btnResetDoctorFilter">Đặt lại</button>
            </div>
        </div>
    </div>

    <!-- Card Bảng dữ liệu -->
    <div class="appointment-table-card">
        <div class="table-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="table-title">Danh sách bác sĩ trong hệ thống</h3>
            <button type="button" class="btn-primary-filter" onclick="openAddDoctorModal()">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                <span>Thêm bác sĩ</span>
            </button>
        </div>

        <div class="table-responsive">
            <table class="patient-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">STT</th>
                        <th>Họ và tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Chuyên khoa</th>
                        <th>Trạng thái</th>
                        <th style="text-align: center; width: 220px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="doctorTableBody">
                    <tr>
                        <td colspan="7" class="table-empty-state">Đang tải dữ liệu...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php 
            require __DIR__.'/../../../components/pagination.php'
        ?>
    </div>
</div>

<!-- Modal Thêm Bác Sĩ Mới -->
<div id="addDoctorModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Thêm bác sĩ mới</h3>
            <button type="button" class="modal-close-btn" onclick="closeAddDoctorModal()">&times;</button>
        </div>

        <form id="addDoctorForm">
            <div class="form-row-2">
                <div class="form-group">
                    <label for="addDoctorUsername" class="form-label">Tên đăng nhập <span class="required">*</span></label>
                    <input type="text" id="addDoctorUsername" required class="form-input" placeholder="VD: bs_nguyenvana">
                </div>

                <div class="form-group">
                    <label for="addDoctorPassword" class="form-label">Mật khẩu <span class="required">*</span></label>
                    <input type="password" id="addDoctorPassword" required class="form-input" placeholder="Nhập mật khẩu">
                </div>
            </div>

            <div class="form-group">
                <label for="addDoctorHoTen" class="form-label">Họ và tên <span class="required">*</span></label>
                <input type="text" id="addDoctorHoTen" required class="form-input" placeholder="Nhập họ và tên bác sĩ">
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="addDoctorEmail" class="form-label">Email <span class="required">*</span></label>
                    <input type="email" id="addDoctorEmail" required class="form-input" placeholder="doctor@ctumedic.vn">
                </div>

                <div class="form-group">
                    <label for="addDoctorChuyenKhoa" class="form-label">Chuyên khoa <span class="required">*</span></label>
                    <select id="addDoctorChuyenKhoa" required class="form-select">
                        <option value="">-- Chọn chuyên khoa --</option>
                        <option value="Nội tổng quát">Nội tổng quát</option>
                        <option value="Nội khoa">Nội khoa</option>
                        <option value="Nhi khoa">Nhi khoa</option>
                        <option value="Tim mạch">Tim mạch</option>
                        <option value="Da liễu">Da liễu</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeAddDoctorModal()" class="btn-secondary-reset">Hủy</button>
                <button type="submit" class="btn-primary-filter">Tạo tài khoản</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sửa Thông Tin Bác Sĩ -->
<div id="editDoctorModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Chỉnh sửa thông tin bác sĩ</h3>
            <button type="button" class="modal-close-btn" onclick="closeEditDoctorModal()">&times;</button>
        </div>

        <form id="editDoctorForm">
            <input type="hidden" id="editDoctorId">

            <div class="form-group">
                <label for="editDoctorHoTen" class="form-label">Họ và tên <span class="required">*</span></label>
                <input type="text" id="editDoctorHoTen" required class="form-input" placeholder="Nhập họ và tên">
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="editDoctorEmail" class="form-label">Email <span class="required">*</span></label>
                    <input type="email" id="editDoctorEmail" required class="form-input" placeholder="doctor@ctumedic.vn">
                </div>

                <div class="form-group">
                    <label for="editDoctorChuyenKhoa" class="form-label">Chuyên khoa <span class="required">*</span></label>
                    <select id="editDoctorChuyenKhoa" required class="form-select">
                        <option value="Nội tổng quát">Nội tổng quát</option>
                        <option value="Nội khoa">Nội khoa</option>
                        <option value="Nhi khoa">Nhi khoa</option>
                        <option value="Tim mạch">Tim mạch</option>
                        <option value="Da liễu">Da liễu</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeEditDoctorModal()" class="btn-secondary-reset">Hủy</button>
                <button type="submit" class="btn-primary-filter">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="/dashboard/CTUMedic/frontend/assets/js/management/doctors.js"></script>