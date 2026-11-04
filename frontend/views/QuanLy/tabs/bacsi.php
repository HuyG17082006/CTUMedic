<div class="appointment-container">
    <!-- Thanh tìm kiếm và Nút Thêm Bác sĩ -->
    <div class="appointment-filter-card" style="margin-bottom: 20px; background: #ffffff; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="flex: 2; min-width: 260px;">
                <input type="text" id="searchDoctorInput" placeholder="Tìm kiếm theo họ tên, chuyên khoa, email, tên đăng nhập..." 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none;">
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="button" class="btn-primary" id="btnSearchDoctor" style="padding: 10px 18px; font-size: 14px; cursor: pointer; border-radius: 8px;">Tìm kiếm</button>
                <button type="button" id="btnResetDoctorFilter" style="padding: 10px 14px; font-size: 14px; cursor: pointer; border: 1px solid #d1d5db; background: #f9fafb; border-radius: 8px; color: #374151;">Đặt lại</button>
                <button type="button" onclick="openAddDoctorModal()" style="padding: 10px 18px; font-size: 14px; cursor: pointer; border-radius: 8px; background: #10b981; color: #fff; border: none;">+ Thêm bác sĩ</button>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách bác sĩ -->
    <div class="appointment-table-card" style="background: #ffffff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f3f4f6;">
            <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0;">Danh sách bác sĩ trong hệ thống</h3>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: #f9fafb; color: #4b5563; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 16px; font-weight: 600;">STT</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Họ và tên</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Tên đăng nhập</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Chuyên khoa</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Email</th>
                        <th style="padding: 12px 16px; font-weight: 600; text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody id="doctorTableBody">
                    <tr>
                        <td colspan="6" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm Bác Sĩ -->
<div id="addDoctorModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 500px; border-radius: 12px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; font-size: 18px; color: #111827;">Thêm bác sĩ mới</h3>
        <form id="addDoctorForm">
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Tên đăng nhập (*)</label>
                <input type="text" id="addUsername" required style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Mật khẩu (*)</label>
                <input type="password" id="addPassword" required style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Họ và tên bác sĩ (*)</label>
                <input type="text" id="addHoTen" required style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Chuyên khoa</label>
                <input type="text" id="addChuyenKhoa" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Email</label>
                <input type="email" id="addEmail" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeAddDoctorModal()" style="padding: 8px 16px; border: 1px solid #d1d5db; background: #fff; border-radius: 6px; cursor: pointer;">Hủy</button>
                <button type="submit" style="padding: 8px 16px; background: #10b981; color: #fff; border: none; border-radius: 6px; cursor: pointer;">Tạo mới</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sửa Bác Sĩ -->
<div id="editDoctorModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 500px; border-radius: 12px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; font-size: 18px; color: #111827;">Chỉnh sửa thông tin bác sĩ</h3>
        <form id="editDoctorForm">
            <input type="hidden" id="editDoctorId">
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Họ và tên (*)</label>
                <input type="text" id="editHoTen" required style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Chuyên khoa</label>
                <input type="text" id="editChuyenKhoa" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Email</label>
                <input type="email" id="editEmail" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeEditDoctorModal()" style="padding: 8px 16px; border: 1px solid #d1d5db; background: #fff; border-radius: 6px; cursor: pointer;">Hủy</button>
                <button type="submit" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer;">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Xác Nhận Xóa Bác Sĩ (Custom Popup HTML thuần) -->
<div id="confirmDeleteDoctorModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 400px; border-radius: 12px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); text-align: center;">
        <div style="width: 48px; height: 48px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 24px; font-weight: bold;">!</div>
        <h3 style="margin: 0 0 8px; font-size: 18px; color: #111827;">Xác nhận xóa bác sĩ?</h3>
        <p id="deleteConfirmMessage" style="font-size: 14px; color: #4b5563; margin: 0 0 20px;"></p>
        
        <input type="hidden" id="deleteDoctorId">
        
        <div style="display: flex; justify-content: center; gap: 12px;">
            <button type="button" onclick="closeDeleteDoctorModal()" style="padding: 9px 18px; border: 1px solid #d1d5db; background: #fff; border-radius: 6px; cursor: pointer; font-size: 14px; color: #374151;">Hủy bỏ</button>
            <button type="button" onclick="confirmExecuteDeleteDoctor()" style="padding: 9px 18px; background: #ef4444; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Đồng ý xóa</button>
        </div>
    </div>
</div>

<script>
    let globalDoctors = [];

    // 1. Tải danh sách bác sĩ
    async function loadDoctors() {
        const tbody = document.getElementById('doctorTableBody');
        tbody.innerHTML = `<tr><td colspan="6" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu...</td></tr>`;

        const search = document.getElementById('searchDoctorInput').value.trim();
        const queryParams = new URLSearchParams({
            api: 'manager_get_doctors',
            search: search
        });

        try {
            const response = await fetch(`index.php?${queryParams.toString()}`, { method: 'GET', credentials: 'include' });
            const result = await response.json();

            if (result.status === 'success') {
                globalDoctors = result.data;
                if (globalDoctors.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" style="padding: 24px; text-align: center; color: #6b7280;">Không tìm thấy bác sĩ nào.</td></tr>`;
                    return;
                }

                let html = '';
                globalDoctors.forEach((d, index) => {
                    const safeName = (d.hoTen || '').replace(/'/g, "\\'");
                    html += `
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 14px 16px;">${index + 1}</td>
                            <td style="padding: 14px 16px; font-weight: 500;">${d.hoTen || 'Chưa cập nhật'}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${d.username || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${d.chuyenKhoa || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${d.email || ''}</td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <button type="button" onclick="openEditDoctorModal(${d.id})" 
                                    style="padding: 4px 10px; background: #eab308; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 4px;">Sửa</button>
                                <button type="button" onclick="deleteDoctor(${d.id}, '${safeName}')" 
                                    style="padding: 4px 10px; background: #ef4444; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Xóa</button>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = `<tr><td colspan="6" style="padding: 24px; text-align: center; color: #dc2626;">Lỗi: ${result.message}</td></tr>`;
            }
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="6" style="padding: 24px; text-align: center; color: #dc2626;">Lỗi kết nối máy chủ!</td></tr>`;
        }
    }

    // 2. Quản lý Modal Thêm
    function openAddDoctorModal() { document.getElementById('addDoctorModal').style.display = 'flex'; }
    function closeAddDoctorModal() { 
        document.getElementById('addDoctorModal').style.display = 'none'; 
        document.getElementById('addDoctorForm').reset();
    }

    // 3. Quản lý Modal Sửa
    function openEditDoctorModal(doctorId) {
        const d = globalDoctors.find(item => item.id == doctorId);
        if (!d) return;

        document.getElementById('editDoctorId').value = d.id;
        document.getElementById('editHoTen').value = d.hoTen || '';
        document.getElementById('editChuyenKhoa').value = d.chuyenKhoa || '';
        document.getElementById('editEmail').value = d.email || '';

        document.getElementById('editDoctorModal').style.display = 'flex';
    }
    function closeEditDoctorModal() { document.getElementById('editDoctorModal').style.display = 'none'; }

    // 4. Quản lý Popup Xóa Mềm Custom (Thay thế confirm gốc)
    function deleteDoctor(id, name) {
        document.getElementById('deleteDoctorId').value = id;
        document.getElementById('deleteConfirmMessage').innerText = `Bạn có chắc chắn muốn vô hiệu hóa tài khoản của bác sĩ "${name}" không?`;
        document.getElementById('confirmDeleteDoctorModal').style.display = 'flex';
    }

    function closeDeleteDoctorModal() {
        document.getElementById('confirmDeleteDoctorModal').style.display = 'none';
    }

    async function confirmExecuteDeleteDoctor() {
        const id = document.getElementById('deleteDoctorId').value;
        closeDeleteDoctorModal();

        try {
            const response = await fetch(`index.php?api=manager_delete_doctor&id=${id}`, {
                method: 'GET',
                credentials: 'include'
            });

            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message || 'Đã vô hiệu hóa tài khoản bác sĩ thành công!');
                loadDoctors();
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (error) {
            console.error(error);
            alert('Đã xảy ra lỗi kết nối máy chủ!');
        }
    }

    // 5. Sự kiện khi trang load xong
    document.addEventListener("DOMContentLoaded", function() {
        loadDoctors();

        // Tìm kiếm & Đặt lại
        document.getElementById('btnSearchDoctor').addEventListener('click', loadDoctors);
        document.getElementById('searchDoctorInput').addEventListener('keypress', (e) => { if (e.key === 'Enter') loadDoctors(); });
        document.getElementById('btnResetDoctorFilter').addEventListener('click', () => {
            document.getElementById('searchDoctorInput').value = '';
            loadDoctors();
        });

        // Submit Form Thêm Bác sĩ
        document.getElementById('addDoctorForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const payload = {
                username: document.getElementById('addUsername').value.trim(),
                password: document.getElementById('addPassword').value,
                hoTen: document.getElementById('addHoTen').value.trim(),
                chuyenKhoa: document.getElementById('addChuyenKhoa').value.trim(),
                email: document.getElementById('addEmail').value.trim()
            };

            try {
                const res = await fetch('index.php?api=manager_add_doctor', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (result.status === 'success') {
                    alert('Thêm bác sĩ thành công!');
                    closeAddDoctorModal();
                    loadDoctors();
                } else {
                    alert('Lỗi: ' + result.message);
                }
            } catch (err) {
                alert('Không thể kết nối máy chủ!');
            }
        });

        // Submit Form Sửa Bác sĩ
        document.getElementById('editDoctorForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const payload = {
                id: document.getElementById('editDoctorId').value,
                hoTen: document.getElementById('editHoTen').value.trim(),
                chuyenKhoa: document.getElementById('editChuyenKhoa').value.trim(),
                email: document.getElementById('editEmail').value.trim()
            };

            try {
                const res = await fetch('index.php?api=manager_update_doctor', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (result.status === 'success') {
                    alert('Cập nhật thông tin bác sĩ thành công!');
                    closeEditDoctorModal();
                    loadDoctors();
                } else {
                    alert('Lỗi: ' + result.message);
                }
            } catch (err) {
                alert('Không thể cập nhật thông tin!');
            }
        });
    });
</script>